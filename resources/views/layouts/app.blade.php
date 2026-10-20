<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIGAP BANSER') - Satkorcab Kab. Blitar</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/banser-logo.png') }}">

    <!-- Google Fonts: Archivo & Public Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&family=Public+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Design Tokens */
            --hero1: #5A0E14;
            --hero2: #240608;
            --red: #E5232B;
            --yellow: #F5EB0F;
            --gold: #C9A24A;
            --green: #0E9B4A;
            --accent: #8E1B22;
            --accent-hover: #72141A;
            --blue: #1D68BD;
            --gray: #6B7280;

            /* Light mode surface & text */
            --bg: #F3F2F0;
            --surface: #FFFFFF;
            --surface-alt: #F9F8F6;
            --ink: #1F1A1C;
            --ink-muted: #696263;
            --border: #E0D9D4;
            --border-light: #EFEBE8;

            /* Shadows & radii */
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 10px rgba(0,0,0,0.07), 0 2px 4px rgba(0,0,0,0.04);
            --shadow-lg: 0 12px 24px -4px rgba(0,0,0,0.12), 0 4px 8px -2px rgba(0,0,0,0.05);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #150E0F;
                --surface: #201516;
                --surface-alt: #291B1D;
                --ink: #F2E9E7;
                --ink-muted: #A89F9E;
                --border: #38292B;
                --border-light: #2D1F21;
                --accent: #D12A31;
                --accent-hover: #E8363E;
                --shadow-sm: 0 1px 3px rgba(0,0,0,0.4);
                --shadow-md: 0 4px 10px rgba(0,0,0,0.5);
                --shadow-lg: 0 12px 24px rgba(0,0,0,0.6);
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Public Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg);
            color: var(--ink);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Archivo', system-ui, -apple-system, sans-serif;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.25;
            color: var(--ink);
        }

        a {
            color: var(--accent);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        a:hover {
            color: var(--accent-hover);
        }

        /* Top Header */
        .site-header {
            background: linear-gradient(135deg, var(--hero1) 0%, var(--hero2) 100%);
            color: #FFFFFF;
            position: relative;
            box-shadow: var(--shadow-md);
        }

        .header-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .header-branding {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            color: #FFFFFF;
        }

        .header-logo-banser {
            width: 64px;
            height: 64px;
            object-fit: contain;
            flex-shrink: 0;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
        }

        .header-title-box h1 {
            color: #FFFFFF;
            font-size: 1.65rem;
            font-weight: 900;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
            text-transform: uppercase;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        .header-title-box p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.01em;
            max-width: 540px;
            line-height: 1.35;
        }

        .header-meta {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-adminpers-box {
            display: flex;
            align-items: center;
            position: relative;
        }

        .header-adminpers-logo {
            height: 68px;
            width: auto;
            max-width: 160px;
            object-fit: contain;
            filter: drop-shadow(0 0 16px rgba(201, 162, 74, 0.5));
            transition: transform 0.25s ease;
        }
        .header-adminpers-logo:hover {
            transform: scale(1.03);
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 14px;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: var(--radius-md);
        }

        .user-avatar-badge {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--gold);
            color: #240608;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #FFFFFF;
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.72rem;
            color: var(--yellow);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .btn-logout {
            background: rgba(229, 35, 43, 0.2);
            color: #FFFFFF;
            border: 1px solid rgba(229, 35, 43, 0.5);
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-logout:hover {
            background: var(--red);
            border-color: var(--red);
        }

        /* Tri-color stripe: Red, Yellow, Green */
        .tri-color-stripe {
            height: 6px;
            width: 100%;
            display: flex;
            background: linear-gradient(90deg, 
                var(--red) 0%, var(--red) 33.333%, 
                var(--yellow) 33.333%, var(--yellow) 66.666%, 
                var(--green) 66.666%, var(--green) 100%);
        }

        /* Navigation Tabs */
        .nav-tabs-wrapper {
            background-color: var(--surface);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-tabs-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
        }

        .nav-tab-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 16px 20px;
            color: var(--ink-muted);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            border-bottom: 4px solid transparent;
            margin-bottom: -1px;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .nav-tab-item:hover {
            color: var(--accent);
            background-color: var(--surface-alt);
        }

        .nav-tab-item.active {
            color: var(--red);
            border-bottom-color: var(--red);
            font-weight: 700;
            background-color: transparent;
        }

        .nav-tab-item svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
        }

        /* Main Container */
        .main-content {
            flex: 1;
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
            padding: 28px 24px 48px;
        }

        /* Flash Messages */
        .flash-container {
            margin-bottom: 24px;
        }

        .alert {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 12px;
            font-size: 0.92rem;
            animation: fadeIn 0.3s ease;
        }

        .alert-success {
            background-color: rgba(14, 155, 74, 0.12);
            border: 1px solid var(--green);
            color: var(--green);
        }

        .alert-error, .alert-danger {
            background-color: rgba(229, 35, 43, 0.12);
            border: 1px solid var(--red);
            color: var(--red);
        }

        .alert-warning {
            background-color: rgba(245, 235, 15, 0.15);
            border: 1px solid var(--gold);
            color: #8C6F12;
        }
        @media (prefers-color-scheme: dark) {
            .alert-warning {
                color: #FFE66D;
            }
        }

        .alert-close {
            background: none;
            border: none;
            color: inherit;
            font-size: 1.25rem;
            cursor: pointer;
            line-height: 1;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }
        .alert-close:hover {
            opacity: 1;
        }

        /* Layout Grid */
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .grid-1-2 {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }

        .grid-2-1 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        /* Cards */
        .card {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-light);
            background-color: var(--surface);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body {
            padding: 20px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            line-height: 1.25;
            white-space: nowrap;
        }

        .btn:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 3px solid rgba(229, 35, 43, 0.45);
            outline-offset: 2px;
        }

        .btn-primary, .btn-accent {
            background-color: var(--accent);
            color: #FFFFFF;
            border-color: var(--accent);
        }
        .btn-primary:hover, .btn-accent:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
            color: #FFFFFF;
        }

        .btn-gold {
            background-color: var(--gold);
            color: #240608;
            font-weight: 700;
            border-color: #B58F3B;
            box-shadow: 0 2px 6px rgba(201, 162, 74, 0.3);
        }
        .btn-gold:hover {
            background-color: #D6AF55;
            color: #1A0406;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: var(--surface-alt);
            color: var(--ink);
            border-color: var(--border);
        }
        .btn-secondary:hover {
            background-color: var(--border-light);
            color: var(--ink);
        }

        .btn-outline-danger {
            background-color: transparent;
            color: var(--red);
            border-color: var(--red);
        }
        .btn-outline-danger:hover {
            background-color: var(--red);
            color: #FFFFFF;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: var(--radius-sm);
        }

        .btn-lg {
            padding: 13px 24px;
            font-size: 1rem;
            font-weight: 700;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-red {
            background-color: rgba(229, 35, 43, 0.14);
            color: var(--red);
            border: 1px solid rgba(229, 35, 43, 0.3);
        }
        .badge-yellow {
            background-color: rgba(245, 235, 15, 0.2);
            color: #8C6F12;
            border: 1px solid rgba(201, 162, 74, 0.4);
        }
        @media (prefers-color-scheme: dark) {
            .badge-yellow {
                color: #F5EB0F;
            }
        }
        .badge-green {
            background-color: rgba(14, 155, 74, 0.15);
            color: var(--green);
            border: 1px solid rgba(14, 155, 74, 0.35);
        }
        .badge-blue {
            background-color: rgba(29, 104, 189, 0.15);
            color: var(--blue);
            border: 1px solid rgba(29, 104, 189, 0.35);
        }
        .badge-gray {
            background-color: rgba(107, 114, 128, 0.15);
            color: var(--gray);
            border: 1px solid rgba(107, 114, 128, 0.3);
        }

        /* Form elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--ink);
        }

        .form-label .required {
            color: var(--red);
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            font-family: inherit;
            font-size: 0.92rem;
            color: var(--ink);
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(142, 27, 34, 0.15);
        }

        .form-control.is-invalid {
            border-color: var(--red);
            background-color: rgba(229, 35, 43, 0.02);
        }

        .form-error {
            margin-top: 6px;
            font-size: 0.82rem;
            color: var(--red);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-hint {
            margin-top: 5px;
            font-size: 0.8rem;
            color: var(--ink-muted);
        }

        /* Tables */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.88rem;
        }

        .table th {
            background-color: var(--surface-alt);
            color: var(--ink);
            font-weight: 700;
            padding: 12px 16px;
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }

        .table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--ink);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: var(--surface-alt);
        }

        /* Maroon Box with Yellow Digits (Surat Number Box) */
        .surat-number-box {
            background: linear-gradient(135deg, var(--hero1) 0%, var(--hero2) 100%);
            border: 1px solid rgba(201, 162, 74, 0.4);
            border-radius: var(--radius-md);
            padding: 12px 18px;
            display: inline-flex;
            flex-direction: column;
            gap: 4px;
            box-shadow: var(--shadow-sm);
        }

        .surat-number-box .label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.75);
            font-weight: 600;
        }

        .surat-number-box .number {
            font-family: 'Archivo', monospace, sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            color: var(--yellow);
            letter-spacing: 0.05em;
            text-shadow: 0 1px 2px rgba(0,0,0,0.6);
        }

        /* Footer */
        .site-footer {
            background-color: var(--hero2);
            color: #FFFFFF;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 24px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 12px;
        }

        .footer-motto {
            font-family: 'Archivo', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--gold);
            letter-spacing: 0.03em;
            text-shadow: 0 2px 4px rgba(0,0,0,0.4);
        }

        .footer-sub {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .grid-3 {
                grid-template-columns: 1fr;
            }
            .grid-1-2, .grid-2-1, .grid-2 {
                grid-template-columns: 1fr;
            }
            .header-container {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }
            .header-branding {
                flex-direction: column;
                text-align: center;
            }
            .header-meta {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 640px) {
            .header-title-box h1 {
                font-size: 1.35rem;
            }
            .nav-tabs-container {
                padding: 0 12px;
            }
            .nav-tab-item {
                padding: 12px 14px;
                font-size: 0.85rem;
            }
            .main-content {
                padding: 20px 14px 36px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Header -->
    <header class="site-header">
        <div class="header-container">
            <a href="{{ route('dashboard') }}" class="header-branding" title="Beranda SIGAP BANSER">
                <!-- BANSER Logo Left (64px) -->
                <img src="{{ asset('images/banser-logo.png') }}" 
                     onerror="this.onerror=null; this.src='{{ asset('images/banser.png') }}';" 
                     alt="Logo Satkorcab Banser" 
                     class="header-logo-banser"
                     width="64" height="64">
                
                <div class="header-title-box">
                    <h1>SIGAP BANSER</h1>
                    <p>Sistem Informasi dan Administrasi Persuratan Satkorcab Kab. Blitar</p>
                </div>
            </a>

            <div class="header-meta">
                <!-- Adminpers Logo Right with gold glow -->
                <div class="header-adminpers-box">
                    <img src="{{ asset('images/LOGO_ADMIN_rev_2.png') }}" 
                         onerror="this.onerror=null; this.src='{{ asset('images/adminpers-logo.png') }}';" 
                         alt="Logo Adminpers Satkorcab Banser" 
                         class="header-adminpers-logo"
                         height="68">
                </div>

                @auth
                <div class="user-dropdown">
                    <div class="user-avatar-badge" title="{{ Auth::user()->name }}">
                        {{ strtoupper(substr(Auth::user()->name ?? 'B', 0, 1)) }}
                    </div>
                    <div class="user-details">
                        <span class="user-name">{{ Auth::user()->name ?? 'Petugas Banser' }}</span>
                        <span class="user-role">{{ Auth::user()->role ?? 'Adminpers' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-logout" title="Keluar dari sistem">Keluar</button>
                    </form>
                </div>
                @endauth
            </div>
        </div>

        <!-- Tri-color stripe: Red, Yellow, Green -->
        <div class="tri-color-stripe" aria-hidden="true"></div>
    </header>

    <!-- Tab Navigation -->
    <nav class="nav-tabs-wrapper" aria-label="Navigasi Utama">
        <div class="nav-tabs-container">
            <a href="{{ route('dashboard') }}" 
               class="nav-tab-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Beranda</span>
            </a>

            <a href="{{ route('surat-masuk.index') }}" 
               class="nav-tab-item {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <span>Surat Masuk</span>
            </a>

            <a href="{{ route('surat-keluar.index') }}" 
               class="nav-tab-item {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                <span>Surat Keluar</span>
            </a>

            <a href="{{ route('profil.index') }}" 
               class="nav-tab-item {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Profil</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Flash Notifications -->
        <div class="flash-container" id="flashMessages">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error" role="alert">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning" role="alert">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>{{ session('warning') }}</span>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error" role="alert">
                    <div>
                        <strong>Perhatian: Terdapat kesalahan pengisian data:</strong>
                        <ul style="margin: 6px 0 0 18px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-motto">"Rapi di meja, solid di laga: satu data, satu komando!"</div>
            <div class="footer-sub">&copy; {{ date('Y') }} Satuan Koordinasi Cabang Barisan Ansor Serbaguna (SATKORCAB BANSER) Kabupaten Blitar</div>
        </div>
    </footer>

    <!-- Vanilla JavaScript for interactive features -->
    <script>
        // Auto-dismiss flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const flashContainer = document.getElementById('flashMessages');
            if (flashContainer) {
                const alerts = flashContainer.querySelectorAll('.alert');
                alerts.forEach(function (alert) {
                    setTimeout(function () {
                        alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-6px)';
                        setTimeout(function () {
                            alert.remove();
                        }, 500);
                    }, 5000);
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
