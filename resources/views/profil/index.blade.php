@extends('layouts.app')

@section('title', 'Profil Satkorcab Banser')

@push('styles')
<style>
    .profil-hero-card {
        background: linear-gradient(135deg, var(--hero1) 0%, #3D070B 60%, #1A0305 100%);
        border-radius: var(--radius-lg);
        color: #FFFFFF;
        padding: 30px;
        margin-bottom: 26px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(90, 14, 20, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profil-hero-card::after {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(201, 162, 74, 0.25) 0%, rgba(201, 162, 74, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .profil-hero-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 24px;
        position: relative;
        z-index: 2;
    }

    .profil-hero-brand {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .profil-hero-emblem {
        width: 82px;
        height: 82px;
        object-fit: contain;
        filter: drop-shadow(0 4px 12px rgba(0,0,0,0.5));
        background: rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 6px;
        border: 1px solid rgba(201, 162, 74, 0.4);
    }

    .profil-hero-title h2 {
        font-size: 1.65rem;
        font-weight: 900;
        letter-spacing: 0.03em;
        margin-bottom: 4px;
        color: #FFFFFF;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    .profil-hero-title p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.92rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profil-badge-wilayah {
        background: rgba(201, 162, 74, 0.25);
        border: 1px solid var(--gold);
        color: #FEE7B3;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .form-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--hero1);
        padding-bottom: 12px;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--border-light);
    }

    .form-section-title svg {
        color: var(--accent);
    }

    .upload-box-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px;
        margin-top: 10px;
    }

    .upload-card {
        background: var(--surface);
        border: 2px dashed var(--border);
        border-radius: var(--radius-md);
        padding: 20px 16px;
        text-align: center;
        position: relative;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .upload-card:hover {
        border-color: var(--accent);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .preview-container {
        width: 100%;
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 10px 0;
        background: repeating-conic-gradient(#E8E5E2 0% 25%, #FFFFFF 0% 50%) 50% / 16px 16px;
        border-radius: 8px;
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .preview-container img {
        max-width: 90%;
        max-height: 90px;
        object-fit: contain;
    }

    .preview-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--ink-muted);
        font-size: 0.78rem;
        gap: 6px;
    }

    .upload-card-label {
        font-weight: 700;
        font-size: 0.92rem;
        color: var(--ink);
        margin-bottom: 4px;
    }

    .upload-card-hint {
        font-size: 0.75rem;
        color: var(--ink-muted);
        margin-bottom: 8px;
    }

    .custom-file-input {
        font-size: 0.8rem;
        width: 100%;
        color: var(--ink-muted);
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.72rem;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .status-pill.ready {
        background-color: #E8F5E9;
        color: #2E7D32;
    }

    .status-pill.empty {
        background-color: #FFF3E0;
        color: #E65100;
    }

    .submit-bar {
        position: sticky;
        bottom: 20px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        padding: 16px 24px;
        border-radius: var(--radius-lg);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 30px;
        z-index: 10;
        flex-wrap: wrap;
        gap: 16px;
    }

    .btn-save-profile {
        background: linear-gradient(135deg, var(--hero1) 0%, var(--accent) 100%);
        color: #FFFFFF;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 12px 28px;
        border-radius: var(--radius-md);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 14px rgba(142, 27, 34, 0.35);
        transition: all 0.2s ease;
    }

    .btn-save-profile:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(142, 27, 34, 0.5);
    }
</style>
@endpush

@section('content')
<div style="max-width: 1040px; margin: 0 auto; padding-bottom: 40px;">
    
    <!-- Hero Profile Banner -->
    <div class="profil-hero-card">
        <div class="profil-hero-inner">
            <div class="profil-hero-brand">
                <img src="{{ asset('images/banser-logo.png') }}" 
                     onerror="this.onerror=null; this.src='{{ asset('images/banser.png') }}';" 
                     alt="Emblem BANSER" 
                     class="profil-hero-emblem">
                <div class="profil-hero-title">
                    <h2>{{ $profil->satkorcab ?: 'SATKORCAB BANSER KABUPATEN BLITAR' }}</h2>
                    <p>
                        <span>Sistem Informasi & Tata Kelola Administrasi Persuratan</span>
                        @if($profil->kode_wilayah)
                            <span class="profil-badge-wilayah">Kode: {{ $profil->kode_wilayah }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <div style="display: flex; gap: 14px; align-items: center;">
                <img src="{{ asset('images/LOGO_ADMIN_rev_2.png') }}" 
                     onerror="this.onerror=null; this.src='{{ asset('images/adminpers-logo.png') }}';" 
                     alt="Logo Adminpers" 
                     style="height: 60px; object-fit: contain; filter: drop-shadow(0 0 10px rgba(201, 162, 74, 0.4));">
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 24px; padding: 14px 18px; border-radius: var(--radius-md); background-color: #FFEBEE; border: 1px solid #FFCDD2; color: #C62828;">
            <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; margin-bottom: 6px;">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span>Mohon periksa kesalahan input berikut:</span>
            </div>
            <ul style="padding-left: 28px; font-size: 0.9rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- KARTU 1: IDENTITAS SATKORCAB & KANTOR -->
        <div class="card" style="margin-bottom: 24px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);">
            <div class="card-body" style="padding: 26px;">
                <div class="form-section-title">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>1. Identitas Satkorcab & Sekretariat</span>
                </div>

                <div class="grid-2" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px; margin-bottom: 18px;">
                    <!-- 1. SSATKORCAB -->
                    <div class="form-group">
                        <label for="satkorcab" class="form-label" style="font-weight: 700; margin-bottom: 6px; display: block;">1. Nama SATKORCAB <span class="required" style="color: var(--red);">*</span></label>
                        <input type="text" 
                               id="satkorcab" 
                               name="satkorcab" 
                               class="form-control" 
                               style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 0.95rem;"
                               value="{{ old('satkorcab', $profil->satkorcab) }}" 
                               placeholder="Contoh: SATKORCAB BANSER KABUPATEN BLITAR" 
                               required>
                        <small style="color: var(--ink-muted); font-size: 0.8rem; margin-top: 4px; display: block;">Nama resmi kesatuan Satkorcab yang dicantumkan pada KOP & surat resmi.</small>
                    </div>

                    <!-- 2. KODE WILAYAH -->
                    <div class="form-group">
                        <label for="kode_wilayah" class="form-label" style="font-weight: 700; margin-bottom: 6px; display: block;">2. KODE WILAYAH</label>
                        <input type="text" 
                               id="kode_wilayah" 
                               name="kode_wilayah" 
                               class="form-control" 
                               style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 0.95rem;"
                               value="{{ old('kode_wilayah', $profil->kode_wilayah) }}" 
                               placeholder="Contoh: X-28 atau 13.08">
                        <small style="color: var(--ink-muted); font-size: 0.8rem; margin-top: 4px; display: block;">Kode klasifikasi teritorial persuratan wilayah BANSER.</small>
                    </div>
                </div>

                <div class="grid-2" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px;">
                    <!-- 8. E-MAIL -->
                    <div class="form-group">
                        <label for="email" class="form-label" style="font-weight: 700; margin-bottom: 6px; display: block;">8. E-MAIL RESMI SEKRETARIAT</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 0.95rem;"
                               value="{{ old('email', $profil->email) }}" 
                               placeholder="Contoh: adminpers.satkorcab@banser-blitar.id">
                        <small style="color: var(--ink-muted); font-size: 0.8rem; margin-top: 4px; display: block;">Alamat surel resmi untuk korespondensi persuratan dinas.</small>
                    </div>

                    <!-- 3. ALAMAT SEKRETARIAT -->
                    <div class="form-group">
                        <label for="alamat_sekretariat" class="form-label" style="font-weight: 700; margin-bottom: 6px; display: block;">3. ALAMAT SEKRETARIAT</label>
                        <textarea id="alamat_sekretariat" 
                                  name="alamat_sekretariat" 
                                  rows="2" 
                                  class="form-control" 
                                  style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 0.95rem;"
                                  placeholder="Contoh: Jl. Ahmad Yani No. 12, Blitar, Jawa Timur">{{ old('alamat_sekretariat', $profil->alamat_sekretariat) }}</textarea>
                        <small style="color: var(--ink-muted); font-size: 0.8rem; margin-top: 4px; display: block;">Alamat lengkap kantor markas komando / sekretariat.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- KARTU 2: DATA PIMPINAN & KOMANDO -->
        <div class="card" style="margin-bottom: 24px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);">
            <div class="card-body" style="padding: 26px;">
                <div class="form-section-title">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>2. Data Pejabat & Pimpinan Komando</span>
                </div>

                <div class="grid-2" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px; margin-bottom: 18px;">
                    <!-- 4. NAMA KETUA ANSOR -->
                    <div class="form-group">
                        <label for="nama_ketua_ansor" class="form-label" style="font-weight: 700; margin-bottom: 6px; display: block;">4. NAMA KETUA GP ANSOR</label>
                        <input type="text" 
                               id="nama_ketua_ansor" 
                               name="nama_ketua_ansor" 
                               class="form-control" 
                               style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 0.95rem;"
                               value="{{ old('nama_ketua_ansor', $profil->nama_ketua_ansor) }}" 
                               placeholder="Nama lengkap Ketua GP Ansor beserta gelar">
                        <small style="color: var(--ink-muted); font-size: 0.8rem; margin-top: 4px; display: block;">Penandatangan / pengesah surat dinas GP Ansor.</small>
                    </div>

                    <!-- 5. NAMA KASATKORCAB -->
                    <div class="form-group">
                        <label for="nama_kasatkorcab" class="form-label" style="font-weight: 700; margin-bottom: 6px; display: block;">5. NAMA KASATKORCAB</label>
                        <input type="text" 
                               id="nama_kasatkorcab" 
                               name="nama_kasatkorcab" 
                               class="form-control" 
                               style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 0.95rem;"
                               value="{{ old('nama_kasatkorcab', $profil->nama_kasatkorcab) }}" 
                               placeholder="Nama lengkap Kepala Satuan Koordinasi Cabang (Kasatkorcab)">
                        <small style="color: var(--ink-muted); font-size: 0.8rem; margin-top: 4px; display: block;">Komandan Satkorcab BANSER penerbit surat keputusan/mandat.</small>
                    </div>
                </div>

                <div class="grid-2" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px;">
                    <!-- 6. ALAMAT KASATKORCAB -->
                    <div class="form-group">
                        <label for="alamat_kasatkorcab" class="form-label" style="font-weight: 700; margin-bottom: 6px; display: block;">6. ALAMAT KASATKORCAB</label>
                        <textarea id="alamat_kasatkorcab" 
                                  name="alamat_kasatkorcab" 
                                  rows="2" 
                                  class="form-control" 
                                  style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 0.95rem;"
                                  placeholder="Alamat domisili Kasatkorcab">{{ old('alamat_kasatkorcab', $profil->alamat_kasatkorcab) }}</textarea>
                        <small style="color: var(--ink-muted); font-size: 0.8rem; margin-top: 4px; display: block;">Alamat kediaman komandan.</small>
                    </div>

                    <!-- 7. NAMA KASETMA -->
                    <div class="form-group">
                        <label for="nama_kasetma" class="form-label" style="font-weight: 700; margin-bottom: 6px; display: block;">7. NAMA KASETMA</label>
                        <input type="text" 
                               id="nama_kasetma" 
                               name="nama_kasetma" 
                               class="form-control" 
                               style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 0.95rem;"
                               value="{{ old('nama_kasetma', $profil->nama_kasetma) }}" 
                               placeholder="Nama lengkap Kepala Sekretariat Markas (Kasetma)">
                        <small style="color: var(--ink-muted); font-size: 0.8rem; margin-top: 4px; display: block;">Penanggung jawab ketatausahaan dan administrasi markas.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- KARTU 3: TEMPAT UPLOAD TANDATANGAN (POINT 9) -->
        <div class="card" style="margin-bottom: 24px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);">
            <div class="card-body" style="padding: 26px;">
                <div class="form-section-title">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span>9. Tempat Upload Tanda Tangan Digital Resmi</span>
                </div>
                <p style="color: var(--ink-muted); font-size: 0.88rem; margin-bottom: 16px;">
                    Unggah pindaian tanda tangan berlatar belakang transparan (format PNG disarankan, maksimal 3 MB). Berkas ini dapat disematkan ke dalam pengesahan surat resmi.
                </p>

                <div class="upload-box-grid">
                    <!-- TTD KETUA ANSOR -->
                    <div class="upload-card">
                        <div>
                            <div class="upload-card-label">Tanda Tangan Ketua GP Ansor</div>
                            <div class="upload-card-hint">Ketua PC GP Ansor</div>
                            
                            @if($profil->ttd_ketua_ansor)
                                <span class="status-pill ready">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Berkas Tersedia
                                </span>
                            @else
                                <span class="status-pill empty">Belum Diunggah</span>
                            @endif

                            <div class="preview-container" id="preview-box-ttd_ketua_ansor">
                                @if($profil->ttd_ketua_ansor)
                                    <img src="{{ route('profil.berkas', 'ttd_ketua_ansor') }}?v={{ time() }}" alt="TTD Ketua Ansor" id="img-ttd_ketua_ansor">
                                @else
                                    <div class="preview-placeholder">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Preview TTD</span>
                                    </div>
                                @endif
                            </div>

                            @if($profil->ttd_ketua_ansor)
                                <label style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.76rem; color: #D32F2F; cursor: pointer; margin-top: 4px;">
                                    <input type="checkbox" name="hapus_ttd_ketua_ansor" value="1">
                                    <span>Hapus berkas ini</span>
                                </label>
                            @endif
                        </div>

                        <div style="margin-top: 10px;">
                            <input type="hidden" name="b64_ttd_ketua_ansor" id="b64-ttd_ketua_ansor">
                            <input type="file" name="ttd_ketua_ansor" id="input-ttd_ketua_ansor" accept="image/*" class="custom-file-input">
                        </div>
                    </div>

                    <!-- TTD KASATKORCAB -->
                    <div class="upload-card">
                        <div>
                            <div class="upload-card-label">Tanda Tangan Kasatkorcab</div>
                            <div class="upload-card-hint">Komandan Satkorcab</div>

                            @if($profil->ttd_kasatkorcab)
                                <span class="status-pill ready">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Berkas Tersedia
                                </span>
                            @else
                                <span class="status-pill empty">Belum Diunggah</span>
                            @endif

                            <div class="preview-container" id="preview-box-ttd_kasatkorcab">
                                @if($profil->ttd_kasatkorcab)
                                    <img src="{{ route('profil.berkas', 'ttd_kasatkorcab') }}?v={{ time() }}" alt="TTD Kasatkorcab" id="img-ttd_kasatkorcab">
                                @else
                                    <div class="preview-placeholder">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Preview TTD</span>
                                    </div>
                                @endif
                            </div>

                            @if($profil->ttd_kasatkorcab)
                                <label style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.76rem; color: #D32F2F; cursor: pointer; margin-top: 4px;">
                                    <input type="checkbox" name="hapus_ttd_kasatkorcab" value="1">
                                    <span>Hapus berkas ini</span>
                                </label>
                            @endif
                        </div>

                        <div style="margin-top: 10px;">
                            <input type="hidden" name="b64_ttd_kasatkorcab" id="b64-ttd_kasatkorcab">
                            <input type="file" name="ttd_kasatkorcab" id="input-ttd_kasatkorcab" accept="image/*" class="custom-file-input">
                        </div>
                    </div>

                    <!-- TTD KASETMA -->
                    <div class="upload-card">
                        <div>
                            <div class="upload-card-label">Tanda Tangan Kasetma</div>
                            <div class="upload-card-hint">Kepala Sekretariat Markas</div>

                            @if($profil->ttd_kasetma)
                                <span class="status-pill ready">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Berkas Tersedia
                                </span>
                            @else
                                <span class="status-pill empty">Belum Diunggah</span>
                            @endif

                            <div class="preview-container" id="preview-box-ttd_kasetma">
                                @if($profil->ttd_kasetma)
                                    <img src="{{ route('profil.berkas', 'ttd_kasetma') }}?v={{ time() }}" alt="TTD Kasetma" id="img-ttd_kasetma">
                                @else
                                    <div class="preview-placeholder">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Preview TTD</span>
                                    </div>
                                @endif
                            </div>

                            @if($profil->ttd_kasetma)
                                <label style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.76rem; color: #D32F2F; cursor: pointer; margin-top: 4px;">
                                    <input type="checkbox" name="hapus_ttd_kasetma" value="1">
                                    <span>Hapus berkas ini</span>
                                </label>
                            @endif
                        </div>

                        <div style="margin-top: 10px;">
                            <input type="hidden" name="b64_ttd_kasetma" id="b64-ttd_kasetma">
                            <input type="file" name="ttd_kasetma" id="input-ttd_kasetma" accept="image/*" class="custom-file-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KARTU 4: TEMPAT UPLOAD STEMPEL (POINT 10) -->
        <div class="card" style="margin-bottom: 24px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);">
            <div class="card-body" style="padding: 26px;">
                <div class="form-section-title">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>10. Tempat Upload Stempel Resmi Organisasi</span>
                </div>
                <p style="color: var(--ink-muted); font-size: 0.88rem; margin-bottom: 16px;">
                    Unggah berkas pindaian cap / stempel basah (format PNG transparan disarankan, maksimal 3 MB).
                </p>

                <div class="upload-box-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
                    <!-- STEMPEL GP ANSOR -->
                    <div class="upload-card">
                        <div>
                            <div class="upload-card-label">Stempel Resmi GP Ansor</div>
                            <div class="upload-card-hint">Cap Stempel Pimpinan Cabang</div>

                            @if($profil->stempel_ansor)
                                <span class="status-pill ready">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Berkas Tersedia
                                </span>
                            @else
                                <span class="status-pill empty">Belum Diunggah</span>
                            @endif

                            <div class="preview-container" id="preview-box-stempel_ansor">
                                @if($profil->stempel_ansor)
                                    <img src="{{ route('profil.berkas', 'stempel_ansor') }}?v={{ time() }}" alt="Stempel GP Ansor" id="img-stempel_ansor">
                                @else
                                    <div class="preview-placeholder">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Preview Stempel Ansor</span>
                                    </div>
                                @endif
                            </div>

                            @if($profil->stempel_ansor)
                                <label style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.76rem; color: #D32F2F; cursor: pointer; margin-top: 4px;">
                                    <input type="checkbox" name="hapus_stempel_ansor" value="1">
                                    <span>Hapus berkas ini</span>
                                </label>
                            @endif
                        </div>

                        <div style="margin-top: 10px;">
                            <input type="hidden" name="b64_stempel_ansor" id="b64-stempel_ansor">
                            <input type="file" name="stempel_ansor" id="input-stempel_ansor" accept="image/*" class="custom-file-input">
                        </div>
                    </div>

                    <!-- STEMPEL BANSER -->
                    <div class="upload-card">
                        <div>
                            <div class="upload-card-label">Stempel Resmi Satkorcab BANSER</div>
                            <div class="upload-card-hint">Cap Stempel Satkorcab Banser</div>

                            <span class="status-pill {{ $profil->stempel_banser ? 'ready' : 'empty' }}" id="pill-stempel_banser">
                                @if($profil->stempel_banser)
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Berkas Tersedia
                                @else
                                    Belum Diunggah
                                @endif
                            </span>

                            <div class="preview-container" id="preview-box-stempel_banser">
                                @if($profil->stempel_banser)
                                    <img src="{{ route('profil.berkas', 'stempel_banser') }}?v={{ time() }}" alt="Stempel BANSER" id="img-stempel_banser">
                                @else
                                    <div class="preview-placeholder">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Preview Stempel Banser</span>
                                    </div>
                                @endif
                            </div>

                            @if($profil->stempel_banser)
                                <label style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.76rem; color: #D32F2F; cursor: pointer; margin-top: 4px;">
                                    <input type="checkbox" name="hapus_stempel_banser" value="1">
                                    <span>Hapus berkas ini</span>
                                </label>
                            @endif
                        </div>

                        <div style="margin-top: 10px;">
                            <input type="hidden" name="b64_stempel_banser" id="b64-stempel_banser">
                            <input type="file" name="stempel_banser" id="input-stempel_banser" accept="image/*" class="custom-file-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STICKY ACTION SUBMIT BAR -->
        <div class="submit-bar">
            <div style="display: flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" fill="none" stroke="var(--gold)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-size: 0.88rem; color: var(--ink-muted);">
                    Data profil akan menjadi rujukan utama kop surat, identitas instansi, dan legalisasi persuratan.
                </span>
            </div>

            <button type="submit" class="btn-save-profile">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <span>Simpan Perubahan Profil</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fields = [
        'ttd_ketua_ansor',
        'ttd_kasatkorcab',
        'ttd_kasetma',
        'stempel_ansor',
        'stempel_banser'
    ];

    fields.forEach(function (field) {
        const input = document.getElementById('input-' + field);
        const previewBox = document.getElementById('preview-box-' + field);
        const pill = document.getElementById('pill-' + field);
        const b64Input = document.getElementById('b64-' + field);

        if (!input || !previewBox) return;

        input.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Format berkas tidak didukung. Mohon pilih gambar format PNG, JPG, atau WEBP.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                const dataUrl = event.target.result;

                // Tampilkan preview instan di layar
                previewBox.innerHTML = '<img src="' + dataUrl + '" alt="Preview ' + field + '" style="max-width: 90%; max-height: 90px; object-fit: contain; animation: fadeIn 0.3s ease;">';

                // Jika ukuran file melebihi 1.5 MB, lakukan auto-resize di canvas client-side
                if (file.size > 1.5 * 1024 * 1024) {
                    const img = new Image();
                    img.onload = function () {
                        const canvas = document.createElement('canvas');
                        let w = img.width;
                        let h = img.height;
                        const maxDim = 1200;

                        if (w > maxDim || h > maxDim) {
                            if (w > h) {
                                h = Math.round((h * maxDim) / w);
                                w = maxDim;
                            } else {
                                w = Math.round((w * maxDim) / h);
                                h = maxDim;
                            }
                        }

                        canvas.width = w;
                        canvas.height = h;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, w, h);

                        const compressedUrl = canvas.toDataURL('image/png', 0.9);
                        if (b64Input) {
                            b64Input.value = compressedUrl;
                        }

                        if (pill) {
                            pill.className = 'status-pill ready';
                            pill.innerHTML = '<svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Dioptimasi & Siap Simpan';
                        }
                    };
                    img.src = dataUrl;
                } else {
                    if (b64Input) b64Input.value = '';
                    if (pill) {
                        const sizeKb = Math.round(file.size / 1024);
                        pill.className = 'status-pill ready';
                        pill.innerHTML = '<svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Dipilih (' + sizeKb + ' KB)';
                    }
                }
            };

            reader.readAsDataURL(file);
        });
    });
});
</script>
@endpush
