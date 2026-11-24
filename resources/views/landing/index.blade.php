<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIGAP BANSER - Sistem Informasi & Administrasi Persuratan Terpadu Satkorcab Banser Kabupaten Blitar. Layanan persuratan digital, disposisi komando, dan penerbitan SK resmi.">
    <meta name="keywords" content="Banser, Satkorcab Blitar, Ansor, SIGAP Banser, Persuratan Digital, Disposisi Banser">
    <meta name="author" content="Satkorcab Banser Kabupaten Blitar">
    <title>SIGAP BANSER - Portal Komando & Administrasi Persuratan Satkorcab Kabupaten Blitar</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/banser-logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,600;0,700;0,800;0,900;1,700&family=JetBrains+Mono:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-deep: #070305;
            --bg-card: rgba(18, 7, 12, 0.76);
            --bg-card-hover: rgba(28, 11, 18, 0.88);
            --border-card: rgba(201, 162, 74, 0.22);
            --border-glow: rgba(245, 192, 66, 0.55);
            
            --banser-red: #8E1B22;
            --banser-crimson: #B91C1C;
            --neon-red: #FF2E38;
            --neon-gold: #F5C042;
            --neon-yellow: #FFF133;
            --neon-green: #10B981;
            --neon-cyan: #06B6D4;
            
            --text-main: #FFFFFF;
            --text-muted: #A39397;
            --text-dim: #736267;
            
            --font-main: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-display: 'Archivo', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-deep);
            color: var(--text-main);
            overflow-x: hidden;
            position: relative;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Tactical Canvas Matrix Overlay */
        #matrixCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        /* Ambient Glow Blobs */
        .ambient-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(140px);
            pointer-events: none;
            z-index: 1;
            opacity: 0.35;
            animation: blobPulse 18s infinite alternate ease-in-out;
        }
        .blob-1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #8E1B22 0%, transparent 70%);
            top: -150px;
            left: -150px;
        }
        .blob-2 {
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, #B45309 0%, transparent 70%);
            top: 40%;
            right: -150px;
            animation-delay: -6s;
        }
        .blob-3 {
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, #047857 0%, transparent 70%);
            bottom: -100px;
            left: 20%;
            animation-delay: -11s;
        }
        @keyframes blobPulse {
            0% { transform: scale(1) translate(0, 0); }
            50% { transform: scale(1.15) translate(40px, 30px); }
            100% { transform: scale(0.92) translate(-30px, -20px); }
        }

        /* Scanline Overlay */
        .scanlines {
            position: fixed;
            inset: 0;
            background: linear-gradient(rgba(201, 162, 74, 0.02) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(201, 162, 74, 0.02) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 2;
        }

        /* Main Container Wrap */
        .site-wrap {
            position: relative;
            z-index: 10;
        }

        /* Section Container */
        .container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ═══════════════════════════════════════════════
           NAVBAR
        ═══════════════════════════════════════════════ */
        .navbar {
            position: sticky;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            background: rgba(10, 4, 7, 0.82);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(201, 162, 74, 0.2);
            transition: all 0.3s ease;
        }
        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 76px;
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: var(--text-main);
        }
        .brand-logos {
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }
        .brand-logo-img {
            height: 44px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.5));
            transition: transform 0.3s ease;
        }
        .brand-logo-img:hover {
            transform: scale(1.08);
        }
        .brand-text {
            display: flex;
            flex-direction: column;
        }
        .brand-title {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 900;
            letter-spacing: 0.04em;
            background: linear-gradient(135deg, #FFFFFF 30%, var(--neon-gold) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
        }
        .brand-sub {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            color: var(--neon-gold);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
        }
        .nav-link {
            color: #D2C6C9;
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.25s ease;
            position: relative;
            padding: 6px 0;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0%;
            height: 2px;
            background: var(--neon-gold);
            box-shadow: 0 0 8px var(--neon-gold);
            transition: width 0.25s ease;
        }
        .nav-link:hover {
            color: #FFFFFF;
        }
        .nav-link:hover::after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .hud-status-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 999px;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: #A7F3D0;
            letter-spacing: 0.05em;
        }
        .status-pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--neon-green);
            box-shadow: 0 0 10px var(--neon-green);
            animation: pulseDot 1.8s infinite;
        }
        @keyframes pulseDot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0.6; }
        }

        .btn-portal-login {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 22px;
            background: linear-gradient(135deg, #8E1B22 0%, #C9A24A 100%);
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.88rem;
            border-radius: 999px;
            box-shadow: 0 4px 20px rgba(142, 27, 34, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        .btn-portal-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transition: left 0.6s ease;
        }
        .btn-portal-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(245, 192, 66, 0.4);
            border-color: var(--neon-gold);
        }
        .btn-portal-login:hover::before {
            left: 100%;
        }

        .btn-mobile-toggle {
            display: none;
            background: none;
            border: 1px solid rgba(201, 162, 74, 0.3);
            border-radius: 8px;
            padding: 8px;
            color: var(--text-main);
            cursor: pointer;
        }

        /* ═══════════════════════════════════════════════
           HERO SECTION
        ═══════════════════════════════════════════════ */
        .hero-section {
            padding: 70px 0 60px 0;
            position: relative;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 48px;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 16px;
            background: rgba(142, 27, 34, 0.25);
            border: 1px solid rgba(245, 192, 66, 0.35);
            border-radius: 999px;
            font-family: var(--font-mono);
            font-size: 0.76rem;
            color: var(--neon-gold);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 22px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
        }
        .hero-badge-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--neon-red);
            box-shadow: 0 0 10px var(--neon-red);
        }

        .hero-title {
            font-family: var(--font-display);
            font-size: 3.2rem;
            font-weight: 900;
            line-height: 1.12;
            letter-spacing: -0.01em;
            margin-bottom: 20px;
        }
        .hero-title-highlight {
            background: linear-gradient(135deg, #FFF133 0%, #F5C042 50%, #FF2E38 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .hero-lead {
            font-size: 1.15rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 34px;
            font-weight: 300;
            max-width: 580px;
        }

        .hero-cta-group {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 15px 32px;
            background: linear-gradient(135deg, #B91C1C 0%, #8E1B22 45%, #C9A24A 100%);
            color: #FFFFFF;
            font-size: 1.05rem;
            font-weight: 800;
            text-decoration: none;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 12px 30px rgba(185, 28, 28, 0.4), 0 0 20px rgba(201, 162, 74, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-hero-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 16px 40px rgba(245, 192, 66, 0.4), 0 0 30px rgba(185, 28, 28, 0.5);
            border-color: var(--neon-gold);
        }
        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 26px;
            background: rgba(25, 10, 16, 0.6);
            color: #E6DBDE;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 12px;
            border: 1px solid rgba(201, 162, 74, 0.3);
            backdrop-filter: blur(12px);
            transition: all 0.3s ease;
        }
        .btn-hero-secondary:hover {
            background: rgba(45, 16, 26, 0.8);
            color: #FFFFFF;
            border-color: var(--neon-gold);
            transform: translateY(-2px);
        }

        .hero-security-notes {
            display: flex;
            align-items: center;
            gap: 22px;
            font-size: 0.82rem;
            color: var(--text-dim);
            font-family: var(--font-mono);
        }
        .sec-note-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ═══════════════════════════════════════════════
           HERO CONSOLE MOCKUP (Tactical HUD)
        ═══════════════════════════════════════════════ */
        .hero-mockup-wrap {
            position: relative;
        }
        .hero-hud-card {
            background: var(--bg-card);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.85), 0 0 50px rgba(142, 27, 34, 0.25);
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }
        .hero-hud-card:hover {
            border-color: var(--border-glow);
            transform: translateY(-4px);
        }
        .hud-card-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(201, 162, 74, 0.15);
            margin-bottom: 18px;
        }
        .hud-dots {
            display: flex;
            gap: 6px;
        }
        .hud-dot-item {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .hud-dot-red { background: #EF4444; }
        .hud-dot-yellow { background: #F59E0B; }
        .hud-dot-green { background: #10B981; }

        .hud-console-title {
            font-family: var(--font-mono);
            font-size: 0.74rem;
            color: var(--neon-gold);
            letter-spacing: 0.08em;
        }

        .hud-banner-box {
            background: linear-gradient(135deg, rgba(90, 14, 20, 0.5) 0%, rgba(20, 5, 8, 0.8) 100%);
            border: 1px solid rgba(201, 162, 74, 0.2);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        .hud-banser-crest {
            width: 58px;
            height: 58px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(245, 192, 66, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
        }
        .hud-banser-crest img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .hud-org-info h4 {
            font-family: var(--font-display);
            font-size: 1rem;
            color: #FFFFFF;
            font-weight: 800;
        }
        .hud-org-info p {
            font-size: 0.8rem;
            color: var(--neon-gold);
            font-family: var(--font-mono);
        }

        .hud-stats-mini-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .mini-stat-box {
            background: rgba(10, 4, 7, 0.65);
            border: 1px solid rgba(201, 162, 74, 0.15);
            border-radius: 10px;
            padding: 12px 14px;
            transition: border-color 0.2s ease;
        }
        .mini-stat-box:hover {
            border-color: rgba(245, 192, 66, 0.4);
        }
        .mini-stat-label {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .mini-stat-val {
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 800;
            color: #FFFFFF;
        }
        .val-gold { color: var(--neon-gold); }
        .val-green { color: var(--neon-green); }

        .hud-activity-strip {
            background: rgba(7, 3, 5, 0.85);
            border: 1px solid rgba(201, 162, 74, 0.15);
            border-radius: 10px;
            padding: 12px 14px;
        }
        .hud-activity-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: var(--font-mono);
            font-size: 0.7rem;
            color: var(--neon-gold);
            margin-bottom: 8px;
        }
        .hud-activity-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.78rem;
            color: #DDD4D6;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }
        .hud-activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .badge-live-tag {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.65rem;
            font-family: var(--font-mono);
            font-weight: 700;
        }
        .tag-sk { background: rgba(239, 68, 68, 0.2); color: #FCA5A5; }
        .tag-disp { background: rgba(245, 158, 11, 0.2); color: #FCD34D; }

        /* Floating Stamp Badge */
        .hud-stamp-float {
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 105px;
            height: 105px;
            background: rgba(142, 27, 34, 0.85);
            border: 2px solid var(--neon-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.7), 0 0 20px rgba(245, 192, 66, 0.3);
            backdrop-filter: blur(10px);
            padding: 8px;
            animation: spinGentle 25s linear infinite;
        }
        .hud-stamp-float img {
            width: 85%;
            height: 85%;
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
        }
        @keyframes spinGentle {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ═══════════════════════════════════════════════
           TICKER RIBBON
        ═══════════════════════════════════════════════ */
        .ticker-section {
            background: linear-gradient(90deg, #5A0E14 0%, #1A0306 50%, #5A0E14 100%);
            border-top: 1px solid rgba(245, 192, 66, 0.3);
            border-bottom: 1px solid rgba(245, 192, 66, 0.3);
            padding: 12px 0;
            overflow: hidden;
            box-shadow: 0 0 25px rgba(142, 27, 34, 0.35);
        }
        .ticker-inner {
            display: flex;
            width: max-content;
            animation: marquee 30s linear infinite;
        }
        .ticker-text {
            display: flex;
            align-items: center;
            gap: 24px;
            padding-right: 24px;
            font-family: var(--font-mono);
            font-size: 0.84rem;
            color: #FFFFFF;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
        }
        .ticker-sep {
            color: var(--neon-gold);
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* ═══════════════════════════════════════════════
           STATS SECTION
        ═══════════════════════════════════════════════ */
        .stats-section {
            padding: 60px 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .stat-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 26px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--border-glow);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6), 0 0 20px rgba(245, 192, 66, 0.15);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #FF2E38, #F5C042, #10B981);
        }
        .stat-icon {
            font-size: 1.8rem;
            margin-bottom: 10px;
            display: inline-block;
        }
        .stat-number {
            font-family: var(--font-display);
            font-size: 2.6rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 6px;
            background: linear-gradient(135deg, #FFFFFF 40%, var(--neon-gold) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .stat-title {
            font-size: 0.92rem;
            color: #FFFFFF;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .stat-desc {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-family: var(--font-mono);
        }

        /* ═══════════════════════════════════════════════
           SECTION TITLE & HEADERS
        ═══════════════════════════════════════════════ */
        .section-header {
            text-align: center;
            max-width: 720px;
            margin: 0 auto 50px auto;
        }
        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 4px 14px;
            background: rgba(201, 162, 74, 0.15);
            border: 1px solid rgba(201, 162, 74, 0.3);
            border-radius: 999px;
            font-family: var(--font-mono);
            font-size: 0.74rem;
            color: var(--neon-gold);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .section-title {
            font-family: var(--font-display);
            font-size: 2.3rem;
            font-weight: 900;
            letter-spacing: -0.01em;
            margin-bottom: 14px;
            line-height: 1.2;
        }
        .section-title-gradient {
            background: linear-gradient(135deg, #FFFFFF 40%, var(--neon-gold) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .section-sub {
            font-size: 1.05rem;
            color: var(--text-muted);
            font-weight: 300;
        }

        /* ═══════════════════════════════════════════════
           FEATURES SECTION
        ═══════════════════════════════════════════════ */
        .features-section {
            padding: 60px 0 80px 0;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        .feature-card {
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            border: 1px solid var(--border-card);
            border-radius: 18px;
            padding: 32px 26px;
            position: relative;
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .feature-card:hover {
            transform: translateY(-6px);
            border-color: var(--border-glow);
            background: var(--bg-card-hover);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.7), 0 0 30px rgba(142, 27, 34, 0.2);
        }
        .feature-icon-box {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(142, 27, 34, 0.6) 0%, rgba(201, 162, 74, 0.3) 100%);
            border: 1px solid rgba(245, 192, 66, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            color: var(--neon-gold);
        }
        .feature-card h3 {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 12px;
            color: #FFFFFF;
        }
        .feature-card p {
            color: var(--text-muted);
            font-size: 0.94rem;
            line-height: 1.65;
            margin-bottom: 18px;
        }
        .feature-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .feature-tag-chip {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.05);
            color: #D3C6CA;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* ═══════════════════════════════════════════════
           SATUAN KHUSUS BANSER SHOWCASE
        ═══════════════════════════════════════════════ */
        .units-section {
            padding: 60px 0 80px 0;
            background: radial-gradient(circle at 50% 50%, rgba(90, 14, 20, 0.18) 0%, transparent 70%);
        }
        .units-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }
        .unit-card {
            background: rgba(15, 6, 10, 0.8);
            border: 1px solid rgba(201, 162, 74, 0.2);
            border-radius: 16px;
            padding: 26px 22px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .unit-card:hover {
            transform: translateY(-5px);
            border-color: var(--neon-gold);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
        }
        .unit-badge-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .unit-code {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--neon-gold);
            padding: 3px 8px;
            background: rgba(201, 162, 74, 0.12);
            border-radius: 4px;
            border: 1px solid rgba(201, 162, 74, 0.25);
            font-weight: 700;
        }
        .unit-name {
            font-family: var(--font-display);
            font-size: 1.3rem;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 6px;
        }
        .unit-full {
            font-size: 0.85rem;
            color: var(--neon-gold);
            font-weight: 600;
            margin-bottom: 12px;
        }
        .unit-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* ═══════════════════════════════════════════════
           WORKFLOW TIMELINE
        ═══════════════════════════════════════════════ */
        .workflow-section {
            padding: 60px 0 80px 0;
        }
        .workflow-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            position: relative;
        }
        .workflow-step {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 28px 20px;
            position: relative;
            transition: all 0.3s ease;
        }
        .workflow-step:hover {
            transform: translateY(-4px);
            border-color: var(--border-glow);
        }
        .step-num {
            font-family: var(--font-mono);
            font-size: 1.8rem;
            font-weight: 900;
            color: rgba(201, 162, 74, 0.4);
            margin-bottom: 12px;
        }
        .workflow-step h4 {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 8px;
        }
        .workflow-step p {
            font-size: 0.86rem;
            color: var(--text-muted);
            line-height: 1.55;
        }

        /* ═══════════════════════════════════════════════
           NAWA PRASETYA BANSER
        ═══════════════════════════════════════════════ */
        .nawa-section {
            padding: 60px 0 80px 0;
            background: linear-gradient(180deg, transparent 0%, rgba(90, 14, 20, 0.15) 50%, transparent 100%);
        }
        .nawa-box {
            background: var(--bg-card);
            backdrop-filter: blur(28px);
            border: 1px solid var(--border-card);
            border-radius: 24px;
            padding: 44px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.8);
            position: relative;
        }
        .nawa-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        .nawa-title-box h3 {
            font-family: var(--font-display);
            font-size: 1.8rem;
            font-weight: 900;
            color: #FFFFFF;
        }
        .nawa-title-box p {
            color: var(--neon-gold);
            font-family: var(--font-mono);
            font-size: 0.88rem;
        }
        .nawa-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .nawa-item {
            background: rgba(10, 4, 7, 0.7);
            border: 1px solid rgba(201, 162, 74, 0.15);
            border-radius: 12px;
            padding: 16px 18px;
            display: flex;
            gap: 14px;
        }
        .nawa-digit {
            font-family: var(--font-mono);
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--neon-gold);
        }
        .nawa-text {
            font-size: 0.88rem;
            color: #E0D4D7;
            line-height: 1.5;
        }

        /* ═══════════════════════════════════════════════
           ORGANIZATION PROFIL / KASATKORCAB QUOTE
        ═══════════════════════════════════════════════ */
        .org-section {
            padding: 60px 0 80px 0;
        }
        .org-card {
            background: linear-gradient(135deg, rgba(25, 9, 15, 0.85) 0%, rgba(10, 4, 7, 0.95) 100%);
            border: 1px solid rgba(245, 192, 66, 0.3);
            border-radius: 24px;
            padding: 44px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.85);
            position: relative;
        }
        .org-detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 16px;
        }
        .org-detail-label {
            font-family: var(--font-mono);
            font-size: 0.74rem;
            color: var(--neon-gold);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .org-detail-val {
            font-size: 1.15rem;
            font-weight: 700;
            color: #FFFFFF;
        }

        .quote-box {
            background: rgba(142, 27, 34, 0.2);
            border-left: 4px solid var(--neon-gold);
            padding: 24px;
            border-radius: 0 16px 16px 0;
        }
        .quote-text {
            font-size: 1.15rem;
            font-style: italic;
            color: #FFFFFF;
            line-height: 1.7;
            margin-bottom: 14px;
        }
        .quote-author {
            font-family: var(--font-display);
            font-weight: 800;
            color: var(--neon-gold);
            font-size: 1rem;
        }
        .quote-role {
            font-family: var(--font-mono);
            font-size: 0.76rem;
            color: var(--text-muted);
        }

        /* ═══════════════════════════════════════════════
           BOTTOM CALL TO ACTION
        ═══════════════════════════════════════════════ */
        .bottom-cta {
            padding: 70px 0 90px 0;
            text-align: center;
        }
        .bottom-cta-box {
            background: radial-gradient(circle at 50% 0%, rgba(185, 28, 28, 0.35) 0%, rgba(18, 7, 12, 0.9) 70%);
            border: 1px solid var(--border-glow);
            border-radius: 28px;
            padding: 60px 30px;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.9), 0 0 50px rgba(185, 28, 28, 0.25);
            position: relative;
        }
        .bottom-cta-box h2 {
            font-family: var(--font-display);
            font-size: 2.6rem;
            font-weight: 900;
            margin-bottom: 16px;
        }
        .bottom-cta-box p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 34px auto;
        }

        /* ═══════════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════════ */
        .site-footer {
            background: #040203;
            border-top: 1px solid rgba(201, 162, 74, 0.15);
            padding: 40px 0 30px 0;
            font-size: 0.88rem;
            color: var(--text-muted);
        }
        .footer-grid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 24px;
            padding-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .footer-brand img {
            height: 38px;
        }
        .footer-links {
            display: flex;
            gap: 20px;
            list-style: none;
        }
        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .footer-links a:hover {
            color: var(--neon-gold);
        }
        .footer-bottom {
            padding-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: var(--font-mono);
            font-size: 0.78rem;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* ═══════════════════════════════════════════════
           RESPONSIVE STYLES
        ═══════════════════════════════════════════════ */
        @media (max-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .hero-title {
                font-size: 2.6rem;
            }
            .features-grid,
            .units-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .workflow-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .nawa-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .org-card {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                position: absolute;
                top: 76px;
                left: 0;
                width: 100%;
                background: rgba(10, 4, 7, 0.96);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 24px;
                border-bottom: 1px solid rgba(201, 162, 74, 0.2);
                gap: 18px;
            }
            .nav-menu.show {
                display: flex;
            }
            .btn-mobile-toggle {
                display: block;
            }
            .hud-status-badge {
                display: none;
            }
            .hero-title {
                font-size: 2.1rem;
            }
            .features-grid,
            .units-grid,
            .stats-grid,
            .workflow-grid,
            .nawa-grid {
                grid-template-columns: 1fr;
            }
            .hero-cta-group {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-hero-primary,
            .btn-hero-secondary {
                justify-content: center;
                text-align: center;
            }
            .footer-grid,
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <!-- Tactical Particle Canvas Grid -->
    <canvas id="matrixCanvas"></canvas>

    <!-- Ambient Glowing Blobs -->
    <div class="ambient-blob blob-1"></div>
    <div class="ambient-blob blob-2"></div>
    <div class="ambient-blob blob-3"></div>

    <!-- Scanline Grid Overlay -->
    <div class="scanlines"></div>

    <div class="site-wrap">

        <!-- ═══════════════════════════════════════════════
             STICKY NAVBAR
        ═══════════════════════════════════════════════ -->
        <nav class="navbar">
            <div class="container nav-inner">
                <a href="{{ url('/') }}" class="nav-brand">
                    <div class="brand-logos">
                        <img src="{{ asset('images/ansor-logo.png') }}" alt="Logo GP Ansor" class="brand-logo-img">
                        <img src="{{ asset('images/banser-logo.png') }}" alt="Logo Banser" class="brand-logo-img">
                    </div>
                    <div class="brand-text">
                        <span class="brand-title">SIGAP BANSER</span>
                        <span class="brand-sub">SATKORCAB KAB. BLITAR</span>
                    </div>
                </a>

                <ul class="nav-menu" id="navMenu">
                    <li><a href="#beranda" class="nav-link">Beranda</a></li>
                    <li><a href="#fitur" class="nav-link">Modul Fitur</a></li>
                    <li><a href="#satuan-khusus" class="nav-link">Satuan Khusus</a></li>
                    <li><a href="#alur" class="nav-link">Alur Surat</a></li>
                    <li><a href="#profil" class="nav-link">Profil Komando</a></li>
                    <li><a href="#kontak" class="nav-link">Sekretariat</a></li>
                </ul>

                <div class="nav-actions">
                    <div class="hud-status-badge">
                        <span class="status-pulse"></span>
                        <span>KODAL: ONLINE</span>
                    </div>

                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-portal-login">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Buka Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-portal-login">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span>Masuk Portal</span>
                        </a>
                    @endauth

                    <button class="btn-mobile-toggle" id="btnToggleNav" aria-label="Toggle Menu">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        @if(session('success'))
            <div class="container" style="padding-top: 20px;">
                <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.45); border-radius: 14px; padding: 14px 22px; color: #A7F3D0; font-family: var(--font-mono); font-size: 0.88rem; display: flex; align-items: center; justify-content: space-between; backdrop-filter: blur(14px); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="font-size: 1.25rem;">✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: #A7F3D0; font-size: 1.4rem; line-height: 1; cursor: pointer; padding: 0 6px;">&times;</button>
                </div>
            </div>
        @endif

        <!-- ═══════════════════════════════════════════════
             HERO SECTION
        ═══════════════════════════════════════════════ -->
        <header class="hero-section" id="beranda">
            <div class="container hero-grid">
                
                <!-- Left Hero Content -->
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        <span>SISTEM INFORMASI & PERSURATAN TERPADU • WILAYAH XII-11</span>
                    </div>

                    <h1 class="hero-title">
                        Sistem Komando & Administrasi Persuratan 
                        <span class="hero-title-highlight">SIGAP BANSER</span>
                    </h1>

                    <p class="hero-lead">
                        Pusat kendali administrasi terintegrasi Satkorcab Banser Kabupaten Blitar. Menghubungkan seluruh Satkoryon se-Kabupaten Blitar dengan manajemen surat masuk, surat keluar, disposisi pimpinan, dan pengarsipan Surat Keputusan secara digital, presisi, dan akuntabel.
                    </p>

                    <div class="hero-cta-group">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-hero-primary">
                                <span>Menuju Pusat Komando</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-hero-primary">
                                <span>Masuk ke Sistem</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        @endauth

                        <a href="#fitur" class="btn-hero-secondary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 16 16 12 12 8"></polyline>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            <span>Jelajahi Fitur</span>
                        </a>
                    </div>

                    <div class="hero-security-notes">
                        <div class="sec-note-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--neon-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            <span>Autentikasi Terenkripsi</span>
                        </div>
                        <div class="sec-note-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--neon-green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span>Stempel & TTD Resmi</span>
                        </div>
                        <div class="sec-note-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--neon-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                            </svg>
                            <span>Standar Baku Ansor-Banser</span>
                        </div>
                    </div>
                </div>

                <!-- Right Hero Tactical Console Mockup -->
                <div class="hero-mockup-wrap">
                    <div class="hero-hud-card">
                        
                        <!-- Top HUD Bar -->
                        <div class="hud-card-topbar">
                            <div class="hud-dots">
                                <span class="hud-dot-item hud-dot-red"></span>
                                <span class="hud-dot-item hud-dot-yellow"></span>
                                <span class="hud-dot-item hud-dot-green"></span>
                            </div>
                            <span class="hud-console-title">KODAL-PERSURATAN // SATKORCAB-BLITAR</span>
                            <span style="font-family: var(--font-mono); font-size: 0.68rem; color: #10B981;">v2.6 SECURE</span>
                        </div>

                        <!-- Organization Banner Inside Mockup -->
                        <div class="hud-banner-box">
                            <div class="hud-banser-crest">
                                <img src="{{ asset('images/banser-logo.png') }}" alt="Emblem Banser">
                            </div>
                            <div class="hud-org-info">
                                <h4>{{ $profil->satkorcab ?? 'SATKORCAB BANSER KABUPATEN BLITAR' }}</h4>
                                <p>KODE WILAYAH: {{ $profil->kode_wilayah ?? 'XII-11' }} • JAWA TIMUR</p>
                            </div>
                        </div>

                        <!-- Mini Stats Grid -->
                        <div class="hud-stats-mini-grid">
                            <div class="mini-stat-box">
                                <div class="mini-stat-label">Surat Masuk</div>
                                <div class="mini-stat-val val-gold">{{ $stats['total_surat_masuk'] }} <span style="font-size:0.8rem; font-weight:normal; color:#A39397;">Berkas</span></div>
                            </div>
                            <div class="mini-stat-box">
                                <div class="mini-stat-label">Surat Keluar / SK</div>
                                <div class="mini-stat-val val-green">{{ $stats['total_surat_keluar'] }} <span style="font-size:0.8rem; font-weight:normal; color:#A39397;">Terbit</span></div>
                            </div>
                            <div class="mini-stat-box">
                                <div class="mini-stat-label">Satkoryon Wilayah</div>
                                <div class="mini-stat-val">{{ $stats['total_satuan'] }} <span style="font-size:0.8rem; font-weight:normal; color:#A39397;">Kecamatan</span></div>
                            </div>
                            <div class="mini-stat-box">
                                <div class="mini-stat-label">Status Komando</div>
                                <div class="mini-stat-val" style="color:#38BDF8; font-size:1.15rem;">SIAGA AKTIF</div>
                            </div>
                        </div>

                        <!-- Mini Activity Feed -->
                        <div class="hud-activity-strip">
                            <div class="hud-activity-title">
                                <span>LOG AKTIVITAS SISTEM TERKINI</span>
                                <span>LIVE</span>
                            </div>
                            <div class="hud-activity-item">
                                <span class="badge-live-tag tag-sk">SK/SPH</span>
                                <span style="flex:1;">Penerbitan Surat Keputusan Pengurus Satkoryon</span>
                                <span style="font-family: var(--font-mono); font-size: 0.7rem; color: #10B981;">VALID</span>
                            </div>
                            <div class="hud-activity-item">
                                <span class="badge-live-tag tag-disp">DISPOSISI</span>
                                <span style="flex:1;">Instruksi Pengamanan Peringatan Hari Santri</span>
                                <span style="font-family: var(--font-mono); font-size: 0.7rem; color: #F5C042;">KASAT</span>
                            </div>
                        </div>

                    </div>

                    <!-- Floating Stamp Official Seal -->
                    <div class="hud-stamp-float" title="Stempel Resmi Satkorcab Banser Kabupaten Blitar">
                        <img src="{{ asset('images/banser-emblem.png') }}" onerror="this.src='{{ asset('images/banser-logo.png') }}'" alt="Stempel Banser">
                    </div>
                </div>

            </div>
        </header>

        <!-- ═══════════════════════════════════════════════
             RUNNING TICKER
        ═══════════════════════════════════════════════ -->
        <section class="ticker-section">
            <div class="ticker-inner">
                <div class="ticker-text">
                    <span>DISIPLIN</span> <span class="ticker-sep">•</span>
                    <span>LOYALITAS</span> <span class="ticker-sep">•</span>
                    <span>SATU KOMANDO</span> <span class="ticker-sep">•</span>
                    <span>BENTENG ULAMA & PENJAGA KEUTUHAN NKRI</span> <span class="ticker-sep">•</span>
                    <span>SATKORCAB BANSER KABUPATEN BLITAR</span> <span class="ticker-sep">•</span>
                    <span>KODE WILAYAH: XII-11</span> <span class="ticker-sep">•</span>
                    <span>SIAGA PENGABDIAN TIADA HENTI</span> <span class="ticker-sep">•</span>
                </div>
                <div class="ticker-text">
                    <span>DISIPLIN</span> <span class="ticker-sep">•</span>
                    <span>LOYALITAS</span> <span class="ticker-sep">•</span>
                    <span>SATU KOMANDO</span> <span class="ticker-sep">•</span>
                    <span>BENTENG ULAMA & PENJAGA KEUTUHAN NKRI</span> <span class="ticker-sep">•</span>
                    <span>SATKORCAB BANSER KABUPATEN BLITAR</span> <span class="ticker-sep">•</span>
                    <span>KODE WILAYAH: XII-11</span> <span class="ticker-sep">•</span>
                    <span>SIAGA PENGABDIAN TIADA HENTI</span> <span class="ticker-sep">•</span>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════
             STATS COUNTERS
        ═══════════════════════════════════════════════ -->
        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">
                    
                    <div class="stat-card">
                        <div class="stat-icon">🏛️</div>
                        <div class="stat-number">{{ $stats['total_satuan'] }}</div>
                        <div class="stat-title">Satkoryon Kecamatan</div>
                        <div class="stat-desc">Terkoordinasi se-Kabupaten Blitar</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">⚡</div>
                        <div class="stat-number">7</div>
                        <div class="stat-title">Satuan Khusus Siaga</div>
                        <div class="stat-desc">Provost, Protokoler, Bagana, Basada, Balantas, Baritim, Balakar</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">📜</div>
                        <div class="stat-number">{{ $stats['total_arsip'] ?: '100%' }}</div>
                        <div class="stat-title">Arsip Surat Digital</div>
                        <div class="stat-desc">Terkatalogisasi & aman tersimpan</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">🛡️</div>
                        <div class="stat-number">24/7</div>
                        <div class="stat-title">Kesiapsiagaan Komando</div>
                        <div class="stat-desc">Pengawalan ulama, umat & NKRI</div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════
             MODUL FITUR UTAMA
        ═══════════════════════════════════════════════ -->
        <section class="features-section" id="fitur">
            <div class="container">
                
                <div class="section-header">
                    <div class="section-tag">
                        <span>MODUL LAYANAN UTAMA</span>
                    </div>
                    <h2 class="section-title">
                        Arsitektur Digital <span class="section-title-gradient">SIGAP BANSER</span>
                    </h2>
                    <p class="section-sub">
                        Enam pilar teknologi dirancang khusus untuk memenuhi standar administrasi resmi organisasi Gerakan Pemuda Ansor dan Banser.
                    </p>
                </div>

                <div class="features-grid">
                    
                    <!-- Fitur 1 -->
                    <div class="feature-card">
                        <div class="feature-icon-box">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <h3>Manajemen Surat Masuk</h3>
                        <p>Pencatatan surat masuk otomatis dengan scan berkas PDF/gambar, indeks klasifikasi, batas waktu tanggapan, serta buku agenda digital.</p>
                        <div class="feature-tags">
                            <span class="feature-tag-chip">Scan Berkas</span>
                            <span class="feature-tag-chip">Agenda Masuk</span>
                            <span class="feature-tag-chip">Pengarsipan</span>
                        </div>
                    </div>

                    <!-- Fitur 2 -->
                    <div class="feature-card">
                        <div class="feature-icon-box">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </div>
                        <h3>Surat Keluar & Penomoran</h3>
                        <p>Penomoran otomatis sesuai kode klasifikasi resmi Ansor-Banser wilayah XII-11, manajemen draf, pengesahan, dan pengiriman dokumen.</p>
                        <div class="feature-tags">
                            <span class="feature-tag-chip">Auto-Numbering</span>
                            <span class="feature-tag-chip">Kode XII-11</span>
                            <span class="feature-tag-chip">Approval</span>
                        </div>
                    </div>

                    <!-- Fitur 3 -->
                    <div class="feature-card">
                        <div class="feature-icon-box">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <h3>Penerbitan SK & SPH Resmi</h3>
                        <p>Pembuatan Surat Keputusan pengesahan pengurus dan Surat Perintah Harian (SPH) komando dengan template baku organisasi berstempel.</p>
                        <div class="feature-tags">
                            <span class="feature-tag-chip">SK Pengurus</span>
                            <span class="feature-tag-chip">SPH Penugasan</span>
                            <span class="feature-tag-chip">Export PDF</span>
                        </div>
                    </div>

                    <!-- Fitur 4 -->
                    <div class="feature-card">
                        <div class="feature-icon-box">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </div>
                        <h3>Lembar Disposisi Kasatkorcab</h3>
                        <p>Pemberian instruksi dan arahan komando berjenjang dari Kasatkorcab ke jajaran staf perwira dan komandan satuan tugas secara realtime.</p>
                        <div class="feature-tags">
                            <span class="feature-tag-chip">Instruksi Pimpinan</span>
                            <span class="feature-tag-chip">Cetak Disposisi</span>
                            <span class="feature-tag-chip">Tracking Status</span>
                        </div>
                    </div>

                    <!-- Fitur 5 -->
                    <div class="feature-card">
                        <div class="feature-icon-box">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3>Database Personel & Satuan</h3>
                        <p>Pendataan identitas anggota Banser, rekam jejak kaderisasi (Diklatsar, Susbalan, Suspim), unit penugasan, serta struktur hierarki.</p>
                        <div class="feature-tags">
                            <span class="feature-tag-chip">Data Anggota</span>
                            <span class="feature-tag-chip">Jenjang Kader</span>
                            <span class="feature-tag-chip">Struktur Posisi</span>
                        </div>
                    </div>

                    <!-- Fitur 6 -->
                    <div class="feature-card">
                        <div class="feature-icon-box">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <path d="M9 15h6"></path>
                                <path d="M9 11h6"></path>
                            </svg>
                        </div>
                        <h3>Rekap & Laporan Eksekutif</h3>
                        <p>Cetak rekapitulasi persuratan berkala dalam format PDF resmi dengan kop surat, stempel, dan tanda tangan digital pengurus Satkorcab.</p>
                        <div class="feature-tags">
                            <span class="feature-tag-chip">Laporan PDF</span>
                            <span class="feature-tag-chip">Filter Periode</span>
                            <span class="feature-tag-chip">Siap Cetak</span>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- ═══════════════════════════════════════════════
             SATUAN KHUSUS BANSER SHOWCASE
        ═══════════════════════════════════════════════ -->
        <section class="units-section" id="satuan-khusus">
            <div class="container">
                
                <div class="section-header">
                    <div class="section-tag">
                        <span>DIVISI KHUSUS SATKORCAB</span>
                    </div>
                    <h2 class="section-title">
                        Satuan Khusus <span class="section-title-gradient">BANSER KAB. BLITAR</span>
                    </h2>
                    <p class="section-sub">
                        Kesiapsiagaan unit-unit operasional Banser dalam pengabdian kemanusiaan, penanggulangan bencana, penegakan ketertiban, dan keamanan NKRI.
                    </p>
                </div>

                <div class="units-grid">
                    
                    <!-- 1. PROVOST -->
                    <div class="unit-card">
                        <div class="unit-badge-header">
                            <span class="unit-code">UNIT 01</span>
                            <span style="font-size: 1.5rem;">⚖️</span>
                        </div>
                        <h3 class="unit-name">PROVOST</h3>
                        <div class="unit-full">Penegak Disiplin & Tata Tertib Korps</div>
                        <p class="unit-desc">
                            Menegakkan kode kehormatan, kedisiplinan prajurit, pengawasan penggunaan seragam atribut resmi, serta pengamanan internal seluruh jajaran personel Banser saat bertugas.
                        </p>
                    </div>

                    <!-- 2. PROTOKOLER -->
                    <div class="unit-card">
                        <div class="unit-badge-header">
                            <span class="unit-code">UNIT 02</span>
                            <span style="font-size: 1.5rem;">🎙️</span>
                        </div>
                        <h3 class="unit-name">PROTOKOLER</h3>
                        <div class="unit-full">Tata Upacara & Pengawalan Kehormatan</div>
                        <p class="unit-desc">
                            Mengatur manajemen keprotokolan acara resmi kenegaraan dan keagamaan NU, tata upacara apel, serta penyambutan dan pengawalan VIP/VVIP para Habaib, Masyayikh, dan Ulama.
                        </p>
                    </div>

                    <!-- 3. BAGANA -->
                    <div class="unit-card">
                        <div class="unit-badge-header">
                            <span class="unit-code">UNIT 03</span>
                            <span style="font-size: 1.5rem;">🆘</span>
                        </div>
                        <h3 class="unit-name">BAGANA</h3>
                        <div class="unit-full">Banser Tanggap Bencana</div>
                        <p class="unit-desc">
                            Satuan reaksi cepat penanggulangan bencana alam, evakuasi dan pencarian korban (SAR), pendirian posko darurat, dapur umum, serta pemulihan pasca bencana di Blitar Raya.
                        </p>
                    </div>

                    <!-- 4. BASADA -->
                    <div class="unit-card">
                        <div class="unit-badge-header">
                            <span class="unit-code">UNIT 04</span>
                            <span style="font-size: 1.5rem;">🩺</span>
                        </div>
                        <h3 class="unit-name">BASADA</h3>
                        <div class="unit-full">Banser Husada (Medis Lapangan)</div>
                        <p class="unit-desc">
                            Pemberian pertolongan pertama pada kecelakaan (P3K), kesiagaan medis lapangan pada apel/kegiatan akbar, pendampingan kesehatan masyarakat, serta aksi donor darah.
                        </p>
                    </div>

                    <!-- 5. BALANTAS -->
                    <div class="unit-card">
                        <div class="unit-badge-header">
                            <span class="unit-code">UNIT 05</span>
                            <span style="font-size: 1.5rem;">🚦</span>
                        </div>
                        <h3 class="unit-name">BALANTAS</h3>
                        <div class="unit-full">Banser Lalu Lintas</div>
                        <p class="unit-desc">
                            Pengawalan kehormatan para Kiai/Ulama, serta pengaturan keamanan dan kelancaran arus lalu lintas bersinergi dengan kepolisian pada pengajian dan agenda akbar kemasyarakatan.
                        </p>
                    </div>

                    <!-- 6. BARITIM -->
                    <div class="unit-card">
                        <div class="unit-badge-header">
                            <span class="unit-code">UNIT 06</span>
                            <span style="font-size: 1.5rem;">⚓</span>
                        </div>
                        <h3 class="unit-name">BARITIM</h3>
                        <div class="unit-full">Banser Maritim (Pesisir & Bahari)</div>
                        <p class="unit-desc">
                            Pengamanan wilayah pesisir pantai Blitar Selatan, mitigasi keselamatan pelayaran nelayan tradisional, SAR perairan darat dan laut, serta pelestarian ekosistem bahari.
                        </p>
                    </div>

                    <!-- 7. BALAKAR -->
                    <div class="unit-card">
                        <div class="unit-badge-header">
                            <span class="unit-code">UNIT 07</span>
                            <span style="font-size: 1.5rem;">🚒</span>
                        </div>
                        <h3 class="unit-name">BALAKAR</h3>
                        <div class="unit-full">Banser Relawan Kebakaran</div>
                        <p class="unit-desc">
                            Korps kesiapsiagaan pemadaman api, evakuasi korban kebakaran, edukasi keselamatan bahaya api lingkungan, serta koordinasi teknis bersama dinas pemadam kebakaran.
                        </p>
                    </div>

                </div>

            </div>
        </section>

        <!-- ═══════════════════════════════════════════════
             ALUR PELAYANAN PERSURATAN
        ═══════════════════════════════════════════════ -->
        <section class="workflow-section" id="alur">
            <div class="container">
                
                <div class="section-header">
                    <div class="section-tag">
                        <span>STANDAR OPERASIONAL PROSEDUR</span>
                    </div>
                    <h2 class="section-title">
                        Alur Tata Kelola <span class="section-title-gradient">Persuratan Satuan</span>
                    </h2>
                    <p class="section-sub">
                        Mekanisme cepat, rapi, dan terdata dari penerimaan berkas hingga penerbitan surat resmi berstempel organisasi.
                    </p>
                </div>

                <div class="workflow-grid">
                    
                    <div class="workflow-step">
                        <div class="step-num">01</div>
                        <h4>Penerimaan & Registrasi</h4>
                        <p>Surat masuk diterima staf sekretariat, dicatat dalam buku agenda digital, dan diunggah pindaian berkas fisiknya.</p>
                    </div>

                    <div class="workflow-step">
                        <div class="step-num">02</div>
                        <h4>Disposisi Komando</h4>
                        <p>Kasatkorcab menelaah surat dan memberikan arahan tindakan resmi melalui lembar disposisi elektronik.</p>
                    </div>

                    <div class="workflow-step">
                        <div class="step-num">03</div>
                        <h4>Penerbitan & Otorisasi</h4>
                        <p>Draft surat balasan atau SK dibuat menggunakan penomoran otomatis, diverifikasi dan dibubuhi tanda tangan & stempel.</p>
                    </div>

                    <div class="workflow-step">
                        <div class="step-num">04</div>
                        <h4>Distribusi & Arsip</h4>
                        <p>Surat didistribusikan ke penerima tujuan dan otomatis tersimpan dalam arsip terenkripsi yang dapat diakses sewaktu-waktu.</p>
                    </div>

                </div>

            </div>
        </section>

        <!-- ═══════════════════════════════════════════════
             NAWA PRASETYA BANSER
        ═══════════════════════════════════════════════ -->
        <section class="nawa-section">
            <div class="container">
                <div class="nawa-box">
                    <div class="nawa-header-inner">
                        <div class="nawa-title-box">
                            <h3>NAWA PRASETYA BANSER</h3>
                            <p>Sembilan Ikrar Janji Kehormatan Prajurit Barisan Ansor Serbaguna</p>
                        </div>
                        <div style="font-family: var(--font-mono); font-size: 0.78rem; color: var(--neon-gold); border: 1px solid rgba(245, 192, 66, 0.3); padding: 6px 14px; border-radius: 999px;">
                            IKRAR KEHORMATAN KORPS
                        </div>
                    </div>

                    <div class="nawa-grid">
                        <div class="nawa-item">
                            <span class="nawa-digit">1.</span>
                            <span class="nawa-text">Bertaqwa kepada Allah SWT dan berpegang teguh pada ajaran Islam Ahlussunnah wal Jama'ah An-Nahdliyah.</span>
                        </div>
                        <div class="nawa-item">
                            <span class="nawa-digit">2.</span>
                            <span class="nawa-text">Setia dan taat kepada Negara Kesatuan Republik Indonesia yang berdasarkan Pancasila dan UUD 1945.</span>
                        </div>
                        <div class="nawa-item">
                            <span class="nawa-digit">3.</span>
                            <span class="nawa-text">Menjunjung tinggi marwah para Ulama Nahdlatul Ulama dan siap membela kehormatan agama dan bangsa.</span>
                        </div>
                        <div class="nawa-item">
                            <span class="nawa-digit">4.</span>
                            <span class="nawa-text">Taat dan patuh kepada pimpinan serta memegang teguh disiplin satu komando.</span>
                        </div>
                        <div class="nawa-item">
                            <span class="nawa-digit">5.</span>
                            <span class="nawa-text">Rela berkorban untuk kepentingan umat, bangsa, dan agama demi kejayaan Nahdlatul Ulama.</span>
                        </div>
                        <div class="nawa-item">
                            <span class="nawa-digit">6.</span>
                            <span class="nawa-text">Menjaga persatuan, kesatuan, serta persaudaraan sesama anak bangsa.</span>
                        </div>
                        <div class="nawa-item">
                            <span class="nawa-digit">7.</span>
                            <span class="nawa-text">Menunjukkan keteladanan budi pekerti yang luhur dan senantiasa berakhlakul karimah.</span>
                        </div>
                        <div class="nawa-item">
                            <span class="nawa-digit">8.</span>
                            <span class="nawa-text">Pantang menyerah dan berputus asa dalam menghadapi setiap rintangan perjuangan.</span>
                        </div>
                        <div class="nawa-item">
                            <span class="nawa-digit">9.</span>
                            <span class="nawa-text">Senantiasa siap sedia mendarmabaktikan jiwa raga untuk kemaslahatan rakyat semesta.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════
             PROFIL SATKORCAB & AMANAT KASATKORCAB
        ═══════════════════════════════════════════════ -->
        <section class="org-section" id="profil">
            <div class="container">
                <div class="org-card">
                    
                    <!-- Left: Detail Organisasi -->
                    <div class="org-left">
                        <div class="section-tag" style="margin-bottom: 12px;">
                            <span>IDENTITAS SATKORCAB</span>
                        </div>
                        <h3 style="font-family: var(--font-display); font-size: 1.8rem; font-weight: 900; margin-bottom: 22px;">
                            {{ $profil->satkorcab ?? 'SATKORCAB BANSER KABUPATEN BLITAR' }}
                        </h3>

                        <div class="org-detail-item">
                            <span class="org-detail-label">Ketua PC GP Ansor Kab. Blitar</span>
                            <span class="org-detail-val">{{ $profil->nama_ketua_ansor ?? 'IMAM MALIKI' }}</span>
                        </div>

                        <div class="org-detail-item">
                            <span class="org-detail-label">Kepala Satuan Koordinasi Cabang (Kasatkorcab)</span>
                            <span class="org-detail-val" style="color: var(--neon-gold);">{{ $profil->nama_kasatkorcab ?? 'M. KHOTIB ASHARI' }}</span>
                        </div>

                        <div class="org-detail-item">
                            <span class="org-detail-label">Kepala Sekretariat Markas (Kasetma)</span>
                            <span class="org-detail-val">{{ $profil->nama_kasetma ?? 'MOH. BADRUZ ZAMAN' }}</span>
                        </div>

                        <div class="org-detail-item">
                            <span class="org-detail-label">Alamat Markas Komando</span>
                            <span class="org-detail-val" style="font-size: 0.95rem; font-weight: 500; color: #DDD4D6;">
                                {{ $profil->alamat_sekretariat ?? 'Graha NU Kabupaten Blitar, Jatinom, Kanigoro, Kab. Blitar' }}
                            </span>
                        </div>
                    </div>

                    <!-- Right: Kutipan Kasatkorcab -->
                    <div class="org-right" id="kontak">
                        <div class="quote-box">
                            <p class="quote-text">
                                "Tertib administrasi adalah cermin kedisiplinan prajurit. Dari pena dan lembar surat yang rapi, ketegasan komando dan marwah Banser terpancar kuat menjaga para Kiai dan keutuhan bumi pertiwi."
                            </p>
                            <div class="quote-author">{{ $profil->nama_kasatkorcab ?? 'M. KHOTIB ASHARI' }}</div>
                            <div class="quote-role">Kasatkorcab Banser Kabupaten Blitar</div>
                        </div>

                        <div style="margin-top: 24px; padding: 18px; background: rgba(10, 4, 7, 0.7); border-radius: 12px; border: 1px solid rgba(201, 162, 74, 0.15);">
                            <div style="font-family: var(--font-mono); font-size: 0.74rem; color: var(--neon-gold); margin-bottom: 6px;">
                                EMAIL RESMI PENGENDALIAN
                            </div>
                            <div style="font-size: 1rem; font-weight: 600; color: #FFFFFF;">
                                {{ $profil->email ?? 'adminpers.satkorcab@banser-blitar.id' }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════
             BOTTOM CALL TO ACTION
        ═══════════════════════════════════════════════ -->
        <section class="bottom-cta">
            <div class="container">
                <div class="bottom-cta-box">
                    <h2>Siap Mengelola Persuratan Satuan?</h2>
                    <p>
                        Akses sistem komando terpadu untuk pengarsipan surat masuk, pengajuan surat keluar, penerbitan Surat Keputusan, dan disposisi Kasatkorcab.
                    </p>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-hero-primary" style="display: inline-flex;">
                            <span>Buka Dashboard Komando</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-hero-primary" style="display: inline-flex;">
                            <span>Masuk ke Portal SIGAP BANSER</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════
             FOOTER
        ═══════════════════════════════════════════════ -->
        <footer class="site-footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <img src="{{ asset('images/ansor-logo.png') }}" alt="Logo GP Ansor">
                        <img src="{{ asset('images/banser-logo.png') }}" alt="Logo Banser">
                        <div>
                            <strong style="color: #FFFFFF; font-size: 1rem; display: block;">SIGAP BANSER</strong>
                            <span style="font-family: var(--font-mono); font-size: 0.72rem; color: var(--neon-gold);">SATKORCAB KABUPATEN BLITAR</span>
                        </div>
                    </div>

                    <ul class="footer-links">
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#fitur">Fitur</a></li>
                        <li><a href="#satuan-khusus">Satuan Khusus</a></li>
                        <li><a href="#alur">Alur</a></li>
                        <li><a href="#profil">Profil</a></li>
                        <li><a href="{{ route('login') }}">Login Portal</a></li>
                    </ul>
                </div>

                <div class="footer-bottom">
                    <div>
                        © {{ date('Y') }} <strong>SATKORCAB BANSER KABUPATEN BLITAR</strong>. Hak Cipta Dilindungi Undang-Undang.
                    </div>
                    <div>
                        Sistem Komando Administrasi & Persuratan Terpadu • Kode Wilayah: XII-11
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- ═══════════════════════════════════════════════
         INTERACTIVE PARTICLES CANVAS & SCRIPTS
    ═══════════════════════════════════════════════ -->
    <script>
        // Mobile Navigation Toggle
        const btnToggleNav = document.getElementById('btnToggleNav');
        const navMenu = document.getElementById('navMenu');
        if (btnToggleNav && navMenu) {
            btnToggleNav.addEventListener('click', () => {
                navMenu.classList.toggle('show');
            });
            // Close nav when clicking a link
            navMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    navMenu.classList.remove('show');
                });
            });
        }

        // Particle Mesh Background Canvas
        const canvas = document.getElementById('matrixCanvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            let width, height;
            let particles = [];

            function resize() {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resize);
            resize();

            const particleCount = Math.min(Math.floor(window.innerWidth / 20), 65);
            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - 0.5) * 0.45,
                    vy: (Math.random() - 0.5) * 0.45,
                    size: Math.random() * 2 + 1,
                    alpha: Math.random() * 0.5 + 0.2
                });
            }

            function animate() {
                ctx.clearRect(0, 0, width, height);

                // Update & draw particles
                for (let i = 0; i < particles.length; i++) {
                    let p = particles[i];
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0) p.x = width;
                    if (p.x > width) p.x = 0;
                    if (p.y < 0) p.y = height;
                    if (p.y > height) p.y = 0;

                    ctx.fillStyle = `rgba(245, 192, 66, ${p.alpha * 0.6})`;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fill();

                    // Connect close particles with subtle tactical lines
                    for (let j = i + 1; j < particles.length; j++) {
                        let p2 = particles[j];
                        let dx = p.x - p2.x;
                        let dy = p.y - p2.y;
                        let dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < 130) {
                            ctx.strokeStyle = `rgba(201, 162, 74, ${0.15 * (1 - dist / 130)})`;
                            ctx.lineWidth = 0.75;
                            ctx.beginPath();
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(p2.x, p2.y);
                            ctx.stroke();
                        }
                    }
                }

                requestAnimationFrame(animate);
            }
            animate();
        }
    </script>
</body>
</html>
