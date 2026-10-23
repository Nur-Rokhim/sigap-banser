@extends('layouts.app')

@section('title', 'Detail Notifikasi - SIGAP BANSER')

@push('styles')
<style>
    .notif-detail-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        max-width: 820px;
        margin: 0 auto;
    }

    .notif-detail-header {
        padding: 24px 28px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: flex-start;
        gap: 18px;
    }

    .notif-detail-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notif-detail-body {
        padding: 28px;
    }

    .notif-detail-pesan {
        font-size: 1rem;
        color: var(--ink);
        line-height: 1.7;
        background: var(--surface-alt);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        margin-bottom: 24px;
        white-space: pre-line;
    }

    .notif-detail-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .notif-meta-item {
        background: var(--surface-alt);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        padding: 12px 16px;
    }

    .notif-meta-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink-muted);
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .notif-meta-value {
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--ink);
    }

    .notif-detail-footer {
        padding: 18px 28px;
        border-top: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="page-container">

    {{-- Breadcrumb --}}
    <nav style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--ink-muted); margin-bottom: 20px;">
        <a href="{{ route('notifikasi.index') }}" style="color: var(--ink-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--ink-muted)'">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Notifikasi
        </a>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span style="color: var(--ink); font-weight: 600;">Detail Notifikasi</span>
    </nav>

    <div class="notif-detail-card">

        {{-- Header Card --}}
        @php
            $warna = $notif->warna_badge;
            $labelTipe = match($notif->tipe) {
                'surat_masuk_baru'       => 'Surat Masuk Baru',
                'surat_keluar_diajukan'  => 'Surat Keluar Diajukan',
                'sk_diajukan'            => 'SK Diajukan untuk Verifikasi',
                'sk_disetujui'           => 'SK Disetujui',
                'disposisi_baru'         => 'Disposisi Baru',
                'perlu_respon'           => 'Perlu Segera Direspon',
                'pengingat'              => 'Pengingat',
                default                  => 'Informasi',
            };
        @endphp

        <div class="notif-detail-header">
            <div class="notif-detail-icon" style="background: {{ $warna }}20;">
                @if($notif->tipe === 'surat_masuk_baru')
                    <svg width="28" height="28" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                @elseif($notif->tipe === 'disposisi_baru')
                    <svg width="28" height="28" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                @elseif(in_array($notif->tipe, ['sk_diajukan', 'sk_disetujui']))
                    <svg width="28" height="28" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                @elseif($notif->tipe === 'perlu_respon')
                    <svg width="28" height="28" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                @else
                    <svg width="28" height="28" fill="none" stroke="{{ $warna }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @endif
            </div>

            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                    <span style="display: inline-flex; padding: 3px 10px; background: {{ $warna }}18; color: {{ $warna }}; border: 1px solid {{ $warna }}33; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;">
                        {{ $labelTipe }}
                    </span>
                    @if($notif->dibaca)
                        <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; background: rgba(14,155,74,0.1); color: #0E9B4A; border: 1px solid rgba(14,155,74,0.3); border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Sudah Dibaca
                        </span>
                    @else
                        <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; background: rgba(229,35,43,0.1); color: #E5232B; border: 1px solid rgba(229,35,43,0.3); border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                            <svg width="8" height="8" viewBox="0 0 8 8" fill="#E5232B"><circle cx="4" cy="4" r="4"/></svg>
                            Belum Dibaca
                        </span>
                    @endif
                </div>
                <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--ink); margin: 0 0 4px 0; line-height: 1.35;">
                    {{ $notif->judul }}
                </h2>
                <div style="font-size: 0.82rem; color: var(--ink-muted);">
                    Diterima {{ $notif->created_at->translatedFormat('d F Y \p\u\k\u\l H:i') }}
                    ({{ $notif->created_at->diffForHumans() }})
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="notif-detail-body">

            {{-- Pesan Isi Notifikasi --}}
            <div style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); letter-spacing: 0.04em; margin-bottom: 8px;">
                Isi Pemberitahuan
            </div>
            <div class="notif-detail-pesan">{{ $notif->pesan }}</div>

            {{-- Metadata --}}
            <div style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); letter-spacing: 0.04em; margin-bottom: 10px;">
                Informasi Notifikasi
            </div>
            <div class="notif-detail-meta-grid">
                <div class="notif-meta-item">
                    <div class="notif-meta-label">Waktu Diterima</div>
                    <div class="notif-meta-value">{{ $notif->created_at->translatedFormat('d M Y, H:i') }} WIB</div>
                </div>

                @if($notif->pengirim)
                <div class="notif-meta-item">
                    <div class="notif-meta-label">Dipicu Oleh</div>
                    <div class="notif-meta-value">{{ $notif->pengirim->name }}</div>
                </div>
                @endif

                <div class="notif-meta-item">
                    <div class="notif-meta-label">Status</div>
                    <div class="notif-meta-value">
                        @if($notif->dibaca)
                            <span style="color: #0E9B4A;">Sudah Dibaca</span>
                            @if($notif->dibaca_pada)
                                — {{ $notif->dibaca_pada->translatedFormat('d M Y H:i') }}
                            @endif
                        @else
                            <span style="color: #E5232B;">Belum Dibaca</span>
                        @endif
                    </div>
                </div>

                @if($notif->entitas_tipe)
                <div class="notif-meta-item">
                    <div class="notif-meta-label">Terkait</div>
                    <div class="notif-meta-value">{{ ucfirst(str_replace('_', ' ', $notif->entitas_tipe)) }} #{{ $notif->entitas_id }}</div>
                </div>
                @endif
            </div>

            {{-- Tombol Tindakan --}}
            @if($notif->url_tujuan)
                <div style="background: linear-gradient(135deg, rgba(142,27,34,0.06) 0%, rgba(201,162,74,0.06) 100%); border: 1px solid rgba(142,27,34,0.15); border-radius: var(--radius-md); padding: 16px 20px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <div style="font-weight: 700; color: var(--ink); font-size: 0.95rem; margin-bottom: 2px;">Tindakan Diperlukan</div>
                        <div style="font-size: 0.85rem; color: var(--ink-muted);">Klik tombol di samping untuk membuka dokumen terkait dan segera merespon notifikasi ini.</div>
                    </div>
                    <a href="{{ $notif->url_tujuan }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; white-space: nowrap;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Buka Dokumen Terkait
                    </a>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="notif-detail-footer">
            <a href="{{ route('notifikasi.index') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Notifikasi
            </a>

            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                @if(!$notif->dibaca)
                    <form action="{{ route('notifikasi.tandai-baca', $notif->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Tandai Sudah Dibaca
                        </button>
                    </form>
                @endif

                <form action="{{ route('notifikasi.destroy', $notif->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Hapus notifikasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
