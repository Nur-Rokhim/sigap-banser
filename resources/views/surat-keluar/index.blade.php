@extends('layouts.app')

@section('title', 'Daftar Surat Keluar')

@section('content')
<div class="page-container">
    <!-- Header with Action Button -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Buku Registrasi Surat Keluar</h2>
            <p style="color: var(--ink-muted); font-size: 0.92rem;">Penerbitan nomor resmi, verifikasi pimpinan, dan distribusi surat dinas Satkorcab.</p>
        </div>
        <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary btn-lg">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Buat Surat Keluar Baru</span>
        </a>
    </div>

    <!-- Search & Filter Card -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-body" style="padding: 16px 20px;">
            <form action="{{ route('surat-keluar.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                <div style="flex: 1; min-width: 240px;">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari nomor surat, tujuan, atau perihal..." 
                           value="{{ request('search') }}">
                </div>

                <div style="min-width: 150px;">
                    <select name="jenis" class="form-control">
                        <option value="">Semua Jenis</option>
                        <option value="SU" {{ request('jenis') === 'SU' ? 'selected' : '' }}>SU (Undangan)</option>
                        <option value="SPH" {{ request('jenis') === 'SPH' ? 'selected' : '' }}>SPH (Pemberitahuan)</option>
                        <option value="ST" {{ request('jenis') === 'ST' ? 'selected' : '' }}>ST (Surat Tugas)</option>
                    </select>
                </div>

                <div style="min-width: 160px;">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="draf" {{ request('status') === 'draf' ? 'selected' : '' }}>Draf</option>
                        <option value="verifikasi" {{ request('status') === 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="terkirim" {{ request('status') === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari</span>
                </button>

                @if(request('search') || request('jenis') || request('status'))
                    <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary" title="Reset filter">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th>Nomor Surat</th>
                        <th>Jenis</th>
                        <th>Perihal & Tujuan</th>
                        <th>Tgl Surat</th>
                        <th>Status</th>
                        <th style="text-align: right; width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratKeluar as $index => $item)
                        <tr>
                            <td style="text-align: center; color: var(--ink-muted);">
                                {{ method_exists($suratKeluar, 'firstItem') && $suratKeluar->firstItem() ? $suratKeluar->firstItem() + $index : $index + 1 }}
                            </td>
                            <td>
                                @if(!empty($item->nomor_surat))
                                    <span style="font-family: 'Archivo', monospace, sans-serif; font-weight: 700; color: var(--accent);">
                                        {{ $item->nomor_surat }}
                                    </span>
                                @else
                                    <span style="font-style: italic; color: var(--ink-muted); font-size: 0.85rem;">
                                        [Draf - Belum Terbit]
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $j = strtoupper($item->jenis_surat ?? $item->jenis ?? 'SU');
                                @endphp
                                @if($j === 'SU')
                                    <span class="badge" style="background-color: rgba(90, 14, 20, 0.1); color: var(--hero1); border: 1px solid rgba(90, 14, 20, 0.2);">SU - Undangan</span>
                                @elseif($j === 'SPH')
                                    <span class="badge" style="background-color: rgba(201, 162, 74, 0.15); color: #8A6516; border: 1px solid rgba(201, 162, 74, 0.3);">SPH - Pemberitahuan</span>
                                @elseif($j === 'ST')
                                    <span class="badge" style="background-color: rgba(14, 155, 74, 0.12); color: var(--green); border: 1px solid rgba(14, 155, 74, 0.25);">ST - Tugas</span>
                                @else
                                    <span class="badge badge-gray">{{ $j }}</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--ink);">{{ $item->perihal ?? '-' }}</div>
                                <small style="color: var(--ink-muted);">Tujuan: {{ $item->tujuan ?? '-' }}</small>
                            </td>
                            <td>
                                {{ isset($item->tanggal_surat) ? \Carbon\Carbon::parse($item->tanggal_surat)->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                @php
                                    $st = strtolower($item->status ?? 'draf');
                                @endphp
                                @if($st === 'draf')
                                    <span class="badge badge-gray">Draf</span>
                                @elseif($st === 'verifikasi')
                                    <span class="badge badge-yellow">Verifikasi</span>
                                @elseif($st === 'disetujui')
                                    <span class="badge badge-green">Disetujui</span>
                                @elseif($st === 'terkirim')
                                    <span class="badge badge-blue">Terkirim</span>
                                @else
                                    <span class="badge badge-gray">{{ ucfirst($st) }}</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px; align-items: center;">
                                    <a href="{{ route('surat-keluar.show', $item->id) }}" class="btn btn-secondary btn-sm" title="Lihat detail">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px 16px;">
                                <div style="color: var(--ink-muted);">
                                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 12px; opacity: 0.5;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    <p><strong>Tidak ada data surat keluar.</strong></p>
                                    <p style="font-size: 0.85rem; margin-top: 4px;">Klik tombol "Buat Surat Keluar Baru" untuk menerbitkan dokumen.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($suratKeluar, 'hasPages') && $suratKeluar->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
                {{ $suratKeluar->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
