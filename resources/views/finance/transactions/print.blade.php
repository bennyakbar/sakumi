<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi #{{ $transaction->transaction_number }}</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 0;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #333;
            -webkit-print-color-adjust: exact;
        }

        .container {
            width: 210mm;
            height: 148mm;
            padding: 10mm 15mm;
            box-sizing: border-box;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #047857;
            /* Emerald 700 */
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .logo {
            height: 50px;
            width: auto;
            object-fit: contain;
        }

        .header-content {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }

        .school-name {
            font-size: 16pt;
            font-weight: 800;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .school-address {
            font-size: 8pt;
            color: #666;
            line-height: 1.2;
        }

        /* INFO GRID */
        .info-bar {
            background-color: #ecfdf5;
            /* Emerald 50 */
            border-radius: 6px;
            padding: 8px 12px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            font-size: 9pt;
            margin-bottom: 10px;
            border: 1px solid #d1fae5;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 7pt;
            text-transform: uppercase;
            color: #059669;
            /* Emerald 600 */
            font-weight: 600;
            margin-bottom: 1px;
        }

        .info-value {
            font-weight: bold;
            color: #1f2937;
        }

        /* TABLE */
        .table-container {
            flex: 1;
        }

        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .transaction-table th {
            text-align: left;
            padding: 6px 8px;
            background-color: #047857;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 8pt;
        }

        .transaction-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }

        .transaction-table tr:last-child td {
            border-bottom: 2px solid #047857;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* TOTAL & TERBILANG */
        .summary-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .terbilang {
            flex: 1;
            font-style: italic;
            font-size: 8pt;
            color: #555;
            padding-right: 20px;
            margin-top: 5px;
        }

        .total-box {
            background: #047857;
            color: white;
            padding: 6px 15px;
            border-radius: 4px;
            font-size: 11pt;
            font-weight: bold;
            white-space: nowrap;
        }

        /* FOOTER */
        .footer {
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            margin-top: auto;
            /* Push to bottom */
        }

        .signature-block {
            text-align: center;
        }

        .signature-space {
            height: 40px;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 140px;
            margin: 0 auto;
            margin-top: 2px;
        }

        .signature-role {
            font-size: 8pt;
            color: #666;
            margin-bottom: 2px;
        }

        /* WATERMARK */
        .watermark {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 80pt;
            color: #047857;
            opacity: 0.03;
            font-weight: 900;
            text-transform: uppercase;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container">
        <div class="watermark">LUNAS</div>

        <!-- Header -->
        <div class="header">
            <img src="{{ asset('img/yayasan_logo.png') }}" alt="Yayasan" class="logo" onerror="this.style.opacity=0">
            <div class="header-content">
                <div class="school-name">MI NURUL FALAH</div>
                <div class="school-address">
                    Komp. Sukamenak Indah Blok G No. 4 A, Ds. Sukamenak, Kec. Margahayu<br>
                    Kab. Bandung, Prov. Jawa Barat, Indonesia
                </div>
            </div>
            <img src="{{ asset('img/logo.png') }}" alt="MI" class="logo" onerror="this.style.opacity=0">
        </div>

        <!-- Info Bar -->
        <div class="info-bar">
            <div class="info-item">
                <div class="info-label">No. Transaksi</div>
                <div class="info-value">{{ $transaction->transaction_number }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Siswa / Kelas</div>
                <div class="info-value">{{ Str::limit($transaction->student->name, 20) }}
                    ({{ $transaction->student->schoolClass->name }})</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tanggal / Metode</div>
                <div class="info-value">{{ $transaction->transaction_date->format('d/m/Y') }} /
                    {{ ucfirst($transaction->payment_method) }}
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th>Keterangan</th>
                        <th width="25%" class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item->description }}</td>
                            <td class="text-right">{{ number_format($item->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    {{-- Fill empty rows to maintain layout stability, limit to 5 total rows --}}
                    @for($i = count($transaction->items); $i < 4; $i++)
                        <tr>
                            <td class="text-center">&nbsp;</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="summary-section">
            <div class="terbilang">
                Terbilang: #
                {{ ucwords(\NumberFormatter::create('id', \NumberFormatter::SPELLOUT)->format($transaction->total_amount)) }}
                Rupiah #
            </div>
            <div class="total-box">
                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="signature-block">
                <div class="signature-role">Penyetor</div>
                <div class="signature-space"></div>
                <div class="signature-line"></div>
                <div style="font-size: 9pt; font-weight: bold;">( ........................... )</div>
            </div>
            <div class="signature-block">
                <div class="signature-role">Bandung, {{ now()->translatedFormat('d F Y') }}</div>
                <div class="signature-role">Penerima</div>
                <div class="signature-space"></div>
                <div class="signature-line"></div>
                <div style="font-size: 9pt; font-weight: bold;">{{ $transaction->creator->name ?? 'Admin Keuangan' }}
                </div>
                <div style="font-size: 8pt; margin-top: 2px;">{{ $transaction->creator->position ?? 'Staff TU' }}</div>
            </div>
        </div>
    </div>
</body>

</html>