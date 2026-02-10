@extends('layouts.app')

@section('header', 'Detail Siswa')

@section('content')
    <div class="max-w-3xl">
        <div class="mb-4">
            <a href="{{ route('master-data.students.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Siswa
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-xl text-gray-800">{{ $student->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">NIS: {{ $student->nis }} {{ $student->nisn ? '| NISN: ' . $student->nisn : '' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('master-data.students.edit', $student) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        Edit
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-6">
                {{-- Data Utama --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Data Utama</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Jenis Kelamin</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Status</dt>
                            <dd class="mt-1">
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
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Kelas</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">{{ $student->schoolClass->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Kategori</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">{{ $student->category->name ?? '-' }}</dd>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Data Kelahiran --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Data Kelahiran</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Tempat Lahir</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">{{ $student->birth_place ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Tanggal Lahir</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">{{ $student->birth_date?->format('d F Y') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Tanggal Masuk</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">{{ $student->enrollment_date?->format('d F Y') ?? '-' }}</dd>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Data Wali --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Data Wali / Orang Tua</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <dt class="text-xs text-gray-500 uppercase">Nama Wali</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">{{ $student->parent_name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase">No. Telepon</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">{{ $student->parent_phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase">No. WhatsApp</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">{{ $student->parent_whatsapp ?? '-' }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-xs text-gray-500 uppercase">Alamat</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">{{ $student->address ?? '-' }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
