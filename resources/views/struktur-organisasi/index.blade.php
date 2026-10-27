@extends('layouts.app')

@section('title', 'Struktur Organisasi Satkorcab — SIGAP BANSER')

@push('styles')
<style>
    /* ── Header Banner ── */
    .page-hero {
        background: linear-gradient(135deg, var(--hero1) 0%, #3D0A0E 60%, var(--hero2) 100%);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(90, 14, 20, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .page-hero::after {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,162,74,0.2) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    .hero-title-group {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .hero-icon-box {
        width: 52px;
        height: 52px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, rgba(229,35,43,0.3) 0%, rgba(201,162,74,0.2) 100%);
        border: 1px solid rgba(201,162,74,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFFFFF;
        box-shadow: 0 4px 14px rgba(0,0,0,0.3);
        flex-shrink: 0;
    }
    .hero-text h1 {
        font-size: 1.45rem;
        font-weight: 900;
        color: #FFFFFF;
        margin-bottom: 3px;
        text-shadow: 0 2px 6px rgba(0,0,0,0.35);
    }
    .hero-text p {
        color: rgba(255,255,255,0.72);
        font-size: 0.88rem;
    }

    /* ── Quick Stats ── */
    .stat-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }
    @media (max-width: 992px) {
        .stat-strip { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 540px) {
        .stat-strip { grid-template-columns: 1fr; }
    }
    .stat-mini-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: var(--shadow-sm);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        text-decoration: none;
        color: inherit;
    }
    .stat-mini-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .stat-mini-num {
        font-size: 1.7rem;
        font-weight: 900;
        font-family: 'Archivo', sans-serif;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-mini-label {
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-muted);
    }
    .stat-mini-icon {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ── Filter / Navigation Pills ── */
    .filter-pills-bar {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        margin-bottom: 24px;
        border-bottom: 1px solid var(--border);
    }
    .filter-pill-btn {
        padding: 9px 18px;
        border-radius: 20px;
        font-size: 0.84rem;
        font-weight: 700;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--ink-muted);
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
        font-family: inherit;
        text-decoration: none;
    }
    .filter-pill-btn:hover {
        background: var(--surface-alt);
        color: var(--ink);
        border-color: var(--ink-muted);
    }
    .filter-pill-btn.active {
        background: var(--accent);
        border-color: var(--accent);
        color: #FFFFFF;
        box-shadow: 0 2px 8px rgba(142,27,34,0.3);
    }

    /* ── Section Dividers & Headers ── */
    .org-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--border-light);
        gap: 12px;
        flex-wrap: wrap;
    }
    .org-section-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .org-section-badge {
        font-size: 0.72rem;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 12px;
        background: rgba(201,162,74,0.15);
        color: #8C6F12;
        border: 1px solid rgba(201,162,74,0.3);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* ── Cards Design ── */
    .pimpinan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 18px;
        margin-bottom: 32px;
    }

    .org-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        display: flex;
        flex-direction: column;
    }
    .org-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: rgba(201,162,74,0.4);
    }

    .org-card-head {
        padding: 14px 18px;
        background: var(--surface-alt);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .org-card-title {
        font-size: 0.94rem;
        font-weight: 800;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .org-card-body {
        padding: 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* Pejabat Highlight Box */
    .pejabat-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        background: rgba(90,14,20,0.03);
        border: 1px solid rgba(90,14,20,0.1);
        border-radius: var(--radius-md);
    }
    .pejabat-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8E1B22, #5A0E14);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.95rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(90,14,20,0.25);
    }
    .pejabat-avatar.gold {
        background: linear-gradient(135deg, #D4A843, #8C6B28);
        color: #1A0608;
    }
    .pejabat-avatar.blue {
        background: linear-gradient(135deg, #1D68BD, #0F4282);
    }
    .pejabat-avatar.green {
        background: linear-gradient(135deg, #0E9B4A, #065F2C);
    }
    .pejabat-info {
        flex: 1;
        overflow: hidden;
    }
    .pejabat-role {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--gold);
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }
    .pejabat-name {
        font-size: 0.96rem;
        font-weight: 800;
        color: var(--ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .pejabat-name.empty {
        color: var(--ink-muted);
        font-style: italic;
        font-weight: 500;
    }

    /* Anggota Chips */
    .anggota-section {
        margin-top: auto;
    }
    .anggota-label {
        font-size: 0.74rem;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--ink-muted);
        letter-spacing: 0.05em;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .anggota-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .anggota-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 14px;
        background: var(--surface-alt);
        border: 1px solid var(--border);
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--ink);
    }
    .anggota-chip-icon {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--gold);
    }

    /* Grid 7 Kolom / 3-4 Grid */
    .biro-grid, .satsus-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 18px;
        margin-bottom: 32px;
    }

    /* Modal Form Styling */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.65);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 16px;
    }
    .modal-overlay.open {
        display: flex;
    }
    .modal-box {
        background: var(--surface);
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 520px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border);
    }
    .modal-head {
        padding: 16px 20px;
        background: var(--surface-alt);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-head h3 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--ink);
    }
    .modal-close-btn {
        background: transparent;
        border: none;
        color: var(--ink-muted);
        cursor: pointer;
        font-size: 1.3rem;
        line-height: 1;
    }
    .modal-body {
        padding: 20px;
    }
    .modal-foot {
        padding: 14px 20px;
        background: var(--surface-alt);
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Print styles */
    @media print {
        .sidebar, .topbar, .site-footer, .filter-pills-bar, .no-print, .btn, .btn-icon {
            display: none !important;
        }
        .main-panel { margin-left: 0 !important; }
        .page-container { max-width: 100% !important; }
    }
</style>
@endpush

@section('content')
<div class="page-container">
    <!-- Header Banner -->
    <div class="page-hero">
        <div class="hero-flex">
            <div class="hero-title-group">
                <div class="hero-icon-box">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="hero-text">
                    <h1>Struktur Organisasi Satkorcab</h1>
                    <p>Bagan Komando, Susunan 7 Biro Markas, dan 7 Satuan Khusus (SATSUS) BANSER Kabupaten Blitar</p>
                </div>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;" class="no-print">
                <button type="button" onclick="window.print()" class="btn btn-secondary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak Bagan</span>
                </button>
                <a href="{{ route('struktur-organisasi.edit') }}" class="btn btn-gold btn-lg">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit Personel Organisasi</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stat-strip">
        <div class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--accent);">{{ $totalPersonel }}</div>
                <div class="stat-mini-label">Total Personel Terisi</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(142,27,34,0.1); color: var(--accent);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <div class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--gold);">{{ 2 + $wakasatkorcabList->count() }}</div>
                <div class="stat-mini-label">Pimpinan Komando</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(201,162,74,0.15); color: #8C6F12;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>

        <div class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--blue);">{{ $biroList->count() }}</div>
                <div class="stat-mini-label">Biro Markas</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(29,104,189,0.1); color: var(--blue);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>

        <div class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--green);">{{ $satsusList->count() }}</div>
                <div class="stat-mini-label">Satuan Khusus (SATSUS)</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(14,155,74,0.1); color: var(--green);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Filter Pills Navigation -->
    <div class="filter-pills-bar no-print">
        <button type="button" class="filter-pill-btn active" onclick="filterSection('semua', this)">
            <span>Semua Unit (17)</span>
        </button>
        <button type="button" class="filter-pill-btn" onclick="filterSection('pimpinan', this)">
            <span>1. Pimpinan Komando</span>
        </button>
        <button type="button" class="filter-pill-btn" onclick="filterSection('biro', this)">
            <span>2. 7 Biro Markas</span>
        </button>
        <button type="button" class="filter-pill-btn" onclick="filterSection('satsus', this)">
            <span>3. 7 Satuan Khusus (SATSUS)</span>
        </button>
    </div>

    <!-- ════════════════════════════════════════════════════════════════
         SECTION 1: PIMPINAN KOMANDO
    ════════════════════════════════════════════════════════════════ -->
    <div id="section-pimpinan" class="org-group-section">
        <div class="org-section-header">
            <div class="org-section-title">
                <svg width="22" height="22" fill="none" stroke="var(--gold)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Pimpinan Komando Satkorcab</span>
            </div>
            <div class="no-print">
                <button type="button" onclick="openModalTambahWakil()" class="btn btn-secondary btn-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Wakil Kasatkorcab</span>
                </button>
            </div>
        </div>

        <div class="pimpinan-grid">
            <!-- 1. KASATKORCAB -->
            @if($kasatkorcab)
            <div class="org-card" style="border-top: 4px solid var(--accent);">
                <div class="org-card-head">
                    <span class="org-card-title">
                        <span class="badge badge-red">KOMANDO UTAMA</span>
                        <span>1. KASATKORCAB</span>
                    </span>
                    <button type="button" class="btn-icon btn-icon-edit no-print" onclick="openModalEditUnit({{ $kasatkorcab->id }}, '{{ addslashes($kasatkorcab->nama_posisi) }}', '{{ addslashes($kasatkorcab->ketua) }}', '', [])" title="Edit Kasatkorcab">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                </div>
                <div class="org-card-body">
                    <div class="pejabat-box">
                        <div class="pejabat-avatar gold">KS</div>
                        <div class="pejabat-info">
                            <div class="pejabat-role">Kepala Satuan Koordinasi Cabang</div>
                            <div class="pejabat-name {{ empty($kasatkorcab->ketua) ? 'empty' : '' }}">
                                {{ $kasatkorcab->ketua ?: '[Belum ditentukan]' }}
                            </div>
                        </div>
                    </div>
                    <p style="font-size: 0.82rem; color: var(--ink-muted); margin: 0; line-height: 1.4;">
                        {{ $kasatkorcab->keterangan ?: 'Pemegang komando operasional tertinggi Satkorcab Banser Kabupaten Blitar.' }}
                    </p>
                </div>
            </div>
            @endif

            <!-- 2. WAKASATKORCAB (Dinamis: 1 atau lebih) -->
            @forelse($wakasatkorcabList as $idx => $wakasat)
            <div class="org-card" style="border-top: 4px solid var(--gold);">
                <div class="org-card-head">
                    <span class="org-card-title">
                        <span class="badge badge-yellow">WAKIL KOMANDO</span>
                        <span>2. {{ $wakasat->nama_posisi }}</span>
                    </span>
                    <div style="display: flex; gap: 4px;" class="no-print">
                        <button type="button" class="btn-icon btn-icon-edit" onclick="openModalEditUnit({{ $wakasat->id }}, '{{ addslashes($wakasat->nama_posisi) }}', '{{ addslashes($wakasat->ketua) }}', '', [])" title="Edit {{ $wakasat->nama_posisi }}">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <form action="{{ route('struktur-organisasi.wakil.destroy', $wakasat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ $wakasat->nama_posisi }}?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-icon-delete" title="Hapus Wakil Kasatkorcab">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="org-card-body">
                    <div class="pejabat-box">
                        <div class="pejabat-avatar">WK</div>
                        <div class="pejabat-info">
                            <div class="pejabat-role">Wakil Kepala Satkorcab</div>
                            <div class="pejabat-name {{ empty($wakasat->ketua) ? 'empty' : '' }}">
                                {{ $wakasat->ketua ?: '[Belum ditentukan]' }}
                            </div>
                        </div>
                    </div>
                    <p style="font-size: 0.82rem; color: var(--ink-muted); margin: 0; line-height: 1.4;">
                        {{ $wakasat->keterangan ?: 'Mendampingi dan mewakili tugas komando Kasatkorcab.' }}
                    </p>
                </div>
            </div>
            @empty
            <div class="org-card" style="border: 1.5px dashed var(--border); display: flex; align-items: center; justify-content: center; padding: 24px;">
                <div style="text-align: center; color: var(--ink-muted);">
                    <p style="font-size: 0.9rem; margin-bottom: 8px;">Belum ada Wakil Kasatkorcab ditambahkan.</p>
                    <button type="button" onclick="openModalTambahWakil()" class="btn btn-secondary btn-sm">
                        + Tambah Wakil Kasatkorcab
                    </button>
                </div>
            </div>
            @endforelse

            <!-- 3. KASETMA -->
            @if($kasetma)
            <div class="org-card" style="border-top: 4px solid var(--blue);">
                <div class="org-card-head">
                    <span class="org-card-title">
                        <span class="badge badge-blue">SEKRETARIAT</span>
                        <span>3. KASETMA</span>
                    </span>
                    <button type="button" class="btn-icon btn-icon-edit no-print" onclick="openModalEditUnit({{ $kasetma->id }}, '{{ addslashes($kasetma->nama_posisi) }}', '{{ addslashes($kasetma->ketua) }}', '', [])" title="Edit Kasetma">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                </div>
                <div class="org-card-body">
                    <div class="pejabat-box">
                        <div class="pejabat-avatar blue">KM</div>
                        <div class="pejabat-info">
                            <div class="pejabat-role">Kepala Sekretariat Markas</div>
                            <div class="pejabat-name {{ empty($kasetma->ketua) ? 'empty' : '' }}">
                                {{ $kasetma->ketua ?: '[Belum ditentukan]' }}
                            </div>
                        </div>
                    </div>
                    <p style="font-size: 0.82rem; color: var(--ink-muted); margin: 0; line-height: 1.4;">
                        {{ $kasetma->keterangan ?: 'Pimpinan tata usaha persuratan dan ketatausahaan markas komando.' }}
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════════
         SECTION 2: BIRO-BIRO MARKAS (7 BIRO)
    ════════════════════════════════════════════════════════════════ -->
    <div id="section-biro" class="org-group-section">
        <div class="org-section-header">
            <div class="org-section-title">
                <svg width="22" height="22" fill="none" stroke="var(--blue)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Biro-Biro Satkorcab (7 Biro)</span>
            </div>
            <span class="org-section-badge">Ketua & Anggota</span>
        </div>

        <div class="biro-grid">
            @foreach($biroList as $index => $biro)
            <div class="org-card">
                <div class="org-card-head">
                    <span class="org-card-title">
                        <span class="badge badge-purple" style="font-family: monospace;">{{ $biro->singkatan }}</span>
                        <span>{{ 4 + $index }}. {{ $biro->nama_posisi }}</span>
                    </span>
                    <button type="button" class="btn-icon btn-icon-edit no-print" onclick="openModalEditUnit({{ $biro->id }}, '{{ addslashes($biro->nama_posisi) }}', '{{ addslashes($biro->ketua) }}', '', {{ json_encode($biro->anggota ?? []) }})" title="Edit Personel {{ $biro->nama_posisi }}">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span>Edit</span>
                    </button>
                </div>

                <div class="org-card-body">
                    <!-- Ketua Biro -->
                    <div class="pejabat-box">
                        <div class="pejabat-avatar">KB</div>
                        <div class="pejabat-info">
                            <div class="pejabat-role">Kepala / Ketua Biro</div>
                            <div class="pejabat-name {{ empty($biro->ketua) ? 'empty' : '' }}">
                                {{ $biro->ketua ?: '[Belum ditentukan]' }}
                            </div>
                        </div>
                    </div>

                    <!-- Anggota Biro -->
                    <div class="anggota-section">
                        <div class="anggota-label">
                            <span>Anggota Biro</span>
                            <span>{{ is_array($biro->anggota) ? count($biro->anggota) : 0 }} Personel</span>
                        </div>
                        <div class="anggota-chips">
                            @if(is_array($biro->anggota) && count($biro->anggota) > 0)
                                @foreach($biro->anggota as $agt)
                                    <span class="anggota-chip">
                                        <span class="anggota-chip-icon"></span>
                                        <span>{{ $agt }}</span>
                                    </span>
                                @endforeach
                            @else
                                <span style="font-size: 0.8rem; color: var(--ink-muted); font-style: italic;">
                                    Belum ada anggota dicatat.
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════════
         SECTION 3: SATUAN KHUSUS (SATSUS - 7 SATSUS)
    ════════════════════════════════════════════════════════════════ -->
    <div id="section-satsus" class="org-group-section">
        <div class="org-section-header">
            <div class="org-section-title">
                <svg width="22" height="22" fill="none" stroke="var(--green)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Satuan Khusus BANSER (7 SATSUS)</span>
            </div>
            <span class="org-section-badge">Ketua, Wakil & Anggota</span>
        </div>

        <div class="satsus-grid">
            @foreach($satsusList as $index => $satsus)
            <div class="org-card">
                <div class="org-card-head">
                    <span class="org-card-title">
                        <span class="badge badge-green" style="font-family: monospace;">{{ $satsus->singkatan }}</span>
                        <span>{{ 11 + $index }}. {{ $satsus->nama_posisi }}</span>
                    </span>
                    <button type="button" class="btn-icon btn-icon-edit no-print" onclick="openModalEditUnit({{ $satsus->id }}, '{{ addslashes($satsus->nama_posisi) }}', '{{ addslashes($satsus->ketua) }}', '{{ addslashes($satsus->wakil) }}', {{ json_encode($satsus->anggota ?? []) }})" title="Edit Personel {{ $satsus->nama_posisi }}">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span>Edit</span>
                    </button>
                </div>

                <div class="org-card-body">
                    <!-- Ketua Satsus -->
                    <div class="pejabat-box">
                        <div class="pejabat-avatar green">KS</div>
                        <div class="pejabat-info">
                            <div class="pejabat-role">Ketua / Komandan Satsus</div>
                            <div class="pejabat-name {{ empty($satsus->ketua) ? 'empty' : '' }}">
                                {{ $satsus->ketua ?: '[Belum ditentukan]' }}
                            </div>
                        </div>
                    </div>

                    <!-- Wakil Ketua Satsus -->
                    <div class="pejabat-box" style="background: rgba(201,162,74,0.04); border-color: rgba(201,162,74,0.18);">
                        <div class="pejabat-avatar gold">WS</div>
                        <div class="pejabat-info">
                            <div class="pejabat-role">Wakil Ketua / Wakil Komandan</div>
                            <div class="pejabat-name {{ empty($satsus->wakil) ? 'empty' : '' }}">
                                {{ $satsus->wakil ?: '[Belum ditentukan]' }}
                            </div>
                        </div>
                    </div>

                    <!-- Anggota Satsus -->
                    <div class="anggota-section">
                        <div class="anggota-label">
                            <span>Anggota Satsus</span>
                            <span>{{ is_array($satsus->anggota) ? count($satsus->anggota) : 0 }} Personel</span>
                        </div>
                        <div class="anggota-chips">
                            @if(is_array($satsus->anggota) && count($satsus->anggota) > 0)
                                @foreach($satsus->anggota as $agt)
                                    <span class="anggota-chip">
                                        <span class="anggota-chip-icon"></span>
                                        <span>{{ $agt }}</span>
                                    </span>
                                @endforeach
                            @else
                                <span style="font-size: 0.8rem; color: var(--ink-muted); font-style: italic;">
                                    Belum ada anggota dicatat.
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════════
     MODAL TAMBAH WAKIL KASATKORCAB
════════════════════════════════════════════════════════════════ -->
<div id="modalTambahWakil" class="modal-overlay">
    <div class="modal-box">
        <form action="{{ route('struktur-organisasi.wakil.store') }}" method="POST">
            @csrf
            <div class="modal-head">
                <h3>Tambah Wakil Kasatkorcab</h3>
                <button type="button" class="modal-close-btn" onclick="closeModalTambahWakil()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Jabatan / Posisi <span class="required">*</span></label>
                    <input type="text" 
                           name="nama_posisi" 
                           class="form-control" 
                           value="Wakil Kasatkorcab {{ $wakasatkorcabList->count() + 1 }}" 
                           placeholder="Contoh: Wakil Kasatkorcab II / Bidang Khusus" 
                           required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Personel Pejabat</label>
                    <input type="text" 
                           name="ketua" 
                           class="form-control" 
                           placeholder="Nama lengkap pejabat yang ditunjuk">
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan / Bidang Tugas</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan bidang koordinasi atau tugas khusus..."></textarea>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-secondary" onclick="closeModalTambahWakil()">Batal</button>
                <button type="submit" class="btn btn-gold">Simpan Wakil Baru</button>
            </div>
        </form>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════════
     MODAL EDIT UNIT CEPAT (BIRO / SATSUS / PIMPINAN)
