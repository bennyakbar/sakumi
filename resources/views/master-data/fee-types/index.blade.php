@extends('layouts.app')

@section('header', 'Jenis Biaya')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-gray-800">Daftar Jenis Biaya</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola jenis biaya sekolah (SPP, pendaftaran, dll)</p>
            </div>
            <a href="{{ route('master-data.fee-types.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Jenis Biaya
            </a>
        </div>

        <div class="p-4 border-b border-gray-100">
            <form method="GET" action="{{ route('master-data.fee-types.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama..."
                    class="flex-1 max-w-xs px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">Cari</button>
                @if(request('search'))
                    <a href="{{ route('master-data.fee-types.index') }}" class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700">Reset</a>
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
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($feeTypes as $i => $feeType)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $feeTypes->firstItem() + $i }}</td>
                            <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $feeType->code }}</td>
                            <td class="px-6 py-4 font-medium">{{ $feeType->name }}</td>
                            <td class="px-6 py-4">
                                @if($feeType->is_monthly)
                                    <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-semibold">Bulanan</span>
                                @else
                                    <span class="bg-purple-100 text-purple-800 py-1 px-3 rounded-full text-xs font-semibold">Sekali</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($feeType->is_active)
                                    <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold">Aktif</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 py-1 px-3 rounded-full text-xs font-semibold">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('master-data.fee-types.edit', $feeType) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Edit</a>
                                    <form action="{{ route('master-data.fee-types.destroy', $feeType) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus jenis biaya ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                Belum ada data jenis biaya.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($feeTypes->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $feeTypes->links() }}
            </div>
        @endif
    </div>
@endsection
