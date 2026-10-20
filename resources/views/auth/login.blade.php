<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Komando SIGAP BANSER - Portal Akses Terpadu</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/banser-logo.png') }}">

    <!-- Google Fonts: Space Grotesk, Archivo, Outfit, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&family=JetBrains+Mono:wght@400;600;700&family=Outfit:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --cyber-bg: #070305;
            --cyber-card: rgba(18, 7, 12, 0.72);
            --cyber-card-border: rgba(201, 162, 74, 0.28);
            --hero1: #5A0E14;
            --hero2: #1A0306;
            --neon-red: #FF2E38;
            --neon-gold: #F5C042;
            --neon-yellow: #FFF133;
            --neon-green: #10B981;
            --neon-cyan: #06B6D4;
            --text-main: #FFFFFF;
            --text-muted: #A39397;
            --input-bg: rgba(10, 4, 7, 0.75);
            --input-border: rgba(201, 162, 74, 0.35);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--cyber-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 16px;
            color: var(--text-main);
            overflow-x: hidden;
            position: relative;
        }

        /* Canvas Particle Cyber Grid */
        #cyberMatrixCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        /* Glowing Ambient Orbs */
        .ambient-glow {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.42;
            pointer-events: none;
            z-index: 2;
            animation: orbFloat 14s infinite alternate ease-in-out;
        }
        .glow-1 {
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, #8E1B22 0%, transparent 70%);
            top: -120px;
            left: -120px;
        }
        .glow-2 {
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, #B45309 0%, transparent 70%);
            bottom: -100px;
            right: -100px;
            animation-delay: -5s;
        }
        .glow-3 {
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, #047857 0%, transparent 70%);
            bottom: 20%;
            left: 15%;
            opacity: 0.25;
            animation-delay: -8s;
        }

        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, 40px) scale(1.08); }
            100% { transform: translate(-25px, -20px) scale(0.96); }
        }

        /* Cyber Scanlines & Grid Overlay */
        .cyber-grid-overlay {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(201, 162, 74, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201, 162, 74, 0.035) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 2;
            pointer-events: none;
        }

        /* Top Military / Command HUD Strip */
        .hud-status-strip {
            position: fixed;
            top: 14px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 6px 18px;
            background: rgba(15, 6, 10, 0.82);
            border: 1px solid rgba(201, 162, 74, 0.35);
            border-radius: 999px;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            letter-spacing: 0.06em;
            color: #E2D9DC;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6), 0 0 16px rgba(201, 162, 74, 0.12);
            white-space: nowrap;
        }
        .hud-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10B981;
            box-shadow: 0 0 10px #10B981;
            animation: radarPulse 1.8s infinite;
        }
        @keyframes radarPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.35); opacity: 0.65; }
        }
        .hud-time {
            color: var(--neon-gold);
            font-weight: 700;
        }

        /* Main Container */
        .login-container {
            width: 100%;
            max-width: 470px;
            position: relative;
            z-index: 10;
            margin: 50px auto 20px auto;
        }

        /* Futuristic Glassmorphic Card */
        .cyber-card {
            background: var(--cyber-card);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border-radius: 24px;
            border: 1px solid var(--cyber-card-border);
            padding: 40px 36px 34px 36px;
            box-shadow: 
                0 30px 70px rgba(0, 0, 0, 0.75),
                0 0 40px rgba(142, 27, 34, 0.22),
                inset 0 1px 1px rgba(255, 255, 255, 0.12);
            position: relative;
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Animated Glowing Neon Cyber Border */
        .cyber-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                #FF2E38 0%, 
                #FFF133 35%, 
                #10B981 70%, 
                #FF2E38 100%);
            background-size: 200% 100%;
            animation: gradientBorderRun 4s linear infinite;
        }

        @keyframes gradientBorderRun {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        /* Sci-Fi Corner Brackets */
        .corner-bracket {
            position: absolute;
            width: 14px;
            height: 14px;
            border-color: rgba(245, 192, 66, 0.75);
            pointer-events: none;
        }
        .corner-tl { top: 10px; left: 10px; border-top: 2px solid; border-left: 2px solid; }
        .corner-tr { top: 10px; right: 10px; border-top: 2px solid; border-right: 2px solid; }
        .corner-bl { bottom: 10px; left: 10px; border-bottom: 2px solid; border-left: 2px solid; }
        .corner-br { bottom: 10px; right: 10px; border-bottom: 2px solid; border-right: 2px solid; }

        /* Header & Holographic Emblem Presentation */
        .cyber-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .emblem-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 18px;
            position: relative;
        }

        /* Glowing Aura Behind Logos */
        .emblem-aura {
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201, 162, 74, 0.28) 0%, rgba(142, 27, 34, 0.18) 50%, transparent 75%);
            filter: blur(18px);
            z-index: 0;
            animation: auraPulse 3s infinite alternate ease-in-out;
        }

        @keyframes auraPulse {
            0% { transform: scale(0.92); opacity: 0.5; }
            100% { transform: scale(1.15); opacity: 0.95; }
        }

        .logo-main-banser {
            width: 82px;
            height: 82px;
            object-fit: contain;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 6px 18px rgba(0, 0, 0, 0.65)) drop-shadow(0 0 12px rgba(245, 192, 66, 0.35));
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: floatLogo 4s ease-in-out infinite alternate;
        }
        .logo-main-banser:hover {
            transform: scale(1.1) rotate(2deg);
        }

        .logo-adminpers-badge {
            height: 60px;
            width: auto;
            object-fit: contain;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 6px 16px rgba(0, 0, 0, 0.6)) drop-shadow(0 0 14px rgba(201, 162, 74, 0.4));
            transition: transform 0.4s ease;
            animation: floatLogo 4.5s ease-in-out infinite alternate-reverse;
        }
        .logo-adminpers-badge:hover {
            transform: scale(1.1) rotate(-2deg);
        }

        @keyframes floatLogo {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-6px); }
        }

        .badge-security {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: rgba(142, 27, 34, 0.3);
            border: 1px solid rgba(229, 35, 43, 0.4);
            border-radius: 999px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            color: #FFB3B7;
            letter-spacing: 0.08em;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .app-glitch-title {
            font-family: 'Archivo', sans-serif;
            font-size: 1.85rem;
            font-weight: 900;
            letter-spacing: 0.05em;
            background: linear-gradient(135deg, #FFFFFF 0%, #FFF5B8 40%, #E8A838 80%, #FFFFFF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 24px rgba(201, 162, 74, 0.35);
            margin-bottom: 5px;
            line-height: 1.15;
        }

        .app-sub-text {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.4;
            font-weight: 500;
        }
        .app-sub-text strong {
            color: #E2D9DC;
            font-weight: 600;
        }

        /* Form Controls & Futuristic Inputs */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.82rem;
            font-weight: 600;
            color: #E2D9DC;
            margin-bottom: 8px;
            letter-spacing: 0.02em;
        }
        .form-label-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            color: var(--neon-gold);
            opacity: 0.85;
        }

        .input-cyber-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            color: #8C7C80;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            transition: color 0.25s ease;
            z-index: 2;
        }

        .form-control-cyber {
            width: 100%;
            padding: 13px 14px 13px 44px;
            font-size: 0.95rem;
            font-family: 'Outfit', sans-serif;
            color: #FFFFFF;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 12px;
            outline: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.4);
        }

        .form-control-cyber::placeholder {
            color: #65585B;
            font-size: 0.88rem;
        }

        .form-control-cyber:focus {
            border-color: var(--neon-gold);
            background: rgba(20, 8, 14, 0.85);
            box-shadow: 
                0 0 20px rgba(201, 162, 74, 0.35),
                inset 0 0 10px rgba(142, 27, 34, 0.25);
        }

        .form-control-cyber:focus + .input-icon-left,
        .input-cyber-wrapper:focus-within .input-icon-left {
            color: var(--neon-gold);
        }

        /* Password Toggle Button */
        .btn-toggle-pwd {
            position: absolute;
            right: 12px;
            background: transparent;
            border: none;
            color: #8C7C80;
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
            z-index: 2;
        }
        .btn-toggle-pwd:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.08);
        }

        /* Error States */
        .form-control-cyber.is-invalid {
            border-color: var(--neon-red);
            box-shadow: 0 0 16px rgba(255, 46, 56, 0.28);
        }
        .form-cyber-error {
            margin-top: 6px;
            font-size: 0.8rem;
            color: #FF8F94;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Checkbox & Options */
        .form-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 0.84rem;
            color: var(--text-muted);
        }

        .custom-cyber-checkbox {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            user-select: none;
            color: #CFC2C5;
            font-size: 0.86rem;
        }
        .custom-cyber-checkbox input {
            display: none;
        }
        .checkbox-box {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 1.5px solid var(--input-border);
            background: rgba(14, 5, 9, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .custom-cyber-checkbox input:checked + .checkbox-box {
            background: var(--neon-gold);
            border-color: var(--neon-gold);
            box-shadow: 0 0 10px rgba(245, 192, 66, 0.5);
        }
        .checkbox-box svg {
            width: 12px;
            height: 12px;
            stroke: #18080C;
            stroke-width: 3;
            display: none;
        }
        .custom-cyber-checkbox input:checked + .checkbox-box svg {
            display: block;
        }

        /* Futuristic Action Button */
        .btn-cyber-submit {
            width: 100%;
            padding: 14px 20px;
            position: relative;
            background: linear-gradient(135deg, #8E1B22 0%, #B8242C 50%, #C9A24A 100%);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 12px;
            color: #FFFFFF;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            cursor: pointer;
            box-shadow: 
                0 10px 24px rgba(142, 27, 34, 0.55),
                0 0 20px rgba(201, 162, 74, 0.35);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Shimmer Wave on Button */
        .btn-cyber-submit::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 40%;
            height: 200%;
            background: linear-gradient(
                90deg, 
                transparent, 
                rgba(255, 255, 255, 0.4), 
                transparent
            );
            transform: rotate(25deg);
            animation: buttonShimmer 3.5s infinite;
        }

        @keyframes buttonShimmer {
            0% { left: -60%; }
            25% { left: 130%; }
            100% { left: 130%; }
        }

        .btn-cyber-submit:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 14px 30px rgba(142, 27, 34, 0.7),
                0 0 30px rgba(245, 192, 66, 0.6);
            background: linear-gradient(135deg, #A82028 0%, #CF2932 50%, #D4AC50 100%);
        }

        .btn-cyber-submit:active {
            transform: translateY(1px);
        }

        /* Cyber Loading Spinner inside button */
        .btn-loader {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #FFFFFF;
            border-radius: 50%;
            animation: spinLoader 0.7s linear infinite;
        }
        @keyframes spinLoader {
            to { transform: rotate(360deg); }
        }

        /* Alert Notification Box */
        .cyber-alert {
            background: rgba(229, 35, 43, 0.16);
            border: 1px solid rgba(255, 46, 56, 0.5);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #FFB3B7;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            box-shadow: 0 0 16px rgba(229, 35, 43, 0.15);
        }

        /* Tactical Command Motto Footer */
        .cyber-footer {
            margin-top: 28px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px dashed rgba(201, 162, 74, 0.2);
        }

        .motto-text {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--neon-gold);
            letter-spacing: 0.04em;
            text-shadow: 0 0 12px rgba(201, 162, 74, 0.4);
            margin-bottom: 6px;
        }

        .security-audit-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem;
            color: #7A6C70;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* Responsive Tweaks */
        @media (max-width: 520px) {
            .hud-status-strip {
                display: none;
            }
            .cyber-card {
                padding: 30px 22px 26px 22px;
                border-radius: 20px;
            }
            .app-glitch-title {
                font-size: 1.6rem;
            }
            .logo-main-banser {
                width: 70px;
                height: 70px;
            }
            .logo-adminpers-badge {
                height: 52px;
            }
        }
    </style>
</head>
<body>
    <!-- HTML5 Canvas Matrix / Interactive Constellation Background -->
    <canvas id="cyberMatrixCanvas"></canvas>

    <!-- Glowing Ambient Cyber Orbs -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>
    <div class="ambient-glow glow-3"></div>

    <!-- Cyber Grid Line Overlay -->
    <div class="cyber-grid-overlay"></div>

    <!-- Top Tactical Status Strip (Command HUD) -->
    <div class="hud-status-strip">
        <span class="hud-dot"></span>
        <span>SATKORCAB BANSER KAB. BLITAR</span>
        <span style="opacity: 0.35;">|</span>
        <span id="liveClock" class="hud-time">--:--:-- WIB</span>
        <span style="opacity: 0.35;">|</span>
        <span style="color: #10B981;">SECURE PROTOCOL AKTIF</span>
    </div>

    <!-- Centerpiece Login Box -->
    <div class="login-container">
        <div class="cyber-card">
            <!-- 4 Sci-Fi Tactical Corner Brackets -->
            <div class="corner-bracket corner-tl"></div>
            <div class="corner-bracket corner-tr"></div>
            <div class="corner-bracket corner-bl"></div>
            <div class="corner-bracket corner-br"></div>

            <!-- Header with Dual Holographic Emblems -->
            <div class="cyber-header">
                <div class="emblem-wrapper">
                    <div class="emblem-aura"></div>

                    <!-- Logo Utama BANSER -->
                    <img src="{{ asset('images/banser-logo.png') }}" 
                         onerror="this.onerror=null; this.src='{{ asset('images/banser.png') }}';" 
                         alt="Emblem Utama BANSER" 
                         class="logo-main-banser"
                         width="82" height="82">

                    <!-- Logo Adminpers Satkorcab Blitar -->
                    <img src="{{ asset('images/LOGO_ADMIN_rev_2.png') }}" 
                         onerror="this.onerror=null; this.src='{{ asset('images/adminpers-logo.png') }}';" 
                         alt="Emblem Adminpers Satkorcab Blitar" 
                         class="logo-adminpers-badge">
                </div>

                <div class="badge-security">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Sistem Terverifikasi // TLS 256-BIT</span>
                </div>

                <h1 class="app-glitch-title">SIGAP BANSER</h1>
                <p class="app-sub-text">
                    Sistem Informasi & Registrasi Administrasi Persuratan<br>
                    <strong>SATKORCAB BANSER KABUPATEN BLITAR</strong>
                </p>
            </div>

            <!-- Error Alerts -->
            @if(session('error'))
                <div class="cyber-alert" role="alert">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="cyber-alert" role="alert">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <ul style="margin-left: 14px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Form Autentikasi -->
            <form action="{{ route('login') }}" method="POST" autocomplete="on" id="cyberLoginForm">
                @csrf

                <!-- Input Username / Identifier -->
                <div class="form-group">
                    <label for="username" class="form-label">
                        <span>Nama Pengguna / ID Petugas</span>
                        <span class="form-label-tag">[USER_ID]</span>
                    </label>
                    <div class="input-cyber-wrapper">
                        <span class="input-icon-left">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               class="form-control-cyber @error('username') is-invalid @enderror" 
                               value="{{ old('username', old('email')) }}" 
                               placeholder="Ketik nama pengguna atau email..." 
                               required 
                               autofocus>
                    </div>
                    @error('username')
                        <div class="form-cyber-error">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Input Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <span>Kunci Sandi Akses</span>
                        <span class="form-label-tag">[PASSPHRASE]</span>
                    </label>
                    <div class="input-cyber-wrapper">
                        <span class="input-icon-left">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control-cyber @error('password') is-invalid @enderror" 
                               placeholder="Masukkan kata sandi..." 
                               required>
                        <button type="button" class="btn-toggle-pwd" id="btnTogglePwd" title="Tampilkan / Sembunyikan Sandi" aria-label="Toggle password visibility">
                            <svg id="eyeIcon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="form-cyber-error">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Remember Me Option -->
                <div class="form-options-row">
                    <label class="custom-cyber-checkbox">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkbox-box">
                            <svg viewBox="0 0 24 24" fill="none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                        <span>Ingat sesi perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-cyber-submit" id="btnSubmit">
                    <span class="btn-loader" id="btnLoader"></span>
                    <svg id="btnIcon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span id="btnText">Otorisasi & Masuk Sistem</span>
                </button>
            </form>

            <!-- Tactical Motto & Security Signature -->
            <div class="cyber-footer">
                <div class="motto-text">
                    "Rapi di meja, solid di laga: satu data, satu komando!"
                </div>
                <div class="security-audit-tag">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>SATKORCAB BANSER KAB. BLITAR &bull; VERSI 2.0 RESMI</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive JavaScript Engine -->
    <script>
        // Real-Time Tactical Military Clock
        function updateLiveClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const clockEl = document.getElementById('liveClock');
            if (clockEl) {
                clockEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
            }
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // Password Visibility Toggle
        const btnTogglePwd = document.getElementById('btnTogglePwd');
        const pwdInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (btnTogglePwd && pwdInput) {
            btnTogglePwd.addEventListener('click', function () {
                const isPassword = pwdInput.type === 'password';
                pwdInput.type = isPassword ? 'text' : 'password';
                if (isPassword) {
                    eyeIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    `;
                    btnTogglePwd.style.color = '#F5C042';
                } else {
                    eyeIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    `;
                    btnTogglePwd.style.color = '#8C7C80';
                }
            });
        }

        // Form Submit Loading Feedback
        const loginForm = document.getElementById('cyberLoginForm');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnLoader = document.getElementById('btnLoader');
        const btnIcon = document.getElementById('btnIcon');
        const btnText = document.getElementById('btnText');

        if (loginForm && btnSubmit) {
            loginForm.addEventListener('submit', function () {
                btnSubmit.style.pointerEvents = 'none';
                btnSubmit.style.opacity = '0.9';
                if (btnLoader) btnLoader.style.display = 'inline-block';
                if (btnIcon) btnIcon.style.display = 'none';
                if (btnText) btnText.textContent = 'Memverifikasi Akses...';
            });
        }

        // HTML5 Cyber Particle Matrix Background (Smooth, Ultra Lightweight 60FPS)
        (function initCyberCanvas() {
            const canvas = document.getElementById('cyberMatrixCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            let width = canvas.width = window.innerWidth;
            let height = canvas.height = window.innerHeight;

            window.addEventListener('resize', () => {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            });

            const particles = [];
            const count = Math.min(Math.floor(width * 0.045), 55);

            for (let i = 0; i < count; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - 0.5) * 0.65,
                    vy: (Math.random() - 0.5) * 0.65,
                    radius: Math.random() * 1.8 + 0.8,
                    color: Math.random() > 0.4 ? 'rgba(245, 192, 66, ' : 'rgba(229, 35, 43, '
                });
            }

            function animate() {
                ctx.clearRect(0, 0, width, height);

                // Draw connecting constellation lines
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < 125) {
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = `rgba(201, 162, 74, ${0.18 * (1 - dist / 125)})`;
                            ctx.lineWidth = 0.65;
                            ctx.stroke();
                        }
                    }
                }

                // Update & draw glowing particles
                for (let i = 0; i < particles.length; i++) {
                    const p = particles[i];
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0 || p.x > width) p.vx *= -1;
                    if (p.y < 0 || p.y > height) p.vy *= -1;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = p.color + '0.65)';
                    ctx.shadowColor = '#F5C042';
                    ctx.shadowBlur = 6;
                    ctx.fill();
                    ctx.shadowBlur = 0;
                }

                requestAnimationFrame(animate);
            }

            animate();
        })();
    </script>
</body>
</html>
