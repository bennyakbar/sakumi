<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\FeeMatrix;
use App\Models\FeeType;
use App\Models\SchoolClass;
use App\Models\StudentCategory;
use Illuminate\Http\Request;

class FeeMatrixController extends Controller
{
    public function index(Request $request)
    {
        $query = FeeMatrix::with(['feeType', 'schoolClass', 'category']);

        if ($request->filled('fee_type_id')) {
            $query->where('fee_type_id', $request->fee_type_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $matrices = $query->orderBy('fee_type_id')->orderBy('class_id')->paginate(20)->withQueryString();
        $feeTypes = FeeType::where('is_active', true)->orderBy('name')->get();
        $classes = SchoolClass::where('is_active', true)->orderBy('level')->orderBy('name')->get();

        return view('master-data.fee-matrix.index', compact('matrices', 'feeTypes', 'classes'));
    }

    public function create()
    {
        $feeTypes = FeeType::where('is_active', true)->orderBy('name')->get();
        $classes = SchoolClass::where('is_active', true)->orderBy('level')->orderBy('name')->get();
        $categories = StudentCategory::orderBy('name')->get();

        return view('master-data.fee-matrix.create', compact('feeTypes', 'classes', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fee_type_id' => 'required|exists:fee_types,id',
            'class_id' => 'nullable|exists:classes,id',
            'category_id' => 'nullable|exists:student_categories,id',
            'amount' => 'required|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        FeeMatrix::create($validated);

        return redirect()->route('master-data.fee-matrix.index')
            ->with('success', 'Matriks biaya berhasil ditambahkan.');
    }

    public function edit(FeeMatrix $fee_matrix)
    {
        $feeTypes = FeeType::where('is_active', true)->orderBy('name')->get();
        $classes = SchoolClass::where('is_active', true)->orderBy('level')->orderBy('name')->get();
        $categories = StudentCategory::orderBy('name')->get();

        return view('master-data.fee-matrix.edit', compact('fee_matrix', 'feeTypes', 'classes', 'categories'));
    }

    public function update(Request $request, FeeMatrix $fee_matrix)
    {
        $validated = $request->validate([
            'fee_type_id' => 'required|exists:fee_types,id',
            'class_id' => 'nullable|exists:classes,id',
            'category_id' => 'nullable|exists:student_categories,id',
            'amount' => 'required|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $fee_matrix->update($validated);

        return redirect()->route('master-data.fee-matrix.index')
            ->with('success', 'Matriks biaya berhasil diperbarui.');
    }

    public function destroy(FeeMatrix $fee_matrix)
    {
        $fee_matrix->delete();

        return redirect()->route('master-data.fee-matrix.index')
            ->with('success', 'Matriks biaya berhasil dihapus.');
    }
}
