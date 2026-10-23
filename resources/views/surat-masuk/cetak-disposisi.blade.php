<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Disposisi - {{ $suratMasuk->no_surat }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000000;
            background: #e5e7eb;
            line-height: 1.3;
            padding: 20px 0;
        }

        /* Top Action Bar (Screen Only) */
        .no-print-bar {
            width: 215mm;
            margin: 0 auto 16px auto;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: 'Public Sans', sans-serif;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            font-size: 0.9rem;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }
        .btn-print {
            background-color: #8E1B22;
            color: #ffffff;
        }
        .btn-print:hover {
            background-color: #72141A;
        }
        .btn-pdf {
            background-color: #1E3A8A;
            color: #ffffff;
        }
        .btn-pdf:hover {
            background-color: #172554;
        }
        .btn-back {
            background-color: #F3F4F6;
            color: #374151;
            border: 1px solid #D1D5DB;
        }
        .btn-back:hover {
            background-color: #E5E7EB;
        }

        /* Lembar Dokumen F4 / Folio */
        .sheet {
            width: 215mm;
            min-height: 330mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 15mm 20mm 20mm 20mm;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            position: relative;
        }

        @page {
            size: 215mm 330mm portrait;
            margin: 15mm 20mm 15mm 20mm;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .no-print-bar {
                display: none !important;
            }
            .sheet {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }

        /* KOP SURAT RESMI BANSER PERSIS SPH */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .kop-table td {
            vertical-align: middle;
            padding: 0;
        }
        .kop-logo-kiri {
            width: 85px;
            text-align: center;
            vertical-align: middle;
        }
        .kop-logo-kiri img {
            max-width: 78px;
            max-height: 80px;
            height: auto;
        }
        .kop-kode-wilayah {
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
            margin-top: 3px;
            letter-spacing: 0.5px;
            font-family: 'Times New Roman', Times, serif;
        }
        .kop-center-text {
            text-align: center;
            padding: 0 6px;
        }
        .kop-satuan-1 {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            line-height: 1.15;
            text-transform: uppercase;
        }
        .kop-satuan-2 {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            line-height: 1.15;
            text-transform: uppercase;
        }
        .kop-satuan-3 {
            font-size: 13.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            line-height: 1.15;
            text-transform: uppercase;
        }
        .kop-satuan-4 {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 1px;
            line-height: 1.25;
            text-transform: uppercase;
        }
        .kop-alamat {
            font-size: 8.5pt;
            margin-top: 5px;
            line-height: 1.2;
            font-family: Arial, Helvetica, sans-serif;
        }
        .kop-logo-kanan {
            width: 95px;
            text-align: center;
            vertical-align: middle;
        }
        .kop-logo-kanan img {
            max-width: 90px;
            max-height: 75px;
            height: auto;
        }

        /* GARIS PEMBATAS GANDA (TEBAL ATAS, TIPIS BAWAH) */
        .kop-divider {
            width: 100%;
            border-top: 2.5px solid #000000;
            border-bottom: 1px solid #000000;
            height: 3px;
            margin-top: 4px;
            margin-bottom: 10px;
        }

        /* JUDUL LEMBAR DISPOSISI */
        .disposisi-title-box {
            text-align: center;
            margin-bottom: 12px;
        }
        .disposisi-title {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .disposisi-agenda {
            font-size: 10pt;
            margin-top: 2px;
            font-weight: bold;
        }

        /* TABEL DATA SURAT MASUK */
        .table-data-surat {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000000;
            margin-bottom: 10px;
        }
        .table-data-surat td {
            border: 1px solid #000000;
            padding: 5px 8px;
            font-size: 10pt;
            vertical-align: top;
        }
        .table-data-surat td.col-head {
            font-weight: bold;
            background-color: #f3f4f6;
            width: 130px;
        }

        /* SIFAT SURAT */
        .checkbox-group {
            display: inline-flex;
            gap: 15px;
            align-items: center;
        }
        .cb-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .box-check {
            display: inline-block;
            width: 13px;
            height: 13px;
            border: 1.5px solid #000000;
            text-align: center;
            line-height: 11px;
            font-size: 10pt;
            font-weight: bold;
        }

        /* TABEL DUA KOLOM: DITERUSKAN & INSTRUKSI */
        .disposisi-grid-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000000;
            margin-bottom: 12px;
        }
        .disposisi-grid-table th {
            border: 1px solid #000000;
            background-color: #f3f4f6;
            padding: 6px 8px;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }
        .disposisi-grid-table td {
            border: 1px solid #000000;
            padding: 8px 10px;
            font-size: 9.5pt;
            vertical-align: top;
            width: 50%;
        }

        .checklist-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .checklist-list li {
            margin-bottom: 5px;
            display: flex;
            align-items: flex-start;
            gap: 6px;
            line-height: 1.25;
        }

        /* KOTAK CATATAN KHUSUS */
        .catatan-box {
            border: 1px solid #000000;
            padding: 8px;
            margin-top: 8px;
            min-height: 75px;
            background: #ffffff;
            font-size: 10pt;
        }
        .catatan-box-title {
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 9pt;
            text-transform: uppercase;
            color: #374151;
        }

        /* TANDA TANGAN DISPOSISI */
        .ttd-disposisi-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .ttd-disposisi-table td {
            vertical-align: top;
            width: 50%;
            padding: 0 10px;
            text-align: center;
            font-size: 10pt;
        }
        .ttd-space {
            position: relative;
            height: 60px;
            margin: 4px auto;
        }
        .ttd-img {
            max-height: 55px;
            max-width: 130px;
            object-fit: contain;
            position: relative;
            z-index: 2;
        }
        .stempel-img {
            position: absolute;
            left: 50%;
            top: -10px;
            margin-left: -40px;
            max-height: 75px;
            max-width: 75px;
            opacity: 0.85;
            z-index: 3;
        }
        .pejabat-name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            font-size: 10.5pt;
        }
    </style>
