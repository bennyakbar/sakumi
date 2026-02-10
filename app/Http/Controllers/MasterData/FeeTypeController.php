<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = FeeType::query();

        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%')
                ->orWhere('code', 'ilike', '%' . $request->search . '%');
        }

        $feeTypes = $query->orderBy('code')->paginate(15)->withQueryString();

        return view('master-data.fee-types.index', compact('feeTypes'));
    }

    public function create()
    {
        return view('master-data.fee-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:fee_types,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_monthly' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_monthly'] = $request->has('is_monthly');
        $validated['is_active'] = $request->has('is_active');

        FeeType::create($validated);

        return redirect()->route('master-data.fee-types.index')
            ->with('success', 'Jenis biaya berhasil ditambahkan.');
    }

    public function edit(FeeType $fee_type)
    {
        return view('master-data.fee-types.edit', ['feeType' => $fee_type]);
    }

    public function update(Request $request, FeeType $fee_type)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:fee_types,code,' . $fee_type->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_monthly' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_monthly'] = $request->has('is_monthly');
        $validated['is_active'] = $request->has('is_active');

        $fee_type->update($validated);

        return redirect()->route('master-data.fee-types.index')
            ->with('success', 'Jenis biaya berhasil diperbarui.');
    }

    public function destroy(FeeType $fee_type)
    {
        if ($fee_type->feeMatrix()->exists()) {
            return redirect()->route('master-data.fee-types.index')
                ->with('error', 'Tidak dapat menghapus jenis biaya yang masih digunakan di matriks biaya.');
        }

        $fee_type->delete();

        return redirect()->route('master-data.fee-types.index')
            ->with('success', 'Jenis biaya berhasil dihapus.');
    }
}
