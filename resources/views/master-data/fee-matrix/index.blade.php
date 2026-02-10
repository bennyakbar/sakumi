@extends('layouts.app')

@section('header', 'Matriks Biaya')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div
            class="p-6 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-gray-800">Matriks Biaya</h3>
                <p class="text-sm text-gray-500 mt-1">Atur tarif per jenis biaya, kelas, dan kategori siswa</p>
            </div>
            <a href="{{ route('master-data.fee-matrix.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Tarif
            </a>
        </div>

        <div class="p-4 border-b border-gray-100">
            <form method="GET" action="{{ route('master-data.fee-matrix.index') }}" class="flex flex-wrap gap-2">
                <select name="fee_type_id"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="">Semua Jenis Biaya</option>
                    @foreach($feeTypes as $ft)
                        <option value="{{ $ft->id }}" {{ request('fee_type_id') == $ft->id ? 'selected' : '' }}>{{ $ft->name }}
                        </option>
                    @endforeach
                </select>
                <select name="class_id"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $cl)
                        <option value="{{ $cl->id }}" {{ request('class_id') == $cl->id ? 'selected' : '' }}>{{ $cl->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">Filter</button>
                @if(request()->hasAny(['fee_type_id', 'class_id']))
                    <a href="{{ route('master-data.fee-matrix.index') }}"
                        class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Jenis Biaya</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Berlaku</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($matrices as $i => $matrix)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $matrices->firstItem() + $i }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $matrix->feeType->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $matrix->schoolClass->name ?? 'Semua Kelas' }}</td>
                            <td class="px-6 py-4">{{ $matrix->category->name ?? 'Semua Kategori' }}</td>
                            <td class="px-6 py-4 font-semibold text-emerald-700">Rp
                                {{ number_format($matrix->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-xs">
                                {{ $matrix->effective_from->format('d/m/Y') }}
                                @if($matrix->effective_to)
                                    — {{ $matrix->effective_to->format('d/m/Y') }}
                                @else
                                    — <span class="text-gray-400">∞</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($matrix->is_active)
                                    <span
                                        class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold">Aktif</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 py-1 px-3 rounded-full text-xs font-semibold">Tidak
                                        Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('master-data.fee-matrix.edit', $matrix) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Edit</a>
                                    <form action="{{ route('master-data.fee-matrix.destroy', $matrix) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus tarif ini?')">
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
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                Belum ada data matriks biaya.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($matrices->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $matrices->links() }}
            </div>
        @endif
    </div>
@endsection