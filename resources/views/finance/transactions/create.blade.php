@extends('layouts.app')

@section('header', 'Catat Pembayaran')

@section('content')
    <div class="max-w-4xl mx-auto">
        {{-- Student Selection --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4">Pilih Siswa</h3>
            <form action="{{ route('finance.transactions.create') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <select name="student_id" onchange="this.form.submit()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                        <option value="">-- Cari Siswa (Ketik Nama / NIS) --</option>
                        @foreach(\App\Models\Student::with('schoolClass')->orderBy('name')->get() as $s)
                            <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} - {{ $s->schoolClass->name ?? '-' }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <noscript>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Pilih</button>
                </noscript>
            </form>
        </div>

        @if($student)
            <div x-data="transactionForm({{ json_encode($obligations) }})">
                <form action="{{ route('finance.transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {{-- Left Column: Student Info & Obligations --}}
                        <div class="lg:col-span-2 space-y-6">

                            {{-- Student Info --}}
                            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 flex items-start gap-4">
                                <div class="bg-indigo-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $student->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $student->nis }} |
                                        {{ $student->schoolClass->name ?? '-' }}</p>
                                    <div class="mt-2 flex gap-2">
                                        <span
                                            class="px-2 py-1 bg-white text-xs font-medium text-gray-600 rounded border border-gray-200">
                                            {{ $student->category->name ?? 'Umum' }}
                                        </span>
                                        <span
                                            class="px-2 py-1 bg-{{ $student->status == 'active' ? 'green' : 'red' }}-100 text-{{ $student->status == 'active' ? 'green' : 'red' }}-700 text-xs font-medium rounded">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Unpaid Obligations --}}
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                <h4 class="font-bold text-gray-800 mb-4 flex items-center justify-between">
                                    Tagihan Belum Lunas
                                    <span class="text-xs font-normal text-gray-500 bg-gray-100 px-2 py-1 rounded">Pilih untuk
                                        bayar</span>
                                </h4>

                                @if(count($obligations) > 0)
                                    <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                                        @foreach($obligations as $ob)
                                            <label
                                                class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                                                :class="{ 'border-indigo-500 bg-indigo-50': selectedObligations.includes({{ $ob->id }}) }">
                                                <input type="checkbox" name="obligations[]" value="{{ $ob->id }}"
                                                    @click="toggleObligation({{ $ob->id }}, {{ $ob->amount }})"
                                                    class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                <div class="ml-3 flex-1">
                                                    <div class="flex justify-between">
                                                        <span class="font-medium text-gray-900">{{ $ob->feeType->name }}</span>
                                                        <span class="font-bold text-gray-900">Rp
                                                            {{ number_format($ob->amount, 0, ',', '.') }}</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::createFromDate($ob->year, $ob->month, 1)->translatedFormat('F Y') }}
                                                    </p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-6 text-gray-500 text-sm">
                                        Tidak ada tagihan tertunggak.
                                    </div>
                                @endif
                            </div>

                            {{-- Additional Items --}}
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                <h4 class="font-bold text-gray-800 mb-4">Pembayaran Lainnya</h4>

                                <template x-for="(item, index) in customItems" :key="index">
                                    <div class="flex gap-3 mb-3 items-start">
                                        <div class="flex-1">
                                            <select :name="`custom_items[${index}][fee_type_id]`" x-model="item.fee_type_id"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-1 outline-none">
                                                <option value="">- Pilih Jenis Biaya -</option>
                                                @foreach($feeTypes as $ft)
                                                    <option value="{{ $ft->id }}">{{ $ft->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-32">
                                            <input type="number" :name="`custom_items[${index}][amount]`"
                                                x-model.number="item.amount" placeholder="Jumlah" required
                                                @input="calculateTotal()"
                                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-1 outline-none text-right">
                                        </div>
                                        <button type="button" @click="removeitem(index)"
                                            class="p-2 text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                <button type="button" @click="addItem()"
                                    class="text-sm text-indigo-600 font-medium hover:text-indigo-800 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Item Manual
                                </button>
                            </div>
                        </div>

                        {{-- Right Column: Summary --}}
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                                <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">Ringkasan Pembayaran</h4>

                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Tagihan Dipilih</span>
                                        <span class="font-medium" x-text="formatRupiah(obligationsTotal)"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Item Tambahan</span>
                                        <span class="font-medium" x-text="formatRupiah(customTotal)"></span>
                                    </div>
                                    <div class="border-t pt-3 flex justify-between items-center">
                                        <span class="font-bold text-lg text-gray-900">Total</span>
                                        <span class="font-bold text-xl text-indigo-600" x-text="formatRupiah(total)"></span>
                                    </div>

                                    <div class="pt-4 space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Metode
                                                Pembayaran</label>
                                            <select name="payment_method" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-1 outline-none">
                                                <option value="cash">Tunai (Cash)</option>
                                                <option value="transfer">Transfer Bank</option>
                                                <option value="qris">QRIS</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan
                                                (Opsional)</label>
                                            <textarea name="notes" rows="2"
                                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-1 outline-none"></textarea>
                                        </div>
                                    </div>

                                    <button type="submit" :disabled="total <= 0"
                                        :class="{'opacity-50 cursor-not-allowed': total <= 0}"
                                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition-all transform hover:-translate-y-0.5 mt-4">
                                        Proses Pembayaran
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <script>
                function transactionForm(initialObligations) {
                    return {
                        selectedObligations: [],
                        obligationsTotal: 0,
                        customItems: [],
                        customTotal: 0,
                        total: 0,

                        toggleObligation(id, amount) {
                            if (this.selectedObligations.includes(id)) {
                                this.selectedObligations = this.selectedObligations.filter(item => item !== id);
                                this.obligationsTotal -= amount;
                            } else {
                                this.selectedObligations.push(id);
                                this.obligationsTotal += amount;
                            }
                            this.calculateTotal();
                        },

                        addItem() {
                            this.customItems.push({ fee_type_id: '', amount: 0 });
                        },

                        removeitem(index) {
                            this.customItems.splice(index, 1);
                            this.calculateTotal();
                        },

                        calculateTotal() {
                            this.customTotal = this.customItems.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
                            this.total = this.obligationsTotal + this.customTotal;
                        },

                        formatRupiah(amount) {
                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
                        }
                    }
                }
            </script>
        @else
            <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Silakan pilih siswa terlebih dahulu</h3>
                <p class="mt-1 text-sm text-gray-500">Gunakan form pencarian di atas untuk memulai transaksi.</p>
            </div>
        @endif
    </div>
@endsection