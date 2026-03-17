<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Wash Hub') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--gold:#f59e0b;--gold-light:#fbbf24;--gold-dark:#d97706;--dark:#0c0a09;--dark-2:#1c1917;--stone-100:#f5f5f4;--stone-200:#e7e5e4;--stone-300:#d6d3d1;--stone-500:#78716c}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh}
        .auth-wrap{display:flex;min-height:100vh}
        .auth-left{flex:1;background:var(--dark);position:relative;overflow:hidden;display:none;align-items:center;justify-content:center}
        @media(min-width:1024px){.auth-left{display:flex}}
        .auth-left::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 20% 50%,rgba(245,158,11,.2) 0%,transparent 60%),radial-gradient(ellipse at 80% 20%,rgba(251,191,36,.1) 0%,transparent 50%)}
        .auth-left-content{position:relative;z-index:10;padding:3rem;max-width:420px}
        .drops{position:absolute;inset:0;pointer-events:none}
        .dr{position:absolute;width:5px;height:5px;background:rgba(245,158,11,.3);border-radius:50%;animation:fall linear infinite}
        .dr:nth-child(1){left:15%;animation-duration:4s}.dr:nth-child(2){left:35%;animation-duration:3.5s;animation-delay:1s;width:3px;height:3px}.dr:nth-child(3){left:55%;animation-duration:5s;animation-delay:.5s}.dr:nth-child(4){left:75%;animation-duration:3s;animation-delay:2s;width:7px;height:7px}.dr:nth-child(5){left:90%;animation-duration:4.5s;animation-delay:1.5s}
        @keyframes fall{0%{top:-10px;opacity:0}10%{opacity:1}90%{opacity:.6}100%{top:100%;opacity:0}}
        .auth-right{flex:1;display:flex;align-items:center;justify-content:center;padding:2rem;background:white}
        .form-box{width:100%;max-width:400px}
        .mobile-brand{display:block;text-align:center;margin-bottom:2rem}@media(min-width:1024px){.mobile-brand{display:none}}
        .mobile-brand h1{font-family:'Space Mono',monospace;font-size:1.6rem;font-weight:700;color:var(--dark)}
        .mobile-brand h1 span{color:var(--gold)}
        .form-header{margin-bottom:1.75rem}.form-header h2{font-size:1.4rem;font-weight:800;color:var(--dark)}.form-header p{color:var(--stone-500);font-size:.85rem;margin-top:.35rem}
        .form-group{margin-bottom:1.1rem}.form-label{display:block;font-size:.75rem;font-weight:600;color:#44403c;margin-bottom:.35rem;letter-spacing:.3px}
        .form-input{width:100%;padding:.7rem .9rem;border:1.5px solid var(--stone-200);border-radius:8px;font-size:.85rem;font-family:inherit;background:white;color:var(--dark);transition:all .2s;outline:none}
        .form-input::placeholder{color:var(--stone-300)}.form-input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(245,158,11,.1)}.form-input.error{border-color:#dc2626}
        .form-error{color:#dc2626;font-size:.72rem;margin-top:.25rem}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
        .form-checkbox{display:flex;align-items:center;gap:.5rem;margin:.75rem 0}.form-checkbox input{accent-color:var(--gold);width:15px;height:15px}.form-checkbox label{font-size:.82rem;color:var(--stone-500);cursor:pointer}
        .btn-primary{width:100%;padding:.8rem;background:var(--gold);color:var(--dark);border:none;border-radius:8px;font-size:.9rem;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s}
        .btn-primary:hover{background:var(--gold-dark);transform:translateY(-1px);box-shadow:0 4px 15px rgba(245,158,11,.3)}
        .form-footer{text-align:center;margin-top:1.25rem;font-size:.85rem;color:var(--stone-500)}.form-footer a{color:var(--gold-dark);font-weight:600;text-decoration:none}.form-footer a:hover{text-decoration:underline}
        .form-link{color:var(--gold-dark);font-size:.75rem;text-decoration:none;font-weight:500}
        .promo-banner{margin-top:1.25rem;padding:.85rem;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:8px;text-align:center}
        .promo-banner p{font-size:.78rem;color:var(--gold-dark);font-weight:500}
        .pricelist-link{display:block;text-align:center;margin-top:.75rem;font-size:.78rem}.pricelist-link a{color:var(--stone-500);text-decoration:none}.pricelist-link a:hover{color:var(--gold-dark)}
        .session-status{padding:.7rem 1rem;background:rgba(22,163,74,.08);border:1px solid rgba(22,163,74,.2);border-radius:8px;color:#16a34a;font-size:.82rem;margin-bottom:1rem}
        .feat{display:flex;align-items:flex-start;gap:.75rem;padding:.75rem 0;border-bottom:1px solid rgba(255,255,255,.05)}.feat:last-child{border:none}
        .feat-icon{width:36px;height:36px;border-radius:8px;background:rgba(245,158,11,.15);border:1px solid rgba(245,158,11,.25);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
        .feat h4{color:rgba(255,255,255,.9);font-size:.82rem;font-weight:600;margin:0}.feat p{color:rgba(255,255,255,.4);font-size:.72rem;margin:.15rem 0 0}
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-left">
        <div class="drops"><div class="dr"></div><div class="dr"></div><div class="dr"></div><div class="dr"></div><div class="dr"></div></div>
        <div class="auth-left-content">
            <div style="font-family:'Space Mono',monospace;font-size:2rem;font-weight:700;color:white">Wash<span style="color:var(--gold-light)">Hub</span></div>
            <p style="color:rgba(255,255,255,.5);font-size:.95rem;margin-top:.5rem;line-height:1.5">Layanan cuci kendaraan profesional dengan sistem loyalty rewards terbaik.</p>
            <div style="margin-top:2rem">
                <div class="feat"><div class="feat-icon">💧</div><div><h4>Cuci Premium</h4><p>Peralatan modern, hasil sempurna</p></div></div>
                <div class="feat"><div class="feat-icon">🎁</div><div><h4>Loyalty Rewards</h4><p>10x cuci = 1x GRATIS!</p></div></div>
                <div class="feat"><div class="feat-icon">📱</div><div><h4>Pantau Online</h4><p>Cek poin & riwayat dari HP</p></div></div>
            </div>
            <div style="display:flex;gap:2rem;margin-top:2rem;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,.06)">
                <div><div style="font-family:'Space Mono',monospace;font-size:1.25rem;font-weight:700;color:var(--gold-light)">500+</div><div style="font-size:.65rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:1px;margin-top:2px">Members</div></div>
                <div><div style="font-family:'Space Mono',monospace;font-size:1.25rem;font-weight:700;color:var(--gold-light)">12K+</div><div style="font-size:.65rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:1px;margin-top:2px">Cuci/Bulan</div></div>
                <div><div style="font-family:'Space Mono',monospace;font-size:1.25rem;font-weight:700;color:var(--gold-light)">4.9</div><div style="font-size:.65rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:1px;margin-top:2px">Rating</div></div>
            </div>
        </div>
    </div>
    <div class="auth-right">
        <div class="form-box">
            <div class="mobile-brand"><h1>Wash<span>Hub</span></h1></div>
            {{ $slot }}
        </div>
    </div>
</div>
</body>
</html>
