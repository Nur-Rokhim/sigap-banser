@extends('layouts.app')

@section('title', 'Buku Surat Keputusan (SK) — SIGAP BANSER')

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
    $totalSk = \App\Models\SuratKeluar::where('jenis_surat_id', $jenisSk->id)->count();
    $totalDraf = \App\Models\SuratKeluar::where('jenis_surat_id', $jenisSk->id)->where('status', 'draf')->count();
    $totalVerifikasi = \App\Models\SuratKeluar::where('jenis_surat_id', $jenisSk->id)->where('status', 'verifikasi')->count();
    $totalDisetujui = \App\Models\SuratKeluar::where('jenis_surat_id', $jenisSk->id)->whereIn('status', ['disetujui', 'terkirim'])->count();
@endphp

<div class="page-container">
    <!-- Header Banner -->
    <div class="page-hero">
        <div class="hero-flex">
            <div class="hero-title-group">
                <div class="hero-icon-box">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="hero-text">
                    <h1>Buku Surat Keputusan (SK)</h1>
                    <p>Dokumen hukum kedinasan, penetapan struktur, dan instruksi definitif Komandan Satkorcab</p>
                </div>
            </div>
            <a href="{{ route('surat-keputusan.create') }}" class="btn btn-gold btn-lg">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buat SK Baru</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stat-strip">
        <a href="{{ route('surat-keputusan.index') }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--accent);">{{ $totalSk }}</div>
                <div class="stat-mini-label">Total SK Diterbitkan</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(142,27,34,0.1); color: var(--accent);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </a>

        <a href="{{ route('surat-keputusan.index', ['status' => 'draf']) }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--gray);">{{ $totalDraf }}</div>
                <div class="stat-mini-label">Draf Disiapkan</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(107,114,128,0.1); color: var(--gray);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </a>

        <a href="{{ route('surat-keputusan.index', ['status' => 'verifikasi']) }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: #B45309;">{{ $totalVerifikasi }}</div>
                <div class="stat-mini-label">Menunggu Verifikasi</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(245,158,11,0.1); color: #B45309;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </a>

        <a href="{{ route('surat-keputusan.index', ['status' => 'disetujui']) }}" class="stat-mini-card">
            <div>
                <div class="stat-mini-num" style="color: var(--green);">{{ $totalDisetujui }}</div>
                <div class="stat-mini-label">Ditetapkan / Disetujui</div>
            </div>
            <div class="stat-mini-icon" style="background: rgba(14,155,74,0.1); color: var(--green);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </a>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('surat-keputusan.index') }}" class="filter-form">
            <div class="search-box-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Cari nomor SK, pokok penetapan, atau kata kunci..." 
                       value="{{ request('search', request('q')) }}">
            </div>

            <div style="min-width: 170px;">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="draf" {{ request('status') === 'draf' ? 'selected' : '' }}>Draf</option>
                    <option value="verifikasi" {{ request('status') === 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui (Bernomor)</option>
                    <option value="terkirim" {{ request('status') === 'terkirim' ? 'selected' : '' }}>Terkirim / Ditetapkan</option>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Filter</span>
            </button>

            @if(request('search') || request('status'))
                <a href="{{ route('surat-keputusan.index') }}" class="btn btn-secondary" title="Reset filter">
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
                <span>Daftar Dokumen Surat Keputusan (SK)</span>
            </h3>
            <span style="font-size: 0.8rem; color: var(--ink-muted);">
                Menampilkan {{ $suratKeputusan->count() }} ketetapan
            </span>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th style="width: 220px;">Nomor SK</th>
                        <th>Tentang / Pokok Keputusan</th>
                        <th style="width: 140px;">Tgl Penetapan</th>
                        <th style="width: 120px; text-align: center;">Status</th>
                        <th style="width: 180px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratKeputusan as $index => $skItem)
                        @php
                            $st = strtolower($skItem->status ?? 'draf');
                            $tentang = $skItem->detailSk->tentang ?? $skItem->perihal;
                            $tentangClean = str_starts_with(strtoupper($tentang), 'SURAT KEPUTUSAN TENTANG ') 
                                ? substr($tentang, 24) 
                                : $tentang;
                        @endphp
                        <tr>
                            <td style="text-align: center; color: var(--ink-muted); font-weight: 600;">
                                {{ method_exists($suratKeputusan, 'firstItem') && $suratKeputusan->firstItem() ? $suratKeputusan->firstItem() + $index : $index + 1 }}
                            </td>
                            <td>
                                @if(!empty($skItem->nomor_surat))
                                    <strong style="font-family: 'Archivo', sans-serif; font-size: 0.92rem; color: var(--accent);">
                                        {{ $skItem->nomor_surat }}
                                    </strong>
                                @else
                                    <span style="font-style: italic; color: var(--ink-muted); font-size: 0.85rem; background: var(--surface-alt); padding: 2px 7px; border-radius: 4px; border: 1px dashed var(--border);">
                                        [Draf - Belum Bernomor]
                                    </span>
                                @endif
                                <div style="font-size: 0.76rem; color: var(--ink-muted); margin-top: 3px;">
                                    Dibuat: {{ $skItem->pembuat->name ?? 'Adminpers' }}
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('surat-keputusan.show', $skItem->id) }}" style="font-weight: 700; color: var(--ink); text-decoration: none;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--ink)'">
                                    {{ $tentangClean }}
                                </a>
                                @if($skItem->suratMasuk)
                                    <div style="font-size: 0.78rem; color: var(--ink-muted); margin-top: 3px;">
                                        Rujukan: {{ $skItem->suratMasuk->no_surat }} ({{ $skItem->suratMasuk->dari }})
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--ink); font-size: 0.88rem;">
                                    {{ \Carbon\Carbon::parse($skItem->tgl_surat)->translatedFormat('d M Y') }}
                                </div>
                                @if(!empty($skItem->detailSk->tgl_hijriyah))
                                    <div style="font-size: 0.75rem; color: var(--ink-muted);">
                                        {{ $skItem->detailSk->tgl_hijriyah }}
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($st === 'draf')
                                    <span class="badge badge-gray">Draf</span>
                                @elseif($st === 'verifikasi')
                                    <span class="badge badge-yellow">Verifikasi</span>
                                @elseif($st === 'disetujui')
                                    <span class="badge badge-green">Disetujui</span>
                                @elseif($st === 'terkirim')
                                    <span class="badge badge-blue">Ditetapkan</span>
                                @else
                                    <span class="badge badge-gray">{{ ucfirst($st) }}</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end;">
                                    <!-- Detail -->
                                    <a href="{{ route('surat-keputusan.show', $skItem->id) }}" class="btn-icon btn-icon-detail" title="Lihat Detail & Pengesahan SK">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Detail</span>
                                    </a>

                                    <!-- PDF -->
                                    <a href="{{ route('surat-keputusan.pdf', $skItem->id) }}" target="_blank" class="btn-icon btn-icon-print" title="Cetak / Unduh PDF Resmi SK">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>PDF</span>
                                    </a>

                                    <!-- Edit -->
                                    @if($st === 'draf' || in_array(strtolower(Auth::user()->role ?? ''), ['super_admin', 'admin_adminpers', 'admin', 'kasetma', 'kasatkorcab']))
                                        <a href="{{ route('surat-keputusan.edit', $skItem->id) }}" class="btn-icon btn-icon-edit" title="Edit SK">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px 16px;">
                                <div style="color: var(--ink-muted);">
                                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 12px; opacity: 0.4;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p style="font-weight: 700; font-size: 1rem; color: var(--ink);">Tidak ada dokumen Surat Keputusan ditemukan.</p>
                                    <p style="font-size: 0.85rem; margin-top: 4px;">Klik tombol di bawah untuk membuat Surat Keputusan (SK) baru.</p>
                                    <div style="margin-top: 16px;">
                                        <a href="{{ route('surat-keputusan.create') }}" class="btn btn-primary btn-sm">
                                            Buat Surat Keputusan Baru
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($suratKeputusan, 'hasPages') && $suratKeputusan->hasPages())
            <div style="padding: 16px 22px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
                {{ $suratKeputusan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
