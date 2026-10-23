@extends('layouts.app')

@section('title', 'Buat Surat Keluar Baru')

@push('styles')
<style>
    .jenis-selector-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }

    .jenis-radio-wrapper {
        position: relative;
    }

    .jenis-radio-input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .jenis-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 16px 12px;
        background-color: var(--surface);
        border: 2px solid var(--border);
        border-radius: var(--radius-md);
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
    }

    .jenis-card:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
    }

    .jenis-radio-input:checked + .jenis-card {
        border-color: var(--accent);
        background-color: rgba(142, 27, 34, 0.05);
        box-shadow: 0 0 0 1px var(--accent);
    }

    .jenis-card .code {
        font-family: 'Archivo', sans-serif;
        font-size: 1.25rem;
        font-weight: 900;
        color: var(--ink);
        margin-bottom: 4px;
    }

    .jenis-radio-input:checked + .jenis-card .code {
        color: var(--accent);
    }

    .jenis-card .desc {
        font-size: 0.82rem;
        color: var(--ink-muted);
        font-weight: 500;
    }

    .preview-box {
        background: linear-gradient(135deg, var(--hero1) 0%, var(--hero2) 100%);
        border: 1px solid rgba(201, 162, 74, 0.45);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .preview-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 600;
    }

    .preview-number {
        font-family: 'Archivo', monospace, sans-serif;
        font-size: 1.35rem;
        font-weight: 900;
        color: var(--yellow);
        letter-spacing: 0.06em;
        text-shadow: 0 1px 3px rgba(0,0,0,0.6);
    }

    .dynamic-section {
        background-color: var(--surface-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 20px;
        margin-bottom: 24px;
        animation: fadeIn 0.25s ease;
    }

    .dynamic-section-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    @media (max-width: 640px) {
        .jenis-selector-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div style="max-width: 960px; margin: 0 auto;">
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('surat-keluar.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.88rem; margin-bottom: 8px; color: var(--ink-muted);">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Surat Keluar
        </a>
        <h2>Buat Surat Keluar Baru</h2>
        <p style="color: var(--ink-muted); font-size: 0.92rem;">Pilih jenis surat, lengkapi data spesifik, dan tentukan alur pengesahan dokumen.</p>
    </div>

    <form action="{{ route('surat-keluar.store') }}" method="POST" id="formSuratKeluar">
        @csrf

        <!-- Card Pemilihan Jenis Surat -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header">
                <span class="card-title">1. Pilih Jenis Surat Keluar</span>
                <span style="font-size: 0.8rem; color: var(--ink-muted);"><span style="color: var(--red);">*</span> Wajib dipilih</span>
            </div>
            <div class="card-body">
                <div class="jenis-selector-grid">
                    <!-- Option 1: SU (Surat Undangan) -->
                    <div class="jenis-radio-wrapper">
                        <input type="radio" 
                               name="jenis_surat" 
                               id="jenis_SU" 
                               value="SU" 
                               class="jenis-radio-input" 
                               {{ old('jenis_surat', 'SU') === 'SU' ? 'checked' : '' }}>
                        <label for="jenis_SU" class="jenis-card">
                            <span class="code">SU</span>
                            <span class="desc">Surat Undangan Kegiatan</span>
                        </label>
                    </div>

                    <!-- Option 2: SPH (Surat Perintah Harian) -->
                    <div class="jenis-radio-wrapper">
                        <input type="radio" 
                               name="jenis_surat" 
                               id="jenis_SPH" 
                               value="SPH" 
                               class="jenis-radio-input" 
                               {{ old('jenis_surat') === 'SPH' ? 'checked' : '' }}>
                        <label for="jenis_SPH" class="jenis-card">
                            <span class="code">SPH</span>
                            <span class="desc">Surat Perintah Harian</span>
                        </label>
                    </div>

                    <!-- Option 3: ST (Surat Tugas) -->
                    <div class="jenis-radio-wrapper">
                        <input type="radio" 
                               name="jenis_surat" 
                               id="jenis_ST" 
                               value="ST" 
                               class="jenis-radio-input" 
                               {{ old('jenis_surat') === 'ST' ? 'checked' : '' }}>
                        <label for="jenis_ST" class="jenis-card">
                            <span class="code">ST</span>
                            <span class="desc">Surat Tugas Operasional</span>
                        </label>
                    </div>

                    <!-- Option 4: SK (Surat Keputusan) -->
                    <div class="jenis-radio-wrapper">
                        <a href="{{ route('surat-keputusan.create') }}" class="jenis-card" style="text-decoration: none; border-style: dashed; border-color: var(--gold);" title="Buka Formulir Khusus Surat Keputusan">
                            <span class="code" style="color: var(--gold);">SK</span>
                            <span class="desc" style="color: var(--ink);">Surat Keputusan (KOP SPH) &rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Nomor Contoh Box (Maroon Gradient, Yellow Text) -->
                <div class="preview-box">
                    <div>
                        <div class="preview-label">Simulasi Format Nomor Resmi:</div>
                        <div class="preview-number" id="contohNomorDisplay">000/SKC-SU/X/2026</div>
                    </div>
                    <div style="font-size: 0.82rem; color: rgba(255, 255, 255, 0.7); max-width: 320px; text-align: right;">
                        Nomor urut resmi otomatis diterbitkan sistem saat surat disetujui pimpinan.
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Data Pokok Surat (Common Fields) -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header">
                <span class="card-title">2. Data Pokok Surat</span>
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
                                    {{ old('surat_masuk_id') == $sm->id ? 'selected' : '' }}>
                                [No: {{ $sm->no_surat }}] Pemohon: {{ $sm->dari }} â€” Perihal: {{ Str::limit($sm->perihal, 60) }}
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
                    <!-- Tujuan Surat (Required) -->
                    <div class="form-group">
                        <label for="tujuan" class="form-label">Tujuan Surat / Kepada Yth.</label>
                        <input type="text" 
                               id="tujuan" 
                               name="tujuan" 
                               class="form-control @error('tujuan') is-invalid @enderror" 
                               value="{{ old('tujuan') }}" 
                               placeholder="Contoh: Seluruh Kasatkoryon Banser Se-Kab. Blitar">
                        <div class="form-hint">Pihak penerima atau sasaran surat.</div>
                        @error('tujuan')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Surat (Required) -->
                    <div class="form-group">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat <span class="required">*</span></label>
                        <input type="date" 
                               id="tanggal_surat" 
                               name="tanggal_surat" 
                               class="form-control @error('tanggal_surat') is-invalid @enderror" 
                               value="{{ old('tanggal_surat', date('Y-m-d')) }}" 
                               required>
                        <div class="form-hint">Mempengaruhi penomoran bulan dan tahun.</div>
                        @error('tanggal_surat')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Perihal (Required) -->
                <div class="form-group">
                    <label for="perihal" class="form-label">Perihal Surat <span class="required">*</span></label>
                    <textarea id="perihal" 
                              name="perihal" 
                              rows="2" 
                              class="form-control @error('perihal') is-invalid @enderror" 
                              placeholder="Contoh: Undangan Rapat Koordinasi dan Apel Siaga Pengamanan Harlah..." 
                              required>{{ old('perihal') }}</textarea>
                    <div class="form-hint">Ringkasan pokok maksud pengiriman surat.</div>
                    @error('perihal')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Dynamic Fields Container -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header">
                <span class="card-title">3. Substansi & Rincian Khusus Dokumen</span>
            </div>
            <div class="card-body">
                <!-- Section 1: SU (Surat Undangan) -->
                <div id="section_SU" class="dynamic-section">
                    <div class="dynamic-section-title">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Rincian Kegiatan (Surat Undangan)</span>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="su_hari_tanggal" class="form-label">Hari / Tanggal Acara</label>
                            <input type="text" 
                                   id="su_hari_tanggal" 
                                   name="acara_tanggal" 
                                   class="form-control" 
                                   value="{{ old('acara_tanggal') }}" 
                                   placeholder="Contoh: Ahad Kliwon, 25 Oktober 2026">
                        </div>

                        <div class="form-group">
                            <label for="su_waktu" class="form-label">Waktu Pelaksanaan</label>
                            <input type="text" 
                                   id="su_waktu" 
                                   name="acara_waktu" 
                                   class="form-control" 
                                   value="{{ old('acara_waktu') }}" 
                                   placeholder="Contoh: Pukul 08.00 WIB s/d Selesai">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="su_tempat" class="form-label">Tempat Kegiatan</label>
                            <input type="text" 
                                   id="su_tempat" 
                                   name="acara_tempat" 
                                   class="form-control" 
                                   value="{{ old('acara_tempat') }}" 
                                   placeholder="Contoh: Gedung PC GP Ansor / Graha Gus Dur Kab. Blitar">
                        </div>

                        <div class="form-group">
                            <label for="su_pakaian" class="form-label">Ketentuan Pakaian (Seragam)</label>
                            <input type="text" 
                                   id="su_pakaian" 
                                   name="acara_pakaian" 
                                   class="form-control" 
                                   value="{{ old('acara_pakaian', 'PDL Lengkap / Baret Hitam') }}" 
                                   placeholder="Contoh: PDL Lengkap / DSH / Jas Ansor">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="su_acara" class="form-label">Agenda Acara</label>
                        <input type="text" 
                               id="su_acara" 
                               name="acara_nama" 
                               class="form-control" 
                               value="{{ old('acara_nama') }}" 
                               placeholder="Contoh: Rapat Koordinasi Satkorcab & Apel Siaga">
                    </div>
                </div>

                <!-- Section 2: SPH (Surat Perintah Harian) -->
                <div id="section_SPH" class="dynamic-section" style="display: none;">
                    <div class="dynamic-section-title">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Rincian Perintah Harian (SPH)</span>
                    </div>

                    <div class="form-group">
                        <label for="sph_dasar" class="form-label">Dasar Surat / Regulasi</label>
                        <textarea id="sph_dasar" name="dasar" rows="2" class="form-control" placeholder="Contoh: hasil Undangan Rapat Bersama PCNU dan PEMKAB Kab.Blitar tentang Hari Santri Nasional...">{{ old('dasar') }}</textarea>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="sph_untuk_satuan" class="form-label">Instruksi Kepada Satuan</label>
                            <input type="text" id="sph_untuk_satuan" name="untuk_satuan" class="form-control" value="{{ old('untuk_satuan') }}" placeholder="Contoh: memerintahkan anggotanya sebanyak 10 Personel untuk membantu pengamanan HSN 2026">
                            <div class="form-hint">Kalimat instruksi ke satuan (muncul di paragraf pembuka PDF).</div>
                        </div>
                        <div class="form-group">
                            <label for="sph_jumlah_personel" class="form-label">Jumlah Personel</label>
                            <input type="text" id="sph_jumlah_personel" name="jumlah_personel" class="form-control" value="{{ old('jumlah_personel') }}" placeholder="Contoh: 10 Personel">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="sph_hari_tanggal" class="form-label">Hari / Tanggal Kegiatan <span class="required">*</span></label>
                            <input type="text" id="sph_hari_tanggal" name="sph_hari_tanggal" class="form-control" value="{{ old('sph_hari_tanggal') }}" placeholder="Contoh: Kamis, 22 Oktober 2026">
                        </div>
                        <div class="form-group">
                            <label for="sph_waktu" class="form-label">Pukul</label>
                            <input type="text" id="sph_waktu" name="sph_waktu" class="form-control" value="{{ old('sph_waktu') }}" placeholder="Contoh: 18.00 WIB - Selesai">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sph_acara" class="form-label">Acara / Nama Kegiatan</label>
                        <input type="text" id="sph_acara" name="sph_acara" class="form-control" value="{{ old('sph_acara') }}" placeholder="Contoh: Pengamanan Hari Santri Nasional Tahun 2026">
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="sph_tempat" class="form-label">Tempat</label>
                            <input type="text" id="sph_tempat" name="sph_tempat" class="form-control" value="{{ old('sph_tempat') }}" placeholder="Contoh: Aloon-aloon Kanigoro">
                        </div>
                        <div class="form-group">
                            <label for="sph_pakaian" class="form-label">Seragam / Pakaian</label>
                            <input type="text" id="sph_pakaian" name="sph_pakaian" class="form-control" value="{{ old('sph_pakaian', 'PDL Lengkap') }}" placeholder="Contoh: PDL Lengkap / DSH">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="sph_isi" class="form-label">Catatan</label>
                        <textarea id="sph_isi" name="isi_perintah" rows="2" class="form-control" placeholder="Contoh: 1. Memakai PDL Lengkap&#10;2. Karena pentingnya acara harap Hadir tepat waktu">{{ old('isi_perintah') }}</textarea>
                    </div>
                </div>
                <!-- Section 3: ST (Surat Tugas) -->
                <div id="section_ST" class="dynamic-section" style="display: none;">
                    <div class="dynamic-section-title">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Rincian Penugasan Personel (Surat Tugas)</span>
                    </div>

                    <div class="form-group">
                        <label for="st_dasar" class="form-label">Dasar Penugasan</label>
                        <textarea id="st_dasar" 
                                  name="st_dasar" 
                                  rows="2" 
                                  class="form-control" 
                                  placeholder="Contoh: Permohonan Pengamanan dari Pengurus Cabang NU No. 89/PCNU/X/2026...">{{ old('st_dasar') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="st_uraian" class="form-label">Uraian / Deskripsi Tugas</label>
                        <textarea id="st_uraian" 
                                  name="uraian_tugas" 
                                  rows="3" 
                                  class="form-control" 
                                  placeholder="Contoh: Melaksanakan pengawalan VVIP dan pengamanan lokasi resepsi Harlah...">{{ old('uraian_tugas') }}</textarea>
                    </div>

                    <div class="grid-3">
                        <div class="form-group">
                            <label for="st_tgl_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" 
                                   id="st_tgl_mulai" 
                                   name="tgl_mulai" 
                                   class="form-control" 
                                   value="{{ old('tgl_mulai') }}">
                        </div>

                        <div class="form-group">
                            <label for="st_tgl_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" 
                                   id="st_tgl_selesai" 
                                   name="tgl_selesai" 
                                   class="form-control" 
                                   value="{{ old('tgl_selesai') }}">
                        </div>

                        <div class="form-group">
                            <label for="st_tempat" class="form-label">Lokasi Penugasan</label>
                            <input type="text" 
                                   id="st_tempat" 
                                   name="st_tempat" 
                                   class="form-control" 
                                   value="{{ old('st_tempat') }}" 
                                   placeholder="Contoh: Alun-Alun Kanigoro Blitar">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="st_penerima" class="form-label">Daftar Penerima Tugas (Personel / Anggota)</label>
                        @if(isset($anggotas) && count($anggotas) > 0)
                            <select id="st_penerima" name="penerima_tugas[]" class="form-control" multiple size="4">
                                @foreach($anggotas as $anggota)
                                    <option value="{{ $anggota->id }}">{{ $anggota->nama }} (NIA: {{ $anggota->nia ?? '-' }})</option>
                                @endforeach
                            </select>
                        @else
                            <textarea id="st_penerima" 
                                      name="penerima_tugas" 
                                      rows="3" 
                                      class="form-control" 
                                      placeholder="Ketikkan nama-nama anggota penerima mandat tugas (pisahkan dengan koma atau baris baru)...">{{ old('penerima_tugas') }}</textarea>
                        @endif
                        <div class="form-hint">Daftar nama anggota Banser yang diberikan surat mandat penugasan.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Form -->
        <div class="card">
            <div class="card-body" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">
                    Batalkan
                </a>

                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <!-- Tombol Simpan Draf -->
                    <button type="submit" name="action_type" value="draft" class="btn btn-secondary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <span>Simpan Draf</span>
                    </button>

                    <!-- Tombol Ajukan ke Pimpinan -->
                    <button type="submit" name="action_type" value="ajukan" class="btn btn-primary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Ajukan ke Pimpinan</span>
                    </button>

                    <!-- Tombol Setujui dan Beri Nomor (Gold, only for Pimpinan / Adminpers) -->
                    @php
                        $userRole = Auth::user()->role ?? '';
                        $isAuthorized = in_array(strtolower($userRole), ['pimpinan', 'admin_adminpers', 'kasatkorcab', 'admin', 'super_admin', 'kasetma']);
                    @endphp
                    @if($isAuthorized)
                        <button type="submit" name="action_type" value="approve" class="btn btn-gold">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Setujui dan Beri Nomor</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisRadios = document.querySelectorAll('input[name="jenis_surat"]');
        const tanggalInput = document.getElementById('tanggal_surat');
        const contohNomor = document.getElementById('contohNomorDisplay');

        const sectionSU = document.getElementById('section_SU');
        const sectionSPH = document.getElementById('section_SPH');
        const sectionST = document.getElementById('section_ST');

        // Roman numeral converter for month (1-12)
        const romanMonths = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];

        function getSelectedJenis() {
            for (let radio of jenisRadios) {
                if (radio.checked) return radio.value;
            }
            return 'SU';
        }

        function updateContohNomor() {
            const jenis = getSelectedJenis();
            let dateVal = tanggalInput.value;
            let d = dateVal ? new Date(dateVal) : new Date();

            let monthIndex = isNaN(d.getMonth()) ? (new Date()).getMonth() : d.getMonth();
            let year = isNaN(d.getFullYear()) ? (new Date()).getFullYear() : d.getFullYear();
            let roman = romanMonths[monthIndex] || "X";

            contohNomor.textContent = `000/SKC-${jenis}/${roman}/${year}`;
        }

        function toggleDynamicSections() {
            const jenis = getSelectedJenis();

            if (sectionSU) sectionSU.style.display = (jenis === 'SU') ? 'block' : 'none';
            if (sectionSPH) sectionSPH.style.display = (jenis === 'SPH') ? 'block' : 'none';
            if (sectionST) sectionST.style.display = (jenis === 'ST') ? 'block' : 'none';

            updateContohNomor();
        }

        // Event listeners
        jenisRadios.forEach(radio => {
            radio.addEventListener('change', toggleDynamicSections);
        });

        if (tanggalInput) {
            tanggalInput.addEventListener('change', updateContohNomor);
        }

        // Initial setup
        toggleDynamicSections();

        const smSelect = document.getElementById('surat_masuk_id');
        if (smSelect && smSelect.value) {
            onSuratMasukAcuanChange(smSelect);
        }
    });

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
</script>
@endpush
