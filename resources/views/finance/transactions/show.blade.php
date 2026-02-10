@extends('layouts.app')

@section('header', 'Detail Transaksi')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <div>
                    <h3 class="font-bold text-lg text-gray-800">Kwitansi Pembayaran</h3>
                    <p class="text-sm text-gray-500">{{ $transaction->transaction_number }}</p>
                </div>
                <div class="flex gap-2 items-center">
                    <a href="{{ route('finance.transactions.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm font-medium">
                        Kembali
                    </a>
                    @if($transaction->status === 'completed')
                        <a href="{{ route('finance.transactions.print', $transaction) }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Cetak Kwitansi
                        </a>
                    @endif
                </div>
            </div>

            {{-- Cancelled Banner --}}
            @if($transaction->status === 'cancelled')
                <div class="bg-red-50 border-b border-red-200 p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                        <div>
                            <p class="font-semibold text-red-800">Transaksi Dibatalkan</p>
                            <p class="text-sm text-red-700 mt-1">Alasan: {{ $transaction->cancellation_reason }}</p>
                            <p class="text-xs text-red-600 mt-1">
                                Dibatalkan oleh: {{ $transaction->canceller->name ?? '-' }}
                                pada {{ $transaction->cancelled_at?->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-8">
                {{-- Header Info --}}
                <div class="flex justify-between mb-8">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Telah terima dari:</p>
                        <h4 class="font-bold text-gray-900 text-lg">{{ $transaction->student->name }}</h4>
                        <p class="text-sm text-gray-600">{{ $transaction->student->schoolClass->name ?? '-' }} -
                            {{ $transaction->student->nis }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 mb-1">Tanggal:</p>
                        <p class="font-semibold text-gray-900">
                            {{ $transaction->transaction_date->translatedFormat('d F Y') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Metode: <span
                                class="uppercase">{{ $transaction->payment_method }}</span></p>
                        <div class="mt-2">
                            @if($transaction->status === 'completed')
                                <span
                                    class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold">Lunas</span>
                            @elseif($transaction->status === 'cancelled')
                                <span
                                    class="bg-red-100 text-red-800 py-1 px-3 rounded-full text-xs font-semibold">Dibatalkan</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Items Table --}}
                <div class="border rounded-lg overflow-hidden mb-6">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-700 font-semibold border-b">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Keterangan</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($transaction->items as $index => $item)
                                <tr>
                                    <td class="px-4 py-3 w-12 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">{{ $item->description }}</td>
                                    <td class="px-4 py-3 text-right font-medium">Rp
                                        {{ number_format($item->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold text-gray-900">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right">Total Pembayaran</td>
                                <td class="px-4 py-3 text-right text-indigo-700 text-lg">Rp
                                    {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Terbilang --}}
                <div class="bg-gray-50 rounded-lg p-4 mb-8">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Terbilang:</span>
                        <em>{{ ucwords(\NumberFormatter::create('id', \NumberFormatter::SPELLOUT)->format($transaction->total_amount)) }}
                            Rupiah</em>
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-between items-end mt-8">
                    <div class="text-xs text-gray-400">
                        <p>Dibuat oleh: {{ $transaction->creator->name ?? 'Admin' }}</p>
                        <p>Waktu: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <div class="border-b border-gray-300 w-48 mb-2"></div>
                        <p class="text-center text-sm text-gray-600">Bagian Keuangan</p>
                    </div>
                </div>

                {{-- Cancel Button --}}
                @if($transaction->status === 'completed')
                    <div class="mt-8 pt-6 border-t border-gray-200" x-data="{ showCancel: false }">
                        <button @click="showCancel = !showCancel"
                            class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                </path>
                            </svg>
                            Batalkan Transaksi
                        </button>

                        <div x-show="showCancel" x-transition class="mt-4 bg-red-50 rounded-lg p-4 border border-red-200">
                            <p class="text-sm text-red-800 font-medium mb-3">Apakah Anda yakin ingin membatalkan transaksi ini?
                            </p>
                            <form method="POST" action="{{ route('finance.transactions.cancel', $transaction) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-red-700 mb-1">Alasan Pembatalan <span
                                            class="text-red-500">*</span></label>
                                    <textarea name="cancellation_reason" rows="2" required
                                        class="w-full px-3 py-2 border border-red-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none"
                                        placeholder="Contoh: Salah input nominal, pembatalan oleh wali murid..."></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                        Ya, Batalkan
                                    </button>
                                    <button type="button" @click="showCancel = false"
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                        Tidak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection