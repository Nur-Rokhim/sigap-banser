@extends('layouts.app')

@section('title', 'Surat Keputusan - ' . ($surat->nomor_surat ?? 'Draf SK #' . $surat->id))

@push('styles')
<style>
    /* Top Action Bar */
    .action-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .action-btn-group {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Workflow Status Bar */
    .status-tracker-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .tracker-steps {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .tracker-step-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--ink-muted);
    }
    .tracker-step-item.active {
        color: var(--accent);
    }
    .tracker-step-item.passed {
        color: var(--green);
    }
    .tracker-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--border);
    }
    .tracker-step-item.active .tracker-dot {
        background: var(--accent);
        box-shadow: 0 0 0 3px rgba(142, 27, 34, 0.2);
    }
    .tracker-step-item.passed .tracker-dot {
        background: var(--green);
    }

    /* Lembar Dokumen SK */
    .sk-document-sheet {
        background: #ffffff;
        color: #000000;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        padding: 40px 48px;
        margin: 0 auto;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11pt;
        line-height: 1.38;
        max-width: 900px;
    }
    @media (max-width: 640px) {
        .sk-document-sheet {
            padding: 20px 16px;
            font-size: 10pt;
        }
    }

    /* KOP SURAT FORM PERSIS KOP SURAT PERINTAH HARIAN */
    .sk-kop-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2px;
    }
    .sk-kop-table td {
        vertical-align: middle;
        padding: 0;
    }
    .sk-kop-logo-kiri {
        width: 85px;
        text-align: center;
        vertical-align: middle;
    }
    .sk-kop-logo-kiri img {
        max-width: 78px;
        max-height: 80px;
        height: auto;
    }
    .sk-kop-kode-wilayah {
        font-size: 9pt;
        font-weight: bold;
        text-align: center;
        margin-top: 3px;
        letter-spacing: 0.5px;
        font-family: 'Times New Roman', Times, serif;
        color: #000000;
    }
    .sk-kop-center {
        text-align: center;
        padding: 0 8px;
        color: #000000;
    }
    .sk-kop-satuan-1 {
        font-size: 14pt;
        font-weight: bold;
        letter-spacing: 0.5px;
        line-height: 1.15;
        text-transform: uppercase;
    }
    .sk-kop-satuan-2 {
        font-size: 14pt;
        font-weight: bold;
        letter-spacing: 0.5px;
        line-height: 1.15;
        text-transform: uppercase;
    }
    .sk-kop-satuan-3 {
        font-size: 13.5pt;
        font-weight: bold;
        letter-spacing: 0.5px;
        line-height: 1.15;
        text-transform: uppercase;
    }
    .sk-kop-satuan-4 {
        font-size: 14pt;
        font-weight: bold;
        letter-spacing: 1px;
        line-height: 1.25;
        text-transform: uppercase;
    }
    .sk-kop-alamat {
        font-size: 8.5pt;
        margin-top: 5px;
        line-height: 1.2;
        font-family: Arial, Helvetica, sans-serif;
    }
    .sk-kop-logo-kanan {
        width: 95px;
        text-align: center;
        vertical-align: middle;
    }
    .sk-kop-logo-kanan img {
        max-width: 90px;
        max-height: 75px;
        height: auto;
    }

    .sk-kop-divider {
        width: 100%;
        border-top: 2.5px solid #000000;
        border-bottom: 1px solid #000000;
        height: 3px;
        margin-top: 4px;
        margin-bottom: 18px;
    }

    /* JUDUL KEPUTUSAN */
    .sk-head-title {
        text-align: center;
        margin-bottom: 16px;
    }
    .sk-head-title h2 {
        font-size: 13pt;
        font-weight: bold;
        text-transform: uppercase;
        margin: 0;
        letter-spacing: 0.5px;
    }
    .sk-head-title .instansi {
        font-size: 11pt;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 2px;
    }
    .sk-head-title .nomor {
        font-size: 11pt;
        font-weight: bold;
        margin-top: 4px;
    }
    .sk-head-title .tentang-label {
        font-size: 11pt;
        font-weight: bold;
        margin-top: 6px;
        text-transform: uppercase;
    }
    .sk-head-title .tentang-text {
        font-size: 11.5pt;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 4px;
        line-height: 1.35;
        padding: 0 20px;
    }

    /* PENGANTAR */
    .sk-intro-p {
        font-size: 11pt;
        text-align: justify;
        line-height: 1.4;
        margin: 14px 0 10px 0;
    }

    /* TABEL KONSIDERANS */
    .konsiderans-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }
    .konsiderans-table td {
        vertical-align: top;
        padding: 3px 0;
        font-size: 11pt;
        line-height: 1.38;
    }
    .konsiderans-label {
        width: 120px;
        font-weight: bold;
    }
    .konsiderans-colon {
        width: 18px;
        text-align: center;
    }
    .konsiderans-val {
        text-align: justify;
        white-space: pre-line;
    }

    /* MEMUTUSKAN */
    .memutuskan-box {
        text-align: center;
        font-weight: bold;
        font-size: 11.5pt;
        letter-spacing: 1px;
        margin: 16px 0 10px 0;
        text-transform: uppercase;
    }

    /* DIKTUM */
    .diktum-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }
    .diktum-table td {
        vertical-align: top;
        padding: 4px 0;
        font-size: 11pt;
        line-height: 1.38;
    }
    .diktum-label {
        width: 120px;
        font-weight: bold;
    }
    .diktum-colon {
        width: 18px;
        text-align: center;
    }
    .diktum-val {
        text-align: justify;
    }

    /* PENETAPAN TANGGAL */
    .penetapan-block {
        margin-top: 16px;
        margin-left: auto;
        width: 280px;
        font-size: 11pt;
        line-height: 1.35;
    }

    /* TANDA TANGAN DOKUMEN PERSIS SPH */
    .sk-ttd-section {
        width: 100%;
        margin-top: 14px;
    }
    .sk-ttd-heading {
        text-align: center;
        font-weight: bold;
        font-size: 10.5pt;
        text-transform: uppercase;
        line-height: 1.2;
        margin-bottom: 6px;
    }
    .sk-ttd-table {
        width: 100%;
        border-collapse: collapse;
    }
    .sk-ttd-table td {
        width: 50%;
        vertical-align: top;
        text-align: center;
        font-size: 10.5pt;
        padding: 0 10px;
    }
    .sk-ttd-jabatan {
        font-weight: bold;
        margin-bottom: 2px;
    }
    .sk-ttd-box {
        position: relative;
        height: 65px;
        margin: 2px auto;
    }
    .sk-ttd-sig {
        max-height: 60px;
        max-width: 140px;
        object-fit: contain;
        position: relative;
        z-index: 2;
    }
    .sk-stempel-banser {
        position: absolute;
        left: 50%;
        top: -10px;
        margin-left: -50px;
        max-height: 85px;
        max-width: 85px;
        opacity: 0.9;
        z-index: 3;
    }
    .sk-pejabat-name {
        font-weight: bold;
        font-size: 10.5pt;
        text-transform: uppercase;
    }

    /* MENGETAHUI GP ANSOR */
    .sk-mengetahui {
        margin-top: 10px;
        text-align: center;
    }
    .sk-mengetahui-instansi {
        font-size: 10.5pt;
        font-weight: bold;
        text-transform: uppercase;
        line-height: 1.2;
    }
    .sk-stempel-ansor {
        position: absolute;
        left: 20px;
        top: -15px;
        max-height: 85px;
        max-width: 85px;
        opacity: 0.9;
        z-index: 3;
    }

    /* TEMBUSAN */
    .sk-tembusan {
        margin-top: 20px;
        font-size: 9.5pt;
        border-top: 1px dotted #000000;
        padding-top: 8px;
    }
    .sk-tembusan-title {
        font-weight: bold;
        margin-bottom: 2px;
    }
