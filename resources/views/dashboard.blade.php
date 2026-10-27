@extends('layouts.app')

@section('title', 'Beranda — Pusat Komando')

@push('styles')
<style>
    /* ── Welcome Hero ── */
    .dashboard-hero {
        background: linear-gradient(135deg, var(--hero1) 0%, #3D0A0E 60%, var(--hero2) 100%);
        border-radius: var(--radius-lg);
        padding: 28px 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(90,14,20,0.4);
    }
    .dashboard-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(229,35,43,0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .dashboard-hero::after {
        content: '';
        position: absolute;
        bottom: -30px; left: 30%;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,162,74,0.1) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    .hero-text h2 {
        font-size: 1.55rem;
        font-weight: 900;
        color: #FFFFFF;
        margin-bottom: 6px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    .hero-text p {
        color: rgba(255,255,255,0.65);
        font-size: 0.9rem;
        line-height: 1.5;
    }
    .hero-text p strong { color: var(--gold); }
    .hero-date-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        padding: 5px 14px;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.7);
        margin-top: 10px;
        backdrop-filter: blur(4px);
    }
    .hero-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        flex-shrink: 0;
    }
    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 18px;
        border-radius: var(--radius-sm);
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
        border: 1px solid transparent;
    }
    .hero-btn-primary {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
        box-shadow: 0 4px 12px rgba(229,35,43,0.4);
    }
    .hero-btn-primary:hover {
        background: #C01920;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(229,35,43,0.5);
    }
    .hero-btn-outline {
        background: rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.85);
        border-color: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
    }
    .hero-btn-outline:hover {
        background: rgba(255,255,255,0.15);
        color: #fff;
        transform: translateY(-1px);
    }

    /* ── Stat Cards ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) { .stats-row { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 480px) { .stats-row { grid-template-columns: 1fr; } }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: var(--shadow-sm);
        transition: transform 0.22s ease, box-shadow 0.22s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px;
        height: 100%;
        border-radius: 4px 0 0 4px;
    }
    .stat-card.blue::before  { background: var(--blue); }
    .stat-card.red::before   { background: var(--red); }
    .stat-card.green::before { background: var(--green); }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    .stat-icon {
        width: 50px; height: 50px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon.blue  { background: rgba(29,104,189,0.1);  color: var(--blue); }
    .stat-icon.red   { background: rgba(229,35,43,0.1);   color: var(--red); }
    .stat-icon.green { background: rgba(14,155,74,0.1);   color: var(--green); }
    .stat-num {
        font-family: 'Archivo', sans-serif;
        font-size: 2.2rem;
        font-weight: 900;
        line-height: 1;
    }
    .stat-num.blue  { color: var(--blue); }
    .stat-num.red   { color: var(--red); }
    .stat-num.green { color: var(--green); }
    .stat-label {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-muted);
        margin-top: 3px;
    }
    .stat-sub {
        font-size: 0.75rem;
        color: var(--ink-muted);
        margin-top: 4px;
    }

    /* ── Quick Links (new) ── */
    .quick-links-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) { .quick-links-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .quick-links-row { grid-template-columns: 1fr 1fr; } }
    .quick-link-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 16px 14px;
        text-align: center;
        text-decoration: none;
        color: var(--ink);
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }
    .quick-link-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
        color: var(--ink);
    }
    .quick-link-icon {
        width: 42px; height: 42px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 2px;
    }
    .quick-link-label {
        font-size: 0.8rem;
        font-weight: 700;
        text-align: center;
        line-height: 1.3;
    }

    /* ── Main Panels ── */
    .dashboard-panels {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 20px;
    }
    @media (max-width: 992px) { .dashboard-panels { grid-template-columns: 1fr; } }

    /* Action Items */
    .action-item {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 13px 18px;
        border-bottom: 1px solid var(--border-light);
        transition: background 0.15s ease;
    }
    .action-item:last-child { border-bottom: none; }
    .action-item:hover { background: var(--surface-alt); }
    .action-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--red);
        flex-shrink: 0;
        margin-top: 6px;
        box-shadow: 0 0 0 3px rgba(229,35,43,0.15);
        animation: pulseDot 2s ease infinite;
    }
    @keyframes pulseDot {
        0%,100% { box-shadow: 0 0 0 3px rgba(229,35,43,0.15); }
        50% { box-shadow: 0 0 0 6px rgba(229,35,43,0.06); }
    }
    .action-info { flex: 1; min-width: 0; }
    .action-perihal {
        font-weight: 700;
        font-size: 0.88rem;
        color: var(--ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 3px;
    }
    .action-meta {
        font-size: 0.76rem;
        color: var(--ink-muted);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }
    .meta-sep { opacity: 0.35; }

    /* Nomor Terakhir */
    .nomor-cards { display: flex; flex-direction: column; gap: 10px; }
    .nomor-card {
        background: linear-gradient(135deg, var(--hero1) 0%, #2D0508 100%);
        border: 1px solid rgba(201,162,74,0.2);
        border-radius: var(--radius-md);
        padding: 14px 16px;
        transition: border-color 0.2s ease, transform 0.2s ease;
    }
    .nomor-card:hover {
        border-color: rgba(201,162,74,0.5);
        transform: translateX(3px);
    }
    .nomor-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }
    .nomor-kode {
        font-size: 0.7rem;
        font-weight: 900;
        background: var(--gold);
        color: #1A0608;
        padding: 2px 8px;
        border-radius: 4px;
        letter-spacing: 0.06em;
    }
    .nomor-jenis {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.55);
        font-weight: 500;
    }
    .nomor-value {
        font-family: 'Archivo', monospace;
        font-weight: 800;
        font-size: 0.95rem;
        color: var(--yellow);
        letter-spacing: 0.02em;
        word-break: break-all;
        margin-bottom: 3px;
        text-shadow: 0 1px 4px rgba(0,0,0,0.5);
    }
    .nomor-tgl {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.4);
    }

    .empty-state-box {
        text-align: center;
        padding: 40px 20px;
        color: var(--ink-muted);
    }
    .empty-state-box svg {
        width: 44px; height: 44px;
        margin: 0 auto 12px;
        opacity: 0.35;
        display: block;
    }
    .empty-state-box p { font-size: 0.88rem; }
