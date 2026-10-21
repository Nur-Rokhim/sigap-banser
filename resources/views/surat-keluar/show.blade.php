@extends('layouts.app')

@section('title', 'Detail Surat Keluar - ' . ($suratKeluar->nomor_surat ?? 'Draf'))

@push('styles')
<style>
    .meta-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .meta-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-muted);
    }

    .meta-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--ink);
    }

    .detail-section-box {
        background-color: var(--surface-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 20px;
        margin-top: 20px;
    }

    .detail-section-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 14px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .actions-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 20px;
        border-top: 1px solid var(--border-light);
        margin-top: 24px;
    }
</style>
@endpush

@section('content')
@php
    $item = $suratKeluar ?? $surat;
    $st = strtolower($item->status ?? 'draf');
    $jenis = strtoupper($item->jenis_surat ?? $item->jenis ?? 'SU');

    $userRole = Auth::user()->role ?? '';
    $isPimpinan = in_array(strtolower($userRole), ['pimpinan', 'admin_adminpers', 'kasatkorcab', 'admin', 'super_admin', 'kasetma']);
@endphp

<div class="page-container" style="max-width: 1040px; margin: 0 auto;">
    <!-- Breadcrumb & Top Bar -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <a href="{{ route('surat-keluar.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.88rem; margin-bottom: 8px; color: var(--ink-muted);">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Buku Registrasi Surat Keluar
            </a>
            <h2>Detail Surat Keluar</h2>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('surat-keluar.pdf', $item->id) }}" target="_blank" class="btn btn-secondary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Unduh / Cetak PDF</span>
            </a>
            @if(Route::has('surat-keluar.edit') && ($st === 'draf' || $isPimpinan))
                <a href="{{ route('surat-keluar.edit', $item->id) }}" class="btn btn-secondary">
                    <span>Edit Data</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="card">
        <div class="card-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="card-title">Lembar Register Surat Keluar</span>
                @if($jenis === 'SU')
                    <span class="badge" style="background-color: rgba(90, 14, 20, 0.1); color: var(--hero1);">SU - Undangan</span>
                @elseif($jenis === 'SPH')
                    <span class="badge" style="background-color: rgba(201, 162, 74, 0.15); color: #8A6516;">SPH - Perintah Harian</span>
                @elseif($jenis === 'ST')
                    <span class="badge" style="background-color: rgba(14, 155, 74, 0.12); color: var(--green);">ST - Tugas</span>
                @endif
            </div>

            <div>
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
            </div>
        </div>

        <div class="card-body">
            <!-- Maroon Box with Yellow Digits (Surat Number Box if assigned) -->
            @if(!empty($item->nomor_surat))
                <div style="margin-bottom: 24px;">
                    <div class="surat-number-box" style="width: 100%;">
                        <span class="label">Nomor Resmi Surat Keluar</span>
                        <span class="number">{{ $item->nomor_surat }}</span>
                    </div>
                </div>
            @else
                <div style="margin-bottom: 24px; padding: 14px 18px; background-color: var(--surface-alt); border: 1.5px dashed var(--border); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="22" height="22" fill="none" stroke="var(--ink-muted)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <strong style="font-size: 0.92rem;">Nomor surat belum diterbitkan</strong>
                            <div style="font-size: 0.8rem; color: var(--ink-muted);">Dokumen berstatus draf atau sedang dalam antrean verifikasi pimpinan.</div>
                        </div>
                    </div>
                    <span class="badge badge-gray">DRAF SURAT</span>
                </div>
            @endif

            <!-- Metadata Grid -->
            <div class="meta-grid">
                <div class="meta-item">
                    <span class="meta-label">Tujuan / Kepada Yth.</span>
                    <span class="meta-value">{{ $item->tujuan ?? '-' }}</span>
                </div>

                <div class="meta-item">
                    <span class="meta-label">Tanggal Surat</span>
                    <span class="meta-value">
                        {{ isset($item->tanggal_surat) ? \Carbon\Carbon::parse($item->tanggal_surat)->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>

                <div class="meta-item">
                    <span class="meta-label">Dibuat Oleh</span>
                    <span class="meta-value">{{ $item->pembuat->name ?? $item->user->name ?? 'Adminpers' }}</span>
                </div>

                <div class="meta-item">
                    <span class="meta-label">Tanggal Dibuat</span>
                    <span class="meta-value">
                        {{ isset($item->created_at) ? \Carbon\Carbon::parse($item->created_at)->translatedFormat('d/m/Y H:i') : '-' }}
                    </span>
                </div>
            </div>

            <!-- Acuan Surat Masuk Box (Jika ada rujukan pemohon & perihal) -->
            @if($item->suratMasuk)
                <div style="margin-top: 16px; padding: 14px 16px; background: rgba(90, 14, 20, 0.04); border: 1px solid rgba(90, 14, 20, 0.2); border-radius: var(--radius-md);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-weight: 700; color: var(--hero1); font-size: 0.88rem; display: flex; align-items: center; gap: 6px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Acuan / Rujukan Surat Masuk (Pemohon & Perihal)
                        </span>
                        <a href="{{ route('surat-masuk.show', $item->suratMasuk->id) }}" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 3px 8px;">
                            Lihat Dokumen Masuk &rarr;
                        </a>
                    </div>
                    <div class="meta-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                        <div class="meta-item">
                            <span class="meta-label">Pemohon / Pengirim</span>
                            <span class="meta-value" style="font-weight: 700; color: var(--ink);">{{ $item->suratMasuk->dari }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Nomor Surat Masuk</span>
                            <span class="meta-value">{{ $item->suratMasuk->no_surat }}</span>
                        </div>
                        <div class="meta-item" style="grid-column: 1 / -1;">
                            <span class="meta-label">Perihal Surat Masuk</span>
                            <span class="meta-value" style="color: var(--ink);">{{ $item->suratMasuk->perihal }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Perihal Box -->
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-light);">
                <span class="meta-label" style="display: block; margin-bottom: 6px;">Perihal Surat</span>
                <div style="font-size: 1rem; color: var(--ink); line-height: 1.6; background-color: var(--surface-alt); padding: 14px 16px; border-radius: var(--radius-md); border: 1px solid var(--border); font-weight: 500;">
                    {{ $item->perihal ?? '-' }}
                </div>
            </div>

            <!-- Rincian Spesifik Sesuai Jenis Surat -->
            @if($jenis === 'SU')
                <div class="detail-section-box">
                    <div class="detail-section-title">
                        <svg width="18" height="18" fill="none" stroke="var(--hero1)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Ketentuan & Jadwal Acara Undangan</span>
                    </div>

                    <div class="meta-grid">
                        <div class="meta-item">
                            <span class="meta-label">Hari / Tanggal Acara</span>
                            <span class="meta-value">{{ $item->acara_tanggal ?? '-' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Waktu</span>
                            <span class="meta-value">{{ $item->acara_waktu ?? '-' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Tempat</span>
                            <span class="meta-value">{{ $item->acara_tempat ?? '-' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Ketentuan Pakaian</span>
                            <span class="meta-value">{{ $item->acara_pakaian ?? '-' }}</span>
                        </div>
                    </div>

                    <div style="margin-top: 12px;">
                        <span class="meta-label" style="display: block; margin-bottom: 4px;">Agenda Acara</span>
                        <div style="font-size: 0.92rem; font-weight: 500;">{{ $item->acara_nama ?? '-' }}</div>
                    </div>
                </div>
            @elseif($jenis === 'SPH')
                <div class="detail-section-box">
                    <div class="detail-section-title">
                        <svg width="18" height="18" fill="none" stroke="var(--gold)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Substansi Instruksi / Surat Perintah Harian (SPH)</span>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <span class="meta-label" style="display: block; margin-bottom: 4px;">Dasar Surat</span>
                        <div style="font-size: 0.92rem;">{{ $item->dasar ?? '-' }}</div>
                    </div>

                    <div class="meta-grid" style="margin-bottom: 14px;">
                        <div class="meta-item">
                            <span class="meta-label">Instruksi Kepada Satuan</span>
                            <span class="meta-value" style="font-weight: 600;">{{ $item->untuk_satuan ?? '-' }}</span>
                        </div>
                        @if(!empty($item->jumlah_personel))
                        <div class="meta-item">
                            <span class="meta-label">Jumlah Personel</span>
                            <span class="meta-value">{{ $item->jumlah_personel }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="meta-grid" style="margin-bottom: 14px;">
                        <div class="meta-item">
                            <span class="meta-label">Hari / Tanggal</span>
                            <span class="meta-value">{{ $item->sph_hari_tanggal ?? '-' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Pukul / Waktu</span>
                            <span class="meta-value">{{ $item->sph_waktu ?? '-' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Acara Kegiatan</span>
                            <span class="meta-value">{{ $item->sph_acara ?? '-' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Tempat</span>
                            <span class="meta-value">{{ $item->sph_tempat ?? '-' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Seragam / Pakaian</span>
                            <span class="meta-value">{{ $item->sph_pakaian ?? 'PDL Lengkap' }}</span>
                        </div>
                    </div>

                    @if(!empty($item->isi_perintah))
                    <div>
                        <span class="meta-label" style="display: block; margin-bottom: 4px;">Catatan</span>
                        <div style="font-size: 0.92rem; white-space: pre-line; line-height: 1.6;">{{ $item->isi_perintah }}</div>
                    </div>
                    @endif
                </div>
            @elseif($jenis === 'ST')
                <div class="detail-section-box">
                    <div class="detail-section-title">
                        <svg width="18" height="18" fill="none" stroke="var(--green)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Rincian Mandat Penugasan (Surat Tugas)</span>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <span class="meta-label" style="display: block; margin-bottom: 4px;">Dasar Penugasan</span>
                        <div style="font-size: 0.92rem;">{{ $item->dasar ?? $item->st_dasar ?? '-' }}</div>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <span class="meta-label" style="display: block; margin-bottom: 4px;">Uraian Tugas Operasional</span>
                        <div style="font-size: 0.92rem; white-space: pre-line; line-height: 1.6;">{{ $item->uraian_tugas ?? '-' }}</div>
                    </div>

                    <div class="meta-grid">
                        <div class="meta-item">
                            <span class="meta-label">Periode Penugasan</span>
                            <span class="meta-value">
                                {{ isset($item->tgl_mulai) ? \Carbon\Carbon::parse($item->tgl_mulai)->format('d/m/Y') : '-' }} s/d 
                                {{ isset($item->tgl_selesai) ? \Carbon\Carbon::parse($item->tgl_selesai)->format('d/m/Y') : '-' }}
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Lokasi Penugasan</span>
                            <span class="meta-value">{{ $item->st_tempat ?? '-' }}</span>
                        </div>
                    </div>

                    <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--border);">
                        <span class="meta-label" style="display: block; margin-bottom: 6px;">Personel Penerima Tugas</span>
                        <div style="font-size: 0.92rem; font-weight: 600; white-space: pre-line;">
                            @if(is_array($item->penerima_tugas))
                                <ul style="margin-left: 20px;">
                                    @foreach($item->penerima_tugas as $penerima)
                                        <li>{{ $penerima }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $item->penerima_tugas ?? '-' }}
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons based on status and user role -->
            <div class="actions-bar">
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <!-- Status: DRAF -> Ajukan ke pimpinan -->
                    @if($st === 'draf')
                        <form action="{{ route('surat-keluar.update', $item->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="verifikasi">
                            <button type="submit" class="btn btn-primary">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Ajukan ke Pimpinan</span>
                            </button>
                        </form>
                    @endif

                    <!-- Status: VERIFIKASI -> Setujui dan beri nomor (Gold, pimpinan/admin_adminpers only) -->
                    @if($st === 'verifikasi' && $isPimpinan)
                        <form action="{{ route('surat-keluar.update', $item->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="disetujui">
                            <input type="hidden" name="generate_nomor" value="1">
                            <button type="submit" class="btn btn-gold">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Setujui dan Beri Nomor</span>
                            </button>
                        </form>
                    @endif

                    <!-- Status: DISETUJUI -> Tandai terkirim -->
                    @if($st === 'disetujui')
                        <form action="{{ route('surat-keluar.update', $item->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="terkirim">
                            <button type="submit" class="btn btn-secondary" style="border-color: var(--blue); color: var(--blue);">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Tandai Terkirim</span>
                            </button>
                        </form>
                    @endif

                    <!-- Status: TERKIRIM Info badge -->
                    @if($st === 'terkirim')
                        <span class="badge badge-blue" style="padding: 8px 14px; font-size: 0.85rem;">
                            Dokumen Telah Terkirim ke Penerima
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
