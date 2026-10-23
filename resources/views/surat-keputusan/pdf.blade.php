<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ $surat->nomor_surat ?? 'Draf Surat Keputusan' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000000;
            background: #ffffff;
            line-height: 1.35;
        }
        @page {
            size: 215mm 330mm portrait;
            margin: 18mm 20mm 18mm 20mm;
        }
        .page {
            width: 100%;
        }

        /* KOP SURAT RESMI BANSER PERSIS KOP SURAT PERINTAH HARIAN */
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
            margin-bottom: 14px;
        }

        /* JUDUL KEPUTUSAN */
        .sk-head-title {
            text-align: center;
            margin-bottom: 14px;
        }
        .sk-head-title h2 {
            font-size: 12.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .sk-head-title .instansi {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 1px;
        }
        .sk-head-title .nomor {
            font-size: 10.5pt;
            font-weight: bold;
            margin-top: 3px;
        }
        .sk-head-title .tentang-label {
            font-size: 10.5pt;
            font-weight: bold;
            margin-top: 5px;
            text-transform: uppercase;
        }
        .sk-head-title .tentang-text {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 3px;
            line-height: 1.3;
            padding: 0 10px;
        }

        /* PENGANTAR */
        .sk-intro-p {
            font-size: 10.5pt;
            text-align: justify;
            line-height: 1.35;
            margin: 10px 0 8px 0;
        }

        /* TABEL KONSIDERANS */
        .konsiderans-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .konsiderans-table td {
            vertical-align: top;
            padding: 2px 0;
            font-size: 10.5pt;
            line-height: 1.35;
        }
        .konsiderans-label {
            width: 110px;
            font-weight: bold;
        }
        .konsiderans-colon {
            width: 15px;
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
            font-size: 11pt;
            letter-spacing: 1px;
            margin: 12px 0 8px 0;
            text-transform: uppercase;
        }

        /* DIKTUM */
        .diktum-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .diktum-table td {
            vertical-align: top;
            padding: 3px 0;
            font-size: 10.5pt;
            line-height: 1.35;
        }
        .diktum-label {
            width: 110px;
            font-weight: bold;
        }
        .diktum-colon {
            width: 15px;
            text-align: center;
        }
        .diktum-val {
            text-align: justify;
        }

        /* PENETAPAN TANGGAL */
        .penetapan-block {
            margin-top: 10px;
            margin-left: 55%;
            width: 45%;
            font-size: 10.5pt;
            line-height: 1.3;
        }

        /* FORMAT TANDA TANGAN PERSIS SPH */
        .sk-ttd-section {
            width: 100%;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .sk-ttd-heading {
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 4px;
        }
        .sk-ttd-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sk-ttd-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 10pt;
            padding: 0 5px;
        }
        .sk-ttd-jabatan {
            font-weight: bold;
            margin-bottom: 2px;
        }
        .sk-ttd-box {
            position: relative;
            height: 60px;
            margin: 2px auto;
        }
        .sk-ttd-sig {
            max-height: 58px;
            max-width: 135px;
            position: relative;
            z-index: 2;
        }
        .sk-stempel-banser {
            position: absolute;
            left: 50%;
            top: -10px;
            margin-left: -48px;
            max-height: 80px;
            max-width: 80px;
            opacity: 0.9;
            z-index: 3;
        }
        .sk-pejabat-name {
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
        }

        /* MENGETAHUI GP ANSOR */
        .sk-mengetahui {
            margin-top: 8px;
            text-align: center;
            page-break-inside: avoid;
        }
        .sk-mengetahui-instansi {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .sk-stempel-ansor {
            position: absolute;
            left: 20px;
            top: -12px;
            max-height: 80px;
            max-width: 80px;
            opacity: 0.9;
            z-index: 3;
        }

        /* TEMBUSAN */
        .sk-tembusan {
            margin-top: 14px;
            font-size: 9pt;
            border-top: 1px dotted #000000;
            padding-top: 6px;
            page-break-inside: avoid;
        }
        .sk-tembusan-title {
            font-weight: bold;
            margin-bottom: 2px;
        }

        /* WATERMARK DRAF */
        .stamp-draf {
            position: fixed;
            top: 110mm;
            left: 15mm;
            width: 180mm;
            text-align: center;
            font-size: 60pt;
            font-weight: bold;
            color: rgba(180, 0, 0, 0.08);
            text-transform: uppercase;
            letter-spacing: 10px;
            transform: rotate(-35deg);
            z-index: -1;
        }
    </style>
</head>
<body>
@php
    use Carbon\Carbon;
    $profil = $profil ?? \App\Models\Profil::getProfil();
    $sk = $sk ?? $surat->detailSk;

    $isDraf = empty($surat->nomor_surat) || strtolower($surat->status ?? 'draf') === 'draf';

    $bulanId = [
        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
        7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
    ];
    $tglCarbon = Carbon::parse($surat->tgl_surat);
    $tglFull   = $tglCarbon->day . ' ' . $bulanId[$tglCarbon->month] . ' ' . $tglCarbon->year;

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

    $ttdKasatkorcabPath = ($profil->ttd_kasatkorcab && file_exists(storage_path('app/public/' . $profil->ttd_kasatkorcab))) 
        ? storage_path('app/public/' . $profil->ttd_kasatkorcab) 
        : null;

    $ttdKasetmaPath = ($profil->ttd_kasetma && file_exists(storage_path('app/public/' . $profil->ttd_kasetma))) 
        ? storage_path('app/public/' . $profil->ttd_kasetma) 
        : null;

    $ttdKetuaAnsorPath = ($profil->ttd_ketua_ansor && file_exists(storage_path('app/public/' . $profil->ttd_ketua_ansor))) 
        ? storage_path('app/public/' . $profil->ttd_ketua_ansor) 
        : null;

    $stempelBanserPath = ($profil->stempel_banser && file_exists(storage_path('app/public/' . $profil->stempel_banser))) 
        ? storage_path('app/public/' . $profil->stempel_banser) 
        : (file_exists(public_path('images/stempel-banser.png')) ? public_path('images/stempel-banser.png') : null);

    $stempelAnsorPath = ($profil->stempel_ansor && file_exists(storage_path('app/public/' . $profil->stempel_ansor))) 
        ? storage_path('app/public/' . $profil->stempel_ansor) 
        : (file_exists(public_path('images/stempel-ansor.png')) ? public_path('images/stempel-ansor.png') : null);

    $kodeWilayah = $profil->kode_wilayah ?: 'XII-11';
    $alamatSekretariat = $profil->alamat_sekretariat ?: 'Graha NU Kabupaten Blitar Jatinom Kanigoro Kode Pos: 66171';

    $tentangClean = $sk->tentang ?? (str_starts_with(strtoupper($surat->perihal), 'SURAT KEPUTUSAN TENTANG ') ? substr($surat->perihal, 24) : $surat->perihal);
@endphp

@if($isDraf)<div class="stamp-draf">DRAF</div>@endif

<div class="page">
    <!-- 1. KOP SURAT PERSIS KOP SURAT SPH -->
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

    <!-- 2. JUDUL DAN NOMOR SURAT KEPUTUSAN -->
    <div class="sk-head-title">
        <h2>SURAT KEPUTUSAN</h2>
        <div class="instansi">SATUAN KOORDINASI CABANG BARISAN ANSOR SERBAGUNA</div>
        <div class="instansi">KABUPATEN BLITAR</div>
        <div class="nomor">Nomor : <strong>{{ $isDraf ? 'DRAF (Menunggu Verifikasi)' : $surat->nomor_surat }}</strong></div>
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
                <td style="width: 95px;">Ditetapkan di</td>
                <td style="width: 12px;">:</td>
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
                        @if($ttdKasatkorcabPath)
                            <img src="{{ $ttdKasatkorcabPath }}" alt="TTD Kepala" class="sk-ttd-sig">
                        @endif
                    </div>
                    <div class="sk-pejabat-name">{{ $profil->nama_kasatkorcab ?: 'M. KHOTIB ASHARI, S. Pd.' }}</div>
                </td>

                <!-- KANAN: KASETMA + STEMPEL BANSER -->
                <td>
                    <div class="sk-ttd-jabatan">Kasetma</div>
                    <div class="sk-ttd-box">
                        @if($stempelBanserPath)
                            <img src="{{ $stempelBanserPath }}" alt="Stempel BANSER" class="sk-stempel-banser">
                        @endif
                        @if($ttdKasetmaPath)
                            <img src="{{ $ttdKasetmaPath }}" alt="TTD Kasetma" class="sk-ttd-sig">
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
                @if($stempelAnsorPath)
                    <img src="{{ $stempelAnsorPath }}" alt="Stempel ANSOR" class="sk-stempel-ansor">
                @endif
                @if($ttdKetuaAnsorPath)
                    <img src="{{ $ttdKetuaAnsorPath }}" alt="TTD Ketua Ansor" class="sk-ttd-sig">
                @endif
            </div>

            <div class="sk-pejabat-name">{{ $profil->nama_ketua_ansor ?: 'IMAM MALIKI' }}</div>
        </div>
    </div>

    <!-- 9. TEMBUSAN -->
    @if(!empty($sk->tembusan))
        <div class="sk-tembusan">
            <div class="sk-tembusan-title">Tembusan Kepada Yth :</div>
            <div style="white-space: pre-line;">{!! nl2br(e($sk->tembusan)) !!}</div>
        </div>
    @endif
</div>

<!-- 10. LAMPIRAN SK JIKA ADA (HALAMAN BERIKUTNYA) -->
@if(!empty($sk->lampiran_sk))
    <div style="page-break-before: always;"></div>
    <div class="page">
        <!-- KOP KECIL / HEADER LAMPIRAN -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo-kiri">
                    @if($logoBanser)
                        <img src="{{ $logoBanser }}" alt="Logo BANSER">
                    @endif
                    <div class="kop-kode-wilayah">{{ $kodeWilayah }}</div>
                </td>
                <td class="kop-center-text">
                    <div class="kop-satuan-1" style="font-size: 12pt;">SATUAN KOORDINASI CABANG</div>
                    <div class="kop-satuan-2" style="font-size: 12pt;">BARISAN ANSOR SERBAGUNA (SATKORCAB BANSER)</div>
                    <div class="kop-satuan-4" style="font-size: 12pt;">KABUPATEN BLITAR</div>
                </td>
                <td class="kop-logo-kanan">
                    @if($logoKanan)
                        <img src="{{ $logoKanan }}" alt="Logo Header">
                    @endif
                </td>
            </tr>
        </table>
        <div class="kop-divider"></div>

        <div style="margin-bottom: 12px; font-size: 10.5pt;">
            <strong>LAMPIRAN SURAT KEPUTUSAN</strong><br>
            Nomor : {{ $isDraf ? 'DRAF' : $surat->nomor_surat }}<br>
            Tentang : {{ $tentangClean }}
        </div>

        <div style="white-space: pre-line; line-height: 1.45; font-size: 10.5pt; text-align: justify;">
            {!! nl2br(e($sk->lampiran_sk)) !!}
        </div>

        <!-- TTD PENETAPAN LAMPIRAN -->
        <div class="sk-ttd-section" style="margin-top: 24px;">
            <div class="sk-ttd-heading">
                SATUAN KOORDINASI CABANG<br>
                BARISAN ANSOR SERBAGUNA KABUPATEN BLITAR
            </div>
            <table class="sk-ttd-table">
                <tr>
                    <td>
                        <div class="sk-ttd-jabatan">Kepala</div>
                        <div class="sk-ttd-box">
                            @if($ttdKasatkorcabPath)
                                <img src="{{ $ttdKasatkorcabPath }}" alt="TTD Kepala" class="sk-ttd-sig">
                            @endif
                        </div>
                        <div class="sk-pejabat-name">{{ $profil->nama_kasatkorcab ?: 'M. KHOTIB ASHARI, S. Pd.' }}</div>
                    </td>
                    <td>
                        <div class="sk-ttd-jabatan">Kasetma</div>
                        <div class="sk-ttd-box">
                            @if($stempelBanserPath)
                                <img src="{{ $stempelBanserPath }}" alt="Stempel BANSER" class="sk-stempel-banser">
                            @endif
                            @if($ttdKasetmaPath)
                                <img src="{{ $ttdKasetmaPath }}" alt="TTD Kasetma" class="sk-ttd-sig">
                            @endif
                        </div>
                        <div class="sk-pejabat-name">{{ $profil->nama_kasetma ?: 'TATANG TENI SUNARKO' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endif

</body>
</html>
