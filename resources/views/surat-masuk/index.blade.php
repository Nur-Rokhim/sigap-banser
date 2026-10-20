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
                                <div style="display: inline-flex; gap: 5px; align-items: center; justify-content: flex-end;">
                                    <!-- Icon Buku: Detail -->
                                    <a href="{{ route('surat-masuk.show', $item->id) }}" class="btn-icon btn-icon-detail" title="Lihat detail & disposisi">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span>Detail</span>
                                    </a>

                                    <!-- Icon Bolpoint: Edit -->
                                    <a href="{{ route('surat-masuk.edit', $item->id) }}" class="btn-icon btn-icon-edit" title="Edit surat masuk">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Icon Tempat Sampah: Hapus dengan Notif Modal Menarik -->
                                    <button type="button" 
                                            class="btn-icon btn-icon-delete" 
                                            title="Hapus surat masuk"
                                            onclick="openDeleteModal('{{ route('surat-masuk.destroy', $item->id) }}', 'Surat Masuk No: {{ $item->no_surat ?? $item->nomor_surat }} (Dari: {{ $item->dari }})')">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
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