</style>
@endpush

@section('content')
<div class="page-container">

    {{-- ── HERO ── --}}
    <div class="dashboard-hero">
        <div class="hero-inner">
            <div class="hero-text">
                <h2>Pusat Komando Administrasi</h2>
                <p>Selamat bertugas, <strong>{{ Auth::user()->name ?? 'Petugas Administrasi' }}</strong>.<br>Pantau dan kelola seluruh persuratan Satkorcab secara terpadu.</p>
                <div class="hero-date-chip">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
            <div class="hero-actions">
                <a href="{{ route('surat-masuk.create') }}" class="hero-btn hero-btn-outline">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Catat Masuk
                </a>
                <a href="{{ route('surat-keluar.create') }}" class="hero-btn hero-btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Surat
                </a>
            </div>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="stats-row">
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <div>
                <div class="stat-num blue">{{ $suratMasukBulanIni ?? 0 }}</div>
                <div class="stat-label">Surat Masuk</div>
                <div class="stat-sub">Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</div>
            </div>
        </div>

        <div class="stat-card red">
            <div class="stat-icon red">
                <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="stat-num red">{{ $menungguDisposisi ?? 0 }}</div>
                <div class="stat-label">Menunggu Disposisi</div>
                <div class="stat-sub">Perlu instruksi pimpinan</div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon green">
                <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <div>
                <div class="stat-num green">{{ $suratKeluarBulanIni ?? 0 }}</div>
                <div class="stat-label">Surat Keluar</div>
                <div class="stat-sub">Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</div>
            </div>
        </div>
    </div>

    {{-- ── QUICK LINKS ── --}}
    <div class="quick-links-row">
        <a href="{{ route('surat-masuk.index') }}" class="quick-link-card">
            <div class="quick-link-icon" style="background: rgba(29,104,189,0.1); color: var(--blue);">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <span class="quick-link-label">Arsip Surat Masuk</span>
        </a>
        <a href="{{ route('surat-keluar.index') }}" class="quick-link-card">
            <div class="quick-link-icon" style="background: rgba(229,35,43,0.1); color: var(--red);">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </div>
            <span class="quick-link-label">Arsip Surat Keluar</span>
        </a>
        <a href="{{ route('surat-keputusan.index') }}" class="quick-link-card">
            <div class="quick-link-icon" style="background: rgba(201,162,74,0.12); color: var(--gold);">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <span class="quick-link-label">Surat Keputusan (SK)</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="quick-link-card">
            <div class="quick-link-icon" style="background: rgba(14,155,74,0.1); color: var(--green);">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <span class="quick-link-label">Laporan Rekap</span>
        </a>
    </div>

    {{-- ── MAIN PANELS ── --}}
    <div class="dashboard-panels">

        {{-- Panel Kiri: Perlu Tindakan --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" fill="none" stroke="var(--red)" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Surat Masuk Perlu Tindakan
                    @if(isset($menungguDisposisi) && $menungguDisposisi > 0)
                        <span class="badge badge-red">{{ $menungguDisposisi }}</span>
                    @endif
                </div>
                <a href="{{ route('surat-masuk.index', ['status' => 'baru']) }}" class="btn btn-secondary" style="font-size:0.78rem; padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div style="padding: 0;">
                @if(isset($perluTindakan) && count($perluTindakan) > 0)
                    @foreach($perluTindakan as $surat)
                    <div class="action-item">
                        <div class="action-dot"></div>
                        <div class="action-info">
                            <div class="action-perihal">{{ $surat->perihal ?? 'Perihal tidak tersedia' }}</div>
                            <div class="action-meta">
                                <span>{{ $surat->nomor_surat ?? '-' }}</span>
                                <span class="meta-sep">•</span>
                                <span>{{ $surat->pengirim ?? $surat->dari ?? '-' }}</span>
                                <span class="meta-sep">•</span>
                                <span>{{ isset($surat->tanggal_diterima) ? \Carbon\Carbon::parse($surat->tanggal_diterima)->format('d/m/Y') : '-' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('surat-masuk.show', $surat->id) }}"
                           class="btn btn-secondary"
                           style="font-size:0.76rem; padding:5px 12px; flex-shrink:0;">
                            Proses →
                        </a>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p><strong>Tidak ada surat yang menumpuk.</strong></p>
                        <p style="margin-top:4px; color:var(--ink-muted); font-size:0.82rem;">Semua surat masuk telah ditindaklanjuti.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Panel Kanan: Nomor Terakhir Terbit --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" fill="none" stroke="var(--gold)" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    Nomor Terakhir Terbit
                </div>
                <a href="{{ route('surat-keluar.create') }}" class="btn btn-gold" style="font-size:0.78rem; padding:6px 12px;">
                    + Nomor Baru
                </a>
            </div>
            <div class="card-body">
                <div class="nomor-cards">
                    @foreach(['SU' => 'Surat Undangan', 'SPH' => 'Surat Perintah Harian', 'ST' => 'Surat Tugas', 'SK' => 'Surat Keputusan'] as $kode => $nama)
                    <div class="nomor-card">
                        <div class="nomor-card-top">
                            <span class="nomor-kode">{{ $kode }}</span>
                            <span class="nomor-jenis">{{ $nama }}</span>
                        </div>
                        <div class="nomor-value">{{ $nomorTerakhir[$kode] ?? '— Belum ada —' }}</div>
                        <div class="nomor-tgl">Terakhir: {{ $tanggalNomorTerakhir[$kode] ?? 'Periode berjalan' }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
