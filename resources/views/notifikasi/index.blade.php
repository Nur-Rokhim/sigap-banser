@extends('layouts.app')

@section('title', 'Notifikasi - SIGAP BANSER')

@push('styles')
<style>
    .notif-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .notif-filter-bar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 12px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 0;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        background: var(--surface);
        box-shadow: var(--shadow-sm);
    }

    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-light);
        text-decoration: none;
        color: inherit;
        transition: background-color 0.18s ease;
        position: relative;
        cursor: pointer;
    }
    .notif-item:last-child {
        border-bottom: none;
    }
    .notif-item:hover {
        background-color: var(--surface-alt);
    }
    .notif-item.belum-dibaca {
        background-color: rgba(142, 27, 34, 0.035);
    }
    .notif-item.belum-dibaca:hover {
        background-color: rgba(142, 27, 34, 0.07);
    }

    /* Titik indikator belum dibaca */
    .notif-unread-dot {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--red);
        flex-shrink: 0;
    }

    .notif-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-left: 6px;
    }

    .notif-body {
        flex: 1;
        min-width: 0;
    }

    .notif-judul {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--ink);
        margin-bottom: 4px;
        line-height: 1.4;
    }
    .notif-item.belum-dibaca .notif-judul {
        color: var(--accent);
    }

    .notif-pesan {
        font-size: 0.87rem;
        color: var(--ink-muted);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .notif-meta {
        font-size: 0.78rem;
        color: var(--ink-muted);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .notif-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        flex-shrink: 0;
    }

    .notif-time {
        font-size: 0.76rem;
        color: var(--ink-muted);
        white-space: nowrap;
    }

    .badge-tipe {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .notif-empty {
        text-align: center;
        padding: 64px 24px;
        color: var(--ink-muted);
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }
    @media (max-width: 600px) {
        .stats-row { grid-template-columns: 1fr; }
    }

    .stat-mini {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: var(--shadow-sm);
    }

    .stat-mini-num {
        font-size: 1.6rem;
        font-weight: 800;
        font-family: 'Archivo', sans-serif;
        line-height: 1;
    }

    .stat-mini-label {
        font-size: 0.8rem;
        color: var(--ink-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
</style>
@endpush

@section('content')
<div class="page-container">

    {{-- Header --}}
    <div class="notif-page-header">
        <div>
            <div style="font-size: 0.82rem; font-weight: 700; text-transform: uppercase; color: var(--gold); letter-spacing: 0.05em; margin-bottom: 4px;">
                Pusat Pemberitahuan
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--ink); margin: 0;">
                Notifikasi
            </h1>
            <p style="font-size: 0.88rem; color: var(--ink-muted); margin: 4px 0 0;">
                Pemberitahuan dan informasi penting yang memerlukan respons Anda
            </p>
        </div>

        @if($totalBelumDibaca > 0)
        <form action="{{ route('notifikasi.tandai-semua-baca') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Tandai Semua Sudah Dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- Stats Mini --}}
    @php
        $userId = Auth::id();
        $totalNotif     = \App\Models\Notifikasi::untukUser($userId)->count();
        $totalBelumBaca = \App\Models\Notifikasi::untukUser($userId)->belumDibaca()->count();
        $totalSudahBaca = $totalNotif - $totalBelumBaca;
    @endphp
    <div class="stats-row">
        <div class="stat-mini">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(142,27,34,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" fill="none" stroke="#8E1B22" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div>
                <div class="stat-mini-num" style="color: var(--accent);">{{ $totalNotif }}</div>
                <div class="stat-mini-label">Total Notifikasi</div>
            </div>
        </div>
        <div class="stat-mini">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(229,35,43,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" fill="none" stroke="#E5232B" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="stat-mini-num" style="color: #E5232B;">{{ $totalBelumBaca }}</div>
                <div class="stat-mini-label">Belum Dibaca</div>
            </div>
        </div>
        <div class="stat-mini">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(14,155,74,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" fill="none" stroke="#0E9B4A" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="stat-mini-num" style="color: #0E9B4A;">{{ $totalSudahBaca }}</div>
                <div class="stat-mini-label">Sudah Dibaca</div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('notifikasi.index') }}" class="notif-filter-bar">
        <select name="status" class="form-control" style="width: auto; padding: 8px 12px; font-size: 0.88rem;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum Dibaca</option>
            <option value="sudah" {{ request('status') === 'sudah' ? 'selected' : '' }}>Sudah Dibaca</option>
        </select>

        <select name="tipe" class="form-control" style="width: auto; padding: 8px 12px; font-size: 0.88rem;" onchange="this.form.submit()">
            <option value="">Semua Jenis</option>
            <option value="surat_masuk_baru"      {{ request('tipe') === 'surat_masuk_baru' ? 'selected' : '' }}>Surat Masuk Baru</option>
            <option value="surat_keluar_diajukan"  {{ request('tipe') === 'surat_keluar_diajukan' ? 'selected' : '' }}>Surat Keluar Diajukan</option>
            <option value="sk_diajukan"            {{ request('tipe') === 'sk_diajukan' ? 'selected' : '' }}>SK Diajukan</option>
            <option value="sk_disetujui"           {{ request('tipe') === 'sk_disetujui' ? 'selected' : '' }}>SK Disetujui</option>
            <option value="disposisi_baru"         {{ request('tipe') === 'disposisi_baru' ? 'selected' : '' }}>Disposisi Baru</option>
            <option value="perlu_respon"           {{ request('tipe') === 'perlu_respon' ? 'selected' : '' }}>Perlu Respon</option>
        </select>

        @if(request('status') || request('tipe'))
            <a href="{{ route('notifikasi.index') }}" class="btn btn-secondary btn-sm">Reset Filter</a>
        @endif
    </form>

    {{-- List Notifikasi --}}
    @if($notifikasi->isEmpty())
        <div class="notif-empty">
            <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 16px; opacity: 0.3; display: block; margin-left: auto; margin-right: auto;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p style="font-size: 1rem; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Tidak Ada Notifikasi</p>
            <p style="font-size: 0.88rem; max-width: 380px; margin: 0 auto;">
                Belum ada notifikasi yang perlu Anda perhatikan saat ini. Sistem akan menginformasikan setiap ada aktivitas penting.
            </p>
        </div>
    @else
        <div class="notif-list">
            @foreach($notifikasi as $notif)
                @php
                    $sudahBaca = $notif->dibaca;
                    $warna = $notif->warna_badge;
                    $labelTipe = match($notif->tipe) {
                        'surat_masuk_baru'       => 'Surat Masuk',
                        'surat_keluar_diajukan'  => 'Surat Keluar',
                        'sk_diajukan'            => 'SK Diajukan',
                        'sk_disetujui'           => 'SK Disetujui',
                        'disposisi_baru'         => 'Disposisi',
                        'perlu_respon'           => 'Perlu Respon',
                        'pengingat'              => 'Pengingat',
                        default                  => 'Info',
                    };
                @endphp
                <div class="notif-item {{ !$sudahBaca ? 'belum-dibaca' : '' }}" onclick="window.location='{{ route('notifikasi.show', $notif->id) }}'">
                    @if(!$sudahBaca)
                        <div class="notif-unread-dot"></div>
                    @endif

                    {{-- Icon --}}
                    <div class="notif-icon-wrap" style="background: {{ $warna }}20;">
                        @if($notif->tipe === 'surat_masuk_baru')
                            <svg width="22" height="22" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        @elseif($notif->tipe === 'disposisi_baru')
                            <svg width="22" height="22" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        @elseif(in_array($notif->tipe, ['sk_diajukan', 'sk_disetujui']))
                            <svg width="22" height="22" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @elseif($notif->tipe === 'perlu_respon')
                            <svg width="22" height="22" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        @else
                            <svg width="22" height="22" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="notif-body">
                        <div class="notif-judul">{{ $notif->judul }}</div>
                        <div class="notif-pesan">{{ $notif->pesan }}</div>
                        <div class="notif-meta">
                            <span class="badge-tipe" style="background: {{ $warna }}18; color: {{ $warna }}; border: 1px solid {{ $warna }}33;">
                                {{ $labelTipe }}
                            </span>
                            @if($notif->pengirim)
                                <span style="display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $notif->pengirim->name }}
                                </span>
                            @endif
                            @if($sudahBaca)
                                <span style="color: #0E9B4A; display: inline-flex; align-items: center; gap: 3px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Dibaca
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="notif-actions">
                        <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                        @if(!$sudahBaca)
                            <form action="{{ route('notifikasi.tandai-baca', $notif->id) }}" method="POST" onclick="event.stopPropagation();" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn-icon" title="Tandai sudah dibaca" style="font-size: 0.75rem; padding: 3px 8px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Baca
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($notifikasi->hasPages())
            <div style="margin-top: 20px;">
                {{ $notifikasi->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
