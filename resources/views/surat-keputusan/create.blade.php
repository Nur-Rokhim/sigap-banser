@extends('layouts.app')

@section('title', 'Buat Surat Keputusan (SK) Baru - Satkorcab BANSER')

@push('styles')
<style>
    /* Kontainer Formulir Dokumen Ber-KOP Resmi */
    .sk-document-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        padding: 32px 36px;
        margin-bottom: 28px;
    }
    @media (max-width: 640px) {
        .sk-document-card {
            padding: 16px;
        }
    }

    /* KOP SURAT FORM PERSIS KOP SURAT SURAT PERINTAH HARIAN */
    .form-kop-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 4px;
    }
    .form-kop-table td {
        vertical-align: middle;
        padding: 0;
    }
    .form-kop-logo-kiri {
        width: 90px;
        text-align: center;
        vertical-align: middle;
    }
    .form-kop-logo-kiri img {
        max-width: 78px;
        max-height: 80px;
        height: auto;
    }
    .form-kop-kode-wilayah {
        font-size: 9pt;
        font-weight: 800;
        text-align: center;
        margin-top: 3px;
        letter-spacing: 0.5px;
        font-family: 'Times New Roman', Times, serif;
        color: #000000;
    }
    .form-kop-center {
        text-align: center;
        padding: 0 10px;
        font-family: 'Times New Roman', Times, serif;
        color: #000000;
    }
    .form-kop-satuan-1 {
        font-size: 14pt;
        font-weight: bold;
        letter-spacing: 0.5px;
        line-height: 1.15;
        text-transform: uppercase;
    }
    .form-kop-satuan-2 {
        font-size: 14pt;
        font-weight: bold;
        letter-spacing: 0.5px;
        line-height: 1.15;
        text-transform: uppercase;
    }
    .form-kop-satuan-3 {
        font-size: 13.5pt;
        font-weight: bold;
        letter-spacing: 0.5px;
        line-height: 1.15;
        text-transform: uppercase;
    }
    .form-kop-satuan-4 {
        font-size: 14pt;
        font-weight: bold;
        letter-spacing: 1px;
        line-height: 1.25;
        text-transform: uppercase;
    }
    .form-kop-alamat {
        font-size: 8.5pt;
        margin-top: 4px;
        line-height: 1.2;
        font-family: Arial, Helvetica, sans-serif;
    }
    .form-kop-logo-kanan {
        width: 95px;
        text-align: center;
        vertical-align: middle;
    }
    .form-kop-logo-kanan img {
        max-width: 90px;
        max-height: 75px;
        height: auto;
    }

    /* GARIS PEMBATAS GANDA (TEBAL ATAS, TIPIS BAWAH) */
    .form-kop-divider {
        width: 100%;
        border-top: 2.5px solid #000000;
        border-bottom: 1px solid #000000;
        height: 3px;
        margin-top: 6px;
        margin-bottom: 24px;
    }

    /* JUDUL KEPUTUSAN DI DALAM FORM */
    .sk-title-header {
        text-align: center;
        margin-bottom: 24px;
        font-family: 'Times New Roman', Times, serif;
    }
    .sk-title-header h2 {
        font-size: 13.5pt;
        font-weight: bold;
        text-transform: uppercase;
        margin: 0;
        letter-spacing: 0.8px;
        color: #000000;
    }
    .sk-title-header .sub-instansi {
        font-size: 11pt;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 2px;
        color: #000000;
    }

    /* SIMULASI NOMOR SK BOX */
    .sk-number-preview {
        background: linear-gradient(135deg, var(--hero1) 0%, var(--hero2) 100%);
        border: 1px solid rgba(201, 162, 74, 0.4);
        border-radius: var(--radius-md);
        padding: 10px 18px;
        margin: 12px auto 16px auto;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        color: #ffffff;
    }
    .sk-number-preview .code {
        font-family: 'Archivo', monospace;
        font-weight: 800;
        font-size: 1.1rem;
        color: var(--yellow);
        letter-spacing: 0.05em;
    }

    /* SECTION HEADER DI DALAM FORM */
    .form-section-heading {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--border);
        margin: 24px 0 16px 0;
        font-size: 1rem;
        font-weight: 800;
        color: var(--ink);
    }
    .form-section-heading svg {
        color: var(--accent);
    }

    /* FORM GRID */
    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    @media (max-width: 768px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    /* PREVIEW TANDA TANGAN DI FORM */
    .ttd-form-preview {
        background: var(--surface-alt);
        border: 1px dashed var(--border);
        border-radius: var(--radius-md);
        padding: 16px;
        margin-top: 24px;
        text-align: center;
        font-family: 'Times New Roman', Times, serif;
    }
</style>
@endpush

@section('content')
<div class="page-container" style="max-width: 960px;">
    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('surat-keputusan.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.88rem; color: var(--ink-muted); text-decoration: none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Surat Keputusan
        </a>
        <span class="badge badge-yellow" style="font-size: 0.78rem;">Formulir Pembuatan SK Baru</span>
    </div>

    @php
        $kodeWilayah = $profil->kode_wilayah ?: 'XII-11';
        $alamatSekretariat = $profil->alamat_sekretariat ?: 'Graha NU Kabupaten Blitar Jatinom Kanigoro Kode Pos: 66171';

        $logoBanser = asset('images/banser-logo.png');
        $logoKanan  = file_exists(public_path('images/logo-banser-merahputih.png')) 
            ? asset('images/logo-banser-merahputih.png') 
            : asset('images/ansor-logo.png');

        $now = \Carbon\Carbon::now();
        $romawiBulan = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        $simulasiNomor = sprintf('000/SKC-SK/%s/%d', $romawiBulan[$now->month], $now->year);
    @endphp

    <form action="{{ route('surat-keputusan.store') }}" method="POST" id="formCreateSk">
        @csrf

        <div class="sk-document-card">
            <!-- 1. KOP SURAT FORM PERSIS DENGAN KOP SURAT PERINTAH HARIAN (SPH) -->
            <table class="form-kop-table">
                <tr>
                    <td class="form-kop-logo-kiri">
                        <img src="{{ $logoBanser }}" alt="Logo BANSER">
                        <div class="form-kop-kode-wilayah">{{ $kodeWilayah }}</div>
                    </td>
                    <td class="form-kop-center">
                        <div class="form-kop-satuan-1">SATUAN KOORDINASI CABANG</div>
                        <div class="form-kop-satuan-2">BARISAN ANSOR SERBAGUNA</div>
                        <div class="form-kop-satuan-3">(SATKORCAB BANSER)</div>
                        <div class="form-kop-satuan-4">KABUPATEN BLITAR</div>
                        <div class="form-kop-alamat">Sekretariat : {{ $alamatSekretariat }}</div>
                    </td>
                    <td class="form-kop-logo-kanan">
                        <img src="{{ $logoKanan }}" alt="Logo Header">
                    </td>
                </tr>
            </table>
            <div class="form-kop-divider"></div>

            <!-- 2. HEADER RESMI SURAT KEPUTUSAN -->
            <div class="sk-title-header">
                <h2>SURAT KEPUTUSAN</h2>
                <div class="sub-instansi">SATUAN KOORDINASI CABANG BARISAN ANSOR SERBAGUNA</div>
                <div class="sub-instansi">KABUPATEN BLITAR</div>

                <div class="sk-number-preview">
                    <span style="font-size: 0.82rem; text-transform: uppercase; color: rgba(255,255,255,0.8);">Nomor SK:</span>
                    <span class="code" id="displayNomorSimulasi">{{ $simulasiNomor }}</span>
                    <span style="font-size: 0.75rem; color: rgba(255,255,255,0.7);">(Draf Otomatis)</span>
                </div>
            </div>

            <!-- 3. IDENTITAS & POKOK KEPUTUSAN (TENTANG) -->
            <div class="form-section-heading">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>1. Pokok Ketetapan (Tentang) & Waktu Penetapan</span>
            </div>

            <div class="form-group">
                <label for="tentang" class="form-label">
                    TENTANG (Judul / Pokok Keputusan yang Ditetapkan) <span class="required">*</span>
                </label>
                <textarea id="tentang" 
                          name="tentang" 
                          rows="2" 
                          class="form-control @error('tentang') is-invalid @enderror" 
                          placeholder="Contoh: PENGESAHAN SUSUNAN PENGURUS SATUAN KOORDINASI RAYON (SATKORYON) BARISAN ANSOR SERBAGUNA KECAMATAN GANDUSARI MASA KHIDMAT 2026-2028" 
                          required>{{ old('tentang') }}</textarea>
                <div class="form-hint">Tuliskan pokok keputusan dengan huruf kapital (misal: PENGESAHAN SUSUNAN PENGURUS... atau PENETAPAN TIM TUGAS...)</div>
                @error('tentang')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="tgl_surat" class="form-label">Tanggal Penetapan (Masehi) <span class="required">*</span></label>
                    <input type="date" 
                           id="tgl_surat" 
                           name="tgl_surat" 
                           class="form-control @error('tgl_surat') is-invalid @enderror" 
                           value="{{ old('tgl_surat', now()->toDateString()) }}" 
                           required>
                    @error('tgl_surat')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tgl_hijriyah" class="form-label">Tanggal Hijriyah (Opsional)</label>
                    <input type="text" 
                           id="tgl_hijriyah" 
                           name="tgl_hijriyah" 
                           class="form-control" 
                           value="{{ old('tgl_hijriyah') }}" 
                           placeholder="Contoh: 05 Sya'ban 1447 H">
                    <div class="form-hint">Penanggalan kalender Islam/Hijriyah resmi</div>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="ditetapkan_di" class="form-label">Tempat Ditetapkan <span class="required">*</span></label>
                    <input type="text" 
                           id="ditetapkan_di" 
                           name="ditetapkan_di" 
                           class="form-control" 
                           value="{{ old('ditetapkan_di', 'Blitar') }}" 
                           required>
                </div>

                <div class="form-group">
                    <label for="surat_masuk_id" class="form-label">Rujukan Surat Masuk (Jika Ada Permohonan SK)</label>
                    <select name="surat_masuk_id" id="surat_masuk_id" class="form-control">
                        <option value="">-- Tanpa Rujukan Surat Masuk --</option>
                        @foreach($suratMasukList as $sm)
                            <option value="{{ $sm->id }}" {{ old('surat_masuk_id') == $sm->id ? 'selected' : '' }}>
                                {{ $sm->no_surat }} - {{ $sm->dari }} ({{ Str::limit($sm->perihal, 40) }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-hint">Pilih surat masuk pemohon (misal surat permohonan SK dari Satkoryon)</div>
                </div>
            </div>

            <!-- 4. KONSIDERANS HUKUM (MENIMBANG, MENGINGAT, MEMPERHATIKAN) -->
            <div class="form-section-heading">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>2. Konsiderans Hukum SK (Menimbang, Mengingat, Memperhatikan)</span>
            </div>

            <div class="form-group">
                <label for="menimbang" class="form-label">
                    Menimbang : <span class="required">*</span>
                </label>
                <textarea id="menimbang" 
                          name="menimbang" 
                          rows="4" 
                          class="form-control @error('menimbang') is-invalid @enderror" 
                          placeholder="Pertimbangan ditetapkannya SK (butir a, b, c...)" 
                          required>{{ old('menimbang', $defaultMenimbang) }}</textarea>
                <div class="form-hint">Alasan faktual dan pertimbangan organisasi mengapa keputusan ini diterbitkan.</div>
                @error('menimbang')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="mengingat" class="form-label">
                    Mengingat : <span class="required">*</span>
                </label>
                <textarea id="mengingat" 
                          name="mengingat" 
                          rows="4" 
                          class="form-control @error('mengingat') is-invalid @enderror" 
                          placeholder="Dasar hukum / peraturan organisasi (butir 1, 2, 3...)" 
                          required>{{ old('mengingat', $defaultMengingat) }}</textarea>
                <div class="form-hint">Landasan konstitusional organisasi (PD/PRT GP Ansor, PO Banser, dsb).</div>
                @error('mengingat')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="memperhatikan" class="form-label">
                    Memperhatikan :
                </label>
                <textarea id="memperhatikan" 
                          name="memperhatikan" 
                          rows="3" 
                          class="form-control" 
                          placeholder="Hasil rapat pleno / musyawarah / rekomendasi...">{{ old('memperhatikan', $defaultMemperhatikan) }}</textarea>
                <div class="form-hint">Hasil musyawarah kerja, rapat pleno pimpinan, atau rekomendasi tertulis.</div>
            </div>

            <!-- 5. DIKTUM KEPUTUSAN (MEMUTUSKAN) -->
            <div class="form-section-heading">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>3. Diktum Keputusan (MEMUTUSKAN - MENETAPKAN)</span>
            </div>

            <div class="form-group">
                <label for="diktum_pertama" class="form-label">
                    Diktum KESATU / PERTAMA <span class="required">*</span>
                </label>
                <textarea id="diktum_pertama" 
                          name="diktum_pertama" 
                          rows="3" 
                          class="form-control @error('diktum_pertama') is-invalid @enderror" 
                          placeholder="Contoh: Mengesahkan Susunan Pengurus Satuan Koordinasi Rayon..." 
                          required>{{ old('diktum_pertama', $defaultDiktum1) }}</textarea>
                @error('diktum_pertama')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="diktum_kedua" class="form-label">
                    Diktum KEDUA
                </label>
                <textarea id="diktum_kedua" 
                          name="diktum_kedua" 
                          rows="2" 
                          class="form-control" 
                          placeholder="Tugas, wewenang, dan tanggung jawab yang diberikan...">{{ old('diktum_kedua', $defaultDiktum2) }}</textarea>
            </div>

            <div class="form-group">
                <label for="diktum_ketiga" class="form-label">
                    Diktum KETIGA
                </label>
                <textarea id="diktum_ketiga" 
                          name="diktum_ketiga" 
                          rows="2" 
                          class="form-control" 
                          placeholder="Kewajiban pelaporan dan koordinasi berkala...">{{ old('diktum_ketiga', $defaultDiktum3) }}</textarea>
            </div>

            <div class="form-group">
                <label for="diktum_keempat" class="form-label">
                    Diktum KEEMPAT (Ketentuan Berlaku & Klausul Perbaikan)
                </label>
                <textarea id="diktum_keempat" 
                          name="diktum_keempat" 
                          rows="2" 
                          class="form-control" 
                          placeholder="Keputusan ini berlaku sejak tanggal ditetapkan...">{{ old('diktum_keempat', $defaultDiktum4) }}</textarea>
            </div>

            <div class="form-group">
                <label for="diktum_kelima" class="form-label">
                    Diktum KELIMA (Opsional)
                </label>
                <textarea id="diktum_kelima" 
                          name="diktum_kelima" 
                          rows="2" 
                          class="form-control" 
                          placeholder="Klausul tambahan lain jika ada...">{{ old('diktum_kelima') }}</textarea>
            </div>

            <!-- 6. LAMPIRAN SK & TEMBUSAN -->
            <div class="form-section-heading">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                <span>4. Lampiran SK & Tembusan</span>
            </div>

            <div class="form-group">
                <label for="lampiran_sk" class="form-label">
                    Lampiran SK (Susunan Pengurus / Personil / Struktur Jabatan - Opsional)
                </label>
                <textarea id="lampiran_sk" 
                          name="lampiran_sk" 
                          rows="4" 
                          class="form-control" 
                          placeholder="Contoh:&#10;1. Kepala Satkoryon : Sahabat Ahmad (NIA: 12.11.01)&#10;2. Wakil Kepala : Sahabat Budi (NIA: 12.11.02)&#10;3. Kasetma : Sahabat Choirul (NIA: 12.11.03)">{{ old('lampiran_sk') }}</textarea>
                <div class="form-hint">Isi jika SK memiliki lembar lampiran susunan personil yang disahkan.</div>
            </div>

            <div class="form-group">
                <label for="tembusan" class="form-label">
                    Tembusan Surat Keputusan
                </label>
                <textarea id="tembusan" 
                          name="tembusan" 
                          rows="3" 
                          class="form-control" 
                          placeholder="Daftar pihak penerima tembusan SK...">{{ old('tembusan', $defaultTembusan) }}</textarea>
            </div>

            <!-- 7. PRATINJAU PEJABAT PENGESAHAN -->
            <div class="ttd-form-preview">
                <div style="font-weight: bold; font-size: 11pt; margin-bottom: 4px;">PENGESAHAN KEPUTUSAN PERSIS STANDAR ACUAN BANSER</div>
                <div style="font-size: 9.5pt; color: #4b5563;">
                    Ditandatangani oleh: <strong>Kepala Satkorcab</strong> ({{ $profil->nama_kasatkorcab ?: 'M. KHOTIB ASHARI, S. Pd.' }}), 
                    <strong>Kasetma</strong> ({{ $profil->nama_kasetma ?: 'TATANG TENI SUNARKO' }}), 
                    serta Mengetahui <strong>Ketua PC GP Ansor Kab. Blitar</strong> ({{ $profil->nama_ketua_ansor ?: 'IMAM MALIKI' }})
                </div>
            </div>

            <!-- TOMBOL AKSI SUBMIT -->
            <div style="margin-top: 28px; display: flex; gap: 12px; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('surat-keputusan.index') }}" class="btn btn-secondary">
                    Batal
                </a>

                <button type="submit" name="aksi_simpan" value="draf" class="btn btn-secondary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    <span>Simpan Sebagai Draf</span>
                </button>

                <button type="submit" name="aksi_simpan" value="ajukan" class="btn btn-primary" style="background-color: #8E1B22;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Simpan & Ajukan Verifikasi</span>
                </button>

                @php
                    $userRole = strtolower(Auth::user()->role ?? '');
                    $isVerifikator = in_array($userRole, ['super_admin', 'kasatkorcab', 'kasetma', 'pimpinan', 'admin_adminpers']);
                @endphp
                @if($isVerifikator)
                    <button type="submit" name="aksi_simpan" value="setujui" class="btn btn-gold" style="font-weight: 800;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Simpan & Terbitkan Nomor Resmi</span>
                    </button>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection
