@extends('layouts.app')

@section('header', 'Tambah Tarif Biaya')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="font-bold text-lg text-gray-800">Form Tambah Tarif</h3>
                <p class="text-sm text-gray-500 mt-1">Kosongkan Kelas/Kategori jika berlaku untuk semua</p>
            </div>

            <form method="POST" action="{{ route('master-data.fee-matrix.store') }}" class="p-6 space-y-5">
                @csrf

                <div>
                    <label for="fee_type_id" class="block text-sm font-medium text-gray-700 mb-1">Jenis Biaya <span class="text-red-500">*</span></label>
                    <select name="fee_type_id" id="fee_type_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                        <option value="">Pilih Jenis Biaya</option>
                        @foreach($feeTypes as $ft)
                            <option value="{{ $ft->id }}" {{ old('fee_type_id') == $ft->id ? 'selected' : '' }}>{{ $ft->name }} ({{ $ft->code }})</option>
                        @endforeach
                    </select>
                    @error('fee_type_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="class_id" id="class_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $cl)
                                <option value="{{ $cl->id }}" {{ old('class_id') == $cl->id ? 'selected' : '' }}>{{ $cl->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori Siswa</label>
                        <select name="category_id" id="category_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0" step="1000"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm"
                        placeholder="0">
                    @error('amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="effective_from" class="block text-sm font-medium text-gray-700 mb-1">Berlaku Dari <span class="text-red-500">*</span></label>
                        <input type="date" name="effective_from" id="effective_from" value="{{ old('effective_from', date('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                        @error('effective_from') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="effective_to" class="block text-sm font-medium text-gray-700 mb-1">Berlaku Sampai</label>
                        <input type="date" name="effective_to" id="effective_to" value="{{ old('effective_to') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                        <p class="mt-1 text-xs text-gray-400">Kosongkan jika tidak ada batas waktu</p>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" id="notes" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">{{ old('notes') }}</textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                        Simpan
                    </button>
                    <a href="{{ route('master-data.fee-matrix.index') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
