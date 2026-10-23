@extends('layouts.app')

@section('title', 'Buku Surat Keputusan (SK) - Satkorcab BANSER')

@push('styles')
<style>
    .page-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .sk-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) {
        .sk-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 500px) {
        .sk-stats-grid {
            grid-template-columns: 1fr;
        }
    }
    .sk-stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .sk-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .sk-stat-num {
        font-size: 1.8rem;
        font-weight: 800;
        font-family: 'Archivo', sans-serif;
        color: var(--accent);
        line-height: 1.1;
    }
    .sk-stat-label {
        font-size: 0.82rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--ink-muted);
        letter-spacing: 0.04em;
    }
    .filter-bar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }
    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        flex: 1;
    }
    .search-input-box {
        position: relative;
        min-width: 260px;
        flex: 1;
    }
    .search-input-box input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        background: var(--surface-alt);
        font-size: 0.88rem;
        color: var(--ink);
    }
    .search-input-box svg {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink-muted);
    }
</style>
@endpush

@section('content')
<div class="page-container">
    <!-- Header Row -->
    <div class="page-header-row">
        <div>
            <div style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: var(--gold); letter-spacing: 0.05em; margin-bottom: 4px;">
                Administrasi Hukum & Organisasi
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--ink); margin: 0;">
                Buku Surat Keputusan (SK)
            </h1>
            <p style="font-size: 0.88rem; color: var(--ink-muted); margin: 4px 0 0 0;">
                Satuan Koordinasi Cabang Barisan Ansor Serbaguna (Satkorcab BANSER) Kabupaten Blitar
            </p>
        </div>

        <a href="{{ route('surat-keputusan.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Buat Surat Keputusan (SK) Baru</span>
        </a>
    </div>

    <!-- Quick Stats -->
    @php
        $totalSk = \App\Models\SuratKeluar::where('jenis_surat_id', $jenisSk->id)->count();
        $totalDraf = \App\Models\SuratKeluar::where('jenis_surat_id', $jenisSk->id)->where('status', 'draf')->count();
        $totalVerifikasi = \App\Models\SuratKeluar::where('jenis_surat_id', $jenisSk->id)->where('status', 'verifikasi')->count();
        $totalDisetujui = \App\Models\SuratKeluar::where('jenis_surat_id', $jenisSk->id)->whereIn('status', ['disetujui', 'terkirim'])->count();
    @endphp

    <div class="sk-stats-grid">
        <div class="sk-stat-card">
            <span class="sk-stat-label">Total Surat Keputusan</span>
            <span class="sk-stat-num">{{ $totalSk }}</span>
        </div>
        <div class="sk-stat-card">
            <span class="sk-stat-label">Draf Disiapkan</span>
            <span class="sk-stat-num" style="color: #4b5563;">{{ $totalDraf }}</span>
        </div>
        <div class="sk-stat-card">
            <span class="sk-stat-label">Menunggu Verifikasi</span>
            <span class="sk-stat-num" style="color: #b45309;">{{ $totalVerifikasi }}</span>
        </div>
        <div class="sk-stat-card">
            <span class="sk-stat-label">Ditetapkan / Disetujui</span>
            <span class="sk-stat-num" style="color: #0e9b4a;">{{ $totalDisetujui }}</span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('surat-keputusan.index') }}" class="filter-bar">
        <div class="filter-group">
            <div class="search-input-box">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search', request('q')) }}" placeholder="Cari nomor SK, perihal penetapan, atau kata kunci...">
            </div>

            <select name="status" class="form-control" style="width: auto; padding: 8px 12px; font-size: 0.88rem;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="draf" {{ request('status') === 'draf' ? 'selected' : '' }}>Draf</option>
                <option value="verifikasi" {{ request('status') === 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui (Bernomor)</option>
                <option value="terkirim" {{ request('status') === 'terkirim' ? 'selected' : '' }}>Terkirim / Ditetapkan</option>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-secondary" style="padding: 8px 14px; font-size: 0.88rem;">
                Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('surat-keputusan.index') }}" class="btn btn-secondary" style="padding: 8px 14px; font-size: 0.88rem;">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Table of SK -->
    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 220px;">Nomor SK</th>
                        <th>Tentang / Pokok Keputusan</th>
                        <th style="width: 140px;">Tgl Penetapan</th>
                        <th style="width: 110px;">Status</th>
                        <th style="width: 170px; text-align: center;">Aksi</th>
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
                            <td>{{ $suratKeputusan->firstItem() + $index }}</td>
                            <td>
                                @if($skItem->nomor_surat)
                                    <div style="font-family: 'Archivo', monospace; font-weight: 700; color: var(--accent); font-size: 0.92rem;">
                                        {{ $skItem->nomor_surat }}
                                    </div>
                                @else
                                    <span class="badge badge-gray" style="font-size: 0.75rem;">
                                        DRAF (Belum Bernomor)
                                    </span>
                                @endif
                                <div style="font-size: 0.75rem; color: var(--ink-muted); margin-top: 2px;">
                                    Dibuat: {{ $skItem->pembuat->name ?? 'Admin' }}
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
                                <div style="font-weight: 600; color: var(--ink);">
                                    {{ \Carbon\Carbon::parse($skItem->tgl_surat)->translatedFormat('d M Y') }}
                                </div>
                                @if(!empty($skItem->detailSk->tgl_hijriyah))
                                    <div style="font-size: 0.75rem; color: var(--ink-muted);">
                                        {{ $skItem->detailSk->tgl_hijriyah }}
                                    </div>
                                @endif
                            </td>
                            <td>
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
                            <td>
                                <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                    <a href="{{ route('surat-keputusan.show', $skItem->id) }}" class="btn-icon btn-icon-detail" title="Lihat Detail & Pengesahan SK">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Detail</span>
                                    </a>

                                    <a href="{{ route('surat-keputusan.pdf', $skItem->id) }}" target="_blank" class="btn-icon" style="color: #8E1B22; border-color: #FECACA; background-color: #FEF2F2;" title="Cetak / Unduh PDF Resmi SK">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>PDF</span>
                                    </a>

                                    @if($st === 'draf' || in_array(strtolower(Auth::user()->role ?? ''), ['super_admin', 'admin_adminpers', 'admin', 'kasetma', 'kasatkorcab']))
                                        <a href="{{ route('surat-keputusan.edit', $skItem->id) }}" class="btn-icon btn-icon-edit" title="Edit SK">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px 16px; color: var(--ink-muted);">
                                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 12px; opacity: 0.4;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p style="font-size: 1rem; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Belum Ada Surat Keputusan</p>
                                <p style="font-size: 0.88rem; max-width: 460px; margin: 0 auto 16px auto;">Belum ada dokumen Surat Keputusan (SK) yang dicatat atau sesuai dengan kata kunci pencarian Anda.</p>
                                <a href="{{ route('surat-keputusan.create') }}" class="btn btn-primary btn-sm">
                                    + Buat Surat Keputusan Baru
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suratKeputusan->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
                {{ $suratKeputusan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
