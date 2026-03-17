<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $transaction->invoice_number }}</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Courier New',Courier,monospace;font-size:12px;width:302px;margin:0 auto;padding:5px;color:#000}
        .center{text-align:center}.right{text-align:right}.bold{font-weight:bold}
        .divider{border-top:1px dashed #000;margin:5px 0}
        .double-divider{border-top:2px double #000;margin:5px 0}
        .header h1{font-size:18px;font-weight:bold;margin-bottom:2px}
        .header p{font-size:10px;line-height:1.4}
        .info-row{display:flex;justify-content:space-between;font-size:11px}
        .item-row{margin-bottom:3px}
        .item-name{font-size:12px}
        .item-detail{display:flex;justify-content:space-between;font-size:11px;padding-left:10px}
        .grand-total{font-size:16px;font-weight:bold}
        .footer{text-align:center;font-size:10px;margin-top:8px;line-height:1.4}
        .loyalty-box{border:1px solid #000;padding:4px;margin:5px 0;text-align:center;font-size:11px}
        @media print{body{width:100%}@page{margin:0;size:80mm auto}}
    </style>
</head>
<body>
    <div class="header center">
        <h1>{{ \App\Models\Setting::getValue('receipt_header', 'WASH HUB') }}</h1>
        <p>{{ \App\Models\Setting::getValue('app_tagline', 'Professional Car Wash') }}</p>
        <p>{{ \App\Models\Setting::getValue('app_address', '') }}</p>
        <p>Telp: {{ \App\Models\Setting::getValue('app_phone', '') }}</p>
    </div>
    <div class="double-divider"></div>
    <div>
        <div class="info-row"><span>No: {{ $transaction->invoice_number }}</span></div>
        <div class="info-row"><span>Tgl: {{ $transaction->created_at->format('d/m/Y H:i') }}</span></div>
        <div class="info-row"><span>Kasir: {{ $transaction->cashier->name ?? '-' }}</span></div>
        @if($transaction->customerProfile)
        <div class="info-row"><span>Member: {{ $transaction->customerProfile->member_code }}</span></div>
        <div class="info-row"><span>Nama: {{ $transaction->customerProfile->user->name ?? '-' }}</span></div>
        @endif
        <div class="info-row"><span>Plat: {{ $transaction->plate_number }}</span><span>{{ strtoupper($transaction->vehicle_type) }}</span></div>
    </div>
    <div class="divider"></div>
    @foreach($transaction->details as $detail)
    <div class="item-row">
        <div class="item-name">{{ $detail->service_name }}</div>
        <div class="item-detail"><span>{{ $detail->qty }} x Rp {{ number_format($detail->unit_price,0,',','.') }}</span><span>Rp {{ number_format($detail->subtotal,0,',','.') }}</span></div>
        @if($detail->discount > 0)<div class="item-detail"><span>Disc:</span><span>-Rp {{ number_format($detail->discount,0,',','.') }}</span></div>@endif
    </div>
    @endforeach
    <div class="divider"></div>
    <div>
        <div class="info-row"><span>Subtotal</span><span>Rp {{ number_format($transaction->subtotal,0,',','.') }}</span></div>
        @if($transaction->discount_amount > 0)<div class="info-row"><span>Diskon</span><span>-Rp {{ number_format($transaction->discount_amount,0,',','.') }}</span></div>@endif
        <div class="double-divider"></div>
        @if($transaction->is_reward_claim)
        <div class="info-row grand-total center"><span>*** GRATIS (REWARD) ***</span></div>
        @else
        <div class="info-row grand-total"><span>TOTAL</span><span>Rp {{ number_format($transaction->grand_total,0,',','.') }}</span></div>
        @endif
        <div class="divider"></div>
        <div class="info-row"><span>Bayar ({{ strtoupper(str_replace('_',' ',$transaction->payment_method)) }})</span><span>Rp {{ number_format($transaction->payment_amount,0,',','.') }}</span></div>
        @if($transaction->change_amount > 0)<div class="info-row"><span>Kembali</span><span>Rp {{ number_format($transaction->change_amount,0,',','.') }}</span></div>@endif
    </div>
    @if($transaction->customerProfile)
    <div class="divider"></div>
    <div class="loyalty-box">
        @if($transaction->points_earned > 0)<div>+{{ $transaction->points_earned }} Poin diperoleh</div>@endif
        <div>Total Poin: {{ $transaction->customerProfile->total_points }}</div>
        <div>Total Cuci: {{ $transaction->customerProfile->total_washes }}/10</div>
        @php $rem = 10 - ($transaction->customerProfile->total_washes % 10); @endphp
        @if($rem < 10 && $rem > 0)<div class="bold">{{ $rem }} cuci lagi dapat GRATIS!</div>@endif
        @if($transaction->customerProfile->getAvailableFreeWashes() > 0)<div class="bold">*** ANDA PUNYA {{ $transaction->customerProfile->getAvailableFreeWashes() }} CUCI GRATIS! ***</div>@endif
    </div>
    @endif
    <div class="divider"></div>
    <div class="footer">
        <p>{{ \App\Models\Setting::getValue('receipt_footer', 'Terima kasih!') }}</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
    <script>window.onload=function(){window.print()}</script>
</body>
</html>
