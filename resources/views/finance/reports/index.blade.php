@extends('layouts.app')

@section('header', 'Laporan Keuangan')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Header & Filter --}}
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="font-bold text-lg text-gray-800">Laporan Pemasukan</h3>
                <p class="text-sm text-gray-500">Ringkasan transaksi keuangan</p>
            </div>
            <div class="flex gap-2 items-center flex-wrap">
                <form action="{{ route('finance.reports.index') }}" method="GET" class="flex gap-2">
                    <select name="month"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month((int) $m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                    <select name="year"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        @for($y = date('Y'); $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                        Tampilkan
                    </button>
                </form>
                <a href="{{ route('finance.reports.yearly', ['year' => $year]) }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Rekap Tahunan
                </a>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Pemasukan Hari Ini</p>
                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($todayIncome, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Bulan Ini</p>
                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($monthIncome, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Tunggakan</p>
                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalArrears, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Siswa Bayar Bulan Ini</p>
                        <p class="text-xl font-bold text-gray-900">{{ $studentsPaidThisMonth }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Income By Type --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-bold text-gray-800 mb-4">Pemasukan per Kategori Biaya</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">Jenis Biaya</th>
                                <th class="px-4 py-3 text-right rounded-r-lg">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($incomeByType as $item)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $item->type }}</td>
                                    <td class="px-4 py-3 text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-center text-gray-500">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Income By Class --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-bold text-gray-800 mb-4">Pemasukan per Kelas</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">Kelas</th>
                                <th class="px-4 py-3 text-right rounded-r-lg">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($incomeByClass as $item)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $item->class_name }}</td>
                                    <td class="px-4 py-3 text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-center text-gray-500">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Daily Income --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h4 class="font-bold text-gray-800 mb-4">Rincian Harian
                ({{ \Carbon\Carbon::create()->month((int) $month)->translatedFormat('F') }} {{ $year }})</h4>
            <div class="overflow-x-auto max-h-96">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium uppercase text-xs sticky top-0 bg-white">
                        <tr>
                            <th class="px-4 py-3 border-b">Tanggal</th>
                            <th class="px-4 py-3 text-right border-b">Total Pemasukan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($dailyIncome as $day)
                            <tr>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($day->date)->translatedFormat('d F Y') }}</td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900">Rp
                                    {{ number_format($day->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-center text-gray-500">Belum ada data transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($dailyIncome->count() > 0)
                        <tfoot class="bg-gray-50 font-bold text-gray-900">
                            <tr>
                                <td class="px-4 py-3">Total</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($dailyIncome->sum('total'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection