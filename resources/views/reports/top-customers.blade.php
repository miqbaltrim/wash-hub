@extends('layouts.app')
@section('title', 'Top Customers')
@section('header')<h2>Top Customers</h2><p>Pelanggan terbaik berdasarkan total spending</p>@endsection
@section('content')
<div class="card" style="overflow:hidden">
    <table>
        <thead><tr><th style="text-align:center;width:50px">#</th><th>Customer</th><th>Member</th><th style="text-align:center">Cuci</th><th style="text-align:center">Poin</th><th style="text-align:right">Total Spending</th><th style="text-align:center">Aksi</th></tr></thead>
        <tbody>
        @foreach($customers as $i => $c)
        <tr>
            <td style="text-align:center">
                @if($i<3)<span style="width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;color:white;background:{{ $i===0?'var(--gold)':($i===1?'var(--stone-500)':'#a16207') }}">{{ $i+1 }}</span>
                @else<span style="color:var(--stone-500)">{{ $i+1 }}</span>@endif
            </td>
            <td style="font-weight:600">{{ $c->user->name ?? '-' }}</td>
            <td class="mono" style="color:var(--gold-dark)">{{ $c->member_code }}</td>
            <td style="text-align:center;font-weight:700">{{ $c->transactions_count }}</td>
            <td style="text-align:center"><span class="badge badge-gold">{{ $c->total_points }}</span></td>
            <td style="text-align:right;font-weight:800;color:var(--gold-dark)">Rp {{ number_format($c->transactions_sum_grand_total??0,0,',','.') }}</td>
            <td style="text-align:center"><a href="{{ route('admin.customers.show',$c) }}" class="btn-outline btn-sm">Detail</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
