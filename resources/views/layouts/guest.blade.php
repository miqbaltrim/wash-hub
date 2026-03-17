<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Wash Hub') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --wash-primary: #4f46e5;
            --wash-secondary: #06b6d4;
            --wash-dark: #0f172a;
            --wash-accent: #22d3ee;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; min-height: 100vh; }
        .auth-container { display: flex; min-height: 100vh; }
        
        /* Left Panel - Visual */
        .auth-visual {
            flex: 1;
            background: var(--wash-dark);
            position: relative;
            overflow: hidden;
            display: none;
        }
        @media (min-width: 1024px) { .auth-visual { display: flex; align-items: center; justify-content: center; } }
        
        .auth-visual::before {
            content: '';
            position: absolute;
            inset: 0;
            background: 
                radial-gradient(ellipse at 20% 50%, rgba(79, 70, 229, 0.3) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(6, 182, 212, 0.25) 0%, transparent 50%),
                radial-gradient(ellipse at 60% 80%, rgba(34, 211, 238, 0.15) 0%, transparent 50%);
        }
        
        /* Animated water drops */
        .water-drops { position: absolute; inset: 0; pointer-events: none; }
        .drop {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(34, 211, 238, 0.4);
            border-radius: 50%;
            animation: dropFall linear infinite;
        }
        .drop:nth-child(1) { left: 15%; animation-duration: 4s; animation-delay: 0s; }
        .drop:nth-child(2) { left: 35%; animation-duration: 3.5s; animation-delay: 1s; width: 4px; height: 4px; }
        .drop:nth-child(3) { left: 55%; animation-duration: 5s; animation-delay: 0.5s; }
        .drop:nth-child(4) { left: 75%; animation-duration: 3s; animation-delay: 2s; width: 8px; height: 8px; }
        .drop:nth-child(5) { left: 90%; animation-duration: 4.5s; animation-delay: 1.5s; width: 5px; height: 5px; }
        .drop:nth-child(6) { left: 25%; animation-duration: 3.8s; animation-delay: 0.8s; width: 3px; height: 3px; }
        .drop:nth-child(7) { left: 65%; animation-duration: 4.2s; animation-delay: 2.5s; }
        .drop:nth-child(8) { left: 45%; animation-duration: 3.2s; animation-delay: 1.2s; width: 7px; height: 7px; }
        
        @keyframes dropFall {
            0% { top: -10px; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 0.6; }
            100% { top: 100%; opacity: 0; }
        }
        
        /* Bubble effect */
        .bubbles { position: absolute; bottom: 0; left: 0; right: 0; height: 300px; pointer-events: none; }
        .bubble {
            position: absolute;
            border: 1px solid rgba(34, 211, 238, 0.2);
            border-radius: 50%;
            animation: bubbleRise ease-in infinite;
        }
        .bubble:nth-child(1) { left: 10%; width: 40px; height: 40px; animation-duration: 6s; }
        .bubble:nth-child(2) { left: 30%; width: 20px; height: 20px; animation-duration: 5s; animation-delay: 1s; }
        .bubble:nth-child(3) { left: 50%; width: 60px; height: 60px; animation-duration: 8s; animation-delay: 2s; }
        .bubble:nth-child(4) { left: 70%; width: 30px; height: 30px; animation-duration: 5.5s; animation-delay: 0.5s; }
        .bubble:nth-child(5) { left: 85%; width: 25px; height: 25px; animation-duration: 7s; animation-delay: 3s; }
        
        @keyframes bubbleRise {
            0% { bottom: -60px; opacity: 0; transform: scale(0.3); }
            20% { opacity: 0.4; }
            100% { bottom: 100%; opacity: 0; transform: scale(1); }
        }
        
        .visual-content { position: relative; z-index: 10; padding: 3rem; max-width: 480px; }
        .visual-logo { font-family: 'Space Mono', monospace; font-size: 2.5rem; font-weight: 700; color: white; letter-spacing: -1px; }
        .visual-logo span { color: var(--wash-accent); }
        .visual-tagline { color: rgba(255,255,255,0.6); font-size: 1.1rem; margin-top: 0.75rem; line-height: 1.6; }
        
        .visual-features { margin-top: 3rem; }
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .feature-item:last-child { border: none; }
        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            background: rgba(79, 70, 229, 0.2);
            border: 1px solid rgba(79, 70, 229, 0.3);
        }
        .feature-text h4 { color: rgba(255,255,255,0.9); font-size: 0.9rem; font-weight: 600; margin: 0; }
        .feature-text p { color: rgba(255,255,255,0.45); font-size: 0.8rem; margin: 4px 0 0; }
        
        .visual-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .stat-item { text-align: center; }
        .stat-num { font-family: 'Space Mono', monospace; font-size: 1.5rem; font-weight: 700; color: var(--wash-accent); }
        .stat-label { font-size: 0.7rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }
        
        /* Right Panel - Form */
        .auth-form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #fafbfc;
            position: relative;
        }
        .auth-form-panel::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.04) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .form-wrapper { width: 100%; max-width: 420px; position: relative; z-index: 1; }
        
        /* Mobile logo */
        .mobile-logo { display: block; text-align: center; margin-bottom: 2rem; }
        @media (min-width: 1024px) { .mobile-logo { display: none; } }
        .mobile-logo h1 { font-family: 'Space Mono', monospace; font-size: 1.8rem; font-weight: 700; color: var(--wash-dark); }
        .mobile-logo h1 span { color: var(--wash-primary); }
        
        .form-header { margin-bottom: 2rem; }
        .form-header h2 { font-size: 1.6rem; font-weight: 700; color: var(--wash-dark); margin: 0; }
        .form-header p { color: #94a3b8; font-size: 0.9rem; margin: 0.5rem 0 0; }
        
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
            letter-spacing: 0.3px;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: white;
            color: var(--wash-dark);
            transition: all 0.2s;
            outline: none;
        }
        .form-input::placeholder { color: #cbd5e1; }
        .form-input:focus {
            border-color: var(--wash-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .form-input.error { border-color: #ef4444; }
        .form-error { color: #ef4444; font-size: 0.75rem; margin-top: 0.35rem; }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        
        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1rem 0;
        }
        .form-checkbox input {
            width: 16px;
            height: 16px;
            accent-color: var(--wash-primary);
            border-radius: 4px;
        }
        .form-checkbox label { font-size: 0.85rem; color: #64748b; cursor: pointer; }
        
        .btn-primary {
            width: 100%;
            padding: 0.85rem;
            background: var(--wash-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35); }
        .btn-primary:active { transform: translateY(0); }
        
        .form-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            color: #cbd5e1;
            font-size: 0.8rem;
        }
        .form-divider::before, .form-divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
        
        .form-footer { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: #64748b; }
        .form-footer a { color: var(--wash-primary); font-weight: 600; text-decoration: none; }
        .form-footer a:hover { text-decoration: underline; }
        
        .form-link { color: var(--wash-primary); font-size: 0.85rem; text-decoration: none; font-weight: 500; }
        .form-link:hover { text-decoration: underline; }
        
        .promo-banner {
            margin-top: 1.5rem;
            padding: 1rem;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 1px solid #fbbf24;
            border-radius: 10px;
            text-align: center;
        }
        .promo-banner p { margin: 0; font-size: 0.8rem; color: #92400e; font-weight: 500; }
        
        .pricelist-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 0.8rem;
            color: #94a3b8;
        }
        .pricelist-link a { color: #64748b; text-decoration: none; }
        .pricelist-link a:hover { color: var(--wash-primary); }

        /* Session status */
        .session-status {
            padding: 0.75rem 1rem;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
            color: #065f46;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Left Visual Panel -->
        <div class="auth-visual">
            <div class="water-drops">
                <div class="drop"></div><div class="drop"></div><div class="drop"></div><div class="drop"></div>
                <div class="drop"></div><div class="drop"></div><div class="drop"></div><div class="drop"></div>
            </div>
            <div class="bubbles">
                <div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>
                <div class="bubble"></div><div class="bubble"></div>
            </div>
            
            <div class="visual-content">
                <div class="visual-logo">Wash<span>Hub</span></div>
                <p class="visual-tagline">Layanan cuci kendaraan profesional dengan sistem membership & loyalty rewards terbaik di kota Anda.</p>
                
                <div class="visual-features">
                    <div class="feature-item">
                        <div class="feature-icon">💧</div>
                        <div class="feature-text">
                            <h4>Cuci Premium</h4>
                            <p>Peralatan modern, hasil maksimal untuk kendaraan Anda</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🎁</div>
                        <div class="feature-text">
                            <h4>Loyalty Rewards</h4>
                            <p>Cuci 10x gratis 1x — klaim kapanpun!</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📱</div>
                        <div class="feature-text">
                            <h4>Pantau Online</h4>
                            <p>Cek poin, riwayat cuci, dan klaim reward dari HP</p>
                        </div>
                    </div>
                </div>
                
                <div class="visual-stats">
                    <div class="stat-item">
                        <div class="stat-num">500+</div>
                        <div class="stat-label">Members</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">12K+</div>
                        <div class="stat-label">Cuci / Bulan</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">4.9</div>
                        <div class="stat-label">Rating</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="auth-form-panel">
            <div class="form-wrapper">
                <div class="mobile-logo">
                    <h1>Wash<span>Hub</span></h1>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
