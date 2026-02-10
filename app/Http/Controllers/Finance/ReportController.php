<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StudentObligation;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default to current month
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Summary Stats
        $todayIncome = Transaction::whereDate('transaction_date', today())
            ->where('status', 'completed')
            ->where('transaction_type', 'income')
            ->sum('total_amount');

        $monthIncome = Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->where('status', 'completed')
            ->where('transaction_type', 'income')
            ->sum('total_amount');

        // Arrears (Total Tunggakan)
        $totalArrears = StudentObligation::where('is_paid', false)->sum('amount');

        // Students who paid this month
        $studentsPaidThisMonth = Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->where('status', 'completed')
            ->where('transaction_type', 'income')
            ->distinct('student_id')
            ->count('student_id');

        // Daily Chart Data/Table
        $dailyIncome = Transaction::selectRaw('DATE(transaction_date) as date, SUM(total_amount) as total')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->where('status', 'completed')
            ->where('transaction_type', 'income')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Income by Fee Type
        $incomeByType = TransactionItem::selectRaw('fee_types.name as type, SUM(transaction_items.amount) as total')
            ->join('fee_types', 'transaction_items.fee_type_id', '=', 'fee_types.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereMonth('transactions.transaction_date', $month)
            ->whereYear('transactions.transaction_date', $year)
            ->where('transactions.status', 'completed')
            ->groupBy('fee_types.name')
            ->orderByDesc('total')
            ->get();

        // Income by Class
        // Income by Class
        $incomeByClass = Transaction::selectRaw('classes.name as class_name, SUM(transactions.total_amount) as total')
            ->join('students', 'transactions.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->whereMonth('transactions.transaction_date', $month)
            ->whereYear('transactions.transaction_date', $year)
            ->where('transactions.status', 'completed')
            ->where('transactions.transaction_type', 'income')
            ->groupBy('classes.name')
            ->orderBy('classes.name')
            ->get();

        return view('finance.reports.index', compact(
            'todayIncome',
            'monthIncome',
            'totalArrears',
            'studentsPaidThisMonth',
            'dailyIncome',
            'incomeByType',
            'incomeByClass',
            'month',
            'year'
        ));
    }

    public function yearly(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // Monthly breakdown for the year
        $monthlyIncome = Transaction::selectRaw('EXTRACT(MONTH FROM transaction_date)::int as month_num, SUM(total_amount) as total')
            ->whereYear('transaction_date', $year)
            ->where('status', 'completed')
            ->where('transaction_type', 'income')
            ->groupByRaw('EXTRACT(MONTH FROM transaction_date)')
            ->orderByRaw('EXTRACT(MONTH FROM transaction_date)')
            ->get()
            ->keyBy('month_num');

        // Yearly total
        $yearlyTotal = Transaction::whereYear('transaction_date', $year)
            ->where('status', 'completed')
            ->where('transaction_type', 'income')
            ->sum('total_amount');

        // Income by Fee Type for the year
        $incomeByType = TransactionItem::selectRaw('fee_types.name as type, SUM(transaction_items.amount) as total')
            ->join('fee_types', 'transaction_items.fee_type_id', '=', 'fee_types.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereYear('transactions.transaction_date', $year)
            ->where('transactions.status', 'completed')
            ->groupBy('fee_types.name')
            ->orderByDesc('total')
            ->get();

        return view('finance.reports.yearly', compact(
            'monthlyIncome',
            'yearlyTotal',
            'incomeByType',
            'year'
        ));
    }
}
