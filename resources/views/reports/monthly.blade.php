@extends('layouts.app')
@section('title', 'Laporan Bulanan')
@section('header')<h2>Laporan Bulanan</h2><p>{{ \Carbon\Carbon::create($year,$month)->translatedFormat('F Y') }}</p>@endsection
@section('actions')<a href="{{ route('reports.export-pdf',['type'=>'monthly','month'=>$month,'year'=>$year]) }}" class="btn-dark btn-sm">📄 Export PDF</a>@endsection
@section('content')
<div class="card" style="padding:1rem;margin-bottom:1rem">
    <form method="GET" style="display:flex;align-items:center;gap:.5rem">
        <select name="month" class="form-input form-select" style="width:160px">@for($m=1;$m<=12;$m++)<option value="{{ $m }}" {{ $month==$m?'selected':'' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>@endfor</select>
        <select name="year" class="form-input form-select" style="width:120px">@for($y=now()->year;$y>=now()->year-3;$y--)<option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>@endfor</select>
        <button type="submit" class="btn-gold btn-sm">Tampilkan</button>
    </form>
</div>
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
    <div class="card stat-card"><p class="stat-label">Total Transaksi</p><p class="stat-value">{{ $summary['total_transactions'] }}</p></div>
    <div class="card stat-card"><p class="stat-label">Revenue</p><p class="stat-value" style="color:var(--gold-dark)">Rp {{ number_format($summary['total_revenue'],0,',','.') }}</p></div>
    <div class="card stat-card"><p class="stat-label">Rata-rata/Hari</p><p class="stat-value">Rp {{ number_format($summary['avg_daily_revenue'],0,',','.') }}</p></div>
    <div class="card stat-card"><p class="stat-label">Total Diskon</p><p class="stat-value" style="color:#dc2626">Rp {{ number_format($summary['total_discount'],0,',','.') }}</p></div>
</div>
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <div class="card" style="padding:1.25rem"><h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem">Revenue Harian</h3><canvas id="dailyChart" height="220"></canvas></div>
    <div class="card" style="padding:1.25rem"><h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem">Metode Pembayaran</h3><canvas id="paymentChart" height="220"></canvas></div>
</div>
<div class="card" style="padding:1.25rem;margin-bottom:1.5rem">
    <h3 style="font-size:.9rem;font-weight:700;margin-bottom:.75rem">Layanan Terpopuler</h3>
    @foreach($servicePopularity as $i=>$s)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem 0;{{ !$loop->last?'border-bottom:1px solid var(--stone-100)':'' }}">
        <div style="display:flex;align-items:center;gap:.5rem"><span style="width:24px;height:24px;border-radius:6px;background:{{ $i===0?'var(--gold-100)':'var(--stone-100)' }};display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:{{ $i===0?'var(--gold-dark)':'var(--stone-500)' }}">{{ $i+1 }}</span><span style="font-weight:600;font-size:.85rem">{{ $s->service_name }}</span></div>
        <div style="display:flex;align-items:center;gap:1rem"><span class="badge badge-gold">{{ $s->total_qty }}x</span><span style="font-weight:700;font-size:.85rem">Rp {{ number_format($s->total_revenue,0,',','.') }}</span></div>
    </div>
    @endforeach
</div>
<div class="card" style="overflow:hidden">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--stone-200)"><h3 style="font-size:.9rem;font-weight:700">Detail Per Hari</h3></div>
    <table>
        <thead><tr><th>Tanggal</th><th style="text-align:center">Transaksi</th><th style="text-align:right">Revenue</th><th style="text-align:right">Diskon</th></tr></thead>
        <tbody>@foreach($dailyData as $d)<tr><td>{{ \Carbon\Carbon::parse($d->date)->format('d M Y (D)') }}</td><td style="text-align:center;font-weight:600">{{ $d->total_trx }}</td><td style="text-align:right;font-weight:700;color:var(--gold-dark)">Rp {{ number_format($d->revenue,0,',','.') }}</td><td style="text-align:right;color:#dc2626">Rp {{ number_format($d->discount,0,',','.') }}</td></tr>@endforeach</tbody>
    </table>
</div>
@endsection
@push('head')<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>@endpush
@push('scripts')
<script>
new Chart(document.getElementById('dailyChart'),{type:'line',data:{labels:{!! json_encode($dailyData->pluck('date')->map(fn($d)=>\Carbon\Carbon::parse($d)->format('d'))) !!},datasets:[{label:'Revenue',data:{!! json_encode($dailyData->pluck('revenue')) !!},borderColor:'#f59e0b',backgroundColor:'rgba(245,158,11,.1)',fill:true,tension:.3,pointBackgroundColor:'#f59e0b'}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'Rp '+(v/1000)+'k'},grid:{color:'#f5f5f4'}},x:{grid:{display:false}}}}});
new Chart(document.getElementById('paymentChart'),{type:'doughnut',data:{labels:{!! json_encode($paymentBreakdown->pluck('payment_method')->map(fn($m)=>strtoupper(str_replace('_',' ',$m)))) !!},datasets:[{data:{!! json_encode($paymentBreakdown->pluck('total')) !!},backgroundColor:['#f59e0b','#0c0a09','#d97706','#78716c','#fbbf24','#44403c']}]},options:{responsive:true}});
</script>
@endpush
