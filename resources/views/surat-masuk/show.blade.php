@extends('layouts.app')

@section('title', 'Detail Surat Masuk - ' . ($suratMasuk->nomor_surat ?? 'Dokumen'))

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

    .timeline-disposisi {
        position: relative;
        padding-left: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .timeline-disposisi::before {
        content: '';
        position: absolute;
        top: 8px;
        bottom: 8px;
        left: 7px;
        width: 2px;
        background-color: var(--border);
    }

    .timeline-entry {
        position: relative;
        background: var(--surface-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 14px 16px;
    }

    .timeline-entry::before {
        content: '';
        position: absolute;
        left: -21px;
        top: 14px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: var(--accent);
        border: 2px solid var(--surface);
    }

    .timeline-entry-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        flex-wrap: wrap;
        gap: 8px;
    }
</style>
@endpush

@section('content')
@php
    $item = $suratMasuk ?? $surat;
    $st = strtolower($item->status ?? 'baru');
    $fileScanPath = $item->file_scan ?? $item->scan_surat ?? null;
@endphp

<div class="page-container">
    <!-- Breadcrumb & Top Bar -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <a href="{{ route('surat-masuk.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.88rem; margin-bottom: 8px; color: var(--ink-muted);">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Surat Masuk
            </a>
            <h2>Detail Surat Masuk</h2>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn btn-secondary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Lembar Disposisi</span>
            </button>
            @if(Route::has('surat-masuk.edit'))
                <a href="{{ route('surat-masuk.edit', $item->id) }}" class="btn btn-secondary">
                    <span>Edit Surat</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Layout Grid: Detail Surat (Left) & Disposisi Section (Right) -->
    <div class="grid-2-1">
        <!-- Kolom Kiri: Informasi Dokumen Surat Masuk -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Informasi Dokumen
                    </span>
                    <div>
                        @if($st === 'baru')
                            <span class="badge badge-red">Baru</span>
                        @elseif($st === 'didisposisi')
                            <span class="badge badge-yellow">Didisposisi</span>
                        @elseif($st === 'selesai')
                            <span class="badge badge-green">Selesai</span>
                        @else
                            <span class="badge badge-gray">{{ ucfirst($st) }}</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <!-- Nomor Surat Highlight Box -->
                    <div style="margin-bottom: 24px;">
                        <div class="surat-number-box" style="width: 100%;">
                            <span class="label">Nomor Surat Masuk</span>
                            <span class="number">{{ $item->nomor_surat ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="meta-grid">
                        <div class="meta-item">
                            <span class="meta-label">Dari / Pengirim</span>
                            <span class="meta-value">{{ $item->pengirim ?? $item->dari ?? '-' }}</span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Tanggal Surat</span>
                            <span class="meta-value">
                                {{ isset($item->tanggal_surat) ? \Carbon\Carbon::parse($item->tanggal_surat)->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Tanggal Diterima</span>
                            <span class="meta-value">
                                {{ isset($item->tanggal_diterima) ? \Carbon\Carbon::parse($item->tanggal_diterima)->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Dicatat Oleh</span>
                            <span class="meta-value">{{ $item->user->name ?? $item->pencatat ?? 'Adminpers Satkorcab' }}</span>
                        </div>
                    </div>

                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-light);">
                        <span class="meta-label" style="display: block; margin-bottom: 6px;">Perihal Surat</span>
                        <div style="font-size: 1rem; color: var(--ink); line-height: 1.6; background-color: var(--surface-alt); padding: 14px 16px; border-radius: var(--radius-md); border: 1px solid var(--border);">
                            {{ $item->perihal ?? '-' }}
                        </div>
                    </div>

                    <!-- Lampiran / Scan Berkas -->
                    <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border-light);">
                        <span class="meta-label" style="display: block; margin-bottom: 10px;">Berkas Scan / Lampiran Fisik</span>
                        @if($fileScanPath)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: var(--surface-alt); border: 1px solid var(--border); border-radius: var(--radius-md);">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <svg width="24" height="24" fill="none" stroke="var(--accent)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <div>
                                        <div style="font-weight: 600; font-size: 0.9rem;">Dokumen Scan Surat</div>
                                        <div style="font-size: 0.78rem; color: var(--ink-muted);">Tersedia untuk diunduh dan diperiksa</div>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $fileScanPath) }}" target="_blank" download class="btn btn-secondary btn-sm">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Unduh Scan</span>
                                </a>
                            </div>
                        @else
                            <div style="font-size: 0.88rem; color: var(--ink-muted); font-style: italic;">
                                Tidak ada berkas digital / scan yang dilampirkan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Disposisi Section -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Form Tambah Disposisi -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">
                        <svg width="20" height="20" fill="none" stroke="var(--gold)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Tindak Lanjut Disposisi
                    </span>
                </div>
                <div class="card-body">
                    <form action="{{ route('surat-masuk.disposisi.store', $item->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="diteruskan_kepada" class="form-label">Diteruskan Kepada <span class="required">*</span></label>
                            <select name="user_id" id="diteruskan_kepada" class="form-control @error('user_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Pejabat / Satuan Penerima --</option>
                                @if(isset($users) && count($users) > 0)
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role ?? 'Petugas' }})</option>
                                    @endforeach
                                @else
                                    <option value="1">Kasatkorcab Banser Kab. Blitar</option>
                                    <option value="2">Wakil Kasatkorcab</option>
                                    <option value="3">Kepala Biro Administrasi & Personalia</option>
                                    <option value="4">Biro Operasional Satkorcab</option>
                                    <option value="5">Kasatkoryon Banser Kecamatan</option>
                                @endif
                            </select>
                            @error('user_id')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="instruksi" class="form-label">Instruksi / Catatan Disposisi <span class="required">*</span></label>
                            <textarea id="instruksi" 
                                      name="instruksi" 
                                      rows="3" 
                                      class="form-control @error('instruksi') is-invalid @enderror" 
                                      placeholder="Contoh: Tindak lanjuti koordinasi dengan Kasatkoryon terkait..." 
                                      required>{{ old('instruksi') }}</textarea>
                            @error('instruksi')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sifat" class="form-label">Sifat Disposisi</label>
                            <select name="sifat" id="sifat" class="form-control">
                                <option value="Biasa">Biasa</option>
                                <option value="Penting">Penting</option>
                                <option value="Segera">Segera / Mendesak</option>
                                <option value="Rahasia">Rahasia</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span>Kirimkan Disposisi</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Riwayat Disposisi yang Ada -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title" style="font-size: 1rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Riwayat Disposisi ({{ isset($item->disposisi) ? count($item->disposisi) : 0 }})
                    </span>
                </div>
                <div class="card-body">
                    @php
                        $disposisiList = $item->disposisi ?? [];
                    @endphp

                    @if(count($disposisiList) > 0)
                        <div class="timeline-disposisi">
                            @foreach($disposisiList as $disp)
                                <div class="timeline-entry">
                                    <div class="timeline-entry-header">
                                        <strong style="font-size: 0.88rem; color: var(--ink);">
                                            Kepada: {{ $disp->kepadaUser->name ?? 'Penerima Disposisi' }}
                                        </strong>
                                        <span class="badge {{ ($disp->sifat ?? '') == 'Segera / Mendesak' ? 'badge-red' : 'badge-yellow' }}" style="font-size: 0.7rem;">
                                            {{ $disp->sifat ?? 'Biasa' }}
                                        </span>
                                    </div>
                                    <p style="font-size: 0.86rem; color: var(--ink); margin-bottom: 6px;">
                                        "{{ $disp->instruksi ?? $disp->catatan ?? '-' }}"
                                    </p>
                                    <div style="font-size: 0.75rem; color: var(--ink-muted);">
                                        Oleh: <strong>{{ $disp->dariUser->name ?? 'Pimpinan' }}</strong> &bull; 
                                        {{ isset($disp->created_at) ? \Carbon\Carbon::parse($disp->created_at)->translatedFormat('d/m/Y H:i') : '-' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 24px 12px; color: var(--ink-muted); font-size: 0.88rem;">
                            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 8px; opacity: 0.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p>Belum ada catatan disposisi pada surat ini.</p>
                            <p style="font-size: 0.8rem; margin-top: 4px;">Gunakan formulir di atas untuk meneruskan disposisi.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
