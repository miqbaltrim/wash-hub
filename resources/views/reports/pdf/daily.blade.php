<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Harian - {{ $date }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 20px; }
        h1 { font-size: 20px; text-align: center; margin-bottom: 5px; }
        h2 { font-size: 14px; text-align: center; color: #666; margin-bottom: 20px; }
        .info { margin-bottom: 15px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 11px; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #f8f8f8; font-weight: bold; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <h1>{{ \App\Models\Setting::getValue('app_name', 'WASH HUB') }}</h1>
    <h2>Laporan Harian - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h2>
    <div class="info">
        <p>Alamat: {{ \App\Models\Setting::getValue('app_address', '-') }}</p>
        <p>Telp: {{ \App\Models\Setting::getValue('app_phone', '-') }}</p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @php
        $totalRevenue = $transactions->sum('grand_total');
        $totalDiscount = $transactions->sum('discount_amount');
    @endphp

    <table>
        <tr><td style="width:50%;border:none;"><strong>Total Transaksi:</strong> {{ $transactions->count() }}</td><td style="border:none;"><strong>Total Revenue:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td></tr>
    </table>

    <table>
        <thead><tr>
            <th>No</th><th>Invoice</th><th>Waktu</th><th>Customer</th><th>Plat</th><th>Layanan</th><th>Bayar</th><th class="text-right">Total</th>
        </tr></thead>
        <tbody>
        @foreach($transactions as $i => $trx)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $trx->invoice_number }}</td>
            <td>{{ $trx->created_at->format('H:i') }}</td>
            <td>{{ $trx->customerProfile->user->name ?? 'Walk-in' }}</td>
            <td>{{ $trx->plate_number }}</td>
            <td>{{ $trx->details->pluck('service_name')->join(', ') }}</td>
            <td class="text-center">{{ strtoupper(str_replace('_',' ',$trx->payment_method)) }}</td>
            <td class="text-right">{{ $trx->is_reward_claim ? 'GRATIS' : 'Rp '.number_format($trx->grand_total,0,',','.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="7" class="text-right">TOTAL REVENUE:</td>
            <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>{{ \App\Models\Setting::getValue('app_name', 'Wash Hub') }} - Laporan ini digenerate otomatis oleh sistem</p>
    </div>
</body>
</html>