</style>
@endpush

@section('content')
@php
    $profil = $profil ?? \App\Models\Profil::getProfil();
    $sk = $sk ?? $surat->detailSk;

    $st = strtolower($surat->status ?? 'draf');
    $userRole = strtolower(Auth::user()->role ?? '');
    $isVerifikator = in_array($userRole, ['super_admin', 'kasatkorcab', 'kasetma', 'pimpinan', 'admin_adminpers']);
    $canEdit = ($st === 'draf' || $isVerifikator);

    $kodeWilayah = $profil->kode_wilayah ?: 'XII-11';
    $alamatSekretariat = $profil->alamat_sekretariat ?: 'Graha NU Kabupaten Blitar Jatinom Kanigoro Kode Pos: 66171';

    $logoBanser = asset('images/banser-logo.png');
    $logoKanan  = file_exists(public_path('images/logo-banser-merahputih.png')) 
        ? asset('images/logo-banser-merahputih.png') 
        : asset('images/ansor-logo.png');

    $ttdKasatkorcab = ($profil->ttd_kasatkorcab && file_exists(storage_path('app/public/' . $profil->ttd_kasatkorcab))) 
        ? asset('storage/' . $profil->ttd_kasatkorcab) 
        : null;
    $ttdKasetma = ($profil->ttd_kasetma && file_exists(storage_path('app/public/' . $profil->ttd_kasetma))) 
        ? asset('storage/' . $profil->ttd_kasetma) 
        : null;
    $ttdKetuaAnsor = ($profil->ttd_ketua_ansor && file_exists(storage_path('app/public/' . $profil->ttd_ketua_ansor))) 
        ? asset('storage/' . $profil->ttd_ketua_ansor) 
        : null;

    $stempelBanser = ($profil->stempel_banser && file_exists(storage_path('app/public/' . $profil->stempel_banser))) 
        ? asset('storage/' . $profil->stempel_banser) 
        : (file_exists(public_path('images/stempel-banser.png')) ? asset('images/stempel-banser.png') : null);
    $stempelAnsor = ($profil->stempel_ansor && file_exists(storage_path('app/public/' . $profil->stempel_ansor))) 
        ? asset('storage/' . $profil->stempel_ansor) 
        : (file_exists(public_path('images/stempel-ansor.png')) ? asset('images/stempel-ansor.png') : null);

    $bulanId = [
        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
        7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
    ];
    $tglCarbon = \Carbon\Carbon::parse($surat->tgl_surat);
    $tglFull   = $tglCarbon->day . ' ' . $bulanId[$tglCarbon->month] . ' ' . $tglCarbon->year;

    $tentangClean = $sk->tentang ?? (str_starts_with(strtoupper($surat->perihal), 'SURAT KEPUTUSAN TENTANG ') ? substr($surat->perihal, 24) : $surat->perihal);
