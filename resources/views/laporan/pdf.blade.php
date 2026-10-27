<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Persuratan {{ $judulPeriode }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
            padding: 30px 40px;
        }

        /* KOP SURAT */
        .kop-table { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .kop-table td { vertical-align: middle; padding: 0; }
        .kop-logo-kiri { width: 85px; text-align: center; }
        .kop-logo-kiri img { max-width: 78px; max-height: 80px; }
        .kop-center { text-align: center; padding: 0 8px; }
        .kop-satuan-1 { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.15; }
        .kop-satuan-2 { font-size: 14pt; font-weight: bold; text-transform: uppercase; line-height: 1.15; }
        .kop-satuan-3 { font-size: 13.5pt; font-weight: bold; text-transform: uppercase; line-height: 1.15; }
        .kop-satuan-4 { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; line-height: 1.25; }
        .kop-alamat { font-size: 8.5pt; margin-top: 5px; font-family: Arial, sans-serif; }
        .kop-kode { font-size: 9pt; font-weight: bold; text-align: center; margin-top: 3px; letter-spacing: 0.5px; }
        .kop-logo-kanan { width: 95px; text-align: center; }
        .kop-logo-kanan img { max-width: 90px; max-height: 75px; }
        .kop-divider {
            width: 100%;
            border-top: 2.5px solid #000;
            border-bottom: 1px solid #000;
            height: 3px;
            margin: 4px 0 14px;
        }

        /* JUDUL LAPORAN */
        .laporan-title {
            text-align: center;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #666;
        }
        .laporan-title h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .laporan-title p {
            font-size: 10.5pt;
            margin-top: 3px;
        }
        .laporan-title .periode {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 4px;
        }

        /* STATS BOX */
        .stats-row {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
        }
        .stats-box {
            flex: 1;
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: center;
            border-radius: 4px;
        }
        .stats-num { font-size: 18pt; font-weight: bold; }
        .stats-label { font-size: 8.5pt; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

        /* SECTION TITLE */
        .section-title {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin: 14px 0 6px;
        }

        /* TABLES */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .data-table th {
            background: #f0f0f0;
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: center;
            font-size: 9.5pt;
            font-weight: bold;
        }
        .data-table td {
            border: 1px solid #bbb;
            padding: 5px 8px;
            font-size: 9.5pt;
            vertical-align: middle;
        }
        .data-table tbody tr:nth-child(even) { background: #fafafa; }
        .data-table .total-row td {
            background: #e8e8e8;
            font-weight: bold;
            border-top: 2px solid #666;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* TTD */
        .ttd-section { margin-top: 20px; }
        .ttd-row { display: flex; justify-content: space-between; }
        .ttd-box { text-align: center; width: 220px; }
        .ttd-line { height: 50px; }
        .ttd-name { font-weight: bold; text-transform: uppercase; font-size: 10pt; }

        /* Footer */
        .page-footer {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px dotted #999;
            font-size: 8.5pt;
            color: #555;
            text-align: center;
        }
    </style>
</head>
<body>

{{-- KOP SURAT --}}
@php
    $logoBanser = public_path('images/banser-logo.png');
    $logoKanan  = file_exists(public_path('images/logo-banser-merahputih.png'))
        ? public_path('images/logo-banser-merahputih.png')
        : public_path('images/ansor-logo.png');
    $kodeWilayah = $profil->kode_wilayah ?: 'XII-11';
    $alamat      = $profil->alamat_sekretariat ?: 'Graha NU Kabupaten Blitar, Jatinom Kanigoro Kode Pos: 66171';

    $namaBulanArr = [
        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
        7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
    ];
    $judulBulanStr = $bulan !== '' ? ($namaBulan[(int)$bulan] ?? '') . ' ' : '';
    $totalBernomor = 0;
    foreach ($keluarPerJenis as $kp) { $totalBernomor += $kp['bernomor']; }
@endphp

<table class="kop-table">
    <tr>
        <td class="kop-logo-kiri">
            <img src="{{ $logoBanser }}" alt="Logo BANSER">
            <div class="kop-kode">{{ $kodeWilayah }}</div>
        </td>
        <td class="kop-center">
            <div class="kop-satuan-1">SATUAN KOORDINASI CABANG</div>
            <div class="kop-satuan-2">BARISAN ANSOR SERBAGUNA</div>
            <div class="kop-satuan-3">(SATKORCAB BANSER)</div>
            <div class="kop-satuan-4">KABUPATEN BLITAR</div>
            <div class="kop-alamat">Sekretariat : {{ $alamat }}</div>
        </td>
        <td class="kop-logo-kanan">
            <img src="{{ $logoKanan }}" alt="Logo Header">
        </td>
    </tr>
</table>
<div class="kop-divider"></div>

{{-- JUDUL --}}
<div class="laporan-title">
    <h2>Laporan Rekap Persuratan</h2>
    <p>Satuan Koordinasi Cabang Barisan Ansor Serbaguna Kabupaten Blitar</p>
    <div class="periode">Periode : {{ $judulPeriode }}</div>
</div>

{{-- RINGKASAN STATISTIK --}}
<div class="section-title">Ringkasan Statistik</div>
<table class="data-table">
    <thead>
        <tr>
            <th>Kategori</th>
            <th style="width:100px;">Total</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Surat Masuk</td>
            <td class="text-center" style="font-weight:bold;">{{ $totalMasuk }}</td>
            <td>Baru: {{ $masukBaru }} | Selesai: {{ $masukSelesai }}</td>
        </tr>
        <tr>
            <td>Surat Keluar</td>
            <td class="text-center" style="font-weight:bold;">{{ $totalKeluar }}</td>
            <td>Bernomor resmi: {{ $totalBernomor }}</td>
        </tr>
        <tr class="total-row">
            <td>Total Dokumen</td>
            <td class="text-center">{{ $totalMasuk + $totalKeluar }}</td>
            <td>Surat masuk dan surat keluar periode {{ $judulPeriode }}</td>
        </tr>
    </tbody>
</table>

{{-- REKAP SURAT KELUAR PER JENIS --}}
<div class="section-title">Rekap Surat Keluar per Jenis</div>
<table class="data-table">
    <thead>
        <tr>
            <th style="width:20px;">No</th>
            <th style="width:60px;">Kode</th>
            <th>Nama Jenis Surat</th>
            <th style="width:80px;">Total Dibuat</th>
            <th style="width:100px;">Bernomor Resmi</th>
            <th style="width:90px;">Draf/Proses</th>
        </tr>
    </thead>
    <tbody>
        @foreach($keluarPerJenis as $kode => $data)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center" style="font-weight:bold; font-family:monospace;">{{ $kode }}</td>
            <td>{{ $data['nama'] }}</td>
            <td class="text-center" style="font-weight:bold;">{{ $data['total'] }}</td>
            <td class="text-center">{{ $data['bernomor'] }}</td>
            <td class="text-center">{{ $data['total'] - $data['bernomor'] }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3" class="text-center">TOTAL SURAT KELUAR</td>
            <td class="text-center">{{ $totalKeluar }}</td>
            <td class="text-center">{{ $totalBernomor }}</td>
            <td class="text-center">{{ $totalKeluar - $totalBernomor }}</td>
        </tr>
    </tbody>
</table>

{{-- DAFTAR SURAT KELUAR BERNOMOR --}}
@if($suratKeluar->whereNotNull('nomor_surat')->count() > 0)
<div class="section-title">Daftar Surat Keluar Bernomor Resmi</div>
<table class="data-table">
    <thead>
        <tr>
            <th style="width:20px;">No</th>
            <th style="width:160px;">Nomor Surat</th>
            <th>Perihal</th>
            <th style="width:80px;">Tanggal</th>
            <th style="width:60px;">Status</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($suratKeluar->whereNotNull('nomor_surat')->sortBy('no_urut') as $s)
        <tr>
            <td class="text-center">{{ $no++ }}</td>
            <td style="font-family:monospace; font-weight:bold; font-size:9pt;">{{ $s->nomor_surat }}</td>
            <td>{{ Str::limit($s->perihal, 70) }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($s->tgl_surat)->format('d/m/Y') }}</td>
            <td class="text-center">{{ ucfirst($s->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- DAFTAR SURAT MASUK --}}
@if($suratMasuk->count() > 0)
<div class="section-title">Daftar Surat Masuk</div>
<table class="data-table">
    <thead>
        <tr>
            <th style="width:20px;">No</th>
            <th style="width:120px;">No. Surat</th>
            <th style="width:110px;">Dari</th>
            <th>Perihal</th>
            <th style="width:80px;">Tgl Terima</th>
            <th style="width:60px;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suratMasuk as $sm)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td style="font-size:8.5pt;">{{ $sm->no_surat }}</td>
            <td style="font-size:8.5pt;">{{ Str::limit($sm->dari, 25) }}</td>
            <td style="font-size:8.5pt;">{{ Str::limit($sm->perihal, 50) }}</td>
            <td class="text-center" style="font-size:8.5pt;">{{ \Carbon\Carbon::parse($sm->tgl_diterima)->format('d/m/Y') }}</td>
            <td class="text-center" style="font-size:8.5pt;">{{ ucfirst($sm->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- TTD --}}
<div class="ttd-section">
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="width:60%; vertical-align:top; font-size:9.5pt; padding-top:10px;">
                <em>Laporan ini dicetak secara otomatis oleh Sistem Informasi dan Administrasi Persuratan (SIGAP BANSER) Satkorcab Kab. Blitar pada {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y, HH:mm') }} WIB.</em>
            </td>
            <td style="width:40%; text-align:center; vertical-align:top;">
                <div style="font-size:9.5pt;">Blitar, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</div>
                <div style="font-size:9.5pt; font-weight:bold; margin-top:2px;">Kasetma Satkorcab Banser</div>
                <div style="height:55px;"></div>
                <div style="font-size:10pt; font-weight:bold; text-transform:uppercase; border-top: 1px solid #000; display:inline-block; padding-top:2px; min-width:150px;">
                    {{ $profil->nama_kasetma ?: 'TATANG TENI SUNARKO' }}
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="page-footer">
    &copy; {{ date('Y') }} SIGAP BANSER — Sistem Informasi dan Administrasi Persuratan Satkorcab Banser Kab. Blitar &bull; Dokumen ini sah tanpa tanda tangan basah
</div>

</body>
</html>
