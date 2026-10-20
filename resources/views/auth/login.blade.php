<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - SIGAP BANSER Satkorcab Kab. Blitar</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/banser-logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --hero1: #5A0E14;
            --hero2: #240608;
            --red: #E5232B;
            --yellow: #F5EB0F;
            --gold: #C9A24A;
            --green: #0E9B4A;
            --accent: #8E1B22;
            --accent-hover: #A11F27;
            --surface: #FFFFFF;
            --ink: #1F1A1C;
            --ink-muted: #696263;
            --border: #DFD7D3;
            --radius-md: 10px;
            --radius-lg: 16px;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --surface: #201516;
                --ink: #F2E9E7;
                --ink-muted: #A89F9E;
                --border: #38292B;
                --accent: #D12A31;
                --accent-hover: #E8363E;
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Public Sans', system-ui, -apple-system, sans-serif;
            background: linear-gradient(145deg, var(--hero1) 0%, var(--hero2) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: var(--ink);
            position: relative;
        }

        /* Subtle background pattern */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(201, 162, 74, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
        }

        .tri-color-bar {
            height: 6px;
            width: 100%;
            border-top-left-radius: var(--radius-lg);
            border-top-right-radius: var(--radius-lg);
            background: linear-gradient(90deg, 
                var(--red) 0%, var(--red) 33.333%, 
                var(--yellow) 33.333%, var(--yellow) 66.666%, 
                var(--green) 66.666%, var(--green) 100%);
        }

        .login-card {
            background-color: var(--surface);
            border-bottom-left-radius: var(--radius-lg);
            border-bottom-right-radius: var(--radius-lg);
            padding: 36px 32px 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.45);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            margin-bottom: 20px;
        }

        .banser-logo-main {
            width: 86px;
            height: 86px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.35));
            transition: transform 0.25s ease;
        }

        .banser-logo-main:hover {
            transform: scale(1.05);
        }

        .adminpers-logo-badge {
            height: 62px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 14px rgba(201, 162, 74, 0.35));
        }

        .app-title {
            font-family: 'Archivo', sans-serif;
            font-size: 1.65rem;
            font-weight: 900;
            color: var(--accent);
            letter-spacing: 0.03em;
            margin-bottom: 4px;
        }

        .app-subtitle {
            font-size: 0.85rem;
            color: var(--ink-muted);
            line-height: 1.35;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--ink);
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            font-size: 0.95rem;
            font-family: inherit;
            color: var(--ink);
            background-color: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(142, 27, 34, 0.2);
        }

        .form-control.is-invalid {
            border-color: var(--red);
        }

        .form-error {
            margin-top: 6px;
            font-size: 0.82rem;
            color: var(--red);
        }

        .form-footer-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 0.85rem;
            color: var(--ink-muted);
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-checkbox input {
            accent-color: var(--accent);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background-color: var(--accent);
            color: #FFFFFF;
            font-family: 'Archivo', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(142, 27, 34, 0.35);
        }

        .btn-submit:hover {
            background-color: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(142, 27, 34, 0.45);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .login-motto {
            margin-top: 24px;
            text-align: center;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 0.02em;
        }

        .alert-box {
            background-color: rgba(229, 35, 43, 0.12);
            border: 1px solid var(--red);
            color: var(--red);
            padding: 12px 14px;
            border-radius: var(--radius-md);
            font-size: 0.88rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="tri-color-bar"></div>
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <!-- Logo Utama BANSER -->
                    <img src="{{ asset('images/banser-logo.png') }}" 
                         onerror="this.onerror=null; this.src='{{ asset('images/banser.png') }}';" 
                         alt="Logo Utama BANSER" 
                         class="banser-logo-main"
                         width="86" height="86">
                    
                    <!-- Logo Adminpers BANSER -->
                    <img src="{{ asset('images/LOGO_ADMIN_rev_2.png') }}" 
                         onerror="this.onerror=null; this.src='{{ asset('images/adminpers-logo.png') }}';" 
                         alt="Logo Adminpers Satkorcab Blitar" 
                         class="adminpers-logo-badge">
                </div>
                <h1 class="app-title">SIGAP BANSER</h1>
                <p class="app-subtitle">Sistem Informasi & Administrasi Persuratan<br>Satkorcab Kab. Blitar</p>
            </div>

            @if(session('error'))
                <div class="alert-box" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box" role="alert">
                    <ul style="margin-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" autocomplete="on">
                @csrf

                <div class="form-group">
                    <label for="username" class="form-label">Nama Pengguna / Email</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control @error('username') is-invalid @enderror" 
                           value="{{ old('username', old('email')) }}" 
                           placeholder="Masukkan username atau email" 
                           required 
                           autofocus>
                    @error('username')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Masukkan kata sandi" 
                           required>
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-footer-options">
                    <label class="remember-checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="login-motto">
                "Rapi di meja, solid di laga: satu data, satu komando!"
            </div>
        </div>
    </div>
</body>
</html>
