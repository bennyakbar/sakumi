<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::query();

        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        $classes = $query->orderBy('level')->orderBy('name')->paginate(15)->withQueryString();

        return view('master-data.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('master-data.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'level' => 'required|integer|min:1|max:6',
            'academic_year' => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        SchoolClass::create($validated);

        return redirect()->route('master-data.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(SchoolClass $class)
    {
        return view('master-data.classes.edit', compact('class'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'level' => 'required|integer|min:1|max:6',
            'academic_year' => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $class->update($validated);

        return redirect()->route('master-data.classes.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $class)
    {
        if ($class->students()->exists()) {
            return redirect()->route('master-data.classes.index')
                ->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa.');
        }

        $class->delete();

        return redirect()->route('master-data.classes.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
