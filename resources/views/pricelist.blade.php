<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pricelist - {{ config('app.name', 'Wash Hub') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        :root{--gold:#f59e0b;--gold-light:#fbbf24;--gold-dark:#d97706;--dark:#0c0a09;--dark-2:#1c1917;--stone-100:#f5f5f4;--stone-200:#e7e5e4;--stone-500:#78716c}
        *{box-sizing:border-box;margin:0;padding:0}body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--stone-100);color:var(--dark)}
    </style>
</head>
<body>
    <div style="background:var(--dark);color:white;position:relative;overflow:hidden">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 30% 50%,rgba(245,158,11,.15) 0%,transparent 60%),radial-gradient(ellipse at 70% 20%,rgba(251,191,36,.1) 0%,transparent 50%)"></div>
        <div style="max-width:900px;margin:0 auto;padding:3rem 1.5rem;text-align:center;position:relative;z-index:1">
            <h1 style="font-family:'Space Mono',monospace;font-size:2.5rem;font-weight:700">Wash<span style="color:var(--gold-light)">Hub</span></h1>
            <p style="color:var(--stone-500);font-size:1rem;margin-top:.5rem">Professional Car Wash Service</p>
            <p style="color:rgba(255,255,255,.3);font-size:.82rem;margin-top:.25rem">{{ \App\Models\Setting::getValue('app_address', '') }}</p>
            <div style="margin-top:1.5rem;display:flex;justify-content:center;gap:.75rem">
                <a href="{{ route('login') }}" style="padding:.65rem 1.5rem;background:var(--gold);color:var(--dark);font-weight:700;border-radius:8px;text-decoration:none;font-size:.9rem">Login</a>
                <a href="{{ route('register') }}" style="padding:.65rem 1.5rem;background:rgba(255,255,255,.08);color:white;font-weight:600;border-radius:8px;text-decoration:none;font-size:.9rem;border:1px solid rgba(255,255,255,.12)">Daftar Member</a>
            </div>
        </div>
    </div>

    <div style="max-width:900px;margin:0 auto;padding:2.5rem 1.5rem">
        <h2 style="font-size:1.5rem;font-weight:800;text-align:center;margin-bottom:2rem">Daftar Harga Layanan</h2>
        @foreach($categories as $cat)
        <div style="margin-bottom:2rem">
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem">
                <span style="width:32px;height:32px;border-radius:8px;background:var(--dark);display:flex;align-items:center;justify-content:center;color:var(--gold);font-weight:700;font-size:.82rem">{{ $loop->iteration }}</span>
                <h3 style="font-size:1rem;font-weight:700">{{ $cat->name }}</h3>
                @if($cat->description)<span style="font-size:.78rem;color:var(--stone-500)">— {{ $cat->description }}</span>@endif
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:.75rem">
                @foreach($cat->activeServices as $svc)
                <div style="background:white;border-radius:12px;border:1px solid var(--stone-200);padding:1rem;transition:all .15s;cursor:default" onmouseover="this.style.borderColor='var(--gold)';this.style.boxShadow='0 4px 12px rgba(245,158,11,.1)'" onmouseout="this.style.borderColor='var(--stone-200)';this.style.boxShadow='none'">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start">
                        <div><h4 style="font-size:.9rem;font-weight:700">{{ $svc->name }}</h4><p style="font-size:.72rem;color:var(--stone-500);margin-top:.2rem">{{ $svc->duration_minutes }} menit • {{ $svc->vehicle_type==='all'?'Semua':ucfirst($svc->vehicle_type) }}</p>@if($svc->description)<p style="font-size:.72rem;color:var(--stone-500);margin-top:.2rem">{{ $svc->description }}</p>@endif</div>
                        <span style="font-size:1rem;font-weight:800;color:var(--gold-dark);white-space:nowrap;margin-left:.75rem">Rp {{ number_format($svc->price,0,',','.') }}</span>
                    </div>
                    @if($svc->points_earned>0)<div style="margin-top:.5rem"><span style="background:rgba(245,158,11,.12);color:var(--gold-dark);padding:.15rem .5rem;border-radius:4px;font-size:.68rem;font-weight:600">+{{ $svc->points_earned }} poin</span></div>@endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div style="background:var(--dark);border-radius:16px;padding:2rem;text-align:center;margin-top:2rem;position:relative;overflow:hidden">
            <div style="position:absolute;right:-20px;top:-20px;width:120px;height:120px;border-radius:50%;background:rgba(245,158,11,.1)"></div>
            <h3 style="color:var(--gold-light);font-size:1.25rem;font-weight:800">🎉 Program Loyalitas</h3>
            <p style="color:rgba(255,255,255,.5);margin-top:.5rem">Cuci 10x dapat 1x cuci <span style="color:var(--gold-light);font-weight:700">GRATIS!</span></p>
            <a href="{{ route('register') }}" style="display:inline-block;margin-top:1rem;padding:.75rem 2rem;background:var(--gold);color:var(--dark);font-weight:700;border-radius:8px;text-decoration:none">Daftar Sekarang</a>
        </div>
    </div>

    <div style="background:var(--dark);color:var(--stone-500);text-align:center;padding:1.5rem;font-size:.78rem;margin-top:2rem">
        <p>&copy; {{ date('Y') }} {{ config('app.name','Wash Hub') }}</p>
        <p style="margin-top:.25rem">{{ \App\Models\Setting::getValue('app_phone','') }}</p>
    </div>
</body>
</html>
