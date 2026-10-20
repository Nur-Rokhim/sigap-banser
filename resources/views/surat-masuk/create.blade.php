@extends('layouts.app')

@section('title', 'Catat Surat Masuk')

@section('content')
<div style="max-width: 860px; margin: 0 auto;">
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('surat-masuk.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.88rem; margin-bottom: 8px; color: var(--ink-muted);">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Buku Agenda Surat Masuk
        </a>
        <h2>Catat Surat Masuk Baru</h2>
        <p style="color: var(--ink-muted); font-size: 0.92rem;">Registrasikan surat dinas masuk ke dalam pangkalan data administrasi Satkorcab.</p>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <svg width="20" height="20" fill="none" stroke="var(--accent)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Formulir Surat Masuk
            </span>
            <span style="font-size: 0.8rem; color: var(--ink-muted);"><span style="color: var(--red);">*</span> Wajib diisi</span>
        </div>

        <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data" class="card-body">
            @csrf

            <!-- Grid 2 for Nomor & Dari -->
            <div class="grid-2">
                <!-- Nomor Surat (Required) -->
                <div class="form-group">
                    <label for="nomor_surat" class="form-label">Nomor Surat <span class="required">*</span></label>
                    <input type="text" 
                           id="nomor_surat" 
                           name="nomor_surat" 
                           class="form-control @error('nomor_surat') is-invalid @enderror" 
                           value="{{ old('nomor_surat') }}" 
                           placeholder="Contoh: 124/PC-GP.Ansor/SR-01/X/2026" 
                           required>
                    <div class="form-hint">Isi persis sesuai nomor yang tertera pada lembar surat.</div>
                    @error('nomor_surat')
                        <div class="form-error">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Dari / Pengirim (Required) -->
                <div class="form-group">
                    <label for="pengirim" class="form-label">Dari / Pengirim <span class="required">*</span></label>
                    <input type="text" 
                           id="pengirim" 
                           name="pengirim" 
                           class="form-control @error('pengirim') is-invalid @enderror" 
                           value="{{ old('pengirim', old('dari')) }}" 
                           placeholder="Contoh: PC GP Ansor Kab. Blitar / PAC Banser Gandusari" 
                           required>
                    <div class="form-hint">Lembaga, instansi, banom, atau perorangan pengirim surat.</div>
                    @error('pengirim')
                        <div class="form-error">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Perihal (Required) -->
            <div class="form-group">
                <label for="perihal" class="form-label">Perihal Surat <span class="required">*</span></label>
                <textarea id="perihal" 
                          name="perihal" 
                          rows="3" 
                          class="form-control @error('perihal') is-invalid @enderror" 
                          placeholder="Tuliskan pokok isi atau perihal surat secara lengkap dan jelas..." 
                          required>{{ old('perihal') }}</textarea>
                <div class="form-hint">Uraikan secara ringkas substansi atau kepentingan pokok surat.</div>
                @error('perihal')
                    <div class="form-error">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Grid 2 for Dates -->
            <div class="grid-2">
                <!-- Tanggal Surat (Optional) -->
                <div class="form-group">
                    <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                    <input type="date" 
                           id="tanggal_surat" 
                           name="tanggal_surat" 
                           class="form-control @error('tanggal_surat') is-invalid @enderror" 
                           value="{{ old('tanggal_surat') }}">
                    <div class="form-hint">Tanggal yang tertera pada surat asal (opsional).</div>
                    @error('tanggal_surat')
                        <div class="form-error">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Tanggal Diterima (Required, default today) -->
                <div class="form-group">
                    <label for="tanggal_diterima" class="form-label">Tanggal Diterima <span class="required">*</span></label>
                    <input type="date" 
                           id="tanggal_diterima" 
                           name="tanggal_diterima" 
                           class="form-control @error('tanggal_diterima') is-invalid @enderror" 
                           value="{{ old('tanggal_diterima', date('Y-m-d')) }}" 
                           required>
                    <div class="form-hint">Waktu dokumen fisik atau digital diterima oleh Adminpers.</div>
                    @error('tanggal_diterima')
                        <div class="form-error">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Scan Surat (File upload, accept PDF/JPG/PNG, max 5MB) -->
            <div class="form-group">
                <label for="scan_surat" class="form-label">Berkas Scan Surat</label>
                <div style="border: 2px dashed var(--border); border-radius: var(--radius-md); padding: 20px; text-align: center; background-color: var(--surface-alt);">
                    <svg width="36" height="36" fill="none" stroke="var(--ink-muted)" viewBox="0 0 24 24" style="margin-bottom: 8px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p style="font-size: 0.9rem; font-weight: 600; margin-bottom: 4px;">Pilih berkas dokumen atau foto fisik surat</p>
                    <p style="font-size: 0.8rem; color: var(--ink-muted); margin-bottom: 12px;">Format didukung: <strong>PDF, JPG, PNG</strong> (Maksimum 5 MB)</p>
                    <input type="file" 
                           id="scan_surat" 
                           name="scan_surat" 
                           accept=".pdf,image/jpeg,image/png" 
                           class="form-control" 
                           style="max-width: 320px; margin: 0 auto;">
                </div>
                @error('scan_surat')
                    <div class="form-error">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border-light);">
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Surat Masuk</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
