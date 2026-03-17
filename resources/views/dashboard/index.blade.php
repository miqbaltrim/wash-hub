@extends('layouts.app')
@section('title', 'Dashboard')
@section('header')<h2>Dashboard</h2><p>{{ now()->translatedFormat('l, d F Y') }}</p>@endsection
@section('actions')<a href="{{ route('transactions.create') }}" class="btn-gold"><span>+</span> Transaksi Baru</a>@endsection

@section('content')
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
    @php $cards = [
        ['Transaksi Hari Ini', $todayTransactions, '🧾', 'var(--gold-50)', 'var(--gold)'],
        ['Revenue Hari Ini', 'Rp '.number_format($todayRevenue,0,',','.'), '💰', '#f0fdf4', '#16a34a'],
        ['Revenue Bulan Ini', 'Rp '.number_format($monthlyRevenue,0,',','.'), '📊', '#eff6ff', '#2563eb'],
        ['Total Customer', $totalCustomers, '👥', '#faf5ff', '#9333ea'],
    ]; @endphp
    @foreach($cards as $c)
    <div class="card stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div><p class="stat-label">{{ $c[0] }}</p><p class="stat-value">{{ $c[1] }}</p></div>
            <div class="stat-icon" style="background:{{ $c[3] }}"><span style="font-size:1.3rem">{{ $c[2] }}</span></div>
        </div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <div class="card" style="padding:1.25rem">
        <h3 style="font-size:.95rem;font-weight:700;color:var(--dark);margin-bottom:1rem">Revenue 7 Hari Terakhir</h3>
        <canvas id="revenueChart" height="220"></canvas>
    </div>
    <div class="card" style="padding:1.25rem">
        <h3 style="font-size:.95rem;font-weight:700;color:var(--dark);margin-bottom:1rem">Top Layanan</h3>
        @forelse($topServices as $i => $svc)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:.6rem 0;{{ !$loop->last ? 'border-bottom:1px solid var(--stone-100)' : '' }}">
            <div style="display:flex;align-items:center;gap:.65rem">
                <span style="width:24px;height:24px;border-radius:6px;background:{{ $i===0?'var(--gold-100)':'var(--stone-100)' }};display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:{{ $i===0?'var(--gold-dark)':'var(--stone-500)' }}">{{ $i+1 }}</span>
                <div><p style="font-size:.82rem;font-weight:600;color:var(--dark)">{{ $svc->service_name }}</p><p style="font-size:.7rem;color:var(--stone-500)">{{ $svc->total_sold }}x terjual</p></div>
            </div>
            <span style="font-size:.78rem;font-weight:700;color:var(--dark)">Rp {{ number_format($svc->revenue,0,',','.') }}</span>
        </div>
        @empty
        <p style="color:var(--stone-500);font-size:.85rem;text-align:center;padding:2rem 0">Belum ada data</p>
        @endforelse
    </div>
</div>

<div class="card" style="padding:1.25rem">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
        <h3 style="font-size:.95rem;font-weight:700;color:var(--dark)">Antrian Cuci Aktif <span class="badge badge-gold" style="margin-left:.5rem">{{ $activeWashes->count() }}</span></h3>
    </div>
    @if($activeWashes->count() > 0)
    <table>
        <thead><tr><th>Invoice</th><th>Customer</th><th>Plat</th><th>Layanan</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        @foreach($activeWashes as $w)
        <tr>
            <td class="mono"><a href="{{ route('transactions.show', $w) }}" style="color:var(--gold-dark);text-decoration:none">{{ $w->invoice_number }}</a></td>
            <td>{{ $w->customerProfile->user->name ?? 'Walk-in' }}</td>
            <td style="font-weight:700">{{ $w->plate_number }}</td>
            <td>@foreach($w->details as $d)<span class="badge badge-gray" style="margin-right:3px">{{ $d->service_name }}</span>@endforeach</td>
            <td><span class="badge {{ $w->wash_status==='waiting' ? 'badge-yellow' : 'badge-blue' }}">{{ $w->wash_status==='waiting' ? 'Menunggu' : 'Dicuci' }}</span></td>
            <td>
                <form method="POST" action="{{ route('transactions.update-status', $w) }}" style="display:inline">@csrf @method('PATCH')
                    @if($w->wash_status==='waiting')
                    <input type="hidden" name="wash_status" value="in_progress"><button class="btn-dark btn-sm" type="submit">Mulai</button>
                    @else
                    <input type="hidden" name="wash_status" value="done"><button class="btn-gold btn-sm" type="submit" style="width:auto">Selesai</button>
                    @endif
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state"><p>Tidak ada antrian aktif saat ini ✨</p></div>
    @endif
</div>
@endsection

@push('head')<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>@endpush
@push('scripts')
<script>
new Chart(document.getElementById('revenueChart'), {
    type:'bar',
    data:{
        labels:{!! json_encode($revenueChart->pluck('date')->map(fn($d)=>\Carbon\Carbon::parse($d)->format('d/m'))) !!},
        datasets:[{label:'Revenue',data:{!! json_encode($revenueChart->pluck('total')) !!},backgroundColor:'#f59e0b',borderRadius:6,borderSkipped:false}]
    },
    options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'Rp '+(v/1000)+'k'},grid:{color:'#f5f5f4'}},x:{grid:{display:false}}}}
});
</script>
@endpush
