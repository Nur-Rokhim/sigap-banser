@extends('layouts.app')

@section('title', 'Buku Registrasi Surat Keluar — SIGAP BANSER')

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

    /* ── Filter Card ── */
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
        min-width: 240px;
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
    $totalKeluar = \App\Models\SuratKeluar::count();
    $drafKeluar = \App\Models\SuratKeluar::where('status', 'draf')->count();
    $verifKeluar = \App\Models\SuratKeluar::where('status', 'verifikasi')->count();
    $terbitKeluar = \App\Models\SuratKeluar::whereIn('status', ['disetujui', 'terkirim'])->count();
@endphp

<div class="page-container">
    <!-- Header Banner -->
    <div class="page-hero">
        <div class="hero-flex">
            <div class="hero-title-group">
                <div class="hero-icon-box">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <div class="hero-text">
                    <h1>Buku Registrasi Surat Keluar</h1>
                    <p>Penomoran resmi otomatis, verifikasi digital pimpinan, dan distribusi surat dinas Satkorcab</p>
                </div>
            </div>
            <a href="{{ route('surat-keluar.create') }}" class="btn btn-gold btn-lg">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buat Surat Keluar</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stat-strip">
        <a href="{{ route('surat-keluar.index') }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--accent);">{{ $totalKeluar }}</div>
                <div class="stat-mini-label">Total Surat Keluar</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(142,27,34,0.1); color: var(--accent);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </a>

        <a href="{{ route('surat-keluar.index', ['status' => 'draf']) }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--gray);">{{ $drafKeluar }}</div>
                <div class="stat-mini-label">Draf Konseptual</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(107,114,128,0.1); color: var(--gray);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </a>

        <a href="{{ route('surat-keluar.index', ['status' => 'verifikasi']) }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: #B45309;">{{ $verifKeluar }}</div>
                <div class="stat-mini-label">Perlu Verifikasi</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(245,158,11,0.1); color: #B45309;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </a>

        <a href="{{ route('surat-keluar.index', ['status' => 'disetujui']) }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--green);">{{ $terbitKeluar }}</div>
                <div class="stat-mini-label">Disetujui / Terbit</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(14,155,74,0.1); color: var(--green);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </a>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form action="{{ route('surat-keluar.index') }}" method="GET" class="filter-form">
            <div class="search-box-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Cari nomor surat, tujuan, atau perihal..." 
                       value="{{ request('search') }}">
            </div>

            <div style="min-width: 150px;">
                <select name="jenis" class="form-control">
                    <option value="">Semua Jenis</option>
                    <option value="SU" {{ request('jenis') === 'SU' ? 'selected' : '' }}>SU (Undangan)</option>
                    <option value="SPH" {{ request('jenis') === 'SPH' ? 'selected' : '' }}>SPH (Perintah Harian)</option>
                    <option value="ST" {{ request('jenis') === 'ST' ? 'selected' : '' }}>ST (Surat Tugas)</option>
                    <option value="SK" {{ request('jenis') === 'SK' ? 'selected' : '' }}>SK (Surat Keputusan)</option>
                </select>
            </div>

            <div style="min-width: 160px;">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="draf" {{ request('status') === 'draf' ? 'selected' : '' }}>Draf</option>
                    <option value="verifikasi" {{ request('status') === 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="terkirim" {{ request('status') === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Filter</span>
            </button>

            @if(request('search') || request('jenis') || request('status'))
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary" title="Reset filter">
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
                <span>Daftar Registrasi Surat Keluar</span>
            </h3>
            <span style="font-size: 0.8rem; color: var(--ink-muted);">
                Menampilkan {{ $suratKeluar->count() }} dokumen
            </span>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th style="width: 220px;">Nomor Surat Resmi</th>
                        <th style="width: 140px;">Jenis Surat</th>
                        <th style="width: 200px;">Acuan Surat Masuk</th>
                        <th>Perihal & Tujuan</th>
                        <th style="width: 110px;">Tgl Surat</th>
                        <th style="width: 110px; text-align: center;">Status</th>
                        <th style="width: 170px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratKeluar as $index => $item)
                        <tr>
                            <td style="text-align: center; color: var(--ink-muted); font-weight: 600;">
                                {{ method_exists($suratKeluar, 'firstItem') && $suratKeluar->firstItem() ? $suratKeluar->firstItem() + $index : $index + 1 }}
                            </td>
                            <td>
                                @if(!empty($item->nomor_surat))
                                    <strong style="font-family: 'Archivo', sans-serif; font-size: 0.92rem; color: var(--accent);">
                                        {{ $item->nomor_surat }}
                                    </strong>
                                @else
                                    <span style="font-style: italic; color: var(--ink-muted); font-size: 0.85rem; background: var(--surface-alt); padding: 2px 7px; border-radius: 4px; border: 1px dashed var(--border);">
                                        [Draf - Belum Terbit]
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $j = strtoupper($item->jenis_surat ?? $item->jenis->kode ?? $item->jenis ?? 'SU');
                                @endphp
                                @if($j === 'SU')
                                    <span class="badge badge-blue">SU (Undangan)</span>
                                @elseif($j === 'SPH')
                                    <span class="badge badge-gold">SPH (Harian)</span>
                                @elseif($j === 'ST')
                                    <span class="badge badge-purple">ST (Tugas)</span>
                                @elseif($j === 'SK')
                                    <span class="badge badge-green">SK (Keputusan)</span>
                                @else
                                    <span class="badge badge-gray">{{ $j }}</span>
                                @endif
                            </td>
                            <td>
                                @if($item->suratMasuk)
                                    <div style="font-size: 0.82rem; line-height: 1.35;">
                                        <div style="font-weight: 700; color: var(--ink); display: flex; align-items: center; gap: 4px;">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent); flex-shrink: 0;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span title="Pemohon: {{ $item->suratMasuk->dari }}">{{ Str::limit($item->suratMasuk->dari, 22) }}</span>
                                        </div>
                                        <span class="badge badge-red" style="font-size: 0.68rem; padding: 1px 6px; margin-top: 3px;">
                                            No: {{ Str::limit($item->suratMasuk->no_surat, 18) }}
                                        </span>
                                    </div>
                                @else
                                    <span style="color: var(--ink-muted); font-size: 0.8rem; font-style: italic;">
                                        - Tanpa Acuan -
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--ink);">{{ $item->perihal ?? '-' }}</div>
                                <small style="color: var(--ink-muted); font-size: 0.8rem;">Tujuan: <strong>{{ $item->tujuan ?? '-' }}</strong></small>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--ink);">
                                    {{ isset($item->tanggal_surat) ? \Carbon\Carbon::parse($item->tanggal_surat)->format('d/m/Y') : '-' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                @php
                                    $st = strtolower($item->status ?? 'draf');
                                @endphp
                                @if($st === 'draf')
                                    <span class="badge badge-gray">Draf</span>
                                @elseif($st === 'verifikasi')
                                    <span class="badge badge-yellow">Verifikasi</span>
                                @elseif($st === 'disetujui')
                                    <span class="badge badge-green">Disetujui</span>
                                @elseif($st === 'terkirim')
                                    <span class="badge badge-blue">Terkirim</span>
                                @else
                                    <span class="badge badge-gray">{{ ucfirst($st) }}</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end;">
                                    <!-- Detail -->
                                    <a href="{{ route('surat-keluar.show', $item->id) }}" class="btn-icon btn-icon-detail" title="Lihat detail surat keluar">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span>Detail</span>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('surat-keluar.edit', $item->id) }}" class="btn-icon btn-icon-edit" title="Edit surat keluar">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Delete -->
                                    <button type="button" 
                                            class="btn-icon btn-icon-delete" 
                                            title="Hapus surat keluar"
                                            onclick="openDeleteModal('{{ route('surat-keluar.destroy', $item->id) }}', 'Surat Keluar {{ $item->nomor_surat ?: ('Draf #' . $item->id) }} (Perihal: {{ addslashes($item->perihal) }})')">
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
                            <td colspan="8" style="text-align: center; padding: 48px 16px;">
                                <div style="color: var(--ink-muted);">
                                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 12px; opacity: 0.4;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    <p style="font-weight: 700; font-size: 1rem; color: var(--ink);">Tidak ada data surat keluar ditemukan.</p>
                                    <p style="font-size: 0.85rem; margin-top: 4px;">Klik tombol "Buat Surat Keluar" untuk menerbitkan dokumen resmi baru.</p>
                                    <div style="margin-top: 16px;">
                                        <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary btn-sm">
                                            Buat Surat Keluar Baru
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($suratKeluar, 'hasPages') && $suratKeluar->hasPages())
            <div style="padding: 16px 22px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
                {{ $suratKeluar->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
