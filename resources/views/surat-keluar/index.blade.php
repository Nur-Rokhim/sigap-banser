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
                        <option value="SPH" {{ request('jenis') === 'SPH' ? 'selected' : '' }}>SPH (Surat Perintah Harian)</option>
                        <option value="ST" {{ request('jenis') === 'ST' ? 'selected' : '' }}>ST (Surat Tugas)</option>
                        <option value="SK" {{ request('jenis') === 'SK' ? 'selected' : '' }}>SK (Surat Keputusan)</option>
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
                        <th>Acuan (Surat Masuk)</th>
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
                                    <span class="badge" style="background-color: rgba(201, 162, 74, 0.15); color: #8A6516; border: 1px solid rgba(201, 162, 74, 0.3);">SPH - Perintah Harian</span>
                                @elseif($j === 'ST')
                                    <span class="badge" style="background-color: rgba(14, 155, 74, 0.12); color: var(--green); border: 1px solid rgba(14, 155, 74, 0.25);">ST - Tugas</span>
                                @else
                                    <span class="badge badge-gray">{{ $j }}</span>
                                @endif
                            </td>
                            <td>
                                @if($item->suratMasuk)
                                    <div style="font-size: 0.85rem; max-width: 260px;">
                                        <div style="font-weight: 700; color: var(--ink); display: flex; align-items: center; gap: 4px;">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent); flex-shrink: 0;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span title="Pemohon: {{ $item->suratMasuk->dari }}">{{ Str::limit($item->suratMasuk->dari, 28) }}</span>
                                        </div>
                                        <div style="color: var(--ink-muted); font-size: 0.8rem; margin-top: 2px; line-height: 1.3;" title="Perihal: {{ $item->suratMasuk->perihal }}">
                                            {{ Str::limit($item->suratMasuk->perihal, 50) }}
                                        </div>
                                        <div style="margin-top: 4px;">
                                            <span class="badge" style="background-color: rgba(90, 14, 20, 0.08); color: var(--hero1); font-size: 0.72rem; padding: 1px 6px;">
                                                No: {{ Str::limit($item->suratMasuk->no_surat, 22) }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <span style="color: var(--ink-muted); font-size: 0.82rem; font-style: italic;">
                                        - Tanpa Acuan -
                                    </span>
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
                                <div style="display: inline-flex; gap: 5px; align-items: center; justify-content: flex-end;">
                                    <!-- Icon Buku: Detail -->
                                    <a href="{{ route('surat-keluar.show', $item->id) }}" class="btn-icon btn-icon-detail" title="Lihat detail surat keluar">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span>Detail</span>
                                    </a>

                                    <!-- Icon Bolpoint: Edit -->
                                    <a href="{{ route('surat-keluar.edit', $item->id) }}" class="btn-icon btn-icon-edit" title="Edit surat keluar">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Icon Tempat Sampah: Hapus dengan Notif Modal Menarik -->
                                    <button type="button" 
                                            class="btn-icon btn-icon-delete" 
                                            title="Hapus surat keluar"
                                            onclick="openDeleteModal('{{ route('surat-keluar.destroy', $item->id) }}', 'Surat Keluar {{ $item->nomor_surat ?: ('Draf #' . $item->id) }} (Perihal: {{ addslashes($item->perihal) }})')">
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
                            <td colspan="8" style="text-align: center; padding: 48px 16px;">
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