@endphp

<div class="page-container">
    <!-- Action Header Bar -->
    <div class="action-header-bar">
        <div>
            <a href="{{ route('surat-keputusan.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.88rem; color: var(--ink-muted); text-decoration: none; margin-bottom: 6px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar SK
            </a>
            <h2 style="margin: 0; font-size: 1.45rem; font-weight: 800; color: var(--ink);">
                {{ $surat->nomor_surat ?? 'Draf Surat Keputusan' }}
            </h2>
        </div>

        <div class="action-btn-group">
            <a href="{{ route('surat-keputusan.pdf', $surat->id) }}" target="_blank" class="btn btn-primary" style="background-color: #8E1B22;" title="Cetak / Unduh Berkas PDF Resmi SK">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak PDF</span>
            </a>

            @if($st === 'draf')
                <form action="{{ route('surat-keputusan.ajukan', $surat->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Ajukan Surat Keputusan ini ke pimpinan untuk verifikasi?')">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Ajukan Verifikasi</span>
                    </button>
                </form>
            @endif

            @if($isVerifikator && in_array($st, ['draf', 'verifikasi']))
                <form action="{{ route('surat-keputusan.setujui', $surat->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-gold" style="font-weight: 800;" onclick="return confirm('Setujui Surat Keputusan ini dan terbitkan nomor resmi otomatis?')">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Setujui & Beri Nomor SK</span>
                    </button>
                </form>
            @endif

            @if($st === 'disetujui')
                <form action="{{ route('surat-keputusan.kirim', $surat->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Tandai Surat Keputusan ini telah resmi ditetapkan / dikirimkan ke satuan?')">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Tandai Ditetapkan</span>
                    </button>
                </form>
            @endif

            @if($canEdit)
                <a href="{{ route('surat-keputusan.edit', $surat->id) }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit SK</span>
                </a>
            @endif

            @if(in_array($userRole, ['super_admin', 'admin_adminpers', 'admin']))
                <button type="button" class="btn btn-secondary" style="color: var(--red); border-color: rgba(229,35,43,0.3);" onclick="confirmDeleteSK()">
                    Hapus
                </button>
                <form id="deleteSkForm" action="{{ route('surat-keputusan.destroy', $surat->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>

    <!-- Workflow Status Tracker -->
    <div class="status-tracker-card">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--ink-muted);">Alur Penetapan:</span>
            <div class="tracker-steps">
                <span class="tracker-step-item {{ in_array($st, ['draf', 'verifikasi', 'disetujui', 'terkirim']) ? 'passed' : '' }}">
                    <span class="tracker-dot"></span> 1. Draf
                </span>
                <span style="color: var(--border);">&bull;</span>
                <span class="tracker-step-item {{ in_array($st, ['verifikasi', 'disetujui', 'terkirim']) ? ($st === 'verifikasi' ? 'active' : 'passed') : '' }}">
                    <span class="tracker-dot"></span> 2. Verifikasi Pimpinan
                </span>
                <span style="color: var(--border);">&bull;</span>
                <span class="tracker-step-item {{ in_array($st, ['disetujui', 'terkirim']) ? ($st === 'disetujui' ? 'active' : 'passed') : '' }}">
                    <span class="tracker-dot"></span> 3. Disetujui (Bernomor Resmi)
                </span>
                <span style="color: var(--border);">&bull;</span>
                <span class="tracker-step-item {{ $st === 'terkirim' ? 'passed' : '' }}">
                    <span class="tracker-dot"></span> 4. Ditetapkan / Terkirim
                </span>
            </div>
        </div>

        <div>
            @if($st === 'draf')
                <span class="badge badge-gray">Draf Pengonsepan</span>
            @elseif($st === 'verifikasi')
                <span class="badge badge-yellow">Menunggu Verifikasi Pimpinan</span>
            @elseif($st === 'disetujui')
                <span class="badge badge-green">Disetujui & Bernomor Resmi</span>
            @elseif($st === 'terkirim')
                <span class="badge badge-blue">Resmi Ditetapkan</span>
            @endif
        </div>
    </div>

    <!-- DOKUMEN FISIK SURAT KEPUTUSAN -->
    <div class="sk-document-sheet">
        <!-- 1. KOP SURAT FORM PERSIS DENGAN KOP SURAT PERINTAH HARIAN (SPH) -->
        <table class="sk-kop-table">
            <tr>
                <td class="sk-kop-logo-kiri">
                    <img src="{{ $logoBanser }}" alt="Logo BANSER">
                    <div class="sk-kop-kode-wilayah">{{ $kodeWilayah }}</div>
                </td>
                <td class="sk-kop-center">
                    <div class="sk-kop-satuan-1">SATUAN KOORDINASI CABANG</div>
                    <div class="sk-kop-satuan-2">BARISAN ANSOR SERBAGUNA</div>
                    <div class="sk-kop-satuan-3">(SATKORCAB BANSER)</div>
                    <div class="sk-kop-satuan-4">KABUPATEN BLITAR</div>
                    <div class="sk-kop-alamat">Sekretariat : {{ $alamatSekretariat }}</div>
                </td>
                <td class="sk-kop-logo-kanan">
                    <img src="{{ $logoKanan }}" alt="Logo Header">
                </td>
            </tr>
        </table>
        <div class="sk-kop-divider"></div>

        <!-- 2. JUDUL DAN NOMOR SURAT KEPUTUSAN -->
        <div class="sk-head-title">
            <h2>SURAT KEPUTUSAN</h2>
            <div class="instansi">SATUAN KOORDINASI CABANG BARISAN ANSOR SERBAGUNA</div>
            <div class="instansi">KABUPATEN BLITAR</div>
            <div class="nomor">Nomor : <strong>{{ $surat->nomor_surat ?? 'DRAF (Menunggu Verifikasi)' }}</strong></div>
            <div class="tentang-label">TENTANG</div>
            <div class="tentang-text">{{ $tentangClean }}</div>
        </div>

        <!-- 3. PEMBUKA KEPUTUSAN -->
        <div class="sk-intro-p">
            Dengan senantiasa memohon petunjuk, bimbingan, dan ridho Allah Subhanahu Wa Ta'ala, Kepala Satuan Koordinasi Cabang (SATKORCAB) Barisan Ansor Serbaguna Kabupaten Blitar :
        </div>

        <!-- 4. KONSIDERANS MENIMBANG, MENGINGAT, MEMPERHATIKAN -->
        <table class="konsiderans-table">
            <tr>
                <td class="konsiderans-label">Menimbang</td>
                <td class="konsiderans-colon">:</td>
                <td class="konsiderans-val">{!! nl2br(e($sk->menimbang ?? '-')) !!}</td>
            </tr>
            <tr>
                <td class="konsiderans-label">Mengingat</td>
                <td class="konsiderans-colon">:</td>
                <td class="konsiderans-val">{!! nl2br(e($sk->mengingat ?? '-')) !!}</td>
            </tr>
            @if(!empty($sk->memperhatikan))
            <tr>
                <td class="konsiderans-label">Memperhatikan</td>
                <td class="konsiderans-colon">:</td>
                <td class="konsiderans-val">{!! nl2br(e($sk->memperhatikan)) !!}</td>
            </tr>
            @endif
        </table>

        <!-- 5. MEMUTUSKAN -->
        <div class="memutuskan-box">
            MEMUTUSKAN :
        </div>

        <!-- 6. DIKTUM KEPUTUSAN -->
        <table class="diktum-table">
            <tr>
                <td class="diktum-label">Menetapkan</td>
                <td class="diktum-colon">:</td>
                <td class="diktum-val"><strong>KEPUTUSAN SATUAN KOORDINASI CABANG BARISAN ANSOR SERBAGUNA KABUPATEN BLITAR TENTANG {{ $tentangClean }}</strong></td>
            </tr>
            <tr>
                <td class="diktum-label">KESATU</td>
                <td class="diktum-colon">:</td>
                <td class="diktum-val">{!! nl2br(e($sk->diktum_pertama ?? '-')) !!}</td>
            </tr>
            @if(!empty($sk->diktum_kedua))
            <tr>
                <td class="diktum-label">KEDUA</td>
                <td class="diktum-colon">:</td>
                <td class="diktum-val">{!! nl2br(e($sk->diktum_kedua)) !!}</td>
            </tr>
            @endif
            @if(!empty($sk->diktum_ketiga))
            <tr>
                <td class="diktum-label">KETIGA</td>
                <td class="diktum-colon">:</td>
                <td class="diktum-val">{!! nl2br(e($sk->diktum_ketiga)) !!}</td>
            </tr>
            @endif
            @if(!empty($sk->diktum_keempat))
            <tr>
                <td class="diktum-label">KEEMPAT</td>
                <td class="diktum-colon">:</td>
                <td class="diktum-val">{!! nl2br(e($sk->diktum_keempat)) !!}</td>
            </tr>
            @endif
            @if(!empty($sk->diktum_kelima))
            <tr>
                <td class="diktum-label">KELIMA</td>
                <td class="diktum-colon">:</td>
                <td class="diktum-val">{!! nl2br(e($sk->diktum_kelima)) !!}</td>
            </tr>
            @endif
        </table>

        <!-- 7. PENETAPAN TANGGAL & TEMPAT -->
        <div class="penetapan-block">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 110px;">Ditetapkan di</td>
                    <td style="width: 15px;">:</td>
                    <td><strong>{{ $sk->ditetapkan_di ?? 'Blitar' }}</strong></td>
                </tr>
                <tr>
                    <td>Pada tanggal</td>
                    <td>:</td>
                    <td><strong>{{ $tglFull }}</strong></td>
                </tr>
                @if(!empty($sk->tgl_hijriyah))
                <tr>
                    <td>Bertepatan</td>
                    <td>:</td>
                    <td>{{ $sk->tgl_hijriyah }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- 8. FORMAT TANDA TANGAN RESMI PERSIS ACUAN BANSER -->
        <div class="sk-ttd-section">
            <div class="sk-ttd-heading">
                SATUAN KOORDINASI CABANG<br>
                BARISAN ANSOR SERBAGUNA
            </div>

            <table class="sk-ttd-table">
                <tr>
                    <!-- KIRI: KEPALA SATKORCAB -->
                    <td>
                        <div class="sk-ttd-jabatan">Kepala</div>
                        <div class="sk-ttd-box">
                            @if($ttdKasatkorcab)
                                <img src="{{ $ttdKasatkorcab }}" alt="TTD Kepala" class="sk-ttd-sig">
                            @endif
                        </div>
                        <div class="sk-pejabat-name">{{ $profil->nama_kasatkorcab ?: 'M. KHOTIB ASHARI, S. Pd.' }}</div>
                    </td>

                    <!-- KANAN: KASETMA + STEMPEL BANSER -->
                    <td>
                        <div class="sk-ttd-jabatan">Kasetma</div>
                        <div class="sk-ttd-box">
                            @if($stempelBanser)
                                <img src="{{ $stempelBanser }}" alt="Stempel BANSER" class="sk-stempel-banser">
                            @endif
                            @if($ttdKasetma)
                                <img src="{{ $ttdKasetma }}" alt="TTD Kasetma" class="sk-ttd-sig">
                            @endif
                        </div>
                        <div class="sk-pejabat-name">{{ $profil->nama_kasetma ?: 'TATANG TENI SUNARKO' }}</div>
                    </td>
                </tr>
            </table>

            <!-- BAWAH TENGAH: MENGETAHUI GP ANSOR + STEMPEL ANSOR KIRI -->
            <div class="sk-mengetahui">
                <div style="font-weight: bold; margin-bottom: 2px;">Mengetahui</div>
                <div class="sk-mengetahui-instansi">
                    PIMPINAN CABANG GERAKAN PEMUDA ANSOR<br>
                    KABUPATEN BLITAR
                </div>
                <div style="font-weight: bold; margin-top: 2px;">Ketua</div>

                <div class="sk-ttd-box" style="width: 260px;">
                    @if($stempelAnsor)
                        <img src="{{ $stempelAnsor }}" alt="Stempel ANSOR" class="sk-stempel-ansor">
                    @endif
                    @if($ttdKetuaAnsor)
                        <img src="{{ $ttdKetuaAnsor }}" alt="TTD Ketua Ansor" class="sk-ttd-sig">
                    @endif
                </div>

                <div class="sk-pejabat-name">{{ $profil->nama_ketua_ansor ?: 'IMAM MALIKI' }}</div>
            </div>
        </div>

        <!-- 9. LAMPIRAN SK JIKA ADA -->
        @if(!empty($sk->lampiran_sk))
            <div style="margin-top: 24px; padding-top: 14px; border-top: 1.5px dashed #000000;">
                <div style="font-weight: bold; font-size: 11pt; text-decoration: underline; margin-bottom: 6px;">
                    LAMPIRAN SURAT KEPUTUSAN :
                </div>
                <div style="white-space: pre-line; line-height: 1.4;">{!! nl2br(e($sk->lampiran_sk)) !!}</div>
            </div>
        @endif

        <!-- 10. TEMBUSAN -->
        @if(!empty($sk->tembusan))
            <div class="sk-tembusan">
                <div class="sk-tembusan-title">Tembusan Kepada Yth :</div>
                <div style="white-space: pre-line;">{!! nl2br(e($sk->tembusan)) !!}</div>
            </div>
        @endif
    </div>
</div>

<script>
function confirmDeleteSK() {
    if (confirm('Apakah Anda yakin ingin menghapus Surat Keputusan ini? Tindakan ini tidak dapat dibatalkan.')) {
        document.getElementById('deleteSkForm').submit();
    }
}
</script>
@endsection
