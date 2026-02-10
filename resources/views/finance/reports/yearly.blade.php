@extends('layouts.app')

@section('header', 'Rekap Tahunan')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Header & Filter --}}
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="font-bold text-lg text-gray-800">Rekap Pemasukan Tahunan</h3>
                <p class="text-sm text-gray-500">Ringkasan pemasukan per bulan</p>
            </div>
            <div class="flex gap-2 items-center">
                <form action="{{ route('finance.reports.yearly') }}" method="GET" class="flex gap-2">
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
                <a href="{{ route('finance.reports.index') }}"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm font-medium">
                    ← Kembali
                </a>
            </div>
        </div>

        {{-- Total Summary --}}
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-200 text-sm font-medium">Total Pemasukan Tahun {{ $year }}</p>
                    <p class="text-3xl font-bold mt-1">Rp {{ number_format($yearlyTotal, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-lg">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Monthly Breakdown --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-bold text-gray-800 mb-4">Rincian per Bulan</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">Bulan</th>
                                <th class="px-4 py-3 text-right rounded-r-lg">Pemasukan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @for($m = 1; $m <= 12; $m++)
                                @php $monthData = $monthlyIncome->get($m); @endphp
                                <tr class="{{ $monthData ? '' : 'text-gray-400' }}">
                                    <td class="px-4 py-3 font-medium">
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </td>
                                    <td class="px-4 py-3 text-right {{ $monthData ? 'font-semibold text-gray-900' : '' }}">
                                        Rp {{ number_format($monthData->total ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot class="bg-indigo-50 font-bold text-indigo-800">
                            <tr>
                                <td class="px-4 py-3 rounded-l-lg">Total</td>
                                <td class="px-4 py-3 text-right rounded-r-lg">Rp
                                    {{ number_format($yearlyTotal, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Income By Type --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-bold text-gray-800 mb-4">per Kategori Biaya</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">Jenis</th>
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
        </div>
    </div>
@endsection