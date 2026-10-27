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
        /* ═══════════════════════════════════════════════════════════
           DESIGN TOKENS
        ═══════════════════════════════════════════════════════════ */
        :root {
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

            --bg: #F0EDE9;
            --surface: #FFFFFF;
            --surface-alt: #F9F7F5;
            --ink: #1F1A1C;
            --ink-muted: #696263;
            --border: #E0D9D4;
            --border-light: #EFEBE8;

            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 10px rgba(0,0,0,0.07), 0 2px 4px rgba(0,0,0,0.04);
            --shadow-lg: 0 12px 24px -4px rgba(0,0,0,0.12), 0 4px 8px -2px rgba(0,0,0,0.05);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;

            /* Sidebar */
            --sidebar-w: 260px;
            --sidebar-collapsed-w: 68px;
            --sidebar-bg: #1A0608;
            --sidebar-border: rgba(255,255,255,0.06);
            --sidebar-item-hover: rgba(255,255,255,0.06);
            --sidebar-item-active-bg: rgba(229,35,43,0.18);
            --sidebar-item-active-border: var(--red);
            --sidebar-text: rgba(255,255,255,0.72);
            --sidebar-text-active: #FFFFFF;
            --topbar-h: 60px;
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

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Public Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            color: var(--ink);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Archivo', system-ui, sans-serif;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.25;
            color: var(--ink);
        }

        a { color: var(--accent); text-decoration: none; transition: color 0.2s ease; }
        a:hover { color: var(--accent-hover); }

        /* ═══════════════════════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 200;
            transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            box-shadow: 6px 0 32px rgba(0,0,0,0.45), 2px 0 0 rgba(229,35,43,0.12);
        }

        /* Gradient top stripe — animated shimmer */
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg,
                var(--red) 0%, var(--yellow) 40%, var(--green) 70%, var(--blue) 100%);
            background-size: 200% 100%;
            z-index: 10;
            animation: shimmerStripe 6s ease infinite;
        }
        @keyframes shimmerStripe {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Radial glow texture */
        .sidebar::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 5%, rgba(229,35,43,0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 85% 95%, rgba(90,14,20,0.4) 0%, transparent 55%),
                radial-gradient(ellipse at 60% 50%, rgba(201,162,74,0.02) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Sidebar Brand Area ── */
        .sidebar-brand {
            padding: 18px 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
            position: relative;
            z-index: 2;
            min-height: 80px;
            background: linear-gradient(180deg, rgba(229,35,43,0.07) 0%, transparent 100%);
        }

        .sidebar-logo-wrap {
            position: relative;
            flex-shrink: 0;
        }
        .sidebar-logo {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: 50%;
            display: block;
            filter: drop-shadow(0 2px 10px rgba(229,35,43,0.5));
            transition: transform 0.3s ease, filter 0.3s ease;
        }
        .sidebar-logo-wrap::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            background: conic-gradient(var(--red) 0deg, var(--yellow) 120deg, var(--green) 240deg, var(--red) 360deg);
            z-index: -1;
            opacity: 0.55;
            animation: rotateConic 8s linear infinite;
        }
        @keyframes rotateConic {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        .sidebar-logo:hover {
            transform: scale(1.1) rotate(-5deg);
            filter: drop-shadow(0 4px 14px rgba(229,35,43,0.7));
        }

        .sidebar-brand-text {
            overflow: hidden;
            transition: opacity 0.2s ease, width 0.28s ease;
        }
        .sidebar-brand-title {
            font-family: 'Archivo', sans-serif;
            font-weight: 900;
            font-size: 1.1rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            line-height: 1;
            white-space: nowrap;
            background: linear-gradient(135deg, #FFFFFF 0%, rgba(255,220,200,0.85) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .sidebar-brand-sub {
            font-size: 0.66rem;
            color: rgba(255,255,255,0.45);
            margin-top: 4px;
            white-space: nowrap;
            letter-spacing: 0.04em;
        }

        /* ── Sidebar Nav ── */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px 8px;
            position: relative;
            z-index: 2;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.08) transparent;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
        }

        .sidebar-section-label {
            font-size: 0.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.22);
            padding: 14px 10px 5px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.08);
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 11px;
            border-radius: var(--radius-md);
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
            position: relative;
            white-space: nowrap;
            margin-bottom: 1px;
            border: 1px solid transparent;
            overflow: hidden;
        }

        /* Hover shimmer effect */
        .sidebar-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.04) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform 0.4s ease;
            pointer-events: none;
        }
        .sidebar-item:hover::before {
            transform: translateX(100%);
        }

        .sidebar-item:hover {
            background: rgba(255,255,255,0.07);
            color: #FFFFFF;
            border-color: rgba(255,255,255,0.08);
            transform: translateX(2px);
        }

        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(229,35,43,0.22) 0%, rgba(229,35,43,0.08) 100%);
            color: #FFFFFF;
            border-color: rgba(229,35,43,0.35);
            box-shadow: inset 3px 0 0 var(--red),
                        0 4px 16px rgba(229,35,43,0.12),
                        0 0 0 1px rgba(229,35,43,0.08) inset;
        }
        .sidebar-item.active:hover { transform: none; }

        .sidebar-item.active .sidebar-item-icon {
            color: #FF6B70;
            filter: drop-shadow(0 0 8px rgba(229,35,43,0.6));
        }

        .sidebar-item-icon {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
            color: rgba(255,255,255,0.42);
            transition: color 0.2s ease, filter 0.2s ease, transform 0.2s ease;
        }
        .sidebar-item:hover .sidebar-item-icon {
            color: rgba(255,255,255,0.88);
            transform: scale(1.1);
        }

        .sidebar-item-text {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: opacity 0.15s ease;
        }

        .sidebar-item-badge {
            background: linear-gradient(135deg, #E5232B, #C01920);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 900;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(229,35,43,0.5);
            animation: badgePop 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }

        /* ── SIPENA Special Item ── */
        .sidebar-item.sipena-item {
            background: linear-gradient(90deg, rgba(201,162,74,0.08) 0%, transparent 100%);
            border-color: rgba(201,162,74,0.12);
        }
        .sidebar-item.sipena-item:hover {
            background: linear-gradient(90deg, rgba(201,162,74,0.15) 0%, rgba(201,162,74,0.05) 100%);
            border-color: rgba(201,162,74,0.3);
            transform: translateX(2px);
        }
        .sipena-icon-wrap {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .sidebar-item:hover .sipena-icon-wrap {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(201,162,74,0.4);
        }
        .sipena-icon-img {
            width: 24px;
            height: 24px;
            object-fit: cover;
            display: block;
        }
        .sipena-ext-badge {
            font-size: 0.58rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            color: var(--gold);
            opacity: 0.7;
            text-transform: uppercase;
            flex-shrink: 0;
            border: 1px solid rgba(201,162,74,0.35);
            padding: 1px 5px;
            border-radius: 4px;
            background: rgba(201,162,74,0.1);
            transition: opacity 0.2s ease;
        }
        .sidebar-item:hover .sipena-ext-badge { opacity: 1; }

        .sidebar-item-ext {
            width: 12px;
            height: 12px;
            opacity: 0.35;
            flex-shrink: 0;
        }

        .sidebar-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, var(--sidebar-border) 20%, var(--sidebar-border) 80%, transparent 100%);
            margin: 6px 8px;
        }

        /* ── Sidebar Footer (User) ── */
        .sidebar-footer {
            border-top: 1px solid var(--sidebar-border);
            padding: 10px 8px;
            flex-shrink: 0;
            position: relative;
            z-index: 2;
            background: linear-gradient(0deg, rgba(0,0,0,0.2) 0%, transparent 100%);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: var(--radius-md);
            border: 1px solid rgba(201,162,74,0.15);
            background: rgba(201,162,74,0.05);
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 8px;
            text-decoration: none;
        }
        .sidebar-user:hover {
            background: rgba(201,162,74,0.1);
            border-color: rgba(201,162,74,0.3);
        }

        .sidebar-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #D4A843 0%, #8C6B28 100%);
            color: #1A0608;
            font-weight: 900;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 0 0 2px rgba(201,162,74,0.4), 0 2px 8px rgba(0,0,0,0.4);
        }

        .sidebar-user-info {
            overflow: hidden;
            flex: 1;
            transition: opacity 0.2s ease;
        }

        .sidebar-user-name {
            font-size: 0.81rem;
            font-weight: 700;
            color: rgba(255,255,255,0.9);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 0.66rem;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            white-space: nowrap;
            margin-top: 1px;
        }

        .sidebar-logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: 100%;
            padding: 7px 12px;
            border-radius: var(--radius-sm);
            background: transparent;
            border: 1px solid rgba(229,35,43,0.2);
            color: rgba(255,120,120,0.75);
            font-size: 0.79rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            font-family: inherit;
        }
        .sidebar-logout-btn:hover {
            background: rgba(229,35,43,0.15);
            border-color: rgba(229,35,43,0.5);
            color: #FF8888;
            box-shadow: 0 2px 12px rgba(229,35,43,0.15);
        }

        /* ── Sidebar Collapsed State ── */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-w);
        }
        .sidebar.collapsed .sidebar-brand-text,
        .sidebar.collapsed .sidebar-item-text,
        .sidebar.collapsed .sidebar-item-badge,
        .sidebar.collapsed .sidebar-item-ext,
        .sidebar.collapsed .sidebar-section-label,
        .sidebar.collapsed .sidebar-user-info,
        .sidebar.collapsed .sidebar-logout-text {
            opacity: 0;
            pointer-events: none;
            width: 0;
            overflow: hidden;
        }
        .sidebar.collapsed .sidebar-item {
            justify-content: center;
            padding: 10px;
        }
        .sidebar.collapsed .sidebar-user {
            justify-content: center;
            padding: 10px;
        }
        .sidebar.collapsed .sidebar-logout-btn {
            justify-content: center;
            padding: 8px;
        }

        /* Tooltip on collapsed */
        .sidebar.collapsed .sidebar-item {
            position: relative;
        }
        .sidebar.collapsed .sidebar-item::after {
            content: attr(data-label);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.85);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: var(--radius-sm);
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s ease;
            z-index: 300;
        }
        .sidebar.collapsed .sidebar-item:hover::after {
            opacity: 1;
        }

        /* ── Toggle Button ── */
        .sidebar-toggle {
            position: fixed;
            top: 14px;
            left: calc(var(--sidebar-w) - 14px);
            width: 28px;
            height: 28px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 300;
            box-shadow: var(--shadow-md);
            transition: left 0.28s cubic-bezier(0.4, 0, 0.2, 1), transform 0.28s ease;
            color: var(--ink-muted);
        }
        .sidebar-toggle:hover {
            background: var(--surface-alt);
            color: var(--ink);
        }
        .sidebar.collapsed ~ .sidebar-toggle,
        .layout-wrapper.sidebar-collapsed .sidebar-toggle {
            left: calc(var(--sidebar-collapsed-w) - 14px);
        }
        .sidebar-toggle svg {
            transition: transform 0.28s ease;
        }
        .sidebar.collapsed .sidebar-toggle-icon {
            transform: rotate(180deg);
        }

        /* ═══════════════════════════════════════════════════════════
           MAIN LAYOUT
        ═══════════════════════════════════════════════════════════ */
        .layout-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .main-panel {
            flex: 1;
            margin-left: var(--sidebar-w);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .layout-wrapper.sidebar-collapsed .main-panel {
            margin-left: var(--sidebar-collapsed-w);
        }

        /* ── Top Bar ── */
        .topbar {
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-page-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--ink);
        }
        .topbar-page-title span {
            color: var(--ink-muted);
            font-weight: 400;
            font-size: 0.85rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Notif Bell in topbar */
        .topbar-notif-btn {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            background: var(--surface-alt);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-muted);
            text-decoration: none;
            transition: all 0.18s ease;
        }
        .topbar-notif-btn:hover {
            background: var(--border-light);
            color: var(--ink);
        }
        .topbar-notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--red);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 800;
            min-width: 17px;
            height: 17px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            border: 2px solid var(--surface);
            animation: badgePop 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }

        /* Adminpers Logo in topbar */
        .topbar-logo {
            height: 38px;
            width: auto;
            max-width: 100px;
            object-fit: contain;
            opacity: 0.9;
            filter: drop-shadow(0 1px 4px rgba(201,162,74,0.3));
            transition: opacity 0.2s ease;
        }
        .topbar-logo:hover { opacity: 1; }

        /* ── Main Content ── */
        .main-content {
            flex: 1;
            padding: 28px 28px 56px;
        }

        /* ── Footer ── */
        .site-footer {
            background: var(--sidebar-bg);
            padding: 14px 24px;
            margin-top: auto;
        }
        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .footer-motto {
            font-size: 0.78rem;
            font-style: italic;
            color: var(--gold);
            font-weight: 600;
        }
        .footer-sub {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.35);
        }

        /* ═══════════════════════════════════════════════════════════
           MOBILE OVERLAY
        ═══════════════════════════════════════════════════════════ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 190;
            backdrop-filter: blur(2px);
        }

        /* ═══════════════════════════════════════════════════════════
           COMPONENTS (BUTTONS, BADGES, CARDS, TABLES, FORMS)
        ═══════════════════════════════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.18s ease;
            text-decoration: none;
            white-space: nowrap;
            font-family: inherit;
        }
        .btn-primary {
            background-color: var(--accent);
            color: #FFFFFF;
            border-color: var(--accent);
        }
        .btn-primary:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(142,27,34,0.3);
        }
        .btn-secondary {
            background-color: var(--surface);
            color: var(--ink);
            border-color: var(--border);
        }
        .btn-secondary:hover {
            background-color: var(--surface-alt);
            color: var(--ink);
            border-color: var(--ink-muted);
        }
        .btn-gold {
            background-color: var(--gold);
            color: #240608;
            border-color: var(--gold);
            font-weight: 800;
        }
        .btn-gold:hover {
            background-color: #B08838;
            border-color: #B08838;
            color: #240608;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(201,162,74,0.35);
        }
        .btn-danger {
            background-color: #DC2626;
            color: #fff;
            border-color: #DC2626;
        }
        .btn-danger:hover {
            background-color: #B91C1C;
            border-color: #B91C1C;
            color: #fff;
        }
        .btn:disabled, .btn[disabled] {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
        }
        .btn svg { flex-shrink: 0; }
        .btn-lg {
            padding: 11px 22px;
            font-size: 0.95rem;
            border-radius: var(--radius-md);
            box-shadow: 0 4px 14px rgba(142,27,34,0.25);
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: var(--radius-sm);
        }

        /* ── Action Icon Buttons (Detail, Edit, Hapus) ── */
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            line-height: 1.2;
            font-family: inherit;
            background: transparent;
        }
        .btn-icon svg {
            flex-shrink: 0;
            transition: transform 0.15s ease;
        }
        .btn-icon:hover svg {
            transform: scale(1.12);
        }

        /* Detail Button: Subtle Indigo / Cyan tint */
        .btn-icon-detail {
            color: #1D68BD;
            background: rgba(29, 104, 189, 0.08);
            border-color: rgba(29, 104, 189, 0.22);
        }
        .btn-icon-detail:hover {
            color: #FFFFFF;
            background: #1D68BD;
            border-color: #1D68BD;
            box-shadow: 0 3px 10px rgba(29, 104, 189, 0.35);
            transform: translateY(-1px);
        }

        /* Edit Button: Amber / Gold tint */
        .btn-icon-edit {
            color: #B45309;
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.28);
        }
        .btn-icon-edit:hover {
            color: #FFFFFF;
            background: #D97706;
            border-color: #D97706;
            box-shadow: 0 3px 10px rgba(217, 119, 6, 0.35);
            transform: translateY(-1px);
        }

        /* Delete Button: Rose / Red tint */
        .btn-icon-delete {
            color: #DC2626;
            background: rgba(220, 38, 38, 0.08);
            border-color: rgba(220, 38, 38, 0.24);
        }
        .btn-icon-delete:hover {
            color: #FFFFFF;
            background: #DC2626;
            border-color: #DC2626;
            box-shadow: 0 3px 10px rgba(220, 38, 38, 0.35);
            transform: translateY(-1px);
        }

        /* Print / Download Button */
        .btn-icon-print, .btn-icon-download {
            color: #059669;
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.26);
        }
        .btn-icon-print:hover, .btn-icon-download:hover {
            color: #FFFFFF;
            background: #059669;
            border-color: #059669;
            box-shadow: 0 3px 10px rgba(5, 150, 105, 0.35);
            transform: translateY(-1px);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
            line-height: 1.2;
            border: 1px solid transparent;
        }
        .badge-red    { background: rgba(229,35,43,0.12); color: var(--red); border-color: rgba(229,35,43,0.25); }
        .badge-green  { background: rgba(14,155,74,0.12); color: var(--green); border-color: rgba(14,155,74,0.25); }
        .badge-yellow { background: rgba(245,235,15,0.15); color: #8C6F12; border-color: rgba(201,162,74,0.3); }
        .badge-blue   { background: rgba(29,104,189,0.12); color: var(--blue); border-color: rgba(29,104,189,0.25); }
        .badge-purple { background: rgba(139,92,246,0.12); color: #7C3AED; border-color: rgba(139,92,246,0.25); }
        .badge-gold   { background: rgba(201,162,74,0.15); color: #8C6F12; border-color: rgba(201,162,74,0.35); }
        .badge-gray   { background: rgba(107,114,128,0.1); color: var(--gray); border-color: rgba(107,114,128,0.2); }

        @media (prefers-color-scheme: dark) {
            .badge-yellow { color: #FFE66D; }
            .badge-gold   { color: #F5D77F; }
            .badge-purple { color: #C4B5FD; }
        }

        /* Cards */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: var(--shadow-md);
        }
        .card-header {
            padding: 16px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background: var(--surface-alt);
        }
        .card-title {
            font-size: 0.94rem;
            font-weight: 800;
            color: var(--ink);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-body { padding: 22px; }

        /* Tables */
        .table-wrapper {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table.table, table.data-table, .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }
        table.table thead th, table.data-table thead th, .table thead th {
            background: var(--surface-alt);
            padding: 13px 18px;
            text-align: left;
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--ink-muted);
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }
        table.table tbody tr, table.data-table tbody tr, .table tbody tr {
            border-bottom: 1px solid var(--border-light);
            transition: background-color 0.15s ease;
        }
        table.table tbody tr:hover, table.data-table tbody tr:hover, .table tbody tr:hover {
            background: rgba(90, 14, 20, 0.025);
        }
        table.table tbody tr:last-child, table.data-table tbody tr:last-child, .table tbody tr:last-child {
            border-bottom: none;
        }
        table.table td, table.data-table td, .table td {
            padding: 14px 18px;
            color: var(--ink);
            vertical-align: middle;
            line-height: 1.45;
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .page-item .page-link, .pagination a, .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--ink);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .page-item:hover .page-link, .pagination a:hover {
            background: var(--surface-alt);
            border-color: var(--accent);
            color: var(--accent);
        }
        .page-item.active .page-link, .pagination .active span {
            background: var(--accent);
            border-color: var(--accent);
            color: #FFFFFF;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(142, 27, 34, 0.35);
        }
        .page-item.disabled .page-link, .pagination .disabled span {
            opacity: 0.45;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Forms */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--ink-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 6px;
        }
        .required, .text-danger {
            color: var(--red);
            font-weight: 800;
            margin-left: 2px;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: var(--surface-alt);
            color: var(--ink);
            font-size: 0.92rem;
            font-family: inherit;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(142,27,34,0.12);
        }
        .form-control.is-invalid { border-color: var(--red); background: rgba(229,35,43,0.02); }
        .invalid-feedback, .form-error {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--red);
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 5px;
        }
        .invalid-feedback svg, .form-error svg {
            flex-shrink: 0;
        }
        .form-hint {
            font-size: 0.78rem;
            color: var(--ink-muted);
            margin-top: 5px;
        }
        textarea.form-control { resize: vertical; min-height: 80px; }
        select.form-control { cursor: pointer; }

        /* Alert / Flash */
        .flash-container { margin-bottom: 24px; }
        .alert {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 10px;
            font-size: 0.9rem;
            animation: slideDown 0.3s ease;
        }
        .alert-success { background: rgba(14,155,74,0.1); border: 1px solid var(--green); color: var(--green); }
        .alert-error, .alert-danger { background: rgba(229,35,43,0.1); border: 1px solid var(--red); color: var(--red); }
        .alert-warning { background: rgba(201,162,74,0.12); border: 1px solid var(--gold); color: #8C6F12; }
        .alert-info { background: rgba(29,104,189,0.1); border: 1px solid var(--blue); color: var(--blue); }
        @media (prefers-color-scheme: dark) {
            .alert-warning { color: #FFE66D; }
            .alert-info { color: #78B0F0; }
        }
        .alert-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.6;
            flex-shrink: 0;
            line-height: 1;
            padding: 0;
        }
        .alert-close:hover { opacity: 1; }

        /* Grid Utilities */
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        .grid-1-2 { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; }
        .grid-2-1 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }

        /* Page Container */
        .page-container { max-width: 1200px; }

        /* Delete Modal */
        .delete-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
            backdrop-filter: blur(4px);
            animation: fadeIn 0.2s ease;
        }
        .delete-modal-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            animation: slideUp 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .delete-modal-header {
            background: linear-gradient(135deg, #7F1D1D, #991B1B);
            padding: 20px 24px 16px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }
        .delete-icon-pulse {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            animation: pulse 2s ease infinite;
        }
        .delete-modal-close {
            background: rgba(255,255,255,0.15);
            border: none;
            color: #FFFFFF;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }
        .delete-modal-close:hover { background: rgba(255,255,255,0.25); }
        .delete-modal-body { padding: 22px 24px 18px; }
        .delete-modal-title { font-size: 1.1rem; font-weight: 800; margin-bottom: 8px; color: var(--ink); }
        .delete-modal-desc { font-size: 0.9rem; color: var(--ink-muted); margin-bottom: 14px; line-height: 1.5; }
        .delete-warning-box {
            display: flex;
            gap: 10px;
            padding: 12px 14px;
            background: rgba(220,38,38,0.06);
            border: 1px solid rgba(220,38,38,0.2);
            border-radius: var(--radius-sm);
        }
        .delete-modal-footer {
            padding: 14px 24px;
            background: var(--surface-alt);
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Notif Bell */
        .notif-bell-btn {
            position: relative;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            background: var(--surface-alt);
            color: var(--ink-muted);
            text-decoration: none;
            transition: all 0.18s ease;
        }
        .notif-bell-btn:hover { background: var(--border-light); color: var(--ink); }
        .notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--red);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 800;
            min-width: 17px;
            height: 17px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            border: 2px solid var(--surface);
            animation: badgePop 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }

        /* ═══════════════════════════════════════════════════════════
           ANIMATIONS
        ═══════════════════════════════════════════════════════════ */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes badgePop {
            from { transform: scale(0); }
            to   { transform: scale(1); }
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,0.2); }
            50% { box-shadow: 0 0 0 8px rgba(255,255,255,0); }
        }

        /* ═══════════════════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════════════════ */
        @media (max-width: 992px) {
            .grid-3 { grid-template-columns: 1fr 1fr; }
            .grid-4 { grid-template-columns: 1fr 1fr; }
            .grid-1-2, .grid-2-1, .grid-2 { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-w) !important;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .sidebar-overlay {
                display: block;
            }
            .sidebar-overlay.active {
                display: block;
                animation: fadeIn 0.2s ease;
            }
            .main-panel {
                margin-left: 0 !important;
            }
            .sidebar-toggle {
                display: none;
            }
            .topbar-hamburger {
                display: flex !important;
            }
            .main-content {
                padding: 18px 16px 40px;
            }
            .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }

        .topbar-hamburger {
            display: none;
            width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: var(--surface-alt);
            color: var(--ink-muted);
            cursor: pointer;
            transition: all 0.18s ease;
            flex-shrink: 0;
        }
        .topbar-hamburger:hover { background: var(--border-light); color: var(--ink); }
    </style>
    @stack('styles')
</head>
<body>
<div class="layout-wrapper" id="layoutWrapper">

    {{-- ═══════════════════ SIDEBAR ═══════════════════ --}}
    <aside class="sidebar" id="sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="sidebar-logo-wrap">
                <img src="{{ asset('images/banser-logo.png') }}"
                     onerror="this.src='{{ asset('images/banser.png') }}'"
                     alt="Logo BANSER"
                     class="sidebar-logo">
            </div>
            <div class="sidebar-brand-text">
                <div class="sidebar-brand-title">SIGAP BANSER</div>
                <div class="sidebar-brand-sub">Satkorcab Kab. Blitar</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav" aria-label="Menu Utama">

            {{-- UTAMA --}}
            <div class="sidebar-section-label">Utama</div>

            <a href="{{ route('dashboard') }}"
               class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               data-label="Beranda"
               title="Beranda / Dashboard">
                <svg class="sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="sidebar-item-text">Beranda</span>
            </a>

            <a href="{{ route('laporan.index') }}"
               class="sidebar-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}"
               data-label="Laporan"
               title="Laporan Rekap Persuratan">
                <svg class="sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="sidebar-item-text">Laporan</span>
            </a>

            <div class="sidebar-divider"></div>

            {{-- PERSURATAN --}}
            <div class="sidebar-section-label">Persuratan</div>

            <a href="{{ route('surat-masuk.index') }}"
               class="sidebar-item {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}"
               data-label="Surat Masuk"
               title="Surat Masuk">
                <svg class="sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <span class="sidebar-item-text">Surat Masuk</span>
            </a>

            <a href="{{ route('surat-keluar.index') }}"
               class="sidebar-item {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}"
               data-label="Surat Keluar"
               title="Surat Keluar">
                <svg class="sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                <span class="sidebar-item-text">Surat Keluar</span>
            </a>

            <a href="{{ route('surat-keputusan.index') }}"
               class="sidebar-item {{ request()->routeIs('surat-keputusan.*') ? 'active' : '' }}"
               data-label="Surat Keputusan"
               title="Surat Keputusan (SK)">
                <svg class="sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="sidebar-item-text">Surat Keputusan (SK)</span>
            </a>

            <div class="sidebar-divider"></div>

            {{-- ORGANISASI --}}
            <div class="sidebar-section-label">Organisasi</div>

            <a href="{{ route('profil.index') }}"
               class="sidebar-item {{ request()->routeIs('profil.*') ? 'active' : '' }}"
               data-label="Profil Organisasi"
               title="Profil Organisasi Satkorcab">
                <svg class="sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="sidebar-item-text">Profil Organisasi</span>
            </a>

            <a href="{{ route('struktur-organisasi.index') }}"
               class="sidebar-item {{ request()->routeIs('struktur-organisasi.*') ? 'active' : '' }}"
               data-label="Struktur Organisasi"
               title="Struktur Komando, Biro, dan Satsus Satkorcab">
                <svg class="sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="sidebar-item-text">Struktur Organisasi</span>
            </a>

            <a href="https://sipena-banser-blitar.netlify.app"
               target="_blank"
               rel="noopener noreferrer"
               class="sidebar-item sipena-item"
               data-label="SIPENA"
               title="Buka Aplikasi SIPENA BANSER Blitar">
                <div class="sipena-icon-wrap">
                    <img src="{{ asset('images/sipena-icon.jpg') }}"
                         alt="SIPENA"
                         class="sipena-icon-img">
                </div>
                <span class="sidebar-item-text" style="color: rgba(255,220,160,0.9); font-weight: 700;">SIPENA</span>
            </a>

            <div class="sidebar-divider"></div>

            {{-- AKUN --}}
            <div class="sidebar-section-label">Akun</div>

            @php $sidebarNotifCount = \App\Models\Notifikasi::untukUser(Auth::id())->belumDibaca()->count(); @endphp

            <a href="{{ route('notifikasi.index') }}"
               class="sidebar-item {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}"
               data-label="Notifikasi"
               title="Notifikasi"
               id="sidebarNotifItem">
                <svg class="sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="sidebar-item-text">Notifikasi</span>
                @if($sidebarNotifCount > 0)
                    <span class="sidebar-item-badge" id="sidebarNotifBadge">{{ $sidebarNotifCount > 99 ? '99+' : $sidebarNotifCount }}</span>
                @endif
            </a>

            <a href="{{ route('pengaturan.index') }}"
               class="sidebar-item {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}"
               data-label="Pengaturan Akun"
               title="Pengaturan Akun & Ganti Kata Sandi">
                <svg class="sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="sidebar-item-text">Pengaturan Akun</span>
            </a>

        </nav>

        {{-- Footer / User Info --}}
        @auth
        <div class="sidebar-footer">
            <a href="{{ route('pengaturan.index') }}" class="sidebar-user" style="text-decoration:none;">
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'B', 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name ?? 'Petugas Banser' }}</div>
                    <div class="sidebar-user-role">
                        @php
                            $r = strtolower(Auth::user()->role ?? '');
                            echo match($r) {
                                'kasatkorcab'  => 'Kasatkorcab',
                                'kasetma'      => 'Kasetma',
                                'adminpers', 'admin_adminpers' => 'Ka. Biro Adminpers',
                                'super_admin'  => 'Superadmin',
                                'pimpinan'     => 'Pimpinan',
                                default        => ucfirst(Auth::user()->role ?? 'Anggota'),
                            };
                        @endphp
                    </div>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="sidebar-logout-text">Keluar</span>
                </button>
            </form>
        </div>
        @endauth
    </aside>

    {{-- Sidebar Overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    {{-- Sidebar Toggle Button (desktop) --}}
    <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()" aria-label="Toggle sidebar" title="Perluas/Ciutkan menu">
        <svg class="sidebar-toggle-icon" id="toggleIcon" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    {{-- ═══════════════════ MAIN PANEL ═══════════════════ --}}
    <div class="main-panel" id="mainPanel">

        {{-- Top Bar --}}
        <header class="topbar">
            <div class="topbar-left">
                {{-- Mobile hamburger --}}
                <button class="topbar-hamburger" id="mobileMenuBtn" onclick="openMobileSidebar()" aria-label="Buka menu">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="topbar-page-title">
                    @yield('title', 'SIGAP BANSER')
                    <span>— Satkorcab Kab. Blitar</span>
                </div>
            </div>

            <div class="topbar-right">
                {{-- Adminpers Logo --}}
                <img src="{{ asset('images/LOGO_ADMIN_rev_2.png') }}"
                     onerror="this.style.display='none'"
                     alt="Logo Adminpers"
                     class="topbar-logo">

                {{-- Notifikasi Bell --}}
                @auth
                @php $topbarNotifCount = \App\Models\Notifikasi::untukUser(Auth::id())->belumDibaca()->count(); @endphp
                <a href="{{ route('notifikasi.index') }}"
                   class="notif-bell-btn"
                   id="notifBellBtn"
                   title="Notifikasi ({{ $topbarNotifCount }} belum dibaca)">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($topbarNotifCount > 0)
                        <span class="notif-badge" id="notifBadge">{{ $topbarNotifCount > 99 ? '99+' : $topbarNotifCount }}</span>
                    @endif
                </a>
                @endauth
            </div>
        </header>

        {{-- Main Content --}}
        <main class="main-content">
            {{-- Flash Messages --}}
            <div class="flash-container" id="flashMessages">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error" role="alert">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning" role="alert">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>{{ session('warning') }}</span>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info" role="alert">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('info') }}</span>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error" role="alert">
                        <div>
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <strong>Terdapat {{ $errors->count() }} kesalahan pada formulir:</strong>
                            </div>
                            <ul style="margin: 0; padding-left: 20px; font-size: 0.85rem;">
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

        {{-- Footer --}}
        <footer class="site-footer">
            <div class="footer-inner">
                <div class="footer-motto">"Rapi di meja, solid di laga: satu data, satu komando!"</div>
                <div class="footer-sub">&copy; {{ date('Y') }} SATKORCAB BANSER Kabupaten Blitar</div>
            </div>
        </footer>
    </div>
</div>

{{-- ═══════════════════ MODAL HAPUS ═══════════════════ --}}
<div id="modalConfirmDelete" class="delete-modal-overlay" style="display: none;" onclick="if(event.target === this) closeDeleteModal()">
    <div class="delete-modal-card" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="delete-modal-header">
            <div class="delete-icon-pulse">
                <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <button type="button" class="delete-modal-close" onclick="closeDeleteModal()" aria-label="Tutup">&times;</button>
        </div>
        <div class="delete-modal-body">
            <h3 id="deleteModalTitle" class="delete-modal-title">Persetujuan Hapus Dokumen</h3>
            <p class="delete-modal-desc">
                Apakah Anda yakin ingin menghapus <strong id="deleteTargetItem" style="color: var(--ink);"></strong>?
            </p>
            <div class="delete-warning-box">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#DC2626; flex-shrink:0; margin-top:2px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div style="font-size:0.82rem; color:#991B1B; line-height:1.45;">
                    <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Seluruh data terkait akan dihapus permanen.
                </div>
            </div>
        </div>
        <div class="delete-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
            <form id="deleteConfirmForm" method="POST" style="margin:0; display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="display:inline-flex; align-items:center; gap:7px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════ SCRIPTS ═══════════════════ --}}
<script>
(function() {
    // ── Sidebar State Management ──
    var STORAGE_KEY = 'sigap_sidebar_collapsed';
    var wrapper = document.getElementById('layoutWrapper');
    var sidebar = document.getElementById('sidebar');
    var toggleIcon = document.getElementById('toggleIcon');
    var toggleBtn = document.getElementById('sidebarToggle');

    function isCollapsed() {
        return localStorage.getItem(STORAGE_KEY) === '1';
    }

    function applyState(collapsed) {
        if (collapsed) {
            sidebar.classList.add('collapsed');
            wrapper.classList.add('sidebar-collapsed');
        } else {
            sidebar.classList.remove('collapsed');
            wrapper.classList.remove('sidebar-collapsed');
        }
        // Move toggle button
        if (toggleBtn) {
            toggleBtn.style.left = collapsed
                ? 'calc(var(--sidebar-collapsed-w) - 14px)'
                : 'calc(var(--sidebar-w) - 14px)';
        }
        // Rotate icon
        if (toggleIcon) {
            toggleIcon.style.transform = collapsed ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }

    // Apply saved state on load
    applyState(isCollapsed());

    window.toggleSidebar = function() {
        var nowCollapsed = !isCollapsed();
        localStorage.setItem(STORAGE_KEY, nowCollapsed ? '1' : '0');
        applyState(nowCollapsed);
    };

    // ── Mobile Sidebar ──
    window.openMobileSidebar = function() {
        sidebar.classList.add('mobile-open');
        document.getElementById('sidebarOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.closeMobileSidebar = function() {
        sidebar.classList.remove('mobile-open');
        document.getElementById('sidebarOverlay').classList.remove('active');
        document.body.style.overflow = '';
    };

    // ── Flash Message Auto-dismiss ──
    document.addEventListener('DOMContentLoaded', function() {
        var flashContainer = document.getElementById('flashMessages');
        if (flashContainer) {
            flashContainer.querySelectorAll('.alert').forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-6px)';
                    setTimeout(function() { alert.remove(); }, 500);
                }, 5000);
            });
        }
    });

    // ── Delete Modal ──
    window.openDeleteModal = function(actionUrl, itemName) {
        var modal = document.getElementById('modalConfirmDelete');
        var form  = document.getElementById('deleteConfirmForm');
        var target= document.getElementById('deleteTargetItem');
        if (modal && form && target) {
            form.action   = actionUrl;
            target.textContent = itemName;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    };
    window.closeDeleteModal = function() {
        var modal = document.getElementById('modalConfirmDelete');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    };
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { window.closeDeleteModal(); }
    });

    // ── Notif Badge Auto-refresh ──
    (function pollNotif() {
        setInterval(function() {
            fetch('{{ route('notifikasi.count') }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var count = data.count || 0;

                // Topbar badge
                var topBadge = document.getElementById('notifBadge');
                var bellBtn  = document.getElementById('notifBellBtn');
                if (count > 0) {
                    var txt = count > 99 ? '99+' : count;
                    if (topBadge) { topBadge.textContent = txt; }
                    else if (bellBtn) {
                        var sp = document.createElement('span');
                        sp.className = 'notif-badge'; sp.id = 'notifBadge';
                        sp.textContent = txt;
                        bellBtn.appendChild(sp);
                    }
                } else {
                    if (topBadge) topBadge.remove();
                }

                // Sidebar badge
                var sideBadge = document.getElementById('sidebarNotifBadge');
                var sideItem  = document.getElementById('sidebarNotifItem');
                if (count > 0) {
                    var txt2 = count > 99 ? '99+' : count;
                    if (sideBadge) { sideBadge.textContent = txt2; }
                    else if (sideItem) {
                        var sp2 = document.createElement('span');
                        sp2.className = 'sidebar-item-badge'; sp2.id = 'sidebarNotifBadge';
                        sp2.textContent = txt2;
                        sideItem.appendChild(sp2);
                    }
                } else {
                    if (sideBadge) sideBadge.remove();
                }
            })
            .catch(function() {});
        }, 60000);
    })();
})();
</script>
@stack('scripts')
</body>
</html>
