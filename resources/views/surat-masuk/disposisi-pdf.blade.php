<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Lembar Disposisi - {{ $suratMasuk->no_surat }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            color: #000000;
            background: #ffffff;
            line-height: 1.25;
        }
        @page {
            size: 215mm 330mm portrait;
            margin: 15mm 20mm 15mm 20mm;
        }
        .page {
            width: 100%;
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

        /* GARIS PEMBATAS GANDA */
        .kop-divider {
            width: 100%;
            border-top: 2.5px solid #000000;
            border-bottom: 1px solid #000000;
            height: 3px;
            margin-top: 4px;
            margin-bottom: 12px;
        }

        /* JUDUL */
        .disposisi-title-box {
            text-align: center;
            margin-bottom: 12px;
        }
        .disposisi-title {
            font-size: 13.5pt;
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

        /* TABEL DATA */
        .table-data-surat {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000000;
            margin-bottom: 10px;
        }
        .table-data-surat td {
            border: 1px solid #000000;
            padding: 5px 7px;
            font-size: 9.5pt;
            vertical-align: top;
        }
        .table-data-surat td.col-head {
            font-weight: bold;
            background-color: #f2f2f2;
            width: 125px;
        }

        .box-check {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1.5px solid #000000;
            text-align: center;
            line-height: 10px;
            font-size: 9pt;
            font-weight: bold;
        }

        /* TABEL DUA KOLOM */
        .disposisi-grid-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000000;
            margin-bottom: 12px;
        }
        .disposisi-grid-table th {
            border: 1px solid #000000;
            background-color: #f2f2f2;
            padding: 5px 8px;
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }
        .disposisi-grid-table td {
            border: 1px solid #000000;
            padding: 6px 8px;
            font-size: 9pt;
            vertical-align: top;
            width: 50%;
        }

        .checklist-item {
            margin-bottom: 4px;
            line-height: 1.25;
        }

        .catatan-box {
            border: 1px solid #000000;
            padding: 6px;
            margin-top: 8px;
            min-height: 70px;
            background: #ffffff;
            font-size: 9.5pt;
        }
        .catatan-box-title {
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 8.5pt;
            text-transform: uppercase;
        }

        /* TANDA TANGAN */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            page-break-inside: avoid;
        }
        .ttd-table td {
            vertical-align: top;
            width: 50%;
            padding: 0 10px;
            text-align: center;
            font-size: 9.5pt;
        }
        .ttd-space {
            position: relative;
            height: 55px;
            margin: 2px auto;
        }
        .ttd-img {
            max-height: 52px;
            max-width: 120px;
            position: relative;
            z-index: 2;
        }
        .stempel-img {
            position: absolute;
            left: 50%;
            top: -10px;
            margin-left: -40px;
            max-height: 70px;
            max-width: 70px;
            opacity: 0.85;
            z-index: 3;
        }
        .pejabat-name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            font-size: 10pt;
        }
    </style>
</head>
<body>
@php
    use Carbon\Carbon;
    $profil = $profil ?? \App\Models\Profil::getProfil();

    $kodeWilayah = $profil->kode_wilayah ?: 'XII-11';
    $alamatSekretariat = $profil->alamat_sekretariat ?: 'Graha NU Kabupaten Blitar Jatinom Kanigoro Kode Pos: 66171';

    // File paths for DomPDF
    $logoBanser = file_exists(public_path('images/banser-emblem.png')) 
        ? public_path('images/banser-emblem.png') 
        : (file_exists(public_path('images/banser-logo-res.png'))
            ? public_path('images/banser-logo-res.png')
            : (file_exists(public_path('images/banser-logo.png')) 
                ? public_path('images/banser-logo.png') 
                : (file_exists(public_path('images/banser.png')) ? public_path('images/banser.png') : null)));

    $logoKanan = file_exists(public_path('images/logo-banser-merahputih.png'))
        ? public_path('images/logo-banser-merahputih.png')
        : (file_exists(public_path('images/ansor-logo-res.png'))
            ? public_path('images/ansor-logo-res.png')
            : (file_exists(public_path('images/ansor-logo.png')) ? public_path('images/ansor-logo.png') : null));

    $ttdKasatkorcab = ($profil->ttd_kasatkorcab && file_exists(storage_path('app/public/' . $profil->ttd_kasatkorcab))) 
        ? storage_path('app/public/' . $profil->ttd_kasatkorcab) 
        : null;

    $stempelBanser = ($profil->stempel_banser && file_exists(storage_path('app/public/' . $profil->stempel_banser))) 
        ? storage_path('app/public/' . $profil->stempel_banser) 
        : (file_exists(public_path('images/stempel-banser.png')) ? public_path('images/stempel-banser.png') : null);

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

