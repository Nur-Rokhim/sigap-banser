@extends('layouts.app')

@section('title', 'Data Anggota BANSER - Satkorcab Kab. Blitar')

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
    .anggota-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) {
        .anggota-stats-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .anggota-stats-grid { grid-template-columns: 1fr; }
    }
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-info { flex: 1; }
    .stat-num {
        font-size: 1.75rem;
        font-weight: 800;
        font-family: 'Archivo', sans-serif;
        line-height: 1;
    }
    .stat-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--ink-muted);
        letter-spacing: 0.04em;
        margin-top: 3px;
    }

    .filter-bar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .search-input-box {
        position: relative;
        min-width: 240px;
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
        width: 16px;
        height: 16px;
    }

    .anggota-table-wrapper {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .anggota-table {
        width: 100%;
        border-collapse: collapse;
    }
    .anggota-table thead th {
        background: var(--surface-alt);
        padding: 12px 16px;
        text-align: left;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-muted);
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    .anggota-table tbody tr {
        border-bottom: 1px solid var(--border-light);
        transition: background-color 0.15s ease;
    }
    .anggota-table tbody tr:hover {
        background-color: var(--surface-alt);
    }
    .anggota-table tbody tr:last-child {
        border-bottom: none;
    }
    .anggota-table td {
        padding: 13px 16px;
        font-size: 0.9rem;
        color: var(--ink);
        vertical-align: middle;
    }
    .anggota-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--hero1), var(--red));
        color: #fff;
        font-weight: 800;
        font-size: 0.88rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .anggota-name-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .anggota-name-text {
        font-weight: 700;
        color: var(--ink);
    }
    .anggota-nia-text {
        font-size: 0.78rem;
        color: var(--ink-muted);
        margin-top: 1px;
    }
    .action-cell {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .action-btn {
        width: 30px;
        height: 30px;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--ink-muted);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .action-btn:hover {
        background: var(--surface-alt);
        color: var(--ink);
    }
    .action-btn.edit:hover {
        color: var(--gold);
        border-color: var(--gold);
    }
    .action-btn.delete:hover {
        color: var(--red);
        border-color: rgba(229,35,43,0.4);
        background: rgba(229,35,43,0.06);
    }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: var(--ink-muted);
    }
    .empty-state svg {
        width: 56px;
        height: 56px;
        margin: 0 auto 16px;
        opacity: 0.35;
    }
    .empty-state h3 {
        font-size: 1.1rem;
        margin-bottom: 6px;
        color: var(--ink-muted);
    }
</style>
@endpush

