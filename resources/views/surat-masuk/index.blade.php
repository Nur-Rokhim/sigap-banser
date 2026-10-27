@extends('layouts.app')

@section('title', 'Buku Agenda Surat Masuk — SIGAP BANSER')

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
        background: radial-gradient(circle, rgba(201,162,74,0.18) 0%, transparent 70%);
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

    /* ── Stat Cards ── */
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

    /* ── Search & Filter Bar ── */
    .filter-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
    }
    .filter-form {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }
    .search-box-wrap {
        flex: 1;
        min-width: 260px;
        position: relative;
    }
    .search-box-wrap svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink-muted);
        pointer-events: none;
    }
    .search-box-wrap input {
        padding-left: 40px;
    }
</style>
@endpush

@section('content')
@php
    $totalCount = \App\Models\SuratMasuk::count();
    $baruCount = \App\Models\SuratMasuk::where('status', 'baru')->count();
    $disposisiCount = \App\Models\SuratMasuk::where('status', 'didisposisi')->count();
    $selesaiCount = \App\Models\SuratMasuk::where('status', 'selesai')->count();
@endphp

<div class="page-container">
    <!-- Header Banner -->
    <div class="page-hero">
        <div class="hero-flex">
            <div class="hero-title-group">
                <div class="hero-icon-box">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="hero-text">
                    <h1>Buku Agenda Surat Masuk</h1>
                    <p>Registrasi resmi dokumen kedinasan masuk, arsip digital, dan disposisi Komandan Satkorcab</p>
                </div>
            </div>
            <a href="{{ route('surat-masuk.create') }}" class="btn btn-gold btn-lg">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Catat Surat Masuk</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Strip -->
    <div class="stat-strip">
        <a href="{{ route('surat-masuk.index') }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--accent);">{{ $totalCount }}</div>
                <div class="stat-mini-label">Total Surat Masuk</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(142,27,34,0.1); color: var(--accent);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </a>

        <a href="{{ route('surat-masuk.index', ['status' => 'baru']) }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--red);">{{ $baruCount }}</div>
                <div class="stat-mini-label">Surat Baru</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(229,35,43,0.1); color: var(--red);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </a>

        <a href="{{ route('surat-masuk.index', ['status' => 'didisposisi']) }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: #B45309;">{{ $disposisiCount }}</div>
                <div class="stat-mini-label">Telah Didisposisi</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(245,158,11,0.1); color: #B45309;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
        </a>

        <a href="{{ route('surat-masuk.index', ['status' => 'selesai']) }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--green);">{{ $selesaiCount }}</div>
                <div class="stat-mini-label">Selesai / Diarsipkan</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(14,155,74,0.1); color: var(--green);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </a>
    </div>

    <!-- Search & Filter Card -->
    <div class="filter-card">
        <form action="{{ route('surat-masuk.index') }}" method="GET" class="filter-form">
            <div class="search-box-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Cari nomor surat, pengirim, atau perihal..." 
                       value="{{ request('search') }}">
            </div>

            <div style="min-width: 170px;">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="didisposisi" {{ request('status') === 'didisposisi' ? 'selected' : '' }}>Didisposisi</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Filter</span>
            </button>

            @if(request('search') || request('status'))
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary" title="Reset filter">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Reset</span>
                </a>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                <span>Daftar Agenda Surat Masuk</span>
            </h3>
            <span style="font-size: 0.8rem; color: var(--ink-muted);">
                Menampilkan {{ $suratMasuk->count() }} data
            </span>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th style="width: 220px;">Nomor Surat & Scan</th>
                        <th>Pengirim / Asal</th>
                        <th>Perihal Dokumen</th>
                        <th style="width: 120px;">Tgl Diterima</th>
                        <th style="width: 110px; text-align: center;">Status</th>
                        <th style="width: 170px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratMasuk as $index => $item)
                        <tr>
                            <td style="text-align: center; color: var(--ink-muted); font-weight: 600;">
                                {{ method_exists($suratMasuk, 'firstItem') && $suratMasuk->firstItem() ? $suratMasuk->firstItem() + $index : $index + 1 }}
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <strong style="font-family: 'Archivo', sans-serif; font-size: 0.92rem; color: var(--ink);">
                                        {{ $item->nomor_surat ?? $item->no_surat ?? '-' }}
                                    </strong>
                                    @if(!empty($item->file_scan) || !empty($item->scan_surat))
                                        <span title="Tersedia berkas scan digital" style="display: inline-flex; color: var(--accent); background: rgba(142,27,34,0.1); padding: 2px 5px; border-radius: 4px; font-size: 0.72rem; font-weight: 700;">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            PDF
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--ink);">{{ $item->pengirim ?? $item->dari ?? '-' }}</div>
                                @if(!empty($item->tanggal_surat) || !empty($item->tgl_surat))
                                    <small style="color: var(--ink-muted); font-size: 0.78rem;">
                                        Tgl Surat: {{ \Carbon\Carbon::parse($item->tanggal_surat ?? $item->tgl_surat)->format('d/m/Y') }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span style="font-weight: 500; color: var(--ink);">{{ $item->perihal ?? '-' }}</span>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--ink);">
                                    {{ !empty($item->tanggal_diterima ?? $item->tgl_diterima) ? \Carbon\Carbon::parse($item->tanggal_diterima ?? $item->tgl_diterima)->format('d/m/Y') : '-' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                @php
                                    $st = strtolower($item->status ?? 'baru');
                                @endphp
                                @if($st === 'baru')
                                    <span class="badge badge-red">Baru</span>
                                @elseif($st === 'didisposisi')
                                    <span class="badge badge-yellow">Didisposisi</span>
                                @elseif($st === 'selesai')
                                    <span class="badge badge-green">Selesai</span>
                                @else
                                    <span class="badge badge-gray">{{ ucfirst($st) }}</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end;">
                                    <!-- Detail Button -->
                                    <a href="{{ route('surat-masuk.show', $item->id) }}" class="btn-icon btn-icon-detail" title="Lihat detail & disposisi">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span>Detail</span>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('surat-masuk.edit', $item->id) }}" class="btn-icon btn-icon-edit" title="Edit surat masuk">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Delete Button -->
                                    <button type="button" 
                                            class="btn-icon btn-icon-delete" 
                                            title="Hapus surat masuk"
                                            onclick="openDeleteModal('{{ route('surat-masuk.destroy', $item->id) }}', 'Surat Masuk: {{ $item->nomor_surat ?? $item->no_surat }} (Dari: {{ $item->pengirim ?? $item->dari }})')">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px 16px;">
                                <div style="color: var(--ink-muted);">
                                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 12px; opacity: 0.4;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p style="font-weight: 700; font-size: 1rem; color: var(--ink);">Tidak ada data surat masuk ditemukan.</p>
                                    <p style="font-size: 0.85rem; margin-top: 4px;">Gunakan kata kunci pencarian lain atau catat surat baru.</p>
                                    <div style="margin-top: 16px;">
                                        <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary btn-sm">
                                            Catat Surat Masuk Baru
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($suratMasuk, 'hasPages') && $suratMasuk->hasPages())
            <div style="padding: 16px 22px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
                {{ $suratMasuk->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
