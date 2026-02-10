<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Student;
use App\Models\FeeType;
use App\Models\StudentObligation;
use App\Models\FeeMatrix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['student', 'items']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('transaction_number', 'ilike', "%{$search}%")
                ->orWhereHas('student', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                        ->orWhere('nis', 'ilike', "%{$search}%");
                });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('finance.transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        $student = null;
        $obligations = [];
        $feeTypes = FeeType::where('is_active', true)->orderBy('name')->get();

        if ($request->filled('student_id')) {
            $student = Student::with(['category', 'schoolClass'])->find($request->student_id);

            if ($student) {
                // Fetch unpaid obligations
                // Logic: 
                // 1. Get existing unpaid obligations from student_obligations table
                // 2. Generate potential obligations based on FeeMatrix (if not yet in table) - simplified for now: just show what's in table or ad-hoc

                // For this phase, we'll assume obligations are created via a separate process or we allow ad-hoc payment of fees.
                // Let's just list active fee types and allow user to add them.
                // ALSO: Check if there are specific obligations generated.

                $obligations = StudentObligation::where('student_id', $student->id)
                    ->where('is_paid', false)
                    ->with('feeType')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();
            }
        }

        return view('finance.transactions.create', compact('student', 'obligations', 'feeTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_method' => 'required|in:cash,transfer,qris',
            'obligations' => 'array',
            'obligations.*' => 'exists:student_obligations,id',
            'custom_items' => 'array',
            'custom_items.*.fee_type_id' => 'required|exists:fee_types,id',
            'custom_items.*.amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $itemsToCreate = [];
            $obligationsToUpdate = [];

            // 1. Calculate total from selected obligations
            if ($request->filled('obligations')) {
                $obligations = StudentObligation::whereIn('id', $request->obligations)
                    ->where('is_paid', false)
                    ->with('feeType')
                    ->get();

                foreach ($obligations as $ob) {
                    $totalAmount += $ob->amount;
                    $itemsToCreate[] = [
                        'fee_type_id' => $ob->fee_type_id,
                        'description' => $ob->feeType->name . ' - ' . \Carbon\Carbon::createFromDate($ob->year, $ob->month, 1)->translatedFormat('F Y'),
                        'amount' => $ob->amount,
                        'month' => $ob->month,
                        'year' => $ob->year,
                        'obligation_id' => $ob->id, // temp key to link back
                    ];
                    $obligationsToUpdate[] = $ob;
                }
            }

            // 2. Calculate total from custom items
            if ($request->filled('custom_items')) {
                foreach ($request->custom_items as $item) {
                    $totalAmount += $item['amount'];
                    $feeType = FeeType::find($item['fee_type_id']);
                    $itemsToCreate[] = [
                        'fee_type_id' => $item['fee_type_id'],
                        'description' => $feeType->name . ' (Manual)',
                        'amount' => $item['amount'],
                        'month' => null,
                        'year' => null,
                        'obligation_id' => null,
                    ];
                }
            }

            if ($totalAmount <= 0) {
                return back()->with('error', 'Total pembayaran tidak boleh 0.');
            }

            // 3. Create Transaction
            $transaction = Transaction::create([
                'transaction_number' => 'TRX-' . date('Ymd') . '-' . rand(1000, 9999),
                'transaction_date' => now(),
                'transaction_type' => 'income',
                'student_id' => $request->student_id,
                'payment_method' => $request->payment_method,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'status' => 'completed',
                'created_by' => Auth::id() ?? 1, // Fallback for dev if auth not fully set
            ]);

            // 4. Create Transaction Items & Update Obligations
            foreach ($itemsToCreate as $itemData) {
                $obligationId = $itemData['obligation_id'];
                unset($itemData['obligation_id']); // remove temp key

                $transactionItem = $transaction->items()->create($itemData);

                // If linked to an obligation, update it
                if ($obligationId) {
                    StudentObligation::where('id', $obligationId)->update([
                        'is_paid' => true,
                        'paid_amount' => $itemData['amount'],
                        'paid_at' => now(),
                        'transaction_item_id' => $transactionItem->id,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('finance.transactions.show', $transaction)
                ->with('success', 'Pembayaran berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['student.schoolClass', 'items.feeType', 'creator', 'canceller']);
        return view('finance.transactions.show', compact('transaction'));
    }

    public function print(Transaction $transaction)
    {
        $transaction->load(['student.schoolClass', 'items.feeType', 'creator']);
        return view('finance.transactions.print', compact('transaction'));
    }

    public function cancel(Request $request, Transaction $transaction)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        if ($transaction->status === 'cancelled') {
            return back()->with('error', 'Transaksi sudah dibatalkan sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // 1. Mark transaction as cancelled
            $transaction->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => Auth::id(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            // 2. Restore linked obligations back to unpaid
            foreach ($transaction->items as $item) {
                StudentObligation::where('transaction_item_id', $item->id)
                    ->update([
                        'is_paid' => false,
                        'paid_amount' => null,
                        'paid_at' => null,
                        'transaction_item_id' => null,
                    ]);
            }

            DB::commit();

            return redirect()->route('finance.transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