@section('content')
<div class="page-container">
    {{-- Page Header --}}
    <div class="page-header-row">
        <div>
            <h2 style="margin:0; font-size:1.45rem; font-weight:800; color:var(--ink);">Data Anggota BANSER</h2>
            <p style="margin:4px 0 0; color:var(--ink-muted); font-size:0.88rem;">
                Direktori anggota Satkorcab Kab. Blitar — referensi penugasan (ST/SPH)
            </p>
        </div>
        <a href="{{ route('anggota.create') }}" class="btn btn-primary" id="btn-tambah-anggota">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Tambah Anggota</span>
        </a>
    </div>

    {{-- Stats --}}
    <div class="anggota-stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(142,27,34,0.1);">
                <svg width="22" height="22" fill="none" stroke="var(--accent)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-num" style="color:var(--accent);">{{ App\Models\Anggota::count() }}</div>
                <div class="stat-label">Total Anggota Terdaftar</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(14,155,74,0.1);">
                <svg width="22" height="22" fill="none" stroke="var(--green)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-num" style="color:var(--green);">{{ App\Models\Anggota::whereNotNull('satuan_id')->count() }}</div>
                <div class="stat-label">Anggota Bertugas di Satuan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(201,162,74,0.1);">
                <svg width="22" height="22" fill="none" stroke="var(--gold)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-num" style="color:var(--gold);">{{ App\Models\PenerimaTugas::distinct('anggota_id')->count() }}</div>
                <div class="stat-label">Pernah Menerima Surat Tugas</div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <form method="GET" action="{{ route('anggota.index') }}" class="filter-bar" id="form-filter-anggota">
        <div class="search-input-box">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="search" id="input-search-anggota"
                   value="{{ request('search') }}"
                   placeholder="Cari nama, NIA, atau jabatan…"
                   autocomplete="off">
        </div>
        <select name="satuan_id" id="select-satuan-filter" class="form-control" style="min-width:180px; max-width:240px; padding: 9px 12px; border-radius:var(--radius-sm); border:1px solid var(--border); background:var(--surface-alt); font-size:0.88rem; color:var(--ink);" onchange="this.form.submit()">
            <option value="">Semua Satuan</option>
            @foreach($satuanList as $sat)
                <option value="{{ $sat->id }}" {{ request('satuan_id') == $sat->id ? 'selected' : '' }}>
                    {{ $sat->nama }} ({{ $sat->tingkat }})
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary" style="padding: 9px 18px;" id="btn-cari-anggota">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            Cari
        </button>
        @if(request('search') || request('satuan_id'))
            <a href="{{ route('anggota.index') }}" class="btn btn-secondary" style="padding: 9px 16px;" id="btn-reset-filter">Reset</a>
        @endif
    </form>

    {{-- Tabel Daftar Anggota --}}
    <div class="anggota-table-wrapper">
        @if($anggota->count() > 0)
            <div style="overflow-x: auto;">
                <table class="anggota-table">
                    <thead>
                        <tr>
                            <th style="width: 44px; text-align:center;">#</th>
                            <th>Nama Anggota</th>
                            <th>Jabatan</th>
                            <th>Satuan</th>
                            <th style="width: 100px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anggota as $item)
                        <tr>
                            <td style="text-align:center; color:var(--ink-muted); font-size:0.82rem;">
                                {{ $anggota->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div class="anggota-name-cell">
                                    <div class="anggota-avatar">
                                        {{ strtoupper(substr($item->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="anggota-name-text">{{ $item->nama }}</div>
                                        @if($item->nia)
                                            <div class="anggota-nia-text">NIA: {{ $item->nia }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->jabatan ?? '-' }}</td>
                            <td>
                                @if($item->satuan)
                                    <span class="badge badge-gray">{{ $item->satuan->nama }}</span>
                                @else
                                    <span style="color:var(--ink-muted); font-size:0.82rem;">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-cell" style="justify-content: center;">
                                    <a href="{{ route('anggota.edit', $item->id) }}"
                                       class="action-btn edit"
                                       title="Edit data anggota">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('anggota.destroy', $item->id) }}"
                                          style="margin:0;"
                                          onsubmit="return confirm('Hapus anggota {{ addslashes($item->nama) }}? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Hapus anggota">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginasi --}}
            @if($anggota->hasPages())
                <div style="padding: 16px 20px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <span style="font-size:0.85rem; color:var(--ink-muted);">
                        Menampilkan {{ $anggota->firstItem() }}–{{ $anggota->lastItem() }} dari {{ $anggota->total() }} anggota
                    </span>
                    <div style="display:flex; gap:6px;">
                        @if($anggota->onFirstPage())
                            <span class="btn btn-secondary" style="opacity:0.5; cursor:not-allowed; padding: 6px 12px; font-size:0.82rem;">‹ Sebelumnya</span>
                        @else
                            <a href="{{ $anggota->previousPageUrl() }}" class="btn btn-secondary" style="padding: 6px 12px; font-size:0.82rem;">‹ Sebelumnya</a>
                        @endif
                        @if($anggota->hasMorePages())
                            <a href="{{ $anggota->nextPageUrl() }}" class="btn btn-secondary" style="padding: 6px 12px; font-size:0.82rem;">Berikutnya ›</a>
                        @else
                            <span class="btn btn-secondary" style="opacity:0.5; cursor:not-allowed; padding: 6px 12px; font-size:0.82rem;">Berikutnya ›</span>
                        @endif
                    </div>
                </div>
            @endif

        @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h3>Belum Ada Data Anggota</h3>
                <p style="font-size:0.88rem; margin-bottom:18px;">
                    @if(request('search') || request('satuan_id'))
                        Tidak ditemukan anggota yang cocok dengan filter pencarian.
                        <a href="{{ route('anggota.index') }}" style="color:var(--accent);">Hapus filter</a>
                    @else
                        Mulai tambahkan data anggota BANSER untuk digunakan pada Surat Tugas dan SPH.
                    @endif
                </p>
                @if(!request('search') && !request('satuan_id'))
                    <a href="{{ route('anggota.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Anggota Pertama
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
