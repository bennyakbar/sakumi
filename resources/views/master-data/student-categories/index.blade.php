@extends('layouts.app')

@section('header', 'Kategori Siswa')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div
            class="p-6 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-gray-800">Daftar Kategori Siswa</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola kategori siswa dan diskon</p>
            </div>
            <a href="{{ route('master-data.student-categories.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kategori
            </a>
        </div>

        <div class="p-4 border-b border-gray-100">
            <form method="GET" action="{{ route('master-data.student-categories.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama..."
                    class="flex-1 max-w-xs px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <button type="submit"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">Cari</button>
                @if(request('search'))
                    <a href="{{ route('master-data.student-categories.index') }}"
                        class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4">Diskon (%)</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $i => $category)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $categories->firstItem() + $i }}</td>
                            <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $category->code }}</td>
                            <td class="px-6 py-4 font-medium">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $category->description ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($category->discount_percentage > 0)
                                    <span
                                        class="bg-amber-100 text-amber-800 py-1 px-3 rounded-full text-xs font-semibold">{{ $category->discount_percentage }}%</span>
                                @else
                                    <span class="text-gray-400">0%</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('master-data.student-categories.edit', $category) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Edit</a>
                                    <form action="{{ route('master-data.student-categories.destroy', $category) }}"
                                        method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 font-medium text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                Belum ada data kategori siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection