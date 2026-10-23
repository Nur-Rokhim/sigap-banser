@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<style>
    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .welcome-text h2 {
        font-size: 1.5rem;
        margin-bottom: 4px;
    }

    .welcome-text p {
        color: var(--ink-muted);
        font-size: 0.92rem;
    }

    .quick-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Summary Cards */
    .summary-card {
        padding: 24px 20px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .summary-card-default {
        background-color: var(--surface);
        color: var(--ink);
    }

    .summary-card-red {
        background: linear-gradient(135deg, var(--red) 0%, #B8131A 100%);
        color: #FFFFFF !important;
        border-color: #B8131A;
        box-shadow: 0 4px 14px rgba(229, 35, 43, 0.35);
    }

    .summary-card-red .summary-title {
        color: rgba(255, 255, 255, 0.9);
    }

    .summary-card-red .summary-count {
        color: #FFFFFF;
    }

    .summary-card-red .summary-desc {
        color: var(--yellow);
    }

    .summary-card-red .summary-icon-bg {
        background-color: rgba(255, 255, 255, 0.2);
        color: #FFFFFF;
    }

    .summary-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .summary-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-muted);
    }

    .summary-count {
        font-family: 'Archivo', sans-serif;
        font-size: 2.35rem;
        font-weight: 900;
        line-height: 1;
        margin: 4px 0;
        color: var(--ink);
    }

    .summary-desc {
        font-size: 0.8rem;
        color: var(--ink-muted);
        font-weight: 500;
    }

    .summary-icon-bg {
        width: 54px;
        height: 54px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--surface-alt);
        color: var(--accent);
        flex-shrink: 0;
    }

    .summary-icon-bg svg {
        width: 28px;
        height: 28px;
    }

    /* Panels layout */
    .dashboard-panels {
        margin-top: 28px;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    @media (max-width: 992px) {
        .dashboard-panels {
            grid-template-columns: 1fr;
        }
    }

    /* Nomor Terakhir Terbit List */
    .nomor-terbit-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .nomor-terbit-item {
        background: linear-gradient(135deg, var(--hero1) 0%, var(--hero2) 100%);
        border: 1px solid rgba(201, 162, 74, 0.35);
        border-radius: var(--radius-md);
        padding: 16px;
        color: #FFFFFF;
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .nomor-terbit-item:hover {
        transform: translateY(-2px);
        border-color: var(--gold);
    }

    .nomor-terbit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .nomor-jenis-tag {
        font-size: 0.75rem;
        font-weight: 800;
        background-color: var(--gold);
        color: #240608;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .nomor-jenis-title {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 600;
    }

    .nomor-code-display {
        font-family: 'Archivo', monospace, sans-serif;
        font-weight: 800;
        font-size: 1.18rem;
        color: var(--yellow);
        letter-spacing: 0.04em;
        word-break: break-all;
        text-shadow: 0 1px 3px rgba(0,0,0,0.6);
        margin: 4px 0;
    }

    .nomor-date-info {
        font-size: 0.78rem;
        color: rgba(255, 255, 255, 0.65);
    }

    /* Perlu Tindakan List */
    .action-item {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-light);
        transition: background-color 0.15s ease;
    }

    .action-item:last-child {
        border-bottom: none;
    }

    .action-item:hover {
        background-color: var(--surface-alt);
    }

    .action-item-info {
        flex: 1;
    }

    .action-item-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--ink);
        margin-bottom: 4px;
    }

    .action-item-meta {
        font-size: 0.82rem;
        color: var(--ink-muted);
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .empty-state {
        text-align: center;
        padding: 36px 16px;
        color: var(--ink-muted);
    }

    .empty-state svg {
        width: 44px;
        height: 44px;
        stroke: var(--ink-muted);
        margin-bottom: 8px;
        opacity: 0.6;
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <!-- Welcome Header & Quick Action Buttons -->
    <div class="dashboard-header">
        <div class="welcome-text">
            <h2>Pusat Komando Administrasi Satkorcab</h2>
            <p>Selamat bertugas, <strong>{{ Auth::user()->name ?? 'Petugas Administrasi' }}</strong>. Pantau dan kelola surat secara terpadu.</p>
        </div>
        <div class="quick-actions">
            <a href="{{ route('surat-masuk.create') }}" class="btn btn-secondary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Catat Surat Masuk</span>
            </a>
            <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buat Surat Keluar</span>
            </a>
        </div>
    </div>

    <!-- Three Summary Cards in a Row -->
    <div class="grid-3">
        <!-- 1. Surat Masuk Bulan Ini (Surface background) -->
        <div class="summary-card summary-card-default">
            <div class="summary-content">
                <span class="summary-title">Surat Masuk Bulan Ini</span>
                <span class="summary-count">{{ $suratMasukBulanIni ?? $totalSuratMasukBulanIni ?? 0 }}</span>
                <span class="summary-desc">Total dokumen surat masuk terdaftar</span>
            </div>
            <div class="summary-icon-bg">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
        </div>

        <!-- 2. Menunggu Disposisi (RED Background #E5232B, White Text) -->
        <div class="summary-card summary-card-red">
            <div class="summary-content">
                <span class="summary-title">Menunggu Disposisi</span>
                <span class="summary-count">{{ $menungguDisposisi ?? 0 }}</span>
                <span class="summary-desc">Memerlukan instruksi pimpinan</span>
            </div>
            <div class="summary-icon-bg">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- 3. Surat Keluar Bulan Ini (Surface background) -->
        <div class="summary-card summary-card-default">
            <div class="summary-content">
                <span class="summary-title">Surat Keluar Bulan Ini</span>
                <span class="summary-count">{{ $suratKeluarBulanIni ?? $totalSuratKeluarBulanIni ?? 0 }}</span>
                <span class="summary-desc">Dokumen surat keluar diterbitkan</span>
            </div>
            <div class="summary-icon-bg">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Panels: Perlu Tindakan & Nomor Terakhir Terbit -->
    <div class="dashboard-panels">
        <!-- Panel 1: Perlu Tindakan (Surat Masuk Status Baru) -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="20" height="20" fill="none" stroke="var(--red)" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Perlu Tindakan (Surat Masuk Baru)</span>
                </div>
                <a href="{{ route('surat-masuk.index', ['status' => 'baru']) }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body" style="padding: 0;">
                @if(isset($perluTindakan) && count($perluTindakan) > 0)
                    <div class="action-items-list">
                        @foreach($perluTindakan as $surat)
                            <div class="action-item">
                                <div class="action-item-info">
                                    <div class="action-item-title">{{ $surat->perihal ?? 'Perihal Tidak Tersedia' }}</div>
                                    <div class="action-item-meta">
                                        <span><strong>No:</strong> {{ $surat->nomor_surat ?? '-' }}</span>
                                        <span>&bull;</span>
                                        <span><strong>Dari:</strong> {{ $surat->pengirim ?? $surat->dari ?? '-' }}</span>
                                        <span>&bull;</span>
                                        <span><strong>Diterima:</strong> {{ isset($surat->tanggal_diterima) ? \Carbon\Carbon::parse($surat->tanggal_diterima)->format('d/m/Y') : '-' }}</span>
                                    </div>
                                </div>
                                <div style="display:flex; align-items:center; gap: 8px;">
                                    <span class="badge badge-red">Baru</span>
                                    <a href="{{ route('surat-masuk.show', $surat->id) }}" class="btn btn-accent btn-sm">
                                        Proses
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p><strong>Bagus! Tidak ada surat masuk yang menumpuk.</strong></p>
                        <p style="font-size: 0.85rem;">Semua surat masuk telah didisposisikan atau diselesaikan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Panel 2: Nomor Terakhir Terbit (SU, SPH, ST in Maroon boxes with Yellow digits) -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="20" height="20" fill="none" stroke="var(--gold)" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    <span>Nomor Terakhir Terbit</span>
                </div>
                <a href="{{ route('surat-keluar.create') }}" class="btn btn-gold btn-sm" title="Buat nomor baru">+ Nomor</a>
            </div>
            <div class="card-body">
                <div class="nomor-terbit-list">
                    <!-- Surat Undangan (SU) -->
                    <div class="nomor-terbit-item">
                        <div class="nomor-terbit-header">
                            <span class="nomor-jenis-tag">SU</span>
                            <span class="nomor-jenis-title">Surat Undangan</span>
                        </div>
                        <div class="nomor-code-display">
                            {{ $nomorTerakhir['SU'] ?? 'Belum ada nomor terbit' }}
                        </div>
                        <div class="nomor-date-info">
                            Terakhir: {{ $tanggalNomorTerakhir['SU'] ?? 'Periode berjalan' }}
                        </div>
                    </div>

                    <!-- Surat Perintah Harian (SPH) -->
                    <div class="nomor-terbit-item">
                        <div class="nomor-terbit-header">
                            <span class="nomor-jenis-tag">SPH</span>
                            <span class="nomor-jenis-title">Surat Perintah Harian</span>
                        </div>
                        <div class="nomor-code-display">
                            {{ $nomorTerakhir['SPH'] ?? 'Belum ada nomor terbit' }}
                        </div>
                        <div class="nomor-date-info">
                            Terakhir: {{ $tanggalNomorTerakhir['SPH'] ?? 'Periode berjalan' }}
                        </div>
                    </div>

                    <!-- Surat Tugas (ST) -->
                    <div class="nomor-terbit-item">
                        <div class="nomor-terbit-header">
                            <span class="nomor-jenis-tag">ST</span>
                            <span class="nomor-jenis-title">Surat Tugas</span>
                        </div>
                        <div class="nomor-code-display">
                            {{ $nomorTerakhir['ST'] ?? 'Belum ada nomor terbit' }}
                        </div>
                        <div class="nomor-date-info">
                            Terakhir: {{ $tanggalNomorTerakhir['ST'] ?? 'Periode berjalan' }}
                        </div>
                    </div>

                    <!-- Surat Keputusan (SK) -->
                    <div class="nomor-terbit-item">
                        <div class="nomor-terbit-header">
                            <span class="nomor-jenis-tag" style="background: rgba(229, 35, 43, 0.15); color: var(--red); border-color: rgba(229, 35, 43, 0.3);">SK</span>
                            <span class="nomor-jenis-title">Surat Keputusan</span>
                        </div>
                        <div class="nomor-code-display">
                            {{ $nomorTerakhir['SK'] ?? 'Belum ada nomor terbit' }}
                        </div>
                        <div class="nomor-date-info">
                            Terakhir: {{ $tanggalNomorTerakhir['SK'] ?? 'Periode berjalan' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