</head>
<body>

@php
    use Carbon\Carbon;
    $profil = $profil ?? \App\Models\Profil::getProfil();

    $kodeWilayah = $profil->kode_wilayah ?: 'XII-11';
    $alamatSekretariat = $profil->alamat_sekretariat ?: 'Graha NU Kabupaten Blitar Jatinom Kanigoro Kode Pos: 66171';

    // Logo URLs for Web View
    $logoBanser = asset('images/banser-logo.png');
    $logoKanan  = file_exists(public_path('images/logo-banser-merahputih.png')) 
        ? asset('images/logo-banser-merahputih.png') 
        : asset('images/ansor-logo.png');

    // TTD and Stempel
    $ttdKasatkorcab = ($profil->ttd_kasatkorcab && file_exists(storage_path('app/public/' . $profil->ttd_kasatkorcab))) 
        ? asset('storage/' . $profil->ttd_kasatkorcab) 
        : null;
    $stempelBanser = ($profil->stempel_banser && file_exists(storage_path('app/public/' . $profil->stempel_banser))) 
        ? asset('storage/' . $profil->stempel_banser) 
        : (file_exists(public_path('images/stempel-banser.png')) ? asset('images/stempel-banser.png') : null);

    // Ambil data disposisi terakhir jika ada
    $disposisiTerakhir = $suratMasuk->disposisi ? $suratMasuk->disposisi->last() : null;
    $sifatSurat = $disposisiTerakhir ? $disposisiTerakhir->sifat : 'Biasa';
    $targetTujuan = $disposisiTerakhir ? strtolower($disposisiTerakhir->tujuan) : '';
    $instruksiTeks = $disposisiTerakhir ? $disposisiTerakhir->instruksi : null;

    $bulanId = [
        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
        7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
    ];
    $now = Carbon::now();
    $tglCetak = $now->day . ' ' . $bulanId[$now->month] . ' ' . $now->year;
@endphp

<!-- Action Bar di Layar -->
<div class="no-print-bar">
    <div style="display: flex; align-items: center; gap: 10px;">
        <a href="{{ route('surat-masuk.show', $suratMasuk->id) }}" class="btn-action btn-back">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Detail Surat</span>
        </a>
        <strong style="font-size: 0.95rem; color: #1f2937;">Format Cetak Lembar Disposisi</strong>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('surat-masuk.disposisi.pdf', $suratMasuk->id) }}" class="btn-action btn-pdf" target="_blank">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>Unduh PDF</span>
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak Sekarang (Print)</span>
        </button>
    </div>
</div>

