@extends('layouts.app')

@section('title', 'Laporan Rekap Persuratan ' . $tahun . ' - SIGAP BANSER')

@push('styles')
<style>
    .laporan-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    /* Filter Bar */
    .filter-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
    }
    .filter-row {
        display: flex;
        align-items: flex-end;
        gap: 14px;
        flex-wrap: wrap;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
        min-width: 140px;
    }
    .filter-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--ink-muted);
    }
    .filter-select {
        padding: 9px 12px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        background: var(--surface-alt);
        color: var(--ink);
        font-size: 0.88rem;
        font-family: inherit;
        cursor: pointer;
    }

    /* Summary Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-2px); }
    .stat-num {
        font-size: 2rem;
        font-weight: 900;
        font-family: 'Archivo', sans-serif;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--ink-muted);
        letter-spacing: 0.04em;
    }
    .stat-sub {
        font-size: 0.78rem;
        color: var(--ink-muted);
        margin-top: 6px;
    }

    /* Section Cards */
    .section-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .section-head {
        padding: 14px 20px;
        background: var(--surface-alt);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-head h3 {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--ink);
        margin: 0;
    }
    .section-body { padding: 0; }

    /* Jenis table */
    .jenis-table {
        width: 100%;
        border-collapse: collapse;
    }
    .jenis-table th {
        background: var(--surface-alt);
        padding: 10px 16px;
        text-align: left;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-muted);
        border-bottom: 1px solid var(--border);
    }
    .jenis-table td {
        padding: 12px 16px;
        font-size: 0.88rem;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .jenis-table tbody tr:last-child td { border-bottom: none; }
    .jenis-table tbody tr:hover { background: var(--surface-alt); }

    /* Bulan bar chart */
    .bar-chart-wrapper {
        padding: 16px 20px;
    }
    .bar-chart {
        display: flex;
        align-items: flex-end;
        gap: 4px;
        height: 80px;
    }
    .bar-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        position: relative;
    }
    .bar-fill {
        width: 100%;
        border-radius: 4px 4px 0 0;
        transition: height 0.4s ease;
        min-height: 2px;
        position: relative;
        cursor: default;
    }
    .bar-fill:hover::after {
        content: attr(data-val);
        position: absolute;
        top: -24px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--ink);
        color: var(--surface);
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        white-space: nowrap;
        z-index: 10;
    }
    .bar-label {
        font-size: 0.65rem;
        color: var(--ink-muted);
        text-align: center;
        font-weight: 600;
    }

    /* Recent nomor list */
    .nomor-list { list-style: none; padding: 0 20px 16px; }
    .nomor-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-light);
        gap: 10px;
    }
    .nomor-item:last-child { border-bottom: none; }
    .nomor-badge {
        background: linear-gradient(135deg, var(--hero1), var(--red));
        color: var(--yellow);
        font-weight: 800;
        font-size: 0.8rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-family: 'Archivo', monospace;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .nomor-perihal {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ink);
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .nomor-tgl {
        font-size: 0.78rem;
        color: var(--ink-muted);
        white-space: nowrap;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="page-container">

    {{-- Header --}}
    <div class="laporan-header-row">
        <div>
            <h2 style="margin:0; font-size:1.45rem; font-weight:800; color:var(--ink);">
                Laporan Rekap Persuratan
            </h2>
            <p style="margin:4px 0 0; color:var(--ink-muted); font-size:0.88rem;">
                Statistik surat masuk dan keluar —
                @if($bulan)
                    {{ $namaBulan[(int)$bulan] ?? '' }}
                @endif
                Tahun {{ $tahun }}
            </p>
        </div>
        <a href="{{ route('laporan.pdf', ['tahun' => $tahun, 'bulan' => $bulan, 'jenis' => $jenis]) }}"
           target="_blank"
           class="btn btn-primary"
           style="background-color: #8E1B22;"
           id="btn-cetak-laporan">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            <span>Cetak PDF</span>
        </a>
    </div>

    {{-- Filter --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('laporan.index') }}" class="filter-row" id="form-filter-laporan">
            <div class="filter-group">
                <label class="filter-label">Tahun</label>
                <select name="tahun" class="filter-select" onchange="this.form.submit()" id="select-tahun">
                    @foreach($listTahun as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Bulan</label>
                <select name="bulan" class="filter-select" onchange="this.form.submit()" id="select-bulan">
                    <option value="">Semua Bulan</option>
                    @foreach($namaBulan as $nb => $nm)
                        <option value="{{ $nb }}" {{ (string)$bulan === (string)$nb ? 'selected' : '' }}>{{ $nm }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Jenis Surat Keluar</label>
                <select name="jenis" class="filter-select" onchange="this.form.submit()" id="select-jenis">
                    <option value="">Semua Jenis</option>
                    @foreach($daftarJenis as $j)
                        <option value="{{ $j->kode }}" {{ $jenis === $j->kode ? 'selected' : '' }}>
                            {{ $j->kode }} — {{ $j->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-num" style="color: var(--blue);">{{ $totalMasuk }}</div>
            <div class="stat-label">Total Surat Masuk</div>
            <div class="stat-sub">
                Baru: {{ $masukBaru }} &bull;
                Disposisi: {{ $masukDisposisi }} &bull;
                Selesai: {{ $masukSelesai }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color: var(--accent);">{{ $totalKeluar }}</div>
            <div class="stat-label">Total Surat Keluar</div>
            <div class="stat-sub">
                Draf: {{ $keluarDraf }} &bull;
                Verifikasi: {{ $keluarVerifikasi }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color: var(--green);">{{ $keluarDisetujui + $keluarTerkirim }}</div>
            <div class="stat-label">SK/Surat Bernomor Resmi</div>
            <div class="stat-sub">
                Disetujui: {{ $keluarDisetujui }} &bull;
                Terkirim: {{ $keluarTerkirim }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color: var(--gold);">{{ $totalMasuk + $totalKeluar }}</div>
            <div class="stat-label">Total Dokumen Persuratan</div>
            <div class="stat-sub">Masuk + Keluar Tahun {{ $tahun }}</div>
        </div>
    </div>

    {{-- Two column layout --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
        {{-- Surat Masuk per bulan --}}
        <div class="section-card">
            <div class="section-head">
                <svg width="18" height="18" fill="none" stroke="var(--blue)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3>Surat Masuk per Bulan</h3>
            </div>
            <div class="section-body bar-chart-wrapper">
                @php $maxM = max(array_values($masukPerBulan) + [1]); @endphp
                <div class="bar-chart">
                    @foreach($masukPerBulan as $b => $val)
                        <div class="bar-col">
                            <div class="bar-fill"
                                 style="height:{{ max(4, round($val / $maxM * 70)) }}px; background: {{ $bulan && (int)$bulan === $b ? 'var(--blue)' : 'rgba(29,104,189,0.4)' }};"
                                 data-val="{{ $val }}">
                            </div>
                            <span class="bar-label">{{ substr($namaBulan[$b], 0, 3) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Surat Keluar per bulan --}}
        <div class="section-card">
            <div class="section-head">
                <svg width="18" height="18" fill="none" stroke="var(--accent)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                <h3>Surat Keluar per Bulan</h3>
            </div>
            <div class="section-body bar-chart-wrapper">
                @php $maxK = max(array_values($keluarPerBulan) + [1]); @endphp
                <div class="bar-chart">
                    @foreach($keluarPerBulan as $b => $val)
                        <div class="bar-col">
                            <div class="bar-fill"
                                 style="height:{{ max(4, round($val / $maxK * 70)) }}px; background: {{ $bulan && (int)$bulan === $b ? 'var(--accent)' : 'rgba(142,27,34,0.35)' }};"
                                 data-val="{{ $val }}">
                            </div>
                            <span class="bar-label">{{ substr($namaBulan[$b], 0, 3) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Rekap per Jenis Surat --}}
    <div class="section-card">
        <div class="section-head">
            <svg width="18" height="18" fill="none" stroke="var(--gold)" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3>Rekap Surat Keluar per Jenis</h3>
        </div>
        <div class="section-body">
            <table class="jenis-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Jenis</th>
                        <th style="text-align:center;">Total Dibuat</th>
                        <th style="text-align:center;">Bernomor Resmi</th>
                        <th style="text-align:center;">Masih Draf/Proses</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($keluarPerJenis as $kode => $data)
                    <tr>
                        <td>
                            <span class="badge badge-gray" style="font-family: monospace; font-weight: 800;">{{ $kode }}</span>
                        </td>
                        <td style="font-weight:600;">{{ $data['nama'] }}</td>
                        <td style="text-align:center; font-weight:800; color:var(--ink);">{{ $data['total'] }}</td>
                        <td style="text-align:center;">
                            <span style="color: var(--green); font-weight:700;">{{ $data['bernomor'] }}</span>
                        </td>
                        <td style="text-align:center;">
                            @php $proses = $data['total'] - $data['bernomor']; @endphp
                            @if($proses > 0)
                                <span style="color: var(--gold); font-weight:700;">{{ $proses }}</span>
                            @else
                                <span style="color: var(--ink-muted);">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    {{-- Total --}}
                    <tr style="background: var(--surface-alt); font-weight: 800;">
                        <td colspan="2" style="font-weight:800; text-transform:uppercase; font-size:0.82rem; letter-spacing:0.04em; color:var(--ink-muted);">Total</td>
                        <td style="text-align:center; font-size:1.1rem; color:var(--accent);">{{ $totalKeluar }}</td>
                        <td style="text-align:center; font-size:1.1rem; color:var(--green);">{{ $keluarDisetujui + $keluarTerkirim }}</td>
                        <td style="text-align:center; font-size:1.1rem; color:var(--gold);">{{ $keluarDraf + $keluarVerifikasi }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Nomor Surat Terakhir Diterbitkan --}}
    @if($suratTerakhir->count() > 0)
    <div class="section-card">
        <div class="section-head">
            <svg width="18" height="18" fill="none" stroke="var(--accent)" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            <h3>Nomor Surat Terakhir Diterbitkan (Maks. 20)</h3>
        </div>
        <div class="section-body">
            <ul class="nomor-list">
                @foreach($suratTerakhir as $s)
                <li class="nomor-item">
                    <span class="nomor-badge">{{ $s->nomor_surat }}</span>
                    <span class="nomor-perihal" title="{{ $s->perihal }}">{{ $s->perihal }}</span>
                    <span class="nomor-tgl">{{ \Carbon\Carbon::parse($s->tgl_surat)->isoFormat('D MMM Y') }}</span>
                    <a href="{{ route('surat-keluar.show', $s->id) }}"
                       style="font-size:0.78rem; color:var(--accent); text-decoration:none; flex-shrink:0; white-space:nowrap;">
                        Detail →
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

</div>
@endsection
