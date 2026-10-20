@extends('layouts.app')

@section('title', 'Edit Surat Keluar - ' . ($suratKeluar->nomor_surat ?? 'Draf'))

@push('styles')
<style>
    .dynamic-section {
        background-color: var(--surface-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 20px;
        margin-bottom: 24px;
    }
</style>
@endpush

@section('content')
@php
    $jenis = strtoupper($suratKeluar->jenis_surat ?? $suratKeluar->jenis ?? 'SU');
@endphp

<div style="max-width: 960px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('surat-keluar.show', $suratKeluar->id) }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.88rem; margin-bottom: 8px; color: var(--ink-muted);">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Detail Surat Keluar
        </a>
        <h2>Edit Data Surat Keluar</h2>
        <p style="color: var(--ink-muted); font-size: 0.92rem;">Perbarui rincian surat keluar jenis <strong>{{ $jenis }}</strong>.</p>
    </div>

    <form action="{{ route('surat-keluar.update', $suratKeluar->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header">
                <span class="card-title">1. Data Pokok Surat</span>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label for="tujuan" class="form-label">Tujuan Surat / Kepada Yth. <span class="required">*</span></label>
                        <input type="text" 
                               id="tujuan" 
                               name="tujuan" 
                               class="form-control @error('tujuan') is-invalid @enderror" 
                               value="{{ old('tujuan', $suratKeluar->tujuan) }}" 
                               required>
                        @error('tujuan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat <span class="required">*</span></label>
                        <input type="date" 
                               id="tanggal_surat" 
                               name="tanggal_surat" 
                               class="form-control @error('tanggal_surat') is-invalid @enderror" 
                               value="{{ old('tanggal_surat', isset($suratKeluar->tanggal_surat) ? \Carbon\Carbon::parse($suratKeluar->tanggal_surat)->format('Y-m-d') : date('Y-m-d')) }}" 
                               required>
                        @error('tanggal_surat')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="perihal" class="form-label">Perihal Surat <span class="required">*</span></label>
                    <textarea id="perihal" 
                              name="perihal" 
                              rows="2" 
                              class="form-control @error('perihal') is-invalid @enderror" 
                              required>{{ old('perihal', $suratKeluar->perihal) }}</textarea>
                    @error('perihal')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header">
                <span class="card-title">2. Rincian Khusus Sesuai Jenis ({{ $jenis }})</span>
            </div>
            <div class="card-body">
                @if($jenis === 'SU')
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="acara_tanggal" class="form-label">Hari / Tanggal Acara</label>
                            <input type="text" id="acara_tanggal" name="acara_tanggal" class="form-control" value="{{ old('acara_tanggal', $suratKeluar->acara_tanggal) }}">
                        </div>
                        <div class="form-group">
                            <label for="acara_waktu" class="form-label">Waktu Pelaksanaan</label>
                            <input type="text" id="acara_waktu" name="acara_waktu" class="form-control" value="{{ old('acara_waktu', $suratKeluar->acara_waktu) }}">
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="acara_tempat" class="form-label">Tempat Kegiatan</label>
                            <input type="text" id="acara_tempat" name="acara_tempat" class="form-control" value="{{ old('acara_tempat', $suratKeluar->acara_tempat) }}">
                        </div>
                        <div class="form-group">
                            <label for="acara_pakaian" class="form-label">Ketentuan Pakaian</label>
                            <input type="text" id="acara_pakaian" name="acara_pakaian" class="form-control" value="{{ old('acara_pakaian', $suratKeluar->acara_pakaian) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="acara_nama" class="form-label">Agenda Acara</label>
                        <input type="text" id="acara_nama" name="acara_nama" class="form-control" value="{{ old('acara_nama', $suratKeluar->acara_nama) }}">
                    </div>
                @elseif($jenis === 'SPH')
                    <div class="form-group">
                        <label for="dasar" class="form-label">Dasar Regulasi / Surat</label>
                        <textarea id="dasar" name="dasar" rows="2" class="form-control">{{ old('dasar', $suratKeluar->dasar) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="isi_perintah" class="form-label">Isi Perintah / Petunjuk</label>
                        <textarea id="isi_perintah" name="isi_perintah" rows="4" class="form-control">{{ old('isi_perintah', $suratKeluar->isi_perintah) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="untuk_satuan" class="form-label">Diberlakukan Untuk Satuan</label>
                        <input type="text" id="untuk_satuan" name="untuk_satuan" class="form-control" value="{{ old('untuk_satuan', $suratKeluar->untuk_satuan) }}">
                    </div>
                @elseif($jenis === 'ST')
                    <div class="form-group">
                        <label for="st_dasar" class="form-label">Dasar Penugasan</label>
                        <textarea id="st_dasar" name="st_dasar" rows="2" class="form-control">{{ old('st_dasar', $suratKeluar->st_dasar ?? $suratKeluar->dasar) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="uraian_tugas" class="form-label">Uraian Tugas Operasional</label>
                        <textarea id="uraian_tugas" name="uraian_tugas" rows="3" class="form-control">{{ old('uraian_tugas', $suratKeluar->uraian_tugas) }}</textarea>
                    </div>
                    <div class="grid-3">
                        <div class="form-group">
                            <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" id="tgl_mulai" name="tgl_mulai" class="form-control" value="{{ old('tgl_mulai', isset($suratKeluar->tgl_mulai) ? \Carbon\Carbon::parse($suratKeluar->tgl_mulai)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="form-group">
                            <label for="tgl_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" id="tgl_selesai" name="tgl_selesai" class="form-control" value="{{ old('tgl_selesai', isset($suratKeluar->tgl_selesai) ? \Carbon\Carbon::parse($suratKeluar->tgl_selesai)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="form-group">
                            <label for="st_tempat" class="form-label">Lokasi Penugasan</label>
                            <input type="text" id="st_tempat" name="st_tempat" class="form-control" value="{{ old('st_tempat', $suratKeluar->st_tempat) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="penerima_tugas" class="form-label">Personel Penerima Tugas</label>
                        <textarea id="penerima_tugas" name="penerima_tugas" rows="3" class="form-control">{{ old('penerima_tugas', is_array($suratKeluar->penerima_tugas) ? implode("\n", $suratKeluar->penerima_tugas) : $suratKeluar->penerima_tugas) }}</textarea>
                    </div>
                @endif
            </div>
        </div>

        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 24px;">
            <a href="{{ route('surat-keluar.show', $suratKeluar->id) }}" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>
@endsection
