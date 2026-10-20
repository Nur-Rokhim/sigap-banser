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
            font-size: 12pt;
            color: #000000;
            background: #ffffff;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm 25mm 20mm 35mm;
        }
        /* KOP SURAT */
        .kop-wrapper {
            width: 100%;
            border-bottom: 4px double #000000;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .kop-inner { display: table; width: 100%; }
        .kop-col-logo { display: table-cell; width: 75px; vertical-align: middle; text-align: center; }
        .kop-col-logo img { max-width: 70px; max-height: 56px; height: auto; }
        .kop-col-text { display: table-cell; vertical-align: middle; text-align: center; padding: 0 8px; }
        .kop-ormas { font-size: 8.5pt; margin-bottom: 1px; }
        .kop-nama { font-size: 18pt; font-weight: bold; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 1px; }
        .kop-satuan { font-size: 9pt; font-weight: bold; margin-bottom: 1px; }
        .kop-alamat { font-size: 8pt; color: #555; }
        /* NOMOR BOX */
        .nomor-box {
            background-color: #5A0E14;
            color: #fff;
            padding: 10px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .nomor-box-label { font-size: 8pt; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; margin-bottom: 2px; }
        .nomor-box-value { font-size: 16pt; font-weight: bold; color: #F5EB0F; letter-spacing: 1px; }
        /* JUDUL */
        .judul { text-align: center; margin: 16px 0 20px; }
        .judul h2 { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; text-decoration: underline; margin-bottom: 4px; }
        .judul .nomor-center { font-size: 11pt; }
        /* IDENTITAS */
        .id-table { width: 100%; border-collapse: collapse; margin-bottom: 22px; }
        .id-table td { padding: 3px 4px 3px 0; font-size: 11pt; vertical-align: top; }
        .id-table td.label { width: 120px; }
        .id-table td.colon { width: 12px; }
        /* BODY */
        .body-para { font-size: 11pt; text-align: justify; line-height: 1.7; margin-bottom: 12px; }
        /* UNDANGAN */
        .undangan-box { border-left: 3px solid #5A0E14; padding-left: 14px; margin: 10px 0 14px; }
        .undangan-box table { border-collapse: collapse; width: 100%; }
        .undangan-box td { font-size: 11pt; padding: 3px 4px 3px 0; vertical-align: top; }
        .undangan-box td.label { width: 110px; font-weight: bold; }
        .undangan-box td.colon { width: 12px; }
        /* SPH */
        .sph-row { margin-bottom: 12px; display: table; width: 100%; }
        .sph-label { display: table-cell; width: 155px; font-weight: bold; font-size: 11pt; vertical-align: top; }
        .sph-colon { display: table-cell; width: 14px; vertical-align: top; font-size: 11pt; }
        .sph-val { display: table-cell; font-size: 11pt; text-align: justify; line-height: 1.6; vertical-align: top; }
        /* ST */
        .penerima-tbl { width: 100%; border-collapse: collapse; margin: 8px 0 14px; }
        .penerima-tbl th { font-size: 10pt; font-weight: bold; border: 1px solid #aaa; padding: 4px 8px; background: #f5f5f5; text-align: left; }
        .penerima-tbl td { font-size: 10pt; border: 1px solid #ccc; padding: 4px 8px; }
        .st-detail { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .st-detail td { font-size: 11pt; padding: 3px 4px 3px 0; vertical-align: top; }
        .st-detail td.label { width: 140px; font-weight: bold; }
        .st-detail td.colon { width: 12px; }
        /* TTD */
        .ttd-section { display: table; width: 100%; margin-top: 28px; }
        .ttd-left { display: table-cell; width: 50%; vertical-align: top; }
        .ttd-right { display: table-cell; width: 50%; vertical-align: top; text-align: center; }
        .ttd-jabatan { font-size: 11pt; font-weight: bold; margin-bottom: 4px; }
        .ttd-ruang { height: 58px; }
        .ttd-nama { font-size: 11pt; font-weight: bold; text-decoration: underline; }
        .ttd-nia { font-size: 10pt; margin-top: 2px; }
        /* TEMBUSAN */
        .tembusan { margin-top: 20px; font-size: 10pt; border-top: 1px solid #ccc; padding-top: 8px; }
        /* WATERMARK */
        .stamp-draf {
            position: fixed;
            top: 80mm; left: 20mm;
            width: 170mm; text-align: center;
            font-size: 60pt; font-weight: bold;
            color: rgba(180,0,0,0.10);
            text-transform: uppercase;
            letter-spacing: 10px;
            transform: rotate(-35deg);
        }
        /* FOOTER */
        .pdf-footer {
            position: fixed;
            bottom: 8mm; left: 35mm; right: 25mm;
            text-align: center;
            font-size: 7.5pt; color: #999;
            border-top: 1px solid #ddd; padding-top: 3px;
        }
    </style>
</head>
<body>
@php
    use Carbon\Carbon;
    $jenisKode  = strtoupper($surat->jenis_surat ?? 'SU');
    $nomorSurat = $surat->nomor_surat ?? null;
    $isDraf     = empty($nomorSurat) || $surat->status === 'draf';
    $bulanId = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
        7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $tglSurat  = $surat->tanggal_surat ?? $surat->tgl_surat ?? now()->toDateString();
    $tglCarbon = Carbon::parse($tglSurat);
    $tglFull   = $tglCarbon->day . ' ' . ($bulanId[$tglCarbon->month]) . ' ' . $tglCarbon->year;
    $logoPath  = file_exists(public_path('images/banser-logo.png')) 
        ? public_path('images/banser-logo.png') 
        : (file_exists(public_path('images/banser.png')) ? public_path('images/banser.png') : public_path('images/banser-logo.svg'));
    $logoAdmin = file_exists(public_path('images/LOGO_ADMIN_rev_2.png')) 
        ? public_path('images/LOGO_ADMIN_rev_2.png') 
        : (file_exists(public_path('images/adminpers-logo.png')) ? public_path('images/adminpers-logo.png') : public_path('images/LOGO_ADMIN_rev_2.svg'));
    $jenisTeks = match($jenisKode) {
        'SU'  => 'SURAT UNDANGAN',
        'SPH' => 'SURAT PERINTAH HARIAN',
        'ST'  => 'SURAT TUGAS / MANDAT',
        default => 'SURAT KELUAR',
    };
@endphp
@if($isDraf)<div class="stamp-draf">DRAF</div>@endif

<div class="page">
    {{-- KOP SURAT --}}
    <div class="kop-wrapper">
        <div class="kop-inner">
            <div class="kop-col-logo">
                @if(file_exists($logoPath))<img src="{{ $logoPath }}" alt="Logo BANSER">@endif
            </div>
            <div class="kop-col-text">
                <div class="kop-ormas">PENGURUS CABANG GERAKAN PEMUDA ANSOR KABUPATEN BLITAR</div>
                <div class="kop-nama">BANSER</div>
                <div class="kop-satuan">SATUAN KOORDINASI CABANG (SATKORCAB)</div>
                <div class="kop-satuan">KABUPATEN BLITAR</div>
                <div class="kop-alamat">Jl. Ahmad Yani — Blitar &nbsp;|&nbsp; adminpers.satkorcab@banser-blitar.id</div>
            </div>
            <div class="kop-col-logo">
                @if(file_exists($logoAdmin))<img src="{{ $logoAdmin }}" alt="Logo Adminpers">@endif
            </div>
        </div>
    </div>

    {{-- NOMOR BOX --}}
    @if(!$isDraf)
    <div class="nomor-box">
        <div class="nomor-box-label">Nomor Surat Resmi</div>
        <div class="nomor-box-value">{{ $nomorSurat }}</div>
    </div>
    @endif

    {{-- JUDUL --}}
    <div class="judul">
        <h2>{{ $jenisTeks }}</h2>
        @if(!$isDraf)
            <div class="nomor-center">Nomor: {{ $nomorSurat }}</div>
        @else
            <div class="nomor-center"><em>(Draf – Belum Bernomor)</em></div>
        @endif
    </div>

    {{-- IDENTITAS --}}
    <table class="id-table">
        <tr>
            <td class="label">Blitar</td><td class="colon">,</td>
            <td>{{ $tglFull }}</td>
        </tr>
        <tr>
            <td class="label">Kepada Yth.</td><td class="colon">:</td>
            <td><strong>{{ $surat->tujuan ?: 'Seluruh Anggota BANSER Satkorcab Kab. Blitar' }}</strong></td>
        </tr>
        <tr>
            <td class="label">Perihal</td><td class="colon">:</td>
            <td><strong>{{ $surat->perihal }}</strong></td>
        </tr>
    </table>

    {{-- === SURAT UNDANGAN (SU) === --}}
    @if($jenisKode === 'SU')
    <div class="body-para"><em>Assalamu'alaikum Wr. Wb.</em></div>
    <div class="body-para">
        Dalam rangka kelancaran tugas keorganisasian BANSER, dengan hormat kami mengundang
        Bapak / Ibu / Saudara untuk hadir pada kegiatan berikut:
    </div>
    <div class="undangan-box">
        <table>
            @if($surat->acara_tanggal ?? null)
            <tr><td class="label">Hari / Tanggal</td><td class="colon">:</td>
                <td>{{ Carbon::parse($surat->acara_tanggal)->isoFormat('dddd, D MMMM Y') }}</td></tr>
            @endif
            @if($surat->acara_waktu ?? null)
            <tr><td class="label">Pukul</td><td class="colon">:</td>
                <td>{{ $surat->acara_waktu }} WIB</td></tr>
            @endif
            @if($surat->acara_tempat ?? null)
            <tr><td class="label">Tempat</td><td class="colon">:</td>
                <td>{{ $surat->acara_tempat }}</td></tr>
            @endif
            @if($surat->acara ?? null)
            <tr><td class="label">Acara</td><td class="colon">:</td>
                <td>{{ $surat->acara }}</td></tr>
            @endif
            @if($surat->pakaian ?? null)
            <tr><td class="label">Pakaian</td><td class="colon">:</td>
                <td>{{ $surat->pakaian }}</td></tr>
            @endif
        </table>
    </div>
    <div class="body-para">
        Mengingat pentingnya kegiatan tersebut, mohon kehadiran Bapak / Ibu / Saudara tepat
        pada waktunya. Atas perhatian dan kehadirannya, kami ucapkan terima kasih.
    </div>
    <div class="body-para"><em>Wassalamu'alaikum Wr. Wb.</em></div>
    @endif

    {{-- === SURAT PERINTAH HARIAN (SPH) === --}}
    @if($jenisKode === 'SPH')
    <div class="sph-row">
        <div class="sph-label">Dasar</div><div class="sph-colon">:</div>
        <div class="sph-val">{{ $surat->dasar ?: 'Program Kerja Satkorcab BANSER Kabupaten Blitar.' }}</div>
    </div>
    <div class="sph-row">
        <div class="sph-label">Memerintahkan kepada</div><div class="sph-colon">:</div>
        <div class="sph-val">{{ $surat->untuk_satuan ?: 'Seluruh Anggota BANSER Satkorcab Kab. Blitar' }}</div>
    </div>
    <div class="sph-row">
        <div class="sph-label">Untuk</div><div class="sph-colon">:</div>
        <div class="sph-val">{{ $surat->isi_perintah }}</div>
    </div>
    <div class="body-para">
        Demikian surat perintah harian ini diterbitkan untuk dilaksanakan dengan penuh
        tanggung jawab, disiplin, dan dilaporkan hasilnya kepada pimpinan.
    </div>
    @endif

    {{-- === SURAT TUGAS / MANDAT (ST) === --}}
    @if($jenisKode === 'ST')
    <div class="body-para">
        Berdasarkan <strong>{{ $surat->dasar ?: 'Program Kerja Satkorcab BANSER Kabupaten Blitar' }}</strong>,
        dengan ini kami menugaskan personel berikut:
    </div>
    @if(!empty($surat->penerima_list))
    <table class="penerima-tbl">
        <tr><th style="width:35px; text-align:center;">No.</th><th>Nama / NIA Anggota</th></tr>
        @foreach($surat->penerima_list as $i => $penerima)
        <tr>
            <td style="text-align:center;">{{ $i + 1 }}</td>
            <td>{{ $penerima }}</td>
        </tr>
        @endforeach
    </table>
    @endif
    <div class="body-para">Untuk melaksanakan tugas sebagai berikut:</div>
    <table class="st-detail">
        <tr><td class="label">Uraian Tugas</td><td class="colon">:</td><td>{{ $surat->uraian_tugas }}</td></tr>
        @if($surat->tgl_mulai ?? null)
        <tr><td class="label">Tanggal Mulai</td><td class="colon">:</td>
            <td>{{ Carbon::parse($surat->tgl_mulai)->isoFormat('D MMMM Y') }}</td></tr>
        @endif
        @if($surat->tgl_selesai ?? null)
        <tr><td class="label">Tanggal Selesai</td><td class="colon">:</td>
            <td>{{ Carbon::parse($surat->tgl_selesai)->isoFormat('D MMMM Y') }}</td></tr>
        @endif
        @if($surat->tempat_tugas ?? null)
        <tr><td class="label">Tempat Tugas</td><td class="colon">:</td>
            <td>{{ $surat->tempat_tugas }}</td></tr>
        @endif
    </table>
    <div class="body-para">
        Kepada yang mendapatkan tugas, agar melaksanakannya dengan sebaik-baiknya, penuh
        tanggung jawab, dan melaporkan hasilnya kepada pimpinan.
    </div>
    @endif

    {{-- TTD --}}
    <div class="ttd-section">
        <div class="ttd-left"></div>
        <div class="ttd-right">
            <div class="ttd-jabatan">Blitar, {{ $tglFull }}</div>
            <div class="ttd-jabatan" style="margin-top:4px;">Kasatkorcab BANSER Kab. Blitar</div>
            <div class="ttd-ruang"></div>
            <div class="ttd-nama">( ________________________ )</div>
            <div class="ttd-nia">NIA : ________________________</div>
        </div>
    </div>

    {{-- TEMBUSAN --}}
    <div class="tembusan">
        <strong>Tembusan:</strong><br>
        1. Ketua GP Ansor Kab. Blitar (sebagai laporan);<br>
        2. Arsip.
    </div>
</div>

<div class="pdf-footer">
    Dokumen diterbitkan oleh SIGAP BANSER – Sistem Informasi Administrasi Persuratan Satkorcab Kab. Blitar
    @if(!$isDraf) &nbsp;|&nbsp; {{ $nomorSurat }} @endif
</div>
</body>
</html>