<div class="page">
    <!-- 1. KOP SURAT PERSIS SPH -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo-kiri">
                @if($logoBanser)
                    <img src="{{ $logoBanser }}" alt="Logo BANSER">
                @endif
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
                @if($logoKanan)
                    <img src="{{ $logoKanan }}" alt="Logo Header">
                @endif
            </td>
        </tr>
    </table>
    <div class="kop-divider"></div>

    <!-- 2. JUDUL LEMBAR DISPOSISI -->
    <div class="disposisi-title-box">
        <div class="disposisi-title">LEMBAR DISPOSISI</div>
        <div class="disposisi-agenda">NO. AGENDA : {{ sprintf('%03d/SM/SKC/%d', $suratMasuk->id, Carbon::parse($suratMasuk->tgl_diterima)->year) }}</div>
    </div>

    <!-- 3. TABEL DATA SURAT MASUK -->
    <table class="table-data-surat">
        <tr>
            <td class="col-head">Surat Dari</td>
            <td style="width: 40%;"><strong>{{ $suratMasuk->dari }}</strong></td>
            <td class="col-head" style="width: 105px;">Diterima Tgl</td>
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
                <span class="box-check">{{ strtolower($sifatSurat) == 'biasa' ? 'V' : '' }}</span> Biasa &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="box-check">{{ strtolower($sifatSurat) == 'penting' ? 'V' : '' }}</span> Penting &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="box-check">{{ str_contains(strtolower($sifatSurat), 'segera') ? 'V' : '' }}</span> Segera / Mendesak &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="box-check">{{ strtolower($sifatSurat) == 'rahasia' ? 'V' : '' }}</span> Rahasia
            </td>
        </tr>
        <tr>
            <td class="col-head">Perihal / Isi</td>
            <td colspan="3"><strong>{{ $suratMasuk->perihal }}</strong></td>
        </tr>
    </table>

    <!-- 4. TABEL DUA KOLOM -->
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
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'kasatkorcab') ? 'V' : '' }}</span> Kasatkorcab</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'wakasatkorcab') ? 'V' : '' }}</span> Wakasatkorcab</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'kasetma') ? 'V' : '' }}</span> Kasetma (Kepala Sekretariat Markas)</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'litbang') ? 'V' : '' }}</span> Biro Litbang</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'infokom') ? 'V' : '' }}</span> Biro Infokom</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'adminpers') ? 'V' : '' }}</span> Biro Adminpers</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'logistik') ? 'V' : '' }}</span> Biro Perbekalan & Logistik</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'hubungan') || str_contains($targetTujuan, 'hal') ? 'V' : '' }}</span> Biro Hubungan Antar Lembaga (HAL)</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'provost') ? 'V' : '' }}</span> Provost Banser Satkorcab</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'bagana') ? 'V' : '' }}</span> Bagana (Banser Tanggap Bencana)</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'balakar') ? 'V' : '' }}</span> Balakar (Banser Relawan Kebakaran)</div>
                    <div class="checklist-item"><span class="box-check">{{ str_contains($targetTujuan, 'baritim') ? 'V' : '' }}</span> Baritim (Banser Maritim)</div>
                    <div class="checklist-item">
                        <span class="box-check">{{ str_contains($targetTujuan, 'satkoryon') ? 'V' : '' }}</span> 
                        Kasatkoryon: <strong>{{ str_contains($targetTujuan, 'satkoryon') ? $disposisiTerakhir->tujuan : '................................' }}</strong>
                    </div>
                </td>
                <td>
                    <div class="checklist-item"><span class="box-check">{{ $instruksiTeks && (str_contains(strtolower($instruksiTeks), 'tindak') || str_contains(strtolower($instruksiTeks), 'laksana')) ? 'V' : '' }}</span> Tindak lanjuti / Laksanakan segera</div>
                    <div class="checklist-item"><span class="box-check">{{ $instruksiTeks && str_contains(strtolower($instruksiTeks), 'koordinasi') ? 'V' : '' }}</span> Koordinasikan dengan satuan terkait</div>
                    <div class="checklist-item"><span class="box-check">{{ $instruksiTeks && (str_contains(strtolower($instruksiTeks), 'hadir') || str_contains(strtolower($instruksiTeks), 'wakili')) ? 'V' : '' }}</span> Hadiri / Wakili Satkorcab BANSER</div>
                    <div class="checklist-item"><span class="box-check">{{ $instruksiTeks && (str_contains(strtolower($instruksiTeks), 'pasukan') || str_contains(strtolower($instruksiTeks), 'personil') || str_contains(strtolower($instruksiTeks), 'pengamanan')) ? 'V' : '' }}</span> Siapkan Pasukan / Pengamanan / PAM</div>
                    <div class="checklist-item"><span class="box-check">{{ $instruksiTeks && (str_contains(strtolower($instruksiTeks), 'ketahui') || str_contains(strtolower($instruksiTeks), 'pedoman')) ? 'V' : '' }}</span> Untuk diketahui & dipedomani</div>
                    <div class="checklist-item"><span class="box-check">{{ $instruksiTeks && (str_contains(strtolower($instruksiTeks), 'jadwal') || str_contains(strtolower($instruksiTeks), 'agenda')) ? 'V' : '' }}</span> Masukkan dalam agenda kegiatan</div>
                    <div class="checklist-item"><span class="box-check">{{ $instruksiTeks && str_contains(strtolower($instruksiTeks), 'lapor') ? 'V' : '' }}</span> Laporkan hasil pelaksanaan tugas</div>
                    <div class="checklist-item"><span class="box-check">{{ $instruksiTeks && str_contains(strtolower($instruksiTeks), 'arsip') ? 'V' : '' }}</span> Arsipkan / Dokumentasikan</div>

                    <div class="catatan-box">
                        <div class="catatan-box-title">Catatan Khusus Pimpinan:</div>
                        <div style="font-style: italic;">
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
    <table class="ttd-table">
        <tr>
            <td>
                <div>Diterima oleh / Tanggal :</div>
                <div class="ttd-space" style="border-bottom: 1px dotted #888888; width: 160px; margin-top: 15px;"></div>
                <div style="font-size: 8.5pt; color: #444444; margin-top: 3px;">Nama Penerima Disposisi</div>
            </td>
            <td>
                <div>Blitar, {{ $tglCetak }}</div>
                <div style="font-weight: bold; margin-top: 1px;">SATUAN KOORDINASI CABANG</div>
                <div style="font-weight: bold;">BARISAN ANSOR SERBAGUNA</div>
                <div style="margin-top: 1px; font-weight: bold;">Kepala / Kasetma</div>

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
