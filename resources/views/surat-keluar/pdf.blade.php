<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ $surat->nomor_surat ?? 'Draf Surat Keluar' }}</title>
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
            margin: 20mm 20mm 20mm 20mm;
        }
        .page {
            width: 100%;
        }
        
        /* KOP SURAT RESMI PERSIS CONTOH ACUAN */
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

        /* GARIS PEMBATAS KOP GANDA (TEBAL ATAS, TIPIS BAWAH) */
        .kop-divider {
            width: 100%;
            border-top: 2.5px solid #000000;
            border-bottom: 1px solid #000000;
            height: 3px;
            margin-top: 4px;
            margin-bottom: 14px;
        }

        /* TANGGAL DI KANAN ATAS */
        .header-date-row {
            text-align: right;
            font-size: 11pt;
            margin-bottom: 2px;
        }

        /* NOMOR, LAMPIRAN, HAL */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .meta-table td {
            font-size: 11pt;
            vertical-align: top;
            padding: 1px 0;
            line-height: 1.35;
        }
        .meta-table td.meta-label {
            width: 80px;
        }
        .meta-table td.meta-colon {
            width: 15px;
            text-align: center;
        }

        /* KEPADA YTH */
        .tujuan-container {
            margin-bottom: 12px;
            font-size: 11pt;
            line-height: 1.35;
        }
        .tujuan-container .yth {
            margin-bottom: 2px;
        }
        .tujuan-container .nama-penerima {
            font-weight: bold;
        }

        /* SALAM PEMBUKA & PENUTUP */
        .salam-pembuka {
            font-size: 11pt;
            margin-bottom: 6px;
            line-height: 1.35;
        }
        .salam-pembuka .salam-arab {
            font-style: italic;
            font-weight: bold;
        }
        .salam-pembuka .basmalah {
            font-style: italic;
            font-weight: bold;
        }

        .salam-penutup {
            font-size: 11pt;
            margin-top: 10px;
            margin-bottom: 10px;
            line-height: 1.35;
        }
        .salam-penutup .kalimat-1 {
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        .salam-penutup .kalimat-2 {
            font-style: italic;
            font-weight: bold;
        }

        /* ISI PARAGRAF SURAT */
        .body-p {
            font-size: 11pt;
            text-align: justify;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        /* TABEL RINCIAN AGENDA / JADWAL */
        .rincian-table {
            width: 95%;
            margin: 6px 0 10px 18px;
            border-collapse: collapse;
        }
        .rincian-table td {
            font-size: 11pt;
            vertical-align: top;
            padding: 2px 0;
            line-height: 1.35;
        }
        .rincian-table td.col-label {
            width: 130px;
        }
        .rincian-table td.col-colon {
            width: 15px;
            text-align: center;
        }
        .rincian-table td.col-val {
            text-align: left;
        }

        /* FORMAT TANDA TANGAN PERSIS GAMBAR */
        .ttd-section {
            width: 100%;
            margin-top: 14px;
            page-break-inside: avoid;
        }
        .ttd-satuan-heading {
            text-align: center;
            font-weight: bold;
            font-size: 10.5pt;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 6px;
        }

        .ttd-table-two-cols {
            width: 100%;
            border-collapse: collapse;
        }
        .ttd-table-two-cols td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 10.5pt;
            padding: 0 10px;
        }

        .ttd-jabatan-title {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .ttd-visual-box {
            position: relative;
            height: 65px;
            margin: 2px auto;
        }
        .ttd-img-sig {
            max-height: 62px;
            max-width: 140px;
            object-fit: contain;
            position: relative;
            z-index: 2;
        }
        .stempel-banser-pos {
            position: absolute;
            left: 50%;
            top: -10px;
            margin-left: -50px;
            max-height: 85px;
            max-width: 85px;
            opacity: 0.9;
            z-index: 3;
        }

        .ttd-pejabat-nama {
            font-weight: bold;
            font-size: 10.5pt;
            text-transform: uppercase;
        }

        /* BLOK MENGETAHUI GP ANSOR */
        .mengetahui-block {
            margin-top: 10px;
            text-align: center;
            page-break-inside: avoid;
        }
        .mengetahui-label {
            font-size: 10.5pt;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .mengetahui-instansi {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .mengetahui-jabatan {
            font-size: 10.5pt;
            font-weight: bold;
            margin-top: 2px;
        }
        .mengetahui-visual-box {
            position: relative;
            height: 65px;
            margin: 2px auto;
            width: 260px;
        }
        .stempel-ansor-pos {
            position: absolute;
            left: 20px;
            top: -15px;
            max-height: 85px;
            max-width: 85px;
            opacity: 0.9;
            z-index: 3;
        }

        /* WATERMARK DRAF */
        .stamp-draf {
            position: fixed;
            top: 100mm;
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

    $jenisKode  = strtoupper($surat->jenis_surat ?? $surat->jenis ?? 'SU');
    $nomorSurat = $surat->nomor_surat ?? null;
    $isDraf     = empty($nomorSurat) || strtolower($surat->status ?? 'draf') === 'draf';
    
    $bulanId = [
        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
        7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
    ];

    $tglSurat  = $surat->tanggal_surat ?? $surat->tgl_surat ?? now()->toDateString();
    $tglCarbon = Carbon::parse($tglSurat);
    $tglFull   = $tglCarbon->day . ' ' . ($bulanId[$tglCarbon->month]) . ' ' . $tglCarbon->year;

    // Logo Kiri: Logo BANSER (beserta kode wilayah di bawahnya)
    $logoBanser = file_exists(public_path('images/banser-emblem.png')) 
        ? public_path('images/banser-emblem.png') 
        : (file_exists(public_path('images/banser-logo-res.png'))
            ? public_path('images/banser-logo-res.png')
            : (file_exists(public_path('images/banser-logo.png')) 
                ? public_path('images/banser-logo.png') 
                : (file_exists(public_path('images/banser.png')) ? public_path('images/banser.png') : null)));

    // Logo Kanan: Logo yang barusan diupload user (logo-banser-merahputih.png)
    $logoKanan = file_exists(public_path('images/logo-banser-merahputih.png'))
        ? public_path('images/logo-banser-merahputih.png')
        : (file_exists(public_path('images/ansor-logo-res.png'))
            ? public_path('images/ansor-logo-res.png')
            : (file_exists(public_path('images/ansor-logo.png')) ? public_path('images/ansor-logo.png') : null));

    // Signatures and Stamps
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
@endphp

@if($isDraf)<div class="stamp-draf">DRAF</div>@endif

<div class="page">
    {{-- 1. KOP SURAT RESMI: KIRI LOGO BANSER, KANAN LOGO BARU UPLOAD --}}
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

    {{-- 2. TANGGAL SURAT DI KANAN --}}
    <div class="header-date-row">
        Blitar, {{ $tglFull }}
    </div>

    {{-- 3. NOMOR, LAMPIRAN, HAL --}}
    <table class="meta-table">
        <tr>
            <td class="meta-label">Nomor</td>
            <td class="meta-colon">:</td>
            <td><strong>{{ $isDraf ? 'DRAF (Menunggu Verifikasi)' : $nomorSurat }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Lamp</td>
            <td class="meta-colon">:</td>
            <td>-</td>
        </tr>
        <tr>
            <td class="meta-label">Hal</td>
            <td class="meta-colon">:</td>
            <td><strong>{{ strtoupper($surat->perihal ?? 'INSTRUKSI') }}</strong></td>
        </tr>
    </table>

    {{-- 4. TUJUAN KEPADA YTH --}}
    <div class="tujuan-container">
        <div class="yth">Kepada Yth,</div>
        <div class="nama-penerima">{{ $surat->tujuan ?? 'Kasatkoryon' }}</div>
    </div>

    {{-- 5. SALAM PEMBUKA --}}
    <div class="salam-pembuka">
        <div class="salam-arab">Assalamu'alaikum Wr. Wb.</div>
        <div class="basmalah">Bismillahhirrahmanirahim</div>
    </div>

    <div class="body-p">
        Salam silaturrahmi kami sampaikan, semoga kita senantiasa dalam keadaan sehat walafiat, serta diberi kesuksesan dalam menjalankan aktifitas sehari-hari. Amin.
    </div>

    {{-- 6. BODY TEXT SESUAI JENIS / DETAIL SURAT --}}
    @if($jenisKode === 'SPH' || $jenisKode === 'SP')
        {{-- Paragraf pembuka berdasarkan dasar surat --}}
        @if(!empty($surat->dasar))
            <div class="body-p">
                Berdasarkan {{ $surat->dasar }}, maka dengan ini mengharap dengan hormat kepada sahabat untuk {{ !empty($surat->untuk_satuan) ? $surat->untuk_satuan : 'memerintahkan anggotanya' }} yang akan dilaksanakan :
            </div>
        @else
            <div class="body-p">
                Berdasarkan koordinasi pimpinan dan program kerja Satuan Koordinasi Cabang (SATKORCAB) BANSER Kabupaten Blitar, bersama ini mengharap dengan hormat kepada sahabat untuk {{ !empty($surat->untuk_satuan) ? $surat->untuk_satuan : 'memerintahkan anggotanya' }} yang akan dilaksanakan :
            </div>
        @endif

        {{-- Tabel rincian kegiatan terstruktur (format acuan resmi SPH) --}}
        <table class="rincian-table">
            @if(!empty($surat->sph_hari_tanggal))
            <tr>
                <td class="col-label">Hari / Tanggal</td>
                <td class="col-colon">:</td>
                <td class="col-val"><strong>{{ $surat->sph_hari_tanggal }}</strong></td>
            </tr>
            @endif
            @if(!empty($surat->sph_waktu))
            <tr>
                <td class="col-label">Pukul</td>
                <td class="col-colon">:</td>
                <td class="col-val">{{ $surat->sph_waktu }}</td>
            </tr>
            @endif
            @if(!empty($surat->sph_acara))
            <tr>
                <td class="col-label">Acara</td>
                <td class="col-colon">:</td>
                <td class="col-val"><strong>{{ $surat->sph_acara }}</strong></td>
            </tr>
            @endif
            @if(!empty($surat->sph_tempat))
            <tr>
                <td class="col-label">Tempat</td>
                <td class="col-colon">:</td>
                <td class="col-val">{{ $surat->sph_tempat }}</td>
            </tr>
            @endif
            @if(!empty($surat->sph_pakaian))
            <tr>
                <td class="col-label">Catatan</td>
                <td class="col-colon">:</td>
                <td class="col-val">
                    1. Memakai {{ $surat->sph_pakaian }}<br>
                    2. Karena pentingnya acara harap Hadir tepat waktu
                </td>
            </tr>
            @endif
        </table>

        {{-- Catatan tambahan jika ada isi_perintah --}}
        @if(!empty($surat->isi_perintah))
            <div class="body-p" style="margin-left: 18px; white-space: pre-line;">
                {!! nl2br(e($surat->isi_perintah)) !!}
            </div>
        @endif

    @elseif($jenisKode === 'SU')
        <div class="body-p">
            Sehubungan dengan agenda kegiatan Satuan Koordinasi Cabang (SATKORCAB) BANSER Kabupaten Blitar, maka bersama ini kami mengharap dengan hormat kehadiran Sahabat besok pada :
        </div>

        <table class="rincian-table">
            <tr>
                <td class="col-label">Hari / Tanggal</td>
                <td class="col-colon">:</td>
                <td class="col-val"><strong>{{ $surat->hari_tanggal ?? $surat->acara_tanggal ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="col-label">Pukul</td>
                <td class="col-colon">:</td>
                <td class="col-val">{{ $surat->waktu ?? $surat->acara_waktu ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-label">Acara</td>
                <td class="col-colon">:</td>
                <td class="col-val"><strong>{{ $surat->acara ?? $surat->acara_nama ?? ($surat->perihal ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td class="col-label">Tempat</td>
                <td class="col-colon">:</td>
                <td class="col-val">{{ $surat->tempat ?? $surat->acara_tempat ?? '-' }}</td>
            </tr>
            @if(!empty($surat->pakaian ?? $surat->acara_pakaian))
            <tr>
                <td class="col-label">Catatan</td>
                <td class="col-colon">:</td>
                <td class="col-val">1. Memakai {{ $surat->pakaian ?? $surat->acara_pakaian }}<br>2. Karena pentingnya acara harap Hadir tepat waktu</td>
            </tr>
            @endif
        </table>

    @elseif($jenisKode === 'ST')
        <div class="body-p">
            Berdasarkan {{ $surat->dasar ?? 'kebutuhan penugasan personel Satuan Koordinasi Cabang (SATKORCAB) BANSER Kabupaten Blitar' }}, dengan ini memberikan tugas kedinasan kepada sahabat :
        </div>

        @if(!empty($surat->penerima_list))
            <div style="margin-left: 18px; margin-bottom: 8px;">
                @foreach($surat->penerima_list as $idx => $penerima)
                    <div>{{ $idx + 1 }}. <strong>{{ $penerima }}</strong></div>
                @endforeach
            </div>
        @elseif($surat->penerima_tugas)
            <div style="margin-left: 18px; margin-bottom: 8px;">
                <strong>{{ is_array($surat->penerima_tugas) ? implode(", ", $surat->penerima_tugas) : $surat->penerima_tugas }}</strong>
            </div>
        @endif

        <table class="rincian-table">
            <tr>
                <td class="col-label">Tugas</td>
                <td class="col-colon">:</td>
                <td class="col-val"><strong>{{ $surat->uraian_tugas ?? $surat->perihal }}</strong></td>
            </tr>
            @if(!empty($surat->tgl_mulai))
            <tr>
                <td class="col-label">Waktu Pelaksanaan</td>
                <td class="col-colon">:</td>
                <td class="col-val">
                    {{ Carbon::parse($surat->tgl_mulai)->day . ' ' . $bulanId[Carbon::parse($surat->tgl_mulai)->month] . ' ' . Carbon::parse($surat->tgl_mulai)->year }}
                    @if(!empty($surat->tgl_selesai) && $surat->tgl_selesai != $surat->tgl_mulai)
                        s/d {{ Carbon::parse($surat->tgl_selesai)->day . ' ' . $bulanId[Carbon::parse($surat->tgl_selesai)->month] . ' ' . Carbon::parse($surat->tgl_selesai)->year }}
                    @endif
                </td>
            </tr>
            @endif
            @if(!empty($surat->tempat_tugas ?? $surat->st_tempat))
            <tr>
                <td class="col-label">Tempat</td>
                <td class="col-colon">:</td>
                <td class="col-val">{{ $surat->tempat_tugas ?? $surat->st_tempat }}</td>
            </tr>
            @endif
        </table>
    @endif

    <div class="body-p">
        Demikian surat {{ $surat->perihal ?? 'Instruksi' }} ini kami buat, atas perhatian dan kehadiranya kami sampaikan terimakasih.
    </div>

    {{-- 7. SALAM PENUTUP --}}
    <div class="salam-penutup">
        <div class="kalimat-1">Wallahul Muwafiq Ila Aqwamit Thariq</div>
        <div class="kalimat-2">Wassalamu'alaikum Wr. Wb.</div>
    </div>

    {{-- 8. TANDA TANGAN (KEPALA & KASETMA + STEMPEL BANSER KANAN) --}}
    <div class="ttd-section">
        <div class="ttd-satuan-heading">
            SATUAN KOORDINASI CABANG<br>
            BARISAN ANSOR SERBAGUNA
        </div>

        <table class="ttd-table-two-cols">
            <tr>
                <!-- KIRI: KEPALA SATKORCAB -->
                <td>
                    <div class="ttd-jabatan-title">Kepala</div>
                    <div class="ttd-visual-box">
                        @if($ttdKasatkorcabPath)
                            <img src="{{ $ttdKasatkorcabPath }}" alt="TTD Kepala" class="ttd-img-sig">
                        @endif
                    </div>
                    <div class="ttd-pejabat-nama">{{ $profil->nama_kasatkorcab ?: 'M. KHOTIB ASHARI, S. Pd.' }}</div>
                </td>

                <!-- KANAN: KASETMA + STEMPEL BANSER -->
                <td>
                    <div class="ttd-jabatan-title">Kasetma</div>
                    <div class="ttd-visual-box">
                        @if($stempelBanserPath)
                            <img src="{{ $stempelBanserPath }}" alt="Stempel BANSER" class="stempel-banser-pos">
                        @endif

                        @if($ttdKasetmaPath)
                            <img src="{{ $ttdKasetmaPath }}" alt="TTD Kasetma" class="ttd-img-sig">
                        @endif
                    </div>
                    <div class="ttd-pejabat-nama">{{ $profil->nama_kasetma ?: 'TATANG TENI SUNARKO' }}</div>
                </td>
            </tr>
        </table>

        <!-- BAWAH TENGAH: MENGETAHUI GP ANSOR + STEMPEL ANSOR KIRI -->
        <div class="mengetahui-block">
            <div class="mengetahui-label">Mengetahui</div>
            <div class="mengetahui-instansi">
                PIMPINAN CABANG GERAKAN PEMUDA ANSOR<br>
                KABUPATEN BLITAR
            </div>
            <div class="mengetahui-jabatan">Ketua</div>

            <div class="mengetahui-visual-box">
                @if($stempelAnsorPath)
                    <img src="{{ $stempelAnsorPath }}" alt="Stempel ANSOR" class="stempel-ansor-pos">
                @endif

                @if($ttdKetuaAnsorPath)
                    <img src="{{ $ttdKetuaAnsorPath }}" alt="TTD Ketua Ansor" class="ttd-img-sig">
                @endif
            </div>

            <div class="ttd-pejabat-nama">{{ $profil->nama_ketua_ansor ?: 'IMAM MALIKI' }}</div>
        </div>
    </div>
</div>
</body>
</html>