════════════════════════════════════════════════════════════════ -->
<div id="modalEditUnit" class="modal-overlay">
    <div class="modal-box">
        <form id="formEditUnit" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-head">
                <h3 id="modalUnitTitle">Edit Personel Unit</h3>
                <button type="button" class="modal-close-btn" onclick="closeModalEditUnit()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group" id="groupKetua">
                    <label class="form-label" id="labelKetua">Nama Ketua / Pejabat</label>
                    <input type="text" name="ketua" id="inputKetua" class="form-control" placeholder="Nama pejabat yang memimpin">
                </div>

                <div class="form-group" id="groupWakil" style="display: none;">
                    <label class="form-label">Nama Wakil Ketua / Wakil Komandan</label>
                    <input type="text" name="wakil" id="inputWakil" class="form-control" placeholder="Nama wakil yang ditunjuk">
                </div>

                <div class="form-group" id="groupAnggota">
                    <label class="form-label">Daftar Anggota (Satu Nama per Baris)</label>
                    <textarea name="anggota" id="inputAnggota" class="form-control" rows="5" placeholder="Tulis nama anggota, satu per baris:&#10;Sahabat Ahmad&#10;Sahabat Budi&#10;Sahabat Slamet"></textarea>
                    <div class="form-hint">Tekan Enter untuk memisahkan nama anggota.</div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-secondary" onclick="closeModalEditUnit()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function filterSection(kategori, btn) {
        document.querySelectorAll('.filter-pill-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const secPimpinan = document.getElementById('section-pimpinan');
        const secBiro     = document.getElementById('section-biro');
        const secSatsus   = document.getElementById('section-satsus');

        if (kategori === 'semua') {
            secPimpinan.style.display = 'block';
            secBiro.style.display = 'block';
            secSatsus.style.display = 'block';
        } else if (kategori === 'pimpinan') {
            secPimpinan.style.display = 'block';
            secBiro.style.display = 'none';
            secSatsus.style.display = 'none';
        } else if (kategori === 'biro') {
            secPimpinan.style.display = 'none';
            secBiro.style.display = 'block';
            secSatsus.style.display = 'none';
        } else if (kategori === 'satsus') {
            secPimpinan.style.display = 'none';
            secBiro.style.display = 'none';
            secSatsus.style.display = 'block';
        }
    }

    function openModalTambahWakil() {
        document.getElementById('modalTambahWakil').classList.add('open');
    }
    function closeModalTambahWakil() {
        document.getElementById('modalTambahWakil').classList.remove('open');
    }

    function openModalEditUnit(id, namaPosisi, ketua, wakil, anggota) {
        const form = document.getElementById('formEditUnit');
        form.action = "{{ url('struktur-organisasi/unit') }}/" + id;

        document.getElementById('modalUnitTitle').textContent = 'Edit Personel — ' + namaPosisi;
        document.getElementById('inputKetua').value = ketua || '';

        // Tampilkan input wakil jika Satsus
        const groupWakil = document.getElementById('groupWakil');
        const inputWakil = document.getElementById('inputWakil');
        if (namaPosisi.toUpperCase().includes('SATSUS')) {
            groupWakil.style.display = 'block';
            inputWakil.value = wakil || '';
        } else {
            groupWakil.style.display = 'none';
            inputWakil.value = '';
        }

        // Tampilkan input anggota jika bukan Kasat/Kasetma/Wakasat
        const groupAnggota = document.getElementById('groupAnggota');
        const inputAnggota = document.getElementById('inputAnggota');
        if (namaPosisi.toUpperCase().includes('KASATKORCAB') || namaPosisi.toUpperCase().includes('KASETMA')) {
            groupAnggota.style.display = 'none';
            inputAnggota.value = '';
        } else {
            groupAnggota.style.display = 'block';
            if (Array.isArray(anggota)) {
                inputAnggota.value = anggota.join("\n");
            } else {
                inputAnggota.value = '';
            }
        }

        document.getElementById('modalEditUnit').classList.add('open');
    }

    function closeModalEditUnit() {
        document.getElementById('modalEditUnit').classList.remove('open');
    }

    // Tutup modal jika klik di latar luar
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('open');
        }
    });
</script>
@endpush
@endsection
