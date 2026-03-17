<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0c0a09">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ config('app.name', 'Wash Hub') }} - @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --gold: #f59e0b; --gold-light: #fbbf24; --gold-dark: #d97706; --gold-50: #fffbeb; --gold-100: #fef3c7;
            --dark: #0c0a09; --dark-2: #1c1917; --dark-3: #292524; --dark-4: #44403c;
            --stone-50: #fafaf9; --stone-100: #f5f5f4; --stone-200: #e7e5e4; --stone-300: #d6d3d1; --stone-500: #78716c;
            --safe-bottom: env(safe-area-inset-bottom, 0px);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--stone-50); color: var(--dark); -webkit-font-smoothing: antialiased; }
        [x-cloak] { display: none !important; }

        .app-container { min-height: 100vh; padding-bottom: calc(70px + var(--safe-bottom)); max-width: 480px; margin: 0 auto; }
        @media (min-width: 768px) { .app-container { max-width: 480px; border-left: 1px solid var(--stone-200); border-right: 1px solid var(--stone-200); min-height: 100vh; } }

        .app-header {
            background: var(--dark); color: white; padding: 1rem 1.25rem .75rem;
            padding-top: calc(1rem + env(safe-area-inset-top, 0px));
            position: sticky; top: 0; z-index: 30;
        }
        .app-header-row { display: flex; align-items: center; justify-content: space-between; }
        .app-header h1 { font-family: 'Space Mono', monospace; font-size: 1.1rem; font-weight: 700; }
        .app-header h1 span { color: var(--gold-light); }
        .app-header-actions { display: flex; align-items: center; gap: .5rem; }
        .app-header-actions a, .app-header-actions button { background: rgba(255,255,255,.08); border: none; color: white; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; font-size: 1rem; }

        .page-content { padding: 1rem 1.25rem; }

        /* Bottom Tab Bar */
        .bottom-nav {
            position: fixed; bottom: 0; left: 50%; transform: translateX(-50%);
            width: 100%; max-width: 480px;
            background: var(--dark); border-top: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; justify-content: space-around;
            padding: .5rem 0; padding-bottom: calc(.5rem + var(--safe-bottom));
            z-index: 50;
        }
        .tab-item {
            display: flex; flex-direction: column; align-items: center; gap: .15rem;
            padding: .35rem .75rem; border-radius: 10px; text-decoration: none;
            color: var(--stone-500); font-size: .65rem; font-weight: 500; transition: all .15s;
            position: relative;
        }
        .tab-item .tab-icon { font-size: 1.25rem; line-height: 1; }
        .tab-item.active { color: var(--gold-light); }
        .tab-item.active::before {
            content: ''; position: absolute; top: -8px; left: 50%; transform: translateX(-50%);
            width: 24px; height: 3px; background: var(--gold); border-radius: 0 0 4px 4px;
        }

        /* Cards */
        .card-m { background: white; border-radius: 16px; border: 1px solid var(--stone-200); overflow: hidden; }
        .card-m-dark { background: var(--dark); border-radius: 16px; color: white; overflow: hidden; }
        .card-m-gold { background: linear-gradient(135deg, var(--gold), var(--gold-dark)); border-radius: 16px; color: var(--dark); overflow: hidden; }
        .card-m-pad { padding: 1.25rem; }

        .btn-gold { background: var(--gold); color: var(--dark); font-weight: 700; padding: .75rem 1.25rem; border-radius: 12px; border: none; font-size: .9rem; cursor: pointer; width: 100%; text-align: center; font-family: inherit; transition: all .15s; display: block; text-decoration: none; }
        .btn-gold:hover { background: var(--gold-dark); }
        .btn-dark-m { background: var(--dark); color: white; font-weight: 600; padding: .65rem 1rem; border-radius: 10px; border: none; font-size: .85rem; cursor: pointer; font-family: inherit; text-decoration: none; display: inline-block; }

        .badge-m { padding: .2rem .5rem; border-radius: 6px; font-size: .65rem; font-weight: 600; }
        .badge-m-gold { background: rgba(245,158,11,.15); color: var(--gold); }
        .badge-m-green { background: rgba(22,163,74,.1); color: #16a34a; }

        @if(session('success'))
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: .75rem 1rem; border-radius: 12px; font-size: .82rem; margin-bottom: 1rem; }
        @endif
        @if(session('error'))
        .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: .75rem 1rem; border-radius: 12px; font-size: .82rem; margin-bottom: 1rem; }
        @endif
    </style>
    @stack('head')
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <div class="app-header">
            <div class="app-header-row">
                <h1>Wash<span>Hub</span></h1>
                <div class="app-header-actions">
                    @yield('header-actions')
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" title="Logout">🚪</button>
                    </form>
                </div>
            </div>
            @yield('header-extra')
        </div>

        <!-- Content -->
        <div class="page-content">
            @if(session('success'))<div class="flash-success">✅ {{ session('success') }}</div>@endif
            @if(session('error'))<div class="flash-error">❌ {{ session('error') }}</div>@endif
            @yield('content')
        </div>

        <!-- Bottom Nav -->
        <nav class="bottom-nav">
            <a href="{{ route('customer.dashboard') }}" class="tab-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <span class="tab-icon">🏠</span>Home
            </a>
            <a href="{{ route('customer.transactions') }}" class="tab-item {{ request()->routeIs('customer.transactions') ? 'active' : '' }}">
                <span class="tab-icon">🧾</span>Riwayat
            </a>
            <a href="{{ route('customer.points') }}" class="tab-item {{ request()->routeIs('customer.points') ? 'active' : '' }}">
                <span class="tab-icon">⭐</span>Poin
            </a>
            <a href="{{ route('customer.rewards') }}" class="tab-item {{ request()->routeIs('customer.rewards') ? 'active' : '' }}">
                <span class="tab-icon">🎁</span>Rewards
            </a>
        </nav>
    </div>
    @stack('scripts')
</body>
</html>
