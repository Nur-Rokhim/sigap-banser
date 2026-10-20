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
            font-size: 11.5pt;
            color: #000000;
            background: #ffffff;
            line-height: 1.5;
        }
        @page {
            size: a4 portrait;
            margin: 15mm 20mm 20mm 25mm;
        }
        .page {
            width: 100%;
        }
        
        /* KOP SURAT RESMI */
        .kop-wrapper {
            width: 100%;
            border-bottom: 3px double #000000;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }
        .kop-inner { display: table; width: 100%; }
        .kop-col-logo-left { display: table-cell; width: 80px; vertical-align: middle; text-align: left; }
        .kop-col-logo-left img { max-width: 75px; max-height: 75px; height: auto; }
        .kop-col-text { display: table-cell; vertical-align: middle; text-align: center; padding: 0 10px; }
        .kop-col-logo-right { display: table-cell; width: 80px; vertical-align: middle; text-align: right; }
        .kop-col-logo-right img { max-width: 80px; max-height: 65px; height: auto; }

        .kop-ormas { font-size: 9.5pt; font-weight: bold; margin-bottom: 2px; letter-spacing: 0.5px; text-transform: uppercase; }
        .kop-nama { font-size: 19pt; font-weight: bold; letter-spacing: 4px; text-transform: uppercase; margin-bottom: 2px; }
        .kop-satuan { font-size: 10pt; font-weight: bold; letter-spacing: 1px; margin-bottom: 2px; text-transform: uppercase; }
        .kop-alamat { font-size: 8pt; color: #333; margin-top: 3px; font-style: italic; }

        /* NOMOR & TANGGAL SURAT */
        .header-meta-tbl { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .header-meta-tbl td { font-size: 11pt; vertical-align: top; padding: 2px 0; }
        .header-meta-tbl td.meta-left { width: 60%; }
        .header-meta-tbl td.meta-right { width: 40%; text-align: right; }

        .nomor-meta-tbl { border-collapse: collapse; }
        .nomor-meta-tbl td { font-size: 11pt; vertical-align: top; padding: 2px 0; }
        .nomor-meta-tbl td.label { width: 85px; }
        .nomor-meta-tbl td.colon { width: 12px; }

        /* TUJUAN */
        .tujuan-box { margin-bottom: 16px; font-size: 11pt; line-height: 1.4; }
        .tujuan-box .yth { margin-bottom: 2px; }
        .tujuan-box .nama-penerima { font-weight: bold; }

        /* JUDUL SURAT KHUSUS */
        .judul-center-box { text-align: center; margin: 14px 0 18px; }
        .judul-center-box h2 { font-size: 13.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; text-decoration: underline; margin-bottom: 3px; }
        .judul-center-box .nomor-center { font-size: 11pt; }

        /* BODY PARAGRAPH */
        .body-para { font-size: 11pt; text-align: justify; line-height: 1.6; margin-bottom: 10px; text-indent: 28px; }
        .salam-pembuka { font-size: 11pt; font-style: italic; margin-bottom: 10px; font-weight: bold; }
        .salam-penutup { font-size: 11pt; font-style: italic; margin-top: 12px; margin-bottom: 14px; font-weight: bold; }

        /* DETAIL UNDANGAN */
        .undangan-tbl { width: 92%; margin: 8px auto 14px 28px; border-collapse: collapse; }
        .undangan-tbl td { font-size: 11pt; padding: 3px 0; vertical-align: top; }
        .undangan-tbl td.label { width: 140px; font-weight: bold; }
        .undangan-tbl td.colon { width: 14px; }

        /* DETAIL SPH */
        .sph-row { margin-bottom: 10px; display: table; width: 100%; }
        .sph-label { display: table-cell; width: 140px; font-weight: bold; font-size: 11pt; vertical-align: top; }
        .sph-colon { display: table-cell; width: 14px; vertical-align: top; font-size: 11pt; }
        .sph-val { display: table-cell; font-size: 11pt; text-align: justify; line-height: 1.6; vertical-align: top; }

        /* DETAIL ST */
        .penerima-tbl { width: 100%; border-collapse: collapse; margin: 8px 0 12px; }
        .penerima-tbl th { font-size: 10pt; font-weight: bold; border: 1px solid #777; padding: 4px 8px; background: #eaeaea; text-align: left; }
        .penerima-tbl td { font-size: 10pt; border: 1px solid #999; padding: 4px 8px; }
        
        .st-detail-tbl { width: 94%; margin: 6px auto 12px 28px; border-collapse: collapse; }
        .st-detail-tbl td { font-size: 11pt; padding: 3px 0; vertical-align: top; }
        .st-detail-tbl td.label { width: 140px; font-weight: bold; }
        .st-detail-tbl td.colon { width: 14px; }

        /* TANDA TANGAN KHUSUS SATKORCAB BANSER */
        .ttd-container {
            width: 100%;
            margin-top: 22px;
            page-break-inside: avoid;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
        }
        .ttd-col {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 11pt;
            position: relative;
        }
        .ttd-instansi {
            font-weight: bold;
            font-size: 10.5pt;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .ttd-jabatan {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 6px;
        }
        .ttd-box-visual {
            height: 70px;
            position: relative;
            margin: 4px auto;
        }
        .ttd-img {
            max-height: 65px;
            max-width: 140px;
            object-fit: contain;
        }
        .stempel-img {
            position: absolute;
            left: 20px;
            top: -10px;
            max-height: 80px;
            max-width: 80px;
            opacity: 0.85;
        }
        .ttd-nama {
            font-weight: bold;
            font-size: 11pt;
            text-decoration: underline;
        }
        .ttd-npa {
            font-size: 9.5pt;
            margin-top: 2px;
        }

        /* TEMBUSAN */
        .tembusan-box {
            margin-top: 24px;
            font-size: 9.5pt;
            border-top: 1px dashed #999;
            padding-top: 8px;
            page-break-inside: avoid;
        }
        .tembusan-box strong { text-decoration: underline; }
        .tembusan-box ol { margin-left: 20px; margin-top: 4px; }

        /* STAMP DRAF */
        .stamp-draf {
            position: fixed;
            top: 90mm;
            left: 15mm;
            width: 180mm;
            text-align: center;
            font-size: 65pt;
            font-weight: bold;
            color: rgba(180, 0, 0, 0.08);
            text-transform: uppercase;
            letter-spacing: 12px;
            transform: rotate(-35deg);
            z-index: -1;
        }

        /* FOOTER */
        .pdf-footer {
            position: fixed;
            bottom: 6mm;
            left: 25mm;
            right: 20mm;
            text-align: center;
            font-size: 7.5pt;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 3px;
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

    // Logo Paths
    $logoBanser = file_exists(public_path('images/banser-logo.png')) 
        ? public_path('images/banser-logo.png') 
        : (file_exists(public_path('images/banser.png')) ? public_path('images/banser.png') : public_path('images/banser-logo.svg'));

    $logoAdmin = file_exists(public_path('images/LOGO_ADMIN_rev_2.png')) 
        ? public_path('images/LOGO_ADMIN_rev_2.png') 
        : (file_exists(public_path('images/adminpers-logo.png')) ? public_path('images/adminpers-logo.png') : public_path('images/LOGO_ADMIN_rev_2.svg'));

    // Digital Signature & Stamp Paths
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
        : null;

    $stempelAnsorPath = ($profil->stempel_ansor && file_exists(storage_path('app/public/' . $profil->stempel_ansor))) 
        ? storage_path('app/public/' . $profil->stempel_ansor) 
        : null;

    $namaSatkorcab = $profil->satkorcab ?: 'SATKORCAB BANSER KABUPATEN BLITAR';
    $alamatSekretariat = $profil->alamat_sekretariat ?: 'Jl. Ahmad Yani No. 12, Blitar';
    $emailResmi = $profil->email ?: 'adminpers.satkorcab@banser-blitar.id';
@endphp

@if($isDraf)<div class="stamp-draf">DRAF</div>@endif

<div class="page">
    {{-- 1. KOP SURAT KHUSUS BANSER --}}
    <div class="kop-wrapper">
        <div class="kop-inner">
            <div class="kop-col-logo-left">
                @if(file_exists($logoBanser))
                    <img src="{{ $logoBanser }}" alt="Logo BANSER">
                @endif
            </div>
            <div class="kop-col-text">
                <div class="kop-ormas">PENGURUS CABANG GERAKAN PEMUDA ANSOR KABUPATEN BLITAR</div>
                <div class="kop-nama">BANSER</div>
                <div class="kop-satuan">SATUAN KOORDINASI CABANG (SATKORCAB)</div>
                <div class="kop-satuan">{{ strtoupper($namaSatkorcab) }}</div>
                <div class="kop-alamat">{{ $alamatSekretariat }} &nbsp;|&nbsp; Surel: {{ $emailResmi }}</div>
            </div>
            <div class="kop-col-logo-right">
                @if(file_exists($logoAdmin))
                    <img src="{{ $logoAdmin }}" alt="Logo Adminpers">
                @endif
            </div>
        </div>
    </div>

    {{-- 2. FORMAT KHUSUS SESUAI JENIS SURAT --}}
    @if($jenisKode === 'SU')
        {{-- ================= FORMAT KHUSUS SURAT UNDANGAN (SU) ================= --}}
        <table class="header-meta-tbl">
            <tr>
                <td class="meta-left">
                    <table class="nomor-meta-tbl">
                        <tr>
                            <td class="label">Nomor</td>
                            <td class="colon">:</td>
                            <td><strong>{{ $isDraf ? 'DRAF (Menunggu Verifikasi)' : $nomorSurat }}</strong></td>
                        </tr>
                        <tr>
                            <td class="label">Lampiran</td>
                            <td class="colon">:</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td class="label">Perihal</td>
                            <td class="colon">:</td>
                            <td><strong><u>{{ $surat->perihal ?? 'UNDANGAN KEGIATAN' }}</u></strong></td>
                        </tr>
                    </table>
                </td>
                <td class="meta-right">
                    Blitar, {{ $tglFull }}
                </td>
            </tr>
        </table>

        <div class="tujuan-box">
            <div class="yth">Kepada Yang Terhormat:</div>
            <div class="nama-penerima">{{ $surat->tujuan ?? 'Sahabat-sahabat' }}</div>
            <div class="tempat-penerima">Di Tempat</div>
        </div>

        <div class="salam-pembuka">
            Bismillaahirrohmaanirrohiim<br>
            Assalamu’alaikum Warahmatullahi Wabarakaatuh
        </div>

        <div class="body-para">
            Salam silaturahim teriring do’a kami sampaikan kepada Sahabat, semoga senantiasa dalam limpahan taufiq, hidayah, dan inayah Allah Subhanahu Wa Ta’ala serta sukses dalam menjalankan amanah organisasi.
        </div>

        <div class="body-para">
            Sehubungan dengan agenda program kerja Satuan Koordinasi Cabang (SATKORCAB) BANSER Kabupaten Blitar, bersama ini kami mengharap dengan hormat kehadiran Sahabat pada kegiatan yang akan diselenggarakan besok pada:
        </div>

        <table class="undangan-tbl">
            <tr>
                <td class="label">Hari / Tanggal</td>
                <td class="colon">:</td>
                <td><strong>{{ $surat->hari_tanggal ?? $surat->acara_tanggal ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td class="colon">:</td>
                <td>{{ $surat->waktu ?? $surat->acara_waktu ?? 'Selesai' }}</td>
            </tr>
            <tr>
                <td class="label">Tempat</td>
                <td class="colon">:</td>
                <td>{{ $surat->tempat ?? $surat->acara_tempat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Acara</td>
                <td class="colon">:</td>
                <td><strong>{{ $surat->acara ?? $surat->acara_nama ?? ($surat->perihal ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td class="label">Pakaian / Seragam</td>
                <td class="colon">:</td>
                <td>{{ $surat->pakaian ?? $surat->acara_pakaian ?? 'PDL BANSER Lengkap / Menyesuaikan' }}</td>
            </tr>
        </table>

        <div class="body-para">
            Mengingat pentingnya agenda tersebut, kami mohon Sahabat hadir tepat waktu demi kelancaran dan ketertiban tugas persyarikatan.
        </div>

        <div class="body-para">
            Demikian surat undangan ini kami sampaikan, atas perhatian, kedisiplinan, dan dedikasi Sahabat diucapkan terima kasih.
        </div>

        <div class="salam-penutup">
            Wallahul Muwaffiq Ila Aqwamith Thorieq<br>
            Wassalamu’alaikum Warahmatullahi Wabarakaatuh
        </div>

    @elseif($jenisKode === 'SPH')
        {{-- ================= FORMAT KHUSUS SURAT PERINTAH HARIAN (SPH) ================= --}}
        <div class="judul-center-box">
            <h2>SURAT PERINTAH HARIAN</h2>
            <div class="nomor-center">
                Nomor : {{ $isDraf ? 'DRAF / SPH / ' . date('Y') : $nomorSurat }}
            </div>
        </div>

        <div class="sph-row">
            <div class="sph-label">Menimbang</div>
            <div class="sph-colon">:</div>
            <div class="sph-val">Bahwa demi tertib administrasi, kedisiplinan operasional, serta terwujudnya soliditas garis komando dalam pelaksanaan instruksi organisasi.</div>
        </div>

        <div class="sph-row">
            <div class="sph-label">Dasar / Rujukan</div>
            <div class="sph-colon">:</div>
            <div class="sph-val">{{ $surat->dasar ?? 'Peraturan Organisasi GP Ansor & Pedoman Administrasi Satuan Koordinasi Nasional BANSER.' }}</div>
        </div>

        <div style="text-align: center; font-weight: bold; font-size: 11.5pt; margin: 12px 0; letter-spacing: 2px;">
            MEMERINTAHKAN :
        </div>

        <div class="sph-row">
            <div class="sph-label">Kepada Satuan</div>
            <div class="sph-colon">:</div>
            <div class="sph-val"><strong>{{ $surat->untuk_satuan ?? 'Seluruh Jajaran Satkoryon & Personel BANSER Se-Kabupaten Blitar' }}</strong></div>
        </div>

        <div class="sph-row">
            <div class="sph-label">Isi Perintah</div>
            <div class="sph-colon">:</div>
            <div class="sph-val" style="white-space: pre-line;">{{ $surat->isi_perintah ?? $surat->perihal }}</div>
        </div>

        <div class="body-para" style="margin-top: 14px;">
            Surat Perintah Harian ini dikeluarkan untuk dilaksanakan dengan penuh kesungguhan dan dedikasi, serta dilaporkan pelaksanaannya kepada pimpinan Satkorcab.
        </div>

    @elseif($jenisKode === 'ST')
        {{-- ================= FORMAT KHUSUS SURAT TUGAS / MANDAT (ST) ================= --}}
        <div class="judul-center-box">
            <h2>SURAT TUGAS / MANDAT</h2>
            <div class="nomor-center">
                Nomor : {{ $isDraf ? 'DRAF / ST / ' . date('Y') : $nomorSurat }}
            </div>
        </div>

        <div class="sph-row">
            <div class="sph-label">Dasar Penugasan</div>
            <div class="sph-colon">:</div>
            <div class="sph-val">{{ $surat->dasar ?? 'Program kerja Satuan Koordinasi Cabang (SATKORCAB) BANSER dan arahan Pimpinan Cabang GP Ansor.' }}</div>
        </div>

        <div style="text-align: center; font-weight: bold; font-size: 11.5pt; margin: 12px 0; letter-spacing: 2px;">
            MENUGASKAN :
        </div>

        @if(!empty($surat->penerima_list))
            <table class="penerima-tbl">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">No.</th>
                        <th>Nama Personel / Anggota BANSER</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($surat->penerima_list as $idx => $penerima)
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td><strong>{{ $penerima }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif($surat->penerima_tugas)
            <div style="margin: 6px 0 10px 10px; font-size: 10.5pt; white-space: pre-line;">
                <strong>Personel:</strong><br>
                {{ is_array($surat->penerima_tugas) ? implode(", ", $surat->penerima_tugas) : $surat->penerima_tugas }}
            </div>
        @endif

        <div class="body-para" style="margin-top: 6px;">Untuk melaksanakan tugas kedinasan sebagai berikut:</div>

        <table class="st-detail-tbl">
            <tr>
                <td class="label">Uraian Tugas</td>
                <td class="colon">:</td>
                <td><strong>{{ $surat->uraian_tugas ?? $surat->perihal }}</strong></td>
            </tr>
            @if($surat->tgl_mulai)
                <tr>
                    <td class="label">Periode Tanggal</td>
                    <td class="colon">:</td>
                    <td>
                        {{ Carbon::parse($surat->tgl_mulai)->day . ' ' . $bulanId[Carbon::parse($surat->tgl_mulai)->month] . ' ' . Carbon::parse($surat->tgl_mulai)->year }}
                        @if($surat->tgl_selesai && $surat->tgl_selesai != $surat->tgl_mulai)
                            s/d {{ Carbon::parse($surat->tgl_selesai)->day . ' ' . $bulanId[Carbon::parse($surat->tgl_selesai)->month] . ' ' . Carbon::parse($surat->tgl_selesai)->year }}
                        @endif
                    </td>
                </tr>
            @endif
            @if($surat->tempat_tugas ?? $surat->st_tempat)
                <tr>
                    <td class="label">Lokasi Penugasan</td>
                    <td class="colon">:</td>
                    <td>{{ $surat->tempat_tugas ?? $surat->st_tempat }}</td>
                </tr>
            @endif
        </table>

        <div class="body-para">
            Demikian Surat Tugas ini dibuat agar dilaksanakan dengan sebaik-baiknya, penuh tanggung jawab, serta menjaga martabat dan kehormatan Korps BANSER.
        </div>
    @endif

    {{-- 3. FORMAT TANDA TANGAN KHUSUS SATKORCAB BANSER --}}
    <div class="ttd-container">
        @if($jenisKode !== 'SU')
            <div style="text-align: right; margin-bottom: 8px; font-size: 11pt;">
                Ditetapkan di : Blitar<br>
                Pada tanggal : {{ $tglFull }}
            </div>
        @endif

        <table class="ttd-table">
            <tr>
                <!-- SISI KIRI: KASETMA -->
                <td class="ttd-col">
                    <div class="ttd-instansi">SATUAN KOORDINASI CABANG</div>
                    <div class="ttd-jabatan">Kepala Sekretariat Markas,</div>
                    <div class="ttd-box-visual">
                        @if($ttdKasetmaPath)
                            <img src="{{ $ttdKasetmaPath }}" alt="TTD Kasetma" class="ttd-img">
                        @endif
                    </div>
                    <div class="ttd-nama">{{ $profil->nama_kasetma ?: 'Sahabat Kasetma' }}</div>
                    <div class="ttd-npa">NPA. {{ $profil->kode_wilayah ? $profil->kode_wilayah . '.002' : 'X-28.002' }}</div>
                </td>

                <!-- SISI KANAN: KASATKORCAB (VERIFIKATOR) DILENGKAPI STEMPEL RESMI -->
                <td class="ttd-col">
                    <div class="ttd-instansi">SATUAN KOORDINASI CABANG</div>
                    <div class="ttd-jabatan">Kepala Satuan Koordinasi Cabang,</div>
                    <div class="ttd-box-visual">
                        {{-- Stempel Resmi Banser disematkan di sebelah tanda tangan --}}
                        @if($stempelBanserPath)
                            <img src="{{ $stempelBanserPath }}" alt="Stempel BANSER" class="stempel-img">
                        @endif

                        @if($ttdKasatkorcabPath)
                            <img src="{{ $ttdKasatkorcabPath }}" alt="TTD Kasatkorcab" class="ttd-img">
                        @endif
                    </div>
                    <div class="ttd-nama">{{ $profil->nama_kasatkorcab ?: 'Nur Rokhim' }}</div>
                    <div class="ttd-npa">NPA. {{ $profil->kode_wilayah ? $profil->kode_wilayah . '.001' : 'X-28.001' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- 4. TEMBUSAN RESMI --}}
    <div class="tembusan-box">
        <strong>Tembusan Yth :</strong>
        <ol>
            <li>Ketua PC GP Ansor Kabupaten Blitar (sebagai laporan);</li>
            <li>Satuan Koordinasi Wilayah (SATKORWIL) BANSER Jawa Timur;</li>
            <li>Arsip Markas Komando Satkorcab.</li>
        </ol>
    </div>
</div>

<div class="pdf-footer">
    Dokumen Administrasi Resmi SIGAP BANSER – Satkorcab Kab. Blitar
    @if(!$isDraf) &nbsp;|&nbsp; <strong>{{ $nomorSurat }}</strong> @endif
</div>
</body>
</html>
