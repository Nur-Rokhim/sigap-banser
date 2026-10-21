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
                <!-- Acuan Surat Masuk (Dasar Rujukan Pemohon & Perihal) -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="surat_masuk_id" class="form-label" style="display: flex; align-items: center; justify-content: space-between;">
                        <span>Acuan Surat Masuk (Pemohon & Perihal Rujukan)</span>
                        <span style="font-weight: normal; font-size: 0.78rem; color: var(--ink-muted);">Opsional</span>
                    </label>
                    <select id="surat_masuk_id" name="surat_masuk_id" class="form-control select @error('surat_masuk_id') is-invalid @enderror" onchange="onSuratMasukAcuanChange(this)">
                        <option value="">-- Tanpa Acuan / Surat Inisiatif Mandiri --</option>
                        @foreach($suratMasukList as $sm)
                            <option value="{{ $sm->id }}" 
                                    data-dari="{{ $sm->dari }}" 
                                    data-perihal="{{ $sm->perihal }}"
                                    data-nomor="{{ $sm->no_surat }}"
                                    {{ old('surat_masuk_id', $suratKeluar->surat_masuk_id) == $sm->id ? 'selected' : '' }}>
                                [No: {{ $sm->no_surat }}] Pemohon: {{ $sm->dari }} — Perihal: {{ Str::limit($sm->perihal, 60) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-hint">Pilih surat masuk jika surat keluar ini diterbitkan sebagai tindak lanjut atas permohonan atau surat yang masuk.</div>
                    @error('surat_masuk_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror

                    <!-- Preview Acuan Box -->
                    <div id="acuanPreviewBox" style="display: none; margin-top: 10px; padding: 12px 14px; background: rgba(90, 14, 20, 0.04); border: 1px dashed var(--accent); border-radius: var(--radius-sm);">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--accent); margin-bottom: 4px; display: flex; align-items: center; gap: 5px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Acuan Surat Masuk Terpilih:
                        </div>
                        <div style="font-size: 0.85rem; color: var(--ink);">
                            <strong>Pemohon:</strong> <span id="acuanDariText">-</span> &bull; 
                            <strong>No. Surat:</strong> <span id="acuanNomorText">-</span>
                        </div>
                        <div style="font-size: 0.82rem; color: var(--ink-muted); margin-top: 2px;">
                            <strong>Perihal:</strong> <span id="acuanPerihalText">-</span>
                        </div>
                    </div>
                </div>

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
                        <label for="dasar" class="form-label">Dasar Surat / Regulasi</label>
                        <textarea id="dasar" name="dasar" rows="2" class="form-control" placeholder="Contoh: hasil Undangan Rapat Bersama PCNU dan PEMKAB Kab.Blitar...">{{ old('dasar', $suratKeluar->dasar) }}</textarea>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="untuk_satuan" class="form-label">Instruksi Kepada Satuan</label>
                            <input type="text" id="untuk_satuan" name="untuk_satuan" class="form-control" value="{{ old('untuk_satuan', $suratKeluar->untuk_satuan) }}" placeholder="Contoh: memerintahkan anggotanya sebanyak 10 Personel untuk membantu pengamanan HSN 2026">
                            <div class="form-hint">Kalimat instruksi ke satuan (muncul di paragraf pembuka PDF).</div>
                        </div>
                        <div class="form-group">
                            <label for="sph_jumlah_personel" class="form-label">Jumlah Personel</label>
                            <input type="text" id="sph_jumlah_personel" name="sph_jumlah_personel" class="form-control" value="{{ old('sph_jumlah_personel', $suratKeluar->jumlah_personel) }}" placeholder="Contoh: 10 Personel">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="sph_hari_tanggal" class="form-label">Hari / Tanggal Kegiatan</label>
                            <input type="text" id="sph_hari_tanggal" name="sph_hari_tanggal" class="form-control" value="{{ old('sph_hari_tanggal', $suratKeluar->sph_hari_tanggal) }}" placeholder="Contoh: Kamis, 22 Oktober 2026">
                        </div>
                        <div class="form-group">
                            <label for="sph_waktu" class="form-label">Pukul</label>
                            <input type="text" id="sph_waktu" name="sph_waktu" class="form-control" value="{{ old('sph_waktu', $suratKeluar->sph_waktu) }}" placeholder="Contoh: 18.00 WIB - Selesai">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sph_acara" class="form-label">Acara / Nama Kegiatan</label>
                        <input type="text" id="sph_acara" name="sph_acara" class="form-control" value="{{ old('sph_acara', $suratKeluar->sph_acara) }}" placeholder="Contoh: Pengamanan Hari Santri Nasional Tahun 2026">
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="sph_tempat" class="form-label">Tempat</label>
                            <input type="text" id="sph_tempat" name="sph_tempat" class="form-control" value="{{ old('sph_tempat', $suratKeluar->sph_tempat) }}" placeholder="Contoh: Aloon-aloon Kanigoro">
                        </div>
                        <div class="form-group">
                            <label for="sph_pakaian" class="form-label">Seragam / Pakaian</label>
                            <input type="text" id="sph_pakaian" name="sph_pakaian" class="form-control" value="{{ old('sph_pakaian', $suratKeluar->sph_pakaian ?? 'PDL Lengkap') }}" placeholder="Contoh: PDL Lengkap / DSH">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="isi_perintah" class="form-label">Catatan Tambahan (Opsional)</label>
                        <textarea id="isi_perintah" name="isi_perintah" rows="2" class="form-control" placeholder="Catatan atau instruksi tambahan...">{{ old('isi_perintah', $suratKeluar->isi_perintah) }}</textarea>
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

@push('scripts')
<script>
    function onSuratMasukAcuanChange(selectElem) {
        const selectedOption = selectElem.options[selectElem.selectedIndex];
        const previewBox = document.getElementById('acuanPreviewBox');
        if (selectElem.value && selectedOption) {
            document.getElementById('acuanDariText').textContent = selectedOption.getAttribute('data-dari') || '-';
            document.getElementById('acuanNomorText').textContent = selectedOption.getAttribute('data-nomor') || '-';
            document.getElementById('acuanPerihalText').textContent = selectedOption.getAttribute('data-perihal') || '-';
            if (previewBox) previewBox.style.display = 'block';
        } else {
            if (previewBox) previewBox.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const smSelect = document.getElementById('surat_masuk_id');
        if (smSelect && smSelect.value) {
            onSuratMasukAcuanChange(smSelect);
        }
    });
</script>
@endpush
