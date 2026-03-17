@extends('layouts.app')
@section('title', 'Detail Customer')
@section('header')<h2>Detail Customer</h2>@endsection
@section('actions')<a href="{{ route('admin.customers.index') }}" class="btn-outline btn-sm">← Kembali</a>@endsection
@section('content')
<div style="display:grid;grid-template-columns:300px 1fr;gap:1.5rem">
<div style="display:flex;flex-direction:column;gap:1rem">
    <div class="card" style="padding:1.5rem;text-align:center">
        <div style="width:60px;height:60px;border-radius:14px;background:var(--gold);display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;font-weight:800;font-size:1.5rem;color:var(--dark)">{{ strtoupper(substr($customer->user->name,0,1)) }}</div>
        <h3 style="font-size:1.1rem;font-weight:700">{{ $customer->user->name }}</h3>
        <p style="font-size:.82rem;color:var(--gold-dark);font-family:'Space Mono',monospace">{{ $customer->member_code }}</p>
        <p style="font-size:.82rem;color:var(--stone-500)">{{ $customer->phone }}</p>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;margin-top:1rem">
            <div style="background:var(--gold-50);border-radius:8px;padding:.5rem"><p style="font-size:.6rem;color:var(--stone-500)">Cuci</p><p style="font-weight:800;color:var(--gold-dark)">{{ $customer->total_washes }}</p></div>
            <div style="background:var(--stone-100);border-radius:8px;padding:.5rem"><p style="font-size:.6rem;color:var(--stone-500)">Poin</p><p style="font-weight:800">{{ $customer->total_points }}</p></div>
            <div style="background:#f0fdf4;border-radius:8px;padding:.5rem"><p style="font-size:.6rem;color:var(--stone-500)">Free</p><p style="font-weight:800;color:#16a34a">{{ $customer->getAvailableFreeWashes() }}</p></div>
        </div>
        <div style="margin-top:1rem;background:var(--stone-100);border-radius:8px;padding:.75rem">
            <p style="font-size:.7rem;color:var(--stone-500);margin-bottom:.35rem">Progress cuci gratis</p>
            <div style="width:100%;height:6px;background:var(--stone-200);border-radius:3px;overflow:hidden"><div style="height:100%;background:var(--gold);border-radius:3px;width:{{ ($customer->total_washes%10)*10 }}%;transition:width .3s"></div></div>
            <p style="font-size:.68rem;color:var(--stone-500);margin-top:.25rem">{{ $customer->total_washes%10 }}/10</p>
        </div>
        <a href="{{ route('admin.customers.edit',$customer) }}" class="btn-gold" style="margin-top:1rem;display:block;text-align:center">Edit Customer</a>
    </div>
    <div class="card" style="padding:1.25rem">
        <h3 style="font-size:.85rem;font-weight:700;margin-bottom:.75rem">Kendaraan</h3>
        @foreach($customer->vehicles as $v)
        <div style="display:flex;align-items:center;gap:.5rem;padding:.5rem 0;{{ !$loop->last?'border-bottom:1px solid var(--stone-100)':'' }}">
            <span style="font-size:1.1rem">{{ $v->vehicle_type==='motor'?'🏍':'🚗' }}</span>
            <div><p style="font-weight:700;font-size:.82rem;margin:0">{{ $v->plate_number }}</p><p style="font-size:.68rem;color:var(--stone-500);margin:0">{{ $v->brand }} {{ $v->model }}</p></div>
        </div>
        @endforeach
        <form method="POST" action="{{ route('admin.customers.add-vehicle',$customer) }}" style="margin-top:.75rem;padding-top:.75rem;border-top:1px solid var(--stone-200)">@csrf
            <p style="font-size:.78rem;font-weight:600;margin-bottom:.5rem">+ Tambah Kendaraan</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.35rem">
                <input type="text" name="plate_number" required placeholder="Plat" class="form-input" style="font-size:.78rem;padding:.4rem .6rem;text-transform:uppercase">
                <select name="vehicle_type" required class="form-input form-select" style="font-size:.78rem;padding:.4rem .6rem"><option value="mobil">Mobil</option><option value="motor">Motor</option><option value="suv">SUV</option></select>
                <input type="text" name="brand" placeholder="Merk" class="form-input" style="font-size:.78rem;padding:.4rem .6rem">
                <input type="text" name="vehicle_model" placeholder="Model" class="form-input" style="font-size:.78rem;padding:.4rem .6rem">
            </div>
            <button type="submit" class="btn-dark btn-sm" style="width:100%;margin-top:.5rem">Tambah</button>
        </form>
    </div>
</div>
<div style="display:flex;flex-direction:column;gap:1rem">
    <div class="card" style="overflow:hidden">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--stone-200)"><h3 style="font-size:.9rem;font-weight:700">Transaksi Terakhir</h3></div>
        <table>
            <thead><tr><th>Invoice</th><th>Tanggal</th><th>Layanan</th><th style="text-align:right">Total</th></tr></thead>
            <tbody>
            @forelse($customer->transactions as $trx)
            <tr>
                <td class="mono"><a href="{{ route('transactions.show',$trx) }}" style="color:var(--gold-dark);text-decoration:none">{{ $trx->invoice_number }}</a></td>
                <td>{{ $trx->transaction_date->format('d/m/Y') }}</td>
                <td>@foreach($trx->details as $d)<span class="badge badge-gray" style="margin:1px">{{ $d->service_name }}</span>@endforeach</td>
                <td style="text-align:right;font-weight:700">{{ $trx->is_reward_claim?'GRATIS':'Rp '.number_format($trx->grand_total,0,',','.') }}</td>
            </tr>
            @empty<tr><td colspan="4"><div class="empty-state"><p>Belum ada transaksi</p></div></td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="card" style="padding:1.25rem">
        <h3 style="font-size:.9rem;font-weight:700;margin-bottom:.75rem">Riwayat Poin</h3>
        @forelse($customer->pointHistories as $ph)
        <div style="display:flex;justify-content:space-between;padding:.5rem 0;{{ !$loop->last?'border-bottom:1px solid var(--stone-100)':'' }}">
            <div><p style="font-size:.82rem;font-weight:600;color:{{ $ph->type==='earned'?'#16a34a':'#dc2626' }};margin:0">{{ $ph->type==='earned'?'+':'-' }}{{ $ph->points }} poin</p><p style="font-size:.7rem;color:var(--stone-500);margin:0">{{ $ph->description }}</p></div>
            <div style="text-align:right"><p style="font-size:.68rem;color:var(--stone-300);margin:0">{{ $ph->created_at->format('d/m/Y H:i') }}</p><p style="font-size:.68rem;color:var(--stone-500);margin:0">Saldo: {{ $ph->balance_after }}</p></div>
        </div>
        @empty<p style="color:var(--stone-500);font-size:.82rem">Belum ada riwayat poin</p>@endforelse
    </div>
</div>
</div>
@endsection
