@extends('layouts.app')

@section('header', 'Dashboard Overview')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total Siswa -->
        <a href="{{ route('master-data.students.index') }}" class="block">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Total Siswa Aktif</h3>
                    <div class="bg-indigo-50 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="text-2xl font-bold text-gray-800">{{ number_format($totalStudents) }}</div>
                    <div class="text-gray-500 text-sm">Siswa</div>
                </div>
            </div>
        </a>

        <!-- Card 2: Pemasukan Bulan Ini -->
        <a href="{{ route('finance.reports.index') }}" class="block">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Pemasukan Bulan Ini</h3>
                    <div class="bg-emerald-50 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($monthIncome, 0, ',', '.') }}</div>
                </div>
            </div>
        </a>

        <!-- Card 3: Tunggakan -->
        <a href="{{ route('finance.reports.index') }}" class="block">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Total Tunggakan</h3>
                    <div class="bg-red-50 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalArrears, 0, ',', '.') }}</div>
                </div>
            </div>
        </a>

        <!-- Card 4: Transaksi Hari Ini -->
        <a href="{{ route('finance.transactions.index') }}" class="block">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 text-sm font-medium">Transaksi Hari Ini</h3>
                    <div class="bg-blue-50 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="text-2xl font-bold text-gray-800">{{ $todayTransactionCount }}</div>
                    <div class="text-sm text-gray-500">
                        Rp {{ number_format($todayIncome, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800">Transaksi Terakhir</h3>
            <a href="{{ route('finance.transactions.index') }}"
                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs">
                    <tr>
                        <th class="px-6 py-4">No. Transaksi</th>
                        <th class="px-6 py-4">Siswa</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($recentTransactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <a href="{{ route('finance.transactions.show', $transaction) }}" class="hover:text-indigo-600">
                                    {{ $transaction->transaction_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $transaction->student->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->student->schoolClass->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $transaction->transaction_date->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4 text-green-600 font-semibold">Rp
                                {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold">Lunas</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection