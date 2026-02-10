<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\StudentCategory;
use Illuminate\Http\Request;

class StudentCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentCategory::query();

        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%')
                ->orWhere('code', 'ilike', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('code')->paginate(15)->withQueryString();

        return view('master-data.student-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('master-data.student-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:student_categories,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        StudentCategory::create($validated);

        return redirect()->route('master-data.student-categories.index')
            ->with('success', 'Kategori siswa berhasil ditambahkan.');
    }

    public function edit(StudentCategory $student_category)
    {
        return view('master-data.student-categories.edit', ['category' => $student_category]);
    }

    public function update(Request $request, StudentCategory $student_category)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:student_categories,code,' . $student_category->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $student_category->update($validated);

        return redirect()->route('master-data.student-categories.index')
            ->with('success', 'Kategori siswa berhasil diperbarui.');
    }

    public function destroy(StudentCategory $student_category)
    {
        if ($student_category->students()->exists()) {
            return redirect()->route('master-data.student-categories.index')
                ->with('error', 'Tidak dapat menghapus kategori yang masih memiliki siswa.');
        }

        $student_category->delete();

        return redirect()->route('master-data.student-categories.index')
            ->with('success', 'Kategori siswa berhasil dihapus.');
    }
}
