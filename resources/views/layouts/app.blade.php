<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Wash Hub') }} - @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --gold: #f59e0b; --gold-light: #fbbf24; --gold-dark: #d97706; --gold-50: #fffbeb; --gold-100: #fef3c7;
            --dark: #0c0a09; --dark-2: #1c1917; --dark-3: #292524; --dark-4: #44403c;
            --stone-100: #f5f5f4; --stone-200: #e7e5e4; --stone-300: #d6d3d1; --stone-500: #78716c; --stone-700: #44403c;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; background: var(--stone-100); }
        [x-cloak] { display: none !important; }

        .sidebar {
            position: fixed; left: 0; top: 0; bottom: 0; width: 260px; background: var(--dark);
            display: flex; flex-direction: column; z-index: 40; transition: transform .3s;
        }
        .sidebar-brand {
            padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; gap: .75rem;
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px; background: var(--gold); border-radius: 10px;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }
        .sidebar-brand h1 { font-family: 'Space Mono', monospace; font-size: 1.2rem; font-weight: 700; color: white; margin: 0; }
        .sidebar-brand h1 span { color: var(--gold-light); }
        .sidebar-brand small { display: block; font-family: 'Plus Jakarta Sans'; font-size: .65rem; color: var(--stone-500); font-weight: 400; letter-spacing: 1px; text-transform: uppercase; margin-top: 2px; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 1rem 0; }
        .nav-section { padding: 0 1.25rem; margin-bottom: .5rem; }
        .nav-section-label { font-size: .65rem; font-weight: 700; color: var(--stone-500); letter-spacing: 1.5px; text-transform: uppercase; padding: .5rem 0; }
        .nav-item {
            display: flex; align-items: center; gap: .75rem; padding: .65rem 1.25rem; margin: 2px .75rem;
            border-radius: 8px; color: var(--stone-300); font-size: .85rem; font-weight: 500;
            text-decoration: none; transition: all .15s;
        }
        .nav-item:hover { background: rgba(255,255,255,.06); color: white; }
        .nav-item.active { background: rgba(245,158,11,.12); color: var(--gold-light); }
        .nav-item.active .nav-icon { color: var(--gold); }
        .nav-icon { width: 20px; text-align: center; font-size: 1rem; opacity: .7; }
        .nav-item.active .nav-icon { opacity: 1; }

        .sidebar-footer { padding: 1rem 1.25rem; border-top: 1px solid rgba(255,255,255,.06); }
        .user-card { display: flex; align-items: center; gap: .75rem; }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 8px; background: var(--gold);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .85rem; color: var(--dark);
        }
        .user-info { flex: 1; }
        .user-info p { margin: 0; font-size: .82rem; font-weight: 600; color: white; }
        .user-info small { font-size: .7rem; color: var(--stone-500); }

        .main-content { margin-left: 260px; min-height: 100vh; }
        .topbar {
            background: white; border-bottom: 1px solid var(--stone-200); padding: 1rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 30;
        }
        .topbar-title h2 { font-size: 1.25rem; font-weight: 700; color: var(--dark); margin: 0; }
        .topbar-title p { font-size: .8rem; color: var(--stone-500); margin: .15rem 0 0; }
        .topbar-actions { display: flex; align-items: center; gap: .75rem; }

        .content-area { padding: 1.5rem; }

        .btn-gold { background: var(--gold); color: var(--dark); font-weight: 600; padding: .6rem 1.25rem; border-radius: 8px; border: none; font-size: .85rem; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: .5rem; transition: all .15s; font-family: inherit; }
        .btn-gold:hover { background: var(--gold-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(245,158,11,.3); }
        .btn-outline { background: transparent; color: var(--dark-3); font-weight: 500; padding: .6rem 1.25rem; border-radius: 8px; border: 1.5px solid var(--stone-200); font-size: .85rem; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: .5rem; transition: all .15s; font-family: inherit; }
        .btn-outline:hover { border-color: var(--stone-300); background: white; }
        .btn-dark { background: var(--dark); color: white; font-weight: 600; padding: .6rem 1.25rem; border-radius: 8px; border: none; font-size: .85rem; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: .5rem; transition: all .15s; font-family: inherit; }
        .btn-dark:hover { background: var(--dark-2); }
        .btn-sm { padding: .4rem .85rem; font-size: .78rem; }
        .btn-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .btn-danger:hover { background: #fecaca; }

        .card { background: white; border-radius: 12px; border: 1px solid var(--stone-200); }
        .card-dark { background: var(--dark); border-radius: 12px; color: white; }

        .stat-card { padding: 1.25rem; }
        .stat-card .stat-label { font-size: .78rem; font-weight: 500; color: var(--stone-500); }
        .stat-card .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--dark); margin-top: .25rem; }
        .stat-card .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }

        .badge { padding: .2rem .6rem; border-radius: 6px; font-size: .7rem; font-weight: 600; display: inline-flex; align-items: center; }
        .badge-gold { background: var(--gold-100); color: var(--gold-dark); }
        .badge-green { background: #dcfce7; color: #16a34a; }
        .badge-red { background: #fee2e2; color: #dc2626; }
        .badge-blue { background: #dbeafe; color: #2563eb; }
        .badge-gray { background: #f3f4f6; color: #6b7280; }
        .badge-yellow { background: #fef3c7; color: #d97706; }

        table { width: 100%; border-collapse: collapse; }
        table th { font-size: .72rem; font-weight: 600; color: var(--stone-500); text-transform: uppercase; letter-spacing: .5px; padding: .75rem 1rem; text-align: left; border-bottom: 1px solid var(--stone-200); }
        table td { padding: .75rem 1rem; font-size: .85rem; color: var(--dark-3); border-bottom: 1px solid var(--stone-100); }
        table tbody tr:hover { background: var(--gold-50); }
        table .mono { font-family: 'Space Mono', monospace; font-size: .75rem; }

        .form-label { display: block; font-size: .78rem; font-weight: 600; color: var(--dark-3); margin-bottom: .4rem; }
        .form-input { width: 100%; padding: .65rem .85rem; border: 1.5px solid var(--stone-200); border-radius: 8px; font-size: .85rem; font-family: inherit; background: white; color: var(--dark); transition: all .2s; outline: none; }
        .form-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(245,158,11,.12); }
        .form-input::placeholder { color: var(--stone-300); }
        .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2378716c' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right .75rem center; padding-right: 2rem; }
        .form-error { color: #dc2626; font-size: .75rem; margin-top: .25rem; }

        .empty-state { text-align: center; padding: 3rem 1rem; }
        .empty-state p { color: var(--stone-500); font-size: .9rem; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">🚗</div>
            <div><h1>Wash<span>Hub</span></h1><small>Management</small></div>
        </div>
        <nav class="sidebar-nav">
            @if(auth()->user()->isStaff())
            <div class="nav-section"><div class="nav-section-label">Main</div></div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="nav-icon">📊</span> Dashboard</a>
            <a href="{{ route('transactions.index') }}" class="nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}"><span class="nav-icon">🧾</span> Transaksi</a>
            @endif

            @if(auth()->user()->isAdmin())
            <div class="nav-section" style="margin-top:.75rem"><div class="nav-section-label">Kelola</div></div>
            <a href="{{ route('admin.services.index') }}" class="nav-item {{ request()->routeIs('admin.services.*') || request()->routeIs('admin.service-categories.*') ? 'active' : '' }}"><span class="nav-icon">💰</span> Pricelist</a>
            <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"><span class="nav-icon">👥</span> Customers</a>
            <a href="{{ route('admin.service-categories.index') }}" class="nav-item {{ request()->routeIs('admin.service-categories.*') ? 'active' : '' }}"><span class="nav-icon">📂</span> Kategori</a>

            <div class="nav-section" style="margin-top:.75rem"><div class="nav-section-label">Laporan</div></div>
            <a href="{{ route('reports.daily') }}" class="nav-item {{ request()->routeIs('reports.daily') ? 'active' : '' }}"><span class="nav-icon">📅</span> Harian</a>
            <a href="{{ route('reports.monthly') }}" class="nav-item {{ request()->routeIs('reports.monthly') ? 'active' : '' }}"><span class="nav-icon">📈</span> Bulanan</a>
            <a href="{{ route('reports.custom') }}" class="nav-item {{ request()->routeIs('reports.custom') ? 'active' : '' }}"><span class="nav-icon">📋</span> Custom</a>
            <a href="{{ route('reports.top-customers') }}" class="nav-item {{ request()->routeIs('reports.top-customers') ? 'active' : '' }}"><span class="nav-icon">🏆</span> Top Customer</a>

            <div class="nav-section" style="margin-top:.75rem"><div class="nav-section-label">Sistem</div></div>
            <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><span class="nav-icon">⚙️</span> Pengaturan</a>
            @endif
        </nav>
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <p>{{ Auth::user()->name }}</p>
                    <small>{{ ucfirst(Auth::user()->role) }}</small>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">@csrf
                    <button type="submit" style="background:none;border:none;color:var(--stone-500);cursor:pointer;font-size:1.1rem;padding:4px" title="Logout">🚪</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-title">
                <button onclick="document.getElementById('sidebar').classList.toggle('open')" class="lg:hidden" style="background:none;border:none;font-size:1.3rem;cursor:pointer;margin-right:.5rem;display:none">☰</button>
                @hasSection('header')
                    @yield('header')
                @else
                    <h2>@yield('title', 'Dashboard')</h2>
                @endif
            </div>
            <div class="topbar-actions">@yield('actions')</div>
        </div>

        @if(session('success'))
        <div style="padding:0 1.5rem;margin-top:1rem">
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:.75rem 1rem;border-radius:8px;font-size:.85rem;display:flex;align-items:center;gap:.5rem" x-data="{s:true}" x-show="s">
                ✅ {{ session('success') }}
                <button @click="s=false" style="margin-left:auto;background:none;border:none;color:#16a34a;cursor:pointer">&times;</button>
            </div>
        </div>
        @endif
        @if(session('error'))
        <div style="padding:0 1.5rem;margin-top:1rem">
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:.75rem 1rem;border-radius:8px;font-size:.85rem" x-data="{s:true}" x-show="s">
                ❌ {{ session('error') }}
                <button @click="s=false" style="margin-left:auto;background:none;border:none;color:#dc2626;cursor:pointer">&times;</button>
            </div>
        </div>
        @endif

        <div class="content-area">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
