@extends('layouts.app')

@section('header', 'Import Data Siswa')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <h3 class="font-bold text-lg text-gray-800">Import Siswa dari CSV</h3>
                <p class="text-sm text-gray-500 mt-1">Upload file CSV sesuai template untuk menambah data siswa secara
                    massal</p>
            </div>

            <div class="p-6 space-y-6">
                {{-- Step 1: Download Template --}}
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-5">
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                            1</div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-semibold text-gray-900">Download Template CSV</h4>
                            <p class="text-sm text-gray-600 mt-1">Download file template terlebih dahulu, lalu isi data
                                siswa sesuai format kolom yang tersedia.</p>
                            <a href="{{ route('master-data.students.template') }}"
                                class="inline-flex items-center mt-3 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Download Template
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Fill Guide --}}
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-5">
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                            2</div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-semibold text-gray-900">Isi Data pada Template</h4>
                            <p class="text-sm text-gray-600 mt-1 mb-3">Perhatikan panduan pengisian berikut:</p>
                            <div class="overflow-x-auto">
                                <table class="text-xs text-gray-700 w-full">
                                    <thead>
                                        <tr class="bg-amber-100">
                                            <th class="px-3 py-2 text-left font-semibold">Kolom</th>
                                            <th class="px-3 py-2 text-left font-semibold">Keterangan</th>
                                            <th class="px-3 py-2 text-center font-semibold">Wajib</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-amber-100">
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">nis</td>
                                            <td class="px-3 py-1.5">Nomor Induk Siswa (unik)</td>
                                            <td class="px-3 py-1.5 text-center">✅</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">nisn</td>
                                            <td class="px-3 py-1.5">Nomor Induk Siswa Nasional</td>
                                            <td class="px-3 py-1.5 text-center">-</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">nama</td>
                                            <td class="px-3 py-1.5">Nama lengkap siswa</td>
                                            <td class="px-3 py-1.5 text-center">✅</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">jenis_kelamin</td>
                                            <td class="px-3 py-1.5">L (Laki-laki) atau P (Perempuan)</td>
                                            <td class="px-3 py-1.5 text-center">✅</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">kelas</td>
                                            <td class="px-3 py-1.5">Nama kelas <span
                                                    class="text-amber-700 font-medium">(harus sama persis)</span></td>
                                            <td class="px-3 py-1.5 text-center">✅</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">kategori</td>
                                            <td class="px-3 py-1.5">Nama kategori siswa <span
                                                    class="text-amber-700 font-medium">(harus sama persis)</span></td>
                                            <td class="px-3 py-1.5 text-center">✅</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">tempat_lahir</td>
                                            <td class="px-3 py-1.5">Tempat lahir siswa</td>
                                            <td class="px-3 py-1.5 text-center">-</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">tanggal_lahir</td>
                                            <td class="px-3 py-1.5">Format: YYYY-MM-DD</td>
                                            <td class="px-3 py-1.5 text-center">-</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">nama_wali</td>
                                            <td class="px-3 py-1.5">Nama orang tua / wali</td>
                                            <td class="px-3 py-1.5 text-center">-</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">telepon_wali</td>
                                            <td class="px-3 py-1.5">Nomor telepon wali</td>
                                            <td class="px-3 py-1.5 text-center">-</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">whatsapp_wali</td>
                                            <td class="px-3 py-1.5">Nomor WhatsApp wali</td>
                                            <td class="px-3 py-1.5 text-center">-</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">alamat</td>
                                            <td class="px-3 py-1.5">Alamat lengkap</td>
                                            <td class="px-3 py-1.5 text-center">-</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">status</td>
                                            <td class="px-3 py-1.5">active / graduated / dropout / transferred</td>
                                            <td class="px-3 py-1.5 text-center">✅</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 font-mono">tanggal_masuk</td>
                                            <td class="px-3 py-1.5">Format: YYYY-MM-DD</td>
                                            <td class="px-3 py-1.5 text-center">-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Upload --}}
                <div class="bg-green-50 border border-green-200 rounded-lg p-5">
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                            3</div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-semibold text-gray-900">Upload File CSV</h4>
                            <p class="text-sm text-gray-600 mt-1 mb-3">Pilih file CSV yang sudah diisi data siswa, lalu klik
                                tombol Import.</p>

                            <form action="{{ route('master-data.students.import.post') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-3">
                                    <div>
                                        <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-1">File
                                            CSV</label>
                                        <input type="file" name="csv_file" id="csv_file" accept=".csv,.txt"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 transition-all cursor-pointer">
                                        @error('csv_file')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="submit"
                                            class="inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                </path>
                                            </svg>
                                            Import Data
                                        </button>
                                        <a href="{{ route('master-data.students.index') }}"
                                            class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Import Errors Display --}}
                @if(session('import_errors'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-5">
                        <h4 class="font-semibold text-red-800 mb-2">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Error pada baris berikut:
                        </h4>
                        <ul class="text-sm text-red-700 space-y-1 max-h-48 overflow-y-auto">
                            @foreach(session('import_errors') as $error)
                                <li class="flex items-start">
                                    <span class="inline-block w-1.5 h-1.5 bg-red-400 rounded-full mt-1.5 mr-2 flex-shrink-0"></span>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection