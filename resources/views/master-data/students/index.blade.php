@extends('layouts.app')

@section('header', 'Data Siswa')

@section('content')
    @if(session('import_errors'))
        <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-md shadow-sm">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-amber-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-amber-800">Beberapa baris gagal diimport:</p>
                    <ul class="mt-2 text-sm text-amber-700 space-y-1 max-h-40 overflow-y-auto">
                        @foreach(session('import_errors') as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-gray-800">Daftar Siswa</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola data siswa sekolah</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('master-data.students.import') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm ring-1 ring-green-600/20">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Import Siswa
                </a>
                <a href="{{ route('master-data.students.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Siswa
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="p-4 border-b border-gray-100">
            <form method="GET" action="{{ route('master-data.students.index') }}" class="flex flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIS / NISN..."
                    class="flex-1 min-w-[200px] max-w-xs px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <select name="class_id"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
                <select name="status"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Lulus</option>
                    <option value="dropout" {{ request('status') == 'dropout' ? 'selected' : '' }}>Keluar</option>
                    <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Pindah</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">Filter</button>
                @if(request()->hasAny(['search', 'class_id', 'status']))
                    <a href="{{ route('master-data.students.index') }}" class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">NIS</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">L/P</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($students as $i => $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $students->firstItem() + $i }}</td>
                            <td class="px-6 py-4 font-mono text-gray-900">{{ $student->nis }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <a href="{{ route('master-data.students.show', $student) }}" class="hover:text-indigo-600 transition-colors">{{ $student->name }}</a>
                            </td>
                            <td class="px-6 py-4">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="px-6 py-4">{{ $student->schoolClass->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $student->category->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @switch($student->status)
                                    @case('active')
                                        <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold">Aktif</span>
                                        @break
                                    @case('graduated')
                                        <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-semibold">Lulus</span>
                                        @break
                                    @case('dropout')
                                        <span class="bg-red-100 text-red-800 py-1 px-3 rounded-full text-xs font-semibold">Keluar</span>
                                        @break
                                    @case('transferred')
                                        <span class="bg-amber-100 text-amber-800 py-1 px-3 rounded-full text-xs font-semibold">Pindah</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('master-data.students.show', $student) }}"
                                        class="text-gray-500 hover:text-gray-700 font-medium text-sm">Detail</a>
                                    <a href="{{ route('master-data.students.edit', $student) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Edit</a>
                                    <form action="{{ route('master-data.students.destroy', $student) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Belum ada data siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $students->links() }}
            </div>
        @endif
    </div>
@endsection