<!-- Lembar Disposisi Fisik -->
<div class="sheet">
    <!-- 1. KOP SURAT RESMI SAMA PERSIS DENGAN SPH -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo-kiri">
                <img src="{{ $logoBanser }}" alt="Logo BANSER">
                <div class="kop-kode-wilayah">{{ $kodeWilayah }}</div>
            </td>
            <td class="kop-center-text">
                <div class="kop-satuan-1">SATUAN KOORDINASI CABANG</div>
                <div class="kop-satuan-2">BARISAN ANSOR SERBAGUNA</div>
                <div class="kop-satuan-3">(SATKORCAB BANSER)</div>
                <div class="kop-satuan-4">KABUPATEN BLITAR</div>
                <div class="kop-alamat">Sekretariat : {{ $alamatSekretariat }}</div>
            </td>
            <td class="kop-logo-kanan">
                <img src="{{ $logoKanan }}" alt="Logo Header">
            </td>
        </tr>
    </table>
    <div class="kop-divider"></div>

    <!-- 2. JUDUL LEMBAR DISPOSISI -->
    <div class="disposisi-title-box">
        <div class="disposisi-title">LEMBAR DISPOSISI</div>
        <div class="disposisi-agenda">NO. AGENDA : {{ sprintf('%03d/SM/SKC/%d', $suratMasuk->id, Carbon::parse($suratMasuk->tgl_diterima)->year) }}</div>
    </div>

    <!-- 3. TABEL INFORMASI SURAT MASUK -->
    <table class="table-data-surat">
        <tr>
            <td class="col-head">Surat Dari</td>
            <td style="width: 40%;"><strong>{{ $suratMasuk->dari }}</strong></td>
            <td class="col-head" style="width: 110px;">Diterima Tgl</td>
            <td>{{ Carbon::parse($suratMasuk->tgl_diterima)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="col-head">Nomor Surat</td>
            <td><strong>{{ $suratMasuk->no_surat }}</strong></td>
            <td class="col-head">Tanggal Surat</td>
            <td>{{ $suratMasuk->tgl_surat ? Carbon::parse($suratMasuk->tgl_surat)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="col-head">Sifat Surat</td>
            <td colspan="3">
                <div class="checkbox-group">
                    <span class="cb-item"><span class="box-check">{{ strtolower($sifatSurat) == 'biasa' ? '✓' : '' }}</span> Biasa</span>
                    <span class="cb-item"><span class="box-check">{{ strtolower($sifatSurat) == 'penting' ? '✓' : '' }}</span> Penting</span>
                    <span class="cb-item"><span class="box-check">{{ str_contains(strtolower($sifatSurat), 'segera') ? '✓' : '' }}</span> Segera / Mendesak</span>
                    <span class="cb-item"><span class="box-check">{{ strtolower($sifatSurat) == 'rahasia' ? '✓' : '' }}</span> Rahasia</span>
                </div>
            </td>
        </tr>
        <tr>
            <td class="col-head">Perihal / Isi</td>
            <td colspan="3" style="line-height: 1.35;"><strong>{{ $suratMasuk->perihal }}</strong></td>
        </tr>
    </table>

    <!-- 4. TABEL DUA KOLOM: DITERUSKAN KEPADA & INSTRUKSI / PETUNJUK -->
    <table class="disposisi-grid-table">
        <thead>
            <tr>
                <th>DITERUSKAN KEPADA SAHABAT:</th>
                <th>DISPOSISI / PETUNJUK PIMPINAN:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <ul class="checklist-list">
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'kasatkorcab') ? '✓' : '' }}</span> Kasatkorcab</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'wakasatkorcab') ? '✓' : '' }}</span> Wakasatkorcab</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'kasetma') ? '✓' : '' }}</span> Kasetma (Kepala Sekretariat Markas)</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'litbang') ? '✓' : '' }}</span> Biro Penelitian & Pengembangan (Litbang)</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'infokom') ? '✓' : '' }}</span> Biro Informasi & Komunikasi (Infokom)</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'adminpers') ? '✓' : '' }}</span> Biro Administrasi & Personalia (Adminpers)</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'logistik') ? '✓' : '' }}</span> Biro Perbekalan & Logistik</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'hubungan') || str_contains($targetTujuan, 'hal') ? '✓' : '' }}</span> Biro Hubungan Antar Lembaga (HAL)</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'provost') ? '✓' : '' }}</span> Provost Banser Satkorcab</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'bagana') ? '✓' : '' }}</span> Bagana (Banser Tanggap Bencana)</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'balakar') ? '✓' : '' }}</span> Balakar (Banser Relawan Kebakaran)</li>
                        <li><span class="box-check">{{ str_contains($targetTujuan, 'baritim') ? '✓' : '' }}</span> Baritim (Banser Maritim)</li>
                        <li>
                            <span class="box-check">{{ str_contains($targetTujuan, 'satkoryon') ? '✓' : '' }}</span> 
                            Kasatkoryon: <strong>{{ str_contains($targetTujuan, 'satkoryon') ? $disposisiTerakhir->tujuan : '.........................................' }}</strong>
                        </li>
                    </ul>
                </td>
                <td>
                    <ul class="checklist-list">
                        <li><span class="box-check">{{ $instruksiTeks && (str_contains(strtolower($instruksiTeks), 'tindak') || str_contains(strtolower($instruksiTeks), 'laksana')) ? '✓' : '' }}</span> Tindak lanjuti / Laksanakan segera</li>
                        <li><span class="box-check">{{ $instruksiTeks && str_contains(strtolower($instruksiTeks), 'koordinasi') ? '✓' : '' }}</span> Koordinasikan dengan satuan terkait</li>
                        <li><span class="box-check">{{ $instruksiTeks && (str_contains(strtolower($instruksiTeks), 'hadir') || str_contains(strtolower($instruksiTeks), 'wakili')) ? '✓' : '' }}</span> Hadiri / Wakili Satkorcab BANSER</li>
                        <li><span class="box-check">{{ $instruksiTeks && (str_contains(strtolower($instruksiTeks), 'pasukan') || str_contains(strtolower($instruksiTeks), 'personil') || str_contains(strtolower($instruksiTeks), 'pengamanan')) ? '✓' : '' }}</span> Siapkan Pasukan / Pengamanan / PAM</li>
                        <li><span class="box-check">{{ $instruksiTeks && (str_contains(strtolower($instruksiTeks), 'ketahui') || str_contains(strtolower($instruksiTeks), 'pedoman')) ? '✓' : '' }}</span> Untuk diketahui & dipedomani</li>
                        <li><span class="box-check">{{ $instruksiTeks && (str_contains(strtolower($instruksiTeks), 'jadwal') || str_contains(strtolower($instruksiTeks), 'agenda')) ? '✓' : '' }}</span> Masukkan dalam agenda / kalender kegiatan</li>
                        <li><span class="box-check">{{ $instruksiTeks && str_contains(strtolower($instruksiTeks), 'lapor') ? '✓' : '' }}</span> Laporkan hasil pelaksanaan tugas</li>
                        <li><span class="box-check">{{ $instruksiTeks && str_contains(strtolower($instruksiTeks), 'arsip') ? '✓' : '' }}</span> Arsipkan / Dokumentasikan</li>
                    </ul>

                    <div class="catatan-box">
                        <div class="catatan-box-title">Catatan / Petunjuk Tambahan Pimpinan:</div>
                        <div style="font-style: italic; color: #111827;">
                            @if($instruksiTeks)
                                "{!! nl2br(e($instruksiTeks)) !!}"
                            @else
                                &nbsp;<br>&nbsp;
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- 5. TANDA TANGAN DISPOSISI PIMPINAN -->
    <table class="ttd-disposisi-table">
        <tr>
            <td>
                <!-- Ruang untuk paraf penerima disposisi -->
                <div>Diterima oleh / Tanggal :</div>
                <div class="ttd-space" style="border-bottom: 1px dotted #9ca3af; width: 170px; margin-top: 15px;"></div>
                <div style="font-size: 9pt; color: #4b5563; margin-top: 4px;">Nama Penerima Disposisi</div>
            </td>
            <td>
                <div>Blitar, {{ $tglCetak }}</div>
                <div style="font-weight: bold; margin-top: 2px;">SATUAN KOORDINASI CABANG</div>
                <div style="font-weight: bold;">BARISAN ANSOR SERBAGUNA</div>
                <div style="margin-top: 2px; font-weight: bold;">Kepala / Kasetma</div>

                <div class="ttd-space">
                    @if($stempelBanser)
                        <img src="{{ $stempelBanser }}" alt="Stempel Banser" class="stempel-img">
                    @endif
                    @if($ttdKasatkorcab)
                        <img src="{{ $ttdKasatkorcab }}" alt="TTD Pimpinan" class="ttd-img">
                    @endif
                </div>

                <div class="pejabat-name">{{ $profil->nama_kasatkorcab ?: 'M. KHOTIB ASHARI, S. Pd.' }}</div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
