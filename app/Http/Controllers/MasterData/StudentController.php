<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\StudentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['schoolClass', 'category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', '%' . $search . '%')
                    ->orWhere('nis', 'ilike', '%' . $search . '%')
                    ->orWhere('nisn', 'ilike', '%' . $search . '%');
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('name')->paginate(20)->withQueryString();
        $classes = SchoolClass::where('is_active', true)->orderBy('level')->orderBy('name')->get();

        return view('master-data.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('level')->orderBy('name')->get();
        $categories = StudentCategory::orderBy('name')->get();

        return view('master-data.students.create', compact('classes', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:students,nis',
            'nisn' => 'nullable|string|max:20|unique:students,nisn',
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'category_id' => 'required|exists:student_categories,id',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:100',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'parent_whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,graduated,dropout,transferred',
            'enrollment_date' => 'nullable|date',
        ]);

        Student::create($validated);

        return redirect()->route('master-data.students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Student $student)
    {
        $student->load(['schoolClass', 'category']);

        return view('master-data.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('level')->orderBy('name')->get();
        $categories = StudentCategory::orderBy('name')->get();

        return view('master-data.students.edit', compact('student', 'classes', 'categories'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:students,nis,' . $student->id,
            'nisn' => 'nullable|string|max:20|unique:students,nisn,' . $student->id,
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'category_id' => 'required|exists:student_categories,id',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:100',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'parent_whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,graduated,dropout,transferred',
            'enrollment_date' => 'nullable|date',
        ]);

        $student->update($validated);

        return redirect()->route('master-data.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->delete(); // Soft delete

        return redirect()->route('master-data.students.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_siswa.csv"',
        ];

        $columns = ['nis', 'nisn', 'nama', 'jenis_kelamin', 'kelas', 'kategori', 'tempat_lahir', 'tanggal_lahir', 'nama_wali', 'telepon_wali', 'whatsapp_wali', 'alamat', 'status', 'tanggal_masuk'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            // Sample rows
            fputcsv($file, ['1001', '0012345678', 'Ahmad Fajar', 'L', 'Kelas 1A', 'Reguler', 'Jakarta', '2015-06-15', 'Budi Hartono', '08123456789', '08123456789', 'Jl. Merdeka No. 10', 'active', '2026-07-15']);
            fputcsv($file, ['1002', '0012345679', 'Siti Aisyah', 'P', 'Kelas 1A', 'Yatim', 'Bandung', '2015-03-22', 'Hj. Aminah', '08567890123', '08567890123', 'Jl. Kebon Jati No. 5', 'active', '2026-07-15']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function showImport()
    {
        return view('master-data.students.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        // Read header
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau format tidak valid.');
        }

        // Clean BOM from first column
        $header[0] = preg_replace('/\x{FEFF}/u', '', $header[0]);
        $header = array_map('trim', array_map('strtolower', $header));

        $expectedHeaders = ['nis', 'nisn', 'nama', 'jenis_kelamin', 'kelas', 'kategori', 'tempat_lahir', 'tanggal_lahir', 'nama_wali', 'telepon_wali', 'whatsapp_wali', 'alamat', 'status', 'tanggal_masuk'];

        // Validate header columns
        $missingHeaders = array_diff($expectedHeaders, $header);
        if (count($missingHeaders) > 0) {
            fclose($handle);
            return back()->with('error', 'Kolom CSV tidak sesuai template. Kolom hilang: ' . implode(', ', $missingHeaders));
        }

        // Build lookup maps
        $classMap = SchoolClass::where('is_active', true)->pluck('id', 'name')->toArray();
        $categoryMap = StudentCategory::pluck('id', 'name')->toArray();

        $errors = [];
        $imported = 0;
        $row = 1; // header was row 0

        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle)) !== false) {
                $row++;

                // Skip completely empty rows
                if (count(array_filter($data)) === 0) {
                    continue;
                }

                $rowData = array_combine($header, array_pad($data, count($header), ''));

                // Resolve class and category names to IDs
                $className = trim($rowData['kelas'] ?? '');
                $categoryName = trim($rowData['kategori'] ?? '');
                $classId = $classMap[$className] ?? null;
                $categoryId = $categoryMap[$categoryName] ?? null;

                $record = [
                    'nis' => trim($rowData['nis'] ?? ''),
                    'nisn' => trim($rowData['nisn'] ?? '') ?: null,
                    'name' => trim($rowData['nama'] ?? ''),
                    'gender' => strtoupper(trim($rowData['jenis_kelamin'] ?? '')),
                    'class_id' => $classId,
                    'category_id' => $categoryId,
                    'birth_place' => trim($rowData['tempat_lahir'] ?? '') ?: null,
                    'birth_date' => trim($rowData['tanggal_lahir'] ?? '') ?: null,
                    'parent_name' => trim($rowData['nama_wali'] ?? '') ?: null,
                    'parent_phone' => trim($rowData['telepon_wali'] ?? '') ?: null,
                    'parent_whatsapp' => trim($rowData['whatsapp_wali'] ?? '') ?: null,
                    'address' => trim($rowData['alamat'] ?? '') ?: null,
                    'status' => trim($rowData['status'] ?? 'active'),
                    'enrollment_date' => trim($rowData['tanggal_masuk'] ?? '') ?: null,
                ];

                $validator = Validator::make($record, [
                    'nis' => 'required|string|max:20|unique:students,nis',
                    'nisn' => 'nullable|string|max:20|unique:students,nisn',
                    'name' => 'required|string|max:255',
                    'class_id' => 'required|exists:classes,id',
                    'category_id' => 'required|exists:student_categories,id',
                    'gender' => 'required|in:L,P',
                    'birth_date' => 'nullable|date',
                    'status' => 'required|in:active,graduated,dropout,transferred',
                    'enrollment_date' => 'nullable|date',
                ]);

                if ($validator->fails()) {
                    $rowErrors = [];
                    if (!$classId && $className) {
                        $rowErrors[] = "Kelas '{$className}' tidak ditemukan";
                    }
                    if (!$categoryId && $categoryName) {
                        $rowErrors[] = "Kategori '{$categoryName}' tidak ditemukan";
                    }
                    foreach ($validator->errors()->all() as $e) {
                        $rowErrors[] = $e;
                    }
                    $errors[] = "Baris {$row}: " . implode('; ', $rowErrors);
                    continue;
                }

                Student::create($record);
                $imported++;
            }

            fclose($handle);

            if ($imported > 0) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }

        if ($imported > 0 && count($errors) === 0) {
            return redirect()->route('master-data.students.index')
                ->with('success', "{$imported} siswa berhasil diimport.");
        }

        if ($imported > 0 && count($errors) > 0) {
            return redirect()->route('master-data.students.index')
                ->with('success', "{$imported} siswa berhasil diimport.")
                ->with('import_errors', $errors);
        }

        return back()->with('error', 'Tidak ada data yang berhasil diimport.')
            ->with('import_errors', $errors);
    }
}
