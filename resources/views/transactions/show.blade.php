@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('header')<h2>Detail Transaksi</h2><p>{{ $transaction->invoice_number }}</p>@endsection
@section('actions')
<a href="{{ route('transactions.print', $transaction) }}" target="_blank" class="btn-dark btn-sm">🖨 Cetak</a>
<a href="{{ route('transactions.index') }}" class="btn-outline btn-sm">← Kembali</a>
@endsection
@section('content')
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
<div style="display:flex;flex-direction:column;gap:1rem">
    <div class="card" style="padding:1.25rem">
        <div style="display:flex;justify-content:space-between;margin-bottom:1rem">
            <span class="mono" style="font-size:.9rem;font-weight:700;color:var(--dark)">{{ $transaction->invoice_number }}</span>
            @php $wc=['waiting'=>'badge-yellow','in_progress'=>'badge-blue','done'=>'badge-green','picked_up'=>'badge-gray']; @endphp
            <span class="badge {{ $wc[$transaction->wash_status]??'' }}">{{ ucfirst(str_replace('_',' ',$transaction->wash_status)) }}</span>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem">
            <div><p style="font-size:.72rem;color:var(--stone-500)">Tanggal</p><p style="font-size:.85rem;font-weight:600">{{ $transaction->transaction_date->format('d/m/Y') }}</p></div>
            <div><p style="font-size:.72rem;color:var(--stone-500)">Kasir</p><p style="font-size:.85rem;font-weight:600">{{ $transaction->cashier->name ?? '-' }}</p></div>
            <div><p style="font-size:.72rem;color:var(--stone-500)">Plat</p><p style="font-size:1.1rem;font-weight:800">{{ $transaction->plate_number }}</p></div>
            <div><p style="font-size:.72rem;color:var(--stone-500)">Tipe</p><p style="font-size:.85rem;font-weight:600">{{ ucfirst($transaction->vehicle_type) }}</p></div>
        </div>
    </div>
    <div class="card" style="overflow:hidden">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--stone-200)"><h3 style="font-size:.9rem;font-weight:700">Detail Layanan</h3></div>
        <table>
            <thead><tr><th>Layanan</th><th style="text-align:center">Qty</th><th style="text-align:right">Harga</th><th style="text-align:right">Subtotal</th></tr></thead>
            <tbody>
            @foreach($transaction->details as $d)
            <tr><td><p style="font-weight:600;margin:0">{{ $d->service_name }}</p><p style="font-size:.7rem;color:var(--stone-500);margin:0">{{ $d->service_category }}</p></td><td style="text-align:center">{{ $d->qty }}</td><td style="text-align:right">Rp {{ number_format($d->unit_price,0,',','.') }}</td><td style="text-align:right;font-weight:600">Rp {{ number_format($d->subtotal,0,',','.') }}</td></tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr style="border-top:2px solid var(--stone-200)"><td colspan="3" style="text-align:right;color:var(--stone-500)">Subtotal</td><td style="text-align:right;font-weight:600">Rp {{ number_format($transaction->subtotal,0,',','.') }}</td></tr>
                @if($transaction->discount_amount>0)<tr><td colspan="3" style="text-align:right;color:var(--stone-500)">Diskon</td><td style="text-align:right;color:#dc2626">-Rp {{ number_format($transaction->discount_amount,0,',','.') }}</td></tr>@endif
                <tr><td colspan="3" style="text-align:right;font-weight:800;font-size:1rem">TOTAL</td><td style="text-align:right;font-weight:800;font-size:1.1rem;color:var(--gold-dark)">@if($transaction->is_reward_claim)GRATIS @else Rp {{ number_format($transaction->grand_total,0,',','.') }}@endif</td></tr>
            </tfoot>
        </table>
    </div>
    <div class="card" style="padding:1.25rem">
        <h3 style="font-size:.9rem;font-weight:700;margin-bottom:.75rem">Pembayaran</h3>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem">
            <div><p style="font-size:.72rem;color:var(--stone-500)">Metode</p><p style="font-weight:600;font-size:.85rem">{{ strtoupper(str_replace('_',' ',$transaction->payment_method)) }}</p></div>
            <div><p style="font-size:.72rem;color:var(--stone-500)">Status</p><p style="font-weight:600;font-size:.85rem;color:{{ $transaction->payment_status==='paid'?'#16a34a':'#dc2626' }}">{{ ucfirst($transaction->payment_status) }}</p></div>
            <div><p style="font-size:.72rem;color:var(--stone-500)">Dibayar</p><p style="font-weight:600;font-size:.85rem">Rp {{ number_format($transaction->payment_amount,0,',','.') }}</p></div>
            <div><p style="font-size:.72rem;color:var(--stone-500)">Kembali</p><p style="font-weight:600;font-size:.85rem">Rp {{ number_format($transaction->change_amount,0,',','.') }}</p></div>
        </div>
    </div>
</div>
<div style="display:flex;flex-direction:column;gap:1rem">
    <div class="card" style="padding:1.25rem">
        <h3 style="font-size:.9rem;font-weight:700;margin-bottom:.75rem">Customer</h3>
        @if($transaction->customerProfile)
        <p style="font-weight:700">{{ $transaction->customerProfile->user->name ?? '-' }}</p>
        <p style="font-size:.78rem;color:var(--gold-dark);font-family:'Space Mono',monospace">{{ $transaction->customerProfile->member_code }}</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-top:.75rem">
            <div style="background:var(--gold-50);border-radius:8px;padding:.5rem;text-align:center"><p style="font-size:.65rem;color:var(--stone-500)">Poin</p><p style="font-weight:700;color:var(--gold-dark)">{{ $transaction->customerProfile->total_points }}</p></div>
            <div style="background:var(--stone-100);border-radius:8px;padding:.5rem;text-align:center"><p style="font-size:.65rem;color:var(--stone-500)">Cuci</p><p style="font-weight:700">{{ $transaction->customerProfile->total_washes }}</p></div>
        </div>
        @if($transaction->points_earned>0)<p style="margin-top:.5rem;font-size:.82rem;color:#16a34a;font-weight:600">+{{ $transaction->points_earned }} poin diperoleh</p>@endif
        @else<p style="color:var(--stone-500);font-size:.82rem">Walk-in customer</p>@endif
    </div>
    @if($transaction->payment_status==='paid' && $transaction->wash_status!=='picked_up')
    <div class="card" style="padding:1.25rem">
        <h3 style="font-size:.9rem;font-weight:700;margin-bottom:.75rem">Update Status</h3>
        <form method="POST" action="{{ route('transactions.update-status',$transaction) }}">@csrf @method('PATCH')
        <select name="wash_status" class="form-input form-select" style="margin-bottom:.5rem"><option value="waiting" {{ $transaction->wash_status==='waiting'?'selected':'' }}>Menunggu</option><option value="in_progress" {{ $transaction->wash_status==='in_progress'?'selected':'' }}>Sedang Dicuci</option><option value="done" {{ $transaction->wash_status==='done'?'selected':'' }}>Selesai</option><option value="picked_up" {{ $transaction->wash_status==='picked_up'?'selected':'' }}>Diambil</option></select>
        <button type="submit" class="btn-gold" style="width:100%">Update</button></form>
    </div>
    @endif
    @if($transaction->payment_status==='paid')
    <form method="POST" action="{{ route('transactions.cancel',$transaction) }}" onsubmit="return confirm('Yakin batalkan?')">@csrf<button type="submit" class="btn-danger btn-sm" style="width:100%">Batalkan Transaksi</button></form>
    @endif
</div>
</div>
@endsection
