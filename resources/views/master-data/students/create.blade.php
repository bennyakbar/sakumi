@extends('layouts.app')

@section('header', 'Tambah Siswa Baru')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="font-bold text-lg text-gray-800">Form Tambah Siswa</h3>
                <p class="text-sm text-gray-500 mt-1">Lengkapi data siswa di bawah ini</p>
            </div>

            <form method="POST" action="{{ route('master-data.students.store') }}" class="p-6 space-y-6">
                @csrf

                {{-- Data Utama --}}
                <div class="border-b border-gray-100 pb-6">
                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4">Data Utama</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">NIS <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nis" id="nis" value="{{ old('nis') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                            @error('nis') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                            <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                            @error('nisn') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span
                                    class="text-red-500">*</span></label>
                            <select name="gender" id="gender" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                                <option value="">Pilih</option>
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span
                                    class="text-red-500">*</span></label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Lulus</option>
                                <option value="dropout" {{ old('status') == 'dropout' ? 'selected' : '' }}>Keluar</option>
                                <option value="transferred" {{ old('status') == 'transferred' ? 'selected' : '' }}>Pindah
                                </option>
                            </select>
                            @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas <span
                                    class="text-red-500">*</span></label>
                            <select name="class_id" id="class_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                                <option value="">Pilih Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }} ({{ $class->academic_year }})</option>
                                @endforeach
                            </select>
                            @error('class_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span
                                    class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Data Lahir --}}
                <div class="border-b border-gray-100 pb-6">
                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4">Data Kelahiran</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">Tempat
                                Lahir</label>
                            <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Lahir</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                        </div>
                        <div>
                            <label for="enrollment_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Masuk</label>
                            <input type="date" name="enrollment_date" id="enrollment_date"
                                value="{{ old('enrollment_date') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                        </div>
                    </div>
                </div>

                {{-- Data Wali --}}
                <div class="border-b border-gray-100 pb-6">
                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4">Data Wali / Orang Tua</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Wali</label>
                            <input type="text" name="parent_name" id="parent_name" value="{{ old('parent_name') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                        </div>
                        <div>
                            <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-1">No.
                                Telepon</label>
                            <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                        </div>
                        <div>
                            <label for="parent_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">No.
                                WhatsApp</label>
                            <input type="text" name="parent_whatsapp" id="parent_whatsapp"
                                value="{{ old('parent_whatsapp') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                        Simpan
                    </button>
                    <a href="{{ route('master-data.students.index') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection