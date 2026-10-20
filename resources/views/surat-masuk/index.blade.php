@extends('layouts.app')

@section('title', 'Daftar Surat Masuk')

@section('content')
<div class="page-container">
    <!-- Header with Action Button -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Buku Agenda Surat Masuk</h2>
            <p style="color: var(--ink-muted); font-size: 0.92rem;">Pencatatan, pengarsipan, dan tindak lanjut disposisi surat masuk Satkorcab.</p>
        </div>
        <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary btn-lg">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Catat Surat Masuk Baru</span>
        </a>
    </div>

    <!-- Search & Filter Card -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-body" style="padding: 16px 20px;">
            <form action="{{ route('surat-masuk.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                <div style="flex: 1; min-width: 260px; position: relative;">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari berdasarkan nomor surat, pengirim, atau perihal..." 
                           value="{{ request('search') }}">
                </div>

                <div style="min-width: 170px;">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="didisposisi" {{ request('status') === 'didisposisi' ? 'selected' : '' }}>Didisposisi</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari</span>
                </button>

                @if(request('search') || request('status'))
                    <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary" title="Reset filter">Reset</a>
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
                        <th>Dari / Pengirim</th>
                        <th>Perihal</th>
                        <th>Tgl Diterima</th>
                        <th>Status</th>
                        <th style="text-align: right; width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratMasuk as $index => $item)
                        <tr>
                            <td style="text-align: center; color: var(--ink-muted);">
                                {{ method_exists($suratMasuk, 'firstItem') && $suratMasuk->firstItem() ? $suratMasuk->firstItem() + $index : $index + 1 }}
                            </td>
                            <td>
                                <strong style="font-family: 'Archivo', sans-serif; font-size: 0.95rem;">
                                    {{ $item->nomor_surat ?? '-' }}
                                </strong>
                                @if(isset($item->file_scan) || isset($item->scan_surat))
                                    <span title="Tersedia berkas scan" style="margin-left: 4px; color: var(--accent); vertical-align: middle;">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline-block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $item->pengirim ?? $item->dari ?? '-' }}</div>
                                @if(isset($item->tanggal_surat))
                                    <small style="color: var(--ink-muted);">Tgl Surat: {{ \Carbon\Carbon::parse($item->tanggal_surat)->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td>
                                <span style="font-weight: 500;">{{ $item->perihal ?? '-' }}</span>
                            </td>
                            <td>
                                {{ isset($item->tanggal_diterima) ? \Carbon\Carbon::parse($item->tanggal_diterima)->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                @php
                                    $st = strtolower($item->status ?? 'baru');
                                @endphp
                                @if($st === 'baru')
                                    <span class="badge badge-red">Baru</span>
                                @elseif($st === 'didisposisi')
                                    <span class="badge badge-yellow">Didisposisi</span>
                                @elseif($st === 'selesai')
                                    <span class="badge badge-green">Selesai</span>
                                @else
                                    <span class="badge badge-gray">{{ ucfirst($st) }}</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px; align-items: center;">
                                    <a href="{{ route('surat-masuk.show', $item->id) }}" class="btn btn-secondary btn-sm" title="Lihat detail & disposisi">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p><strong>Tidak ada data surat masuk ditemukan.</strong></p>
                                    <p style="font-size: 0.85rem; margin-top: 4px;">Silakan gunakan kata kunci lain atau catat surat baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($suratMasuk, 'hasPages') && $suratMasuk->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
                {{ $suratMasuk->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
