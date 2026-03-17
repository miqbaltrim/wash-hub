<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 20px; }
        h1 { font-size: 20px; text-align: center; margin-bottom: 5px; }
        h2 { font-size: 14px; text-align: center; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 11px; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #f8f8f8; font-weight: bold; }
        .summary-box { background: #f8f8f8; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <h1>{{ \App\Models\Setting::getValue('app_name', 'WASH HUB') }}</h1>
    <h2>Laporan Bulanan - {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</h2>

    @php
        $totalRevenue = $transactions->sum('grand_total');
        $totalTrx = $transactions->count();
        $totalDiscount = $transactions->sum('discount_amount');
    @endphp

    <div class="summary-box">
        <strong>Ringkasan:</strong> Total {{ $totalTrx }} transaksi | Revenue: Rp {{ number_format($totalRevenue,0,',','.') }} | Diskon: Rp {{ number_format($totalDiscount,0,',','.') }} | Rata-rata/Trx: Rp {{ number_format($totalTrx > 0 ? $totalRevenue/$totalTrx : 0,0,',','.') }}
    </div>

    <table>
        <thead><tr>
            <th>No</th><th>Invoice</th><th>Tanggal</th><th>Plat</th><th>Layanan</th><th>Bayar</th><th class="text-right">Total</th>
        </tr></thead>
        <tbody>
        @foreach($transactions as $i => $trx)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $trx->invoice_number }}</td>
            <td>{{ $trx->transaction_date->format('d/m/Y') }}</td>
            <td>{{ $trx->plate_number }}</td>
            <td>{{ $trx->details->pluck('service_name')->join(', ') }}</td>
            <td class="text-center">{{ strtoupper(str_replace('_',' ',$trx->payment_method)) }}</td>
            <td class="text-right">{{ $trx->is_reward_claim ? 'GRATIS' : 'Rp '.number_format($trx->grand_total,0,',','.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="6" class="text-right">TOTAL:</td>
            <td class="text-right">Rp {{ number_format($totalRevenue,0,',','.') }}</td>
        </tr>
        </tbody>
    </table>

    <div class="footer"><p>Dicetak: {{ now()->format('d/m/Y H:i:s') }} | {{ \App\Models\Setting::getValue('app_name', 'Wash Hub') }}</p></div>
</body>
</html>
