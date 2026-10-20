@extends('layouts.app')

@section('title', 'Edit Surat Masuk')

@section('content')
<div style="max-width: 860px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('surat-masuk.show', $suratMasuk->id) }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.88rem; margin-bottom: 8px; color: var(--ink-muted);">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Detail Surat Masuk
        </a>
        <h2>Edit Data Surat Masuk</h2>
        <p style="color: var(--ink-muted); font-size: 0.92rem;">Perbarui rincian informasi surat masuk jika terdapat kekeliruan pencatatan.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <svg width="20" height="20" fill="none" stroke="var(--accent)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Perbarui Informasi Surat
            </span>
            <span style="font-size: 0.8rem; color: var(--ink-muted);"><span style="color: var(--red);">*</span> Wajib diisi</span>
        </div>

        <form action="{{ route('surat-masuk.update', $suratMasuk->id) }}" method="POST" enctype="multipart/form-data" class="card-body">
            @csrf
            @method('PUT')

            <div class="grid-2">
                <div class="form-group">
                    <label for="nomor_surat" class="form-label">Nomor Surat <span class="required">*</span></label>
                    <input type="text" 
                           id="nomor_surat" 
                           name="nomor_surat" 
                           class="form-control @error('nomor_surat') is-invalid @enderror" 
                           value="{{ old('nomor_surat', $suratMasuk->nomor_surat) }}" 
                           required>
                    @error('nomor_surat')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="pengirim" class="form-label">Dari / Pengirim <span class="required">*</span></label>
                    <input type="text" 
                           id="pengirim" 
                           name="pengirim" 
                           class="form-control @error('pengirim') is-invalid @enderror" 
                           value="{{ old('pengirim', $suratMasuk->pengirim ?? $suratMasuk->dari) }}" 
                           required>
                    @error('pengirim')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="perihal" class="form-label">Perihal Surat <span class="required">*</span></label>
                <textarea id="perihal" 
                          name="perihal" 
                          rows="3" 
                          class="form-control @error('perihal') is-invalid @enderror" 
                          required>{{ old('perihal', $suratMasuk->perihal) }}</textarea>
                @error('perihal')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                    <input type="date" 
                           id="tanggal_surat" 
                           name="tanggal_surat" 
                           class="form-control @error('tanggal_surat') is-invalid @enderror" 
                           value="{{ old('tanggal_surat', isset($suratMasuk->tanggal_surat) ? \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->format('Y-m-d') : '') }}">
                    @error('tanggal_surat')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_diterima" class="form-label">Tanggal Diterima <span class="required">*</span></label>
                    <input type="date" 
                           id="tanggal_diterima" 
                           name="tanggal_diterima" 
                           class="form-control @error('tanggal_diterima') is-invalid @enderror" 
                           value="{{ old('tanggal_diterima', isset($suratMasuk->tanggal_diterima) ? \Carbon\Carbon::parse($suratMasuk->tanggal_diterima)->format('Y-m-d') : date('Y-m-d')) }}" 
                           required>
                    @error('tanggal_diterima')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="status" class="form-label">Status Penanganan</label>
                    <select name="status" id="status" class="form-control">
                        <option value="baru" {{ old('status', $suratMasuk->status) === 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="didisposisi" {{ old('status', $suratMasuk->status) === 'didisposisi' ? 'selected' : '' }}>Didisposisi</option>
                        <option value="selesai" {{ old('status', $suratMasuk->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="scan_surat" class="form-label">Ganti Berkas Scan (Opsional)</label>
                    <input type="file" 
                           id="scan_surat" 
                           name="scan_surat" 
                           accept=".pdf,image/jpeg,image/png" 
                           class="form-control">
                    <div class="form-hint">Biarkan kosong jika tidak ingin mengubah berkas saat ini.</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-light);">
                <a href="{{ route('surat-masuk.show', $suratMasuk->id) }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
