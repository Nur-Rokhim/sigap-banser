@extends('layouts.app')

@section('title', 'Edit Personel Struktur Organisasi — SIGAP BANSER')

@push('styles')
<style>
    .edit-section-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .edit-section-head {
        padding: 16px 22px;
        background: var(--surface-alt);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .edit-section-head h2 {
        margin: 0;
        font-size: 1.08rem;
        font-weight: 800;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .edit-section-body {
        padding: 22px;
    }

    .unit-edit-block {
        background: var(--surface-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        margin-bottom: 18px;
        transition: border-color 0.2s ease;
    }
    .unit-edit-block:hover {
        border-color: rgba(201,162,74,0.4);
    }
    .unit-edit-block:last-child {
        margin-bottom: 0;
    }

    .unit-badge-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px dashed var(--border);
    }
    .unit-name {
        font-weight: 800;
        font-size: 0.98rem;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Wakasatkorcab row dynamic */
    .wakasat-row {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 12px;
        padding: 12px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
    }

    .bottom-save-bar {
        position: sticky;
        bottom: 0;
        background: var(--surface);
        border-top: 1px solid var(--border);
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 -4px 16px rgba(0,0,0,0.08);
        border-radius: var(--radius-md);
        margin-top: 28px;
        z-index: 100;
    }
</style>
@endpush

@section('content')
<div class="page-container" style="max-width: 1060px; margin: 0 auto;">
    <!-- Top Breadcrumb -->
    <div style="margin-bottom: 22px;">
        <a href="{{ route('struktur-organisasi.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.88rem; margin-bottom: 8px; color: var(--ink-muted);">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Struktur Organisasi
        </a>
        <h1 style="font-size: 1.55rem; font-weight: 900; color: var(--ink); margin: 0;">
            Kelola Susunan Personel Organisasi
        </h1>
        <p style="color: var(--ink-muted); font-size: 0.9rem; margin-top: 4px;">
            Perbarui data pejabat Kasatkorcab, Wakil, Kasetma, 7 Biro Markas, dan 7 Satuan Khusus (SATSUS).
        </p>
    </div>

    <form action="{{ route('struktur-organisasi.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- ════════════════════════════════════════════════════════════════
             BAGIAN 1: PIMPINAN UTAMA (KASATKORCAB & KASETMA)
        ════════════════════════════════════════════════════════════════ -->
        <div class="edit-section-card">
            <div class="edit-section-head">
                <h2>
                    <svg width="20" height="20" fill="none" stroke="var(--gold)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>1 & 3. Pimpinan Komando Utama</span>
                </h2>
                <span class="badge badge-red">KASATKORCAB & KASETMA</span>
            </div>
            <div class="edit-section-body">
                <div class="grid-2">
                    <!-- KASATKORCAB -->
                    <div class="unit-edit-block" style="border-left: 4px solid var(--accent);">
                        <div class="unit-badge-title">
                            <span class="unit-name">
                                <span class="badge badge-red">1</span>
                                <span>KASATKORCAB</span>
                            </span>
                            <span style="font-size: 0.75rem; color: var(--ink-muted);">Komandan Cabang</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap Kasatkorcab <span class="required">*</span></label>
                            <input type="text" 
                                   name="kasatkorcab[ketua]" 
                                   class="form-control" 
                                   value="{{ old('kasatkorcab.ketua', $kasatkorcab->ketua ?? '') }}" 
                                   placeholder="Contoh: Komandan H. M. Nur Rokhim" 
                                   required>
                            <div class="form-hint">Otomatis tersinkronisasi ke Profil Organisasi Satkorcab.</div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Keterangan / Catatan Tugas</label>
                            <input type="text" 
                                   name="kasatkorcab[keterangan]" 
                                   class="form-control" 
                                   value="{{ old('kasatkorcab.keterangan', $kasatkorcab->keterangan ?? '') }}" 
                                   placeholder="Kepala Satuan Koordinasi Cabang BANSER Kab. Blitar">
                        </div>
                    </div>

                    <!-- KASETMA -->
                    <div class="unit-edit-block" style="border-left: 4px solid var(--blue);">
                        <div class="unit-badge-title">
                            <span class="unit-name">
                                <span class="badge badge-blue">3</span>
                                <span>KASETMA</span>
                            </span>
                            <span style="font-size: 0.75rem; color: var(--ink-muted);">Kepala Sekretariat Markas</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap Kasetma <span class="required">*</span></label>
                            <input type="text" 
                                   name="kasetma[ketua]" 
                                   class="form-control" 
                                   value="{{ old('kasetma.ketua', $kasetma->ketua ?? '') }}" 
                                   placeholder="Contoh: Sahabat Kasetma Satkorcab" 
                                   required>
                            <div class="form-hint">Otomatis tersinkronisasi ke Profil Organisasi Satkorcab.</div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Keterangan / Catatan Tugas</label>
                            <input type="text" 
                                   name="kasetma[keterangan]" 
                                   class="form-control" 
                                   value="{{ old('kasetma.keterangan', $kasetma->keterangan ?? '') }}" 
                                   placeholder="Kepala Sekretariat Markas Satkorcab BANSER Kab. Blitar">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ════════════════════════════════════════════════════════════════
             BAGIAN 2: WAKASATKORCAB (BISA DITAMBAH / DIKURANGI)
        ════════════════════════════════════════════════════════════════ -->
        <div class="edit-section-card">
            <div class="edit-section-head">
                <h2>
                    <svg width="20" height="20" fill="none" stroke="var(--gold)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>2. WAKASATKORCAB / Wakil Kasatkorcab</span>
                </h2>
                <button type="button" onclick="tambahBarisWakasat()" class="btn btn-secondary btn-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Wakil Kasatkorcab</span>
                </button>
            </div>
            <div class="edit-section-body">
                <p style="font-size: 0.85rem; color: var(--ink-muted); margin-bottom: 16px;">
                    Jumlah Wakil Kasatkorcab dapat disesuaikan (bisa ditambah atau dikurangi sesuai kebutuhan organisasi).
                </p>

                <div id="wakasatContainer">
                    @forelse($wakasatkorcabList as $index => $wakasat)
                    <div class="wakasat-row" id="wakasat-row-{{ $wakasat->id }}">
                        <input type="hidden" name="wakasatkorcab[{{ $index }}][id]" value="{{ $wakasat->id }}">
                        <div style="flex: 1;">
                            <label class="form-label" style="font-size: 0.74rem;">Nama Posisi / Jabatan</label>
                            <input type="text" 
                                   name="wakasatkorcab[{{ $index }}][nama_posisi]" 
                                   class="form-control" 
                                   value="{{ $wakasat->nama_posisi }}" 
                                   placeholder="Contoh: Wakil Kasatkorcab I" 
                                   required>
                        </div>
                        <div style="flex: 1.5;">
                            <label class="form-label" style="font-size: 0.74rem;">Nama Personel Pejabat</label>
                            <input type="text" 
                                   name="wakasatkorcab[{{ $index }}][ketua]" 
                                   class="form-control" 
                                   value="{{ $wakasat->ketua }}" 
                                   placeholder="Nama lengkap pejabat yang ditunjuk">
                        </div>
                        <div style="flex: 1.5;">
                            <label class="form-label" style="font-size: 0.74rem;">Bidang / Keterangan</label>
                            <input type="text" 
                                   name="wakasatkorcab[{{ $index }}][keterangan]" 
                                   class="form-control" 
                                   value="{{ $wakasat->keterangan }}" 
                                   placeholder="Bidang koordinasi / tugas khusus">
                        </div>
                        <div style="padding-top: 24px;">
                            <button type="button" class="btn-icon btn-icon-delete" onclick="hapusBarisWakasat(this)" title="Hapus baris wakil ini">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="wakasat-row" id="wakasat-row-new-0">
                        <div style="flex: 1;">
                            <label class="form-label" style="font-size: 0.74rem;">Nama Posisi / Jabatan</label>
                            <input type="text" name="wakasatkorcab[0][nama_posisi]" class="form-control" value="Wakil Kasatkorcab I" required>
                        </div>
                        <div style="flex: 1.5;">
                            <label class="form-label" style="font-size: 0.74rem;">Nama Personel Pejabat</label>
                            <input type="text" name="wakasatkorcab[0][ketua]" class="form-control" placeholder="Nama pejabat yang ditunjuk">
                        </div>
                        <div style="flex: 1.5;">
                            <label class="form-label" style="font-size: 0.74rem;">Bidang / Keterangan</label>
                            <input type="text" name="wakasatkorcab[0][keterangan]" class="form-control" placeholder="Bidang koordinasi">
                        </div>
                        <div style="padding-top: 24px;">
                            <button type="button" class="btn-icon btn-icon-delete" onclick="hapusBarisWakasat(this)" title="Hapus baris">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ════════════════════════════════════════════════════════════════
             BAGIAN 3: 7 BIRO (KETUA & ANGGOTA)
        ════════════════════════════════════════════════════════════════ -->
        <div class="edit-section-card">
            <div class="edit-section-head">
                <h2>
                    <svg width="20" height="20" fill="none" stroke="var(--blue)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>4 s/d 10. Biro-Biro Markas Satkorcab (7 Biro)</span>
                </h2>
                <span class="badge badge-purple">KETUA & ANGGOTA</span>
            </div>
            <div class="edit-section-body">
                <p style="font-size: 0.85rem; color: var(--ink-muted); margin-bottom: 20px;">
                    Isi nama Kepala / Ketua Biro, dan tuliskan nama-nama anggota pada kotak daftar anggota (pisahkan satu nama per baris).
                </p>

                @foreach($biroList as $index => $biro)
                <div class="unit-edit-block">
                    <div class="unit-badge-title">
                        <span class="unit-name">
                            <span class="badge badge-purple">{{ 4 + $index }}</span>
                            <span>{{ $biro->nama_posisi }}</span>
                        </span>
                        <span class="badge badge-gray" style="font-family: monospace;">{{ $biro->singkatan }}</span>
                    </div>

                    <div class="grid-1-2">
                        <div>
                            <label class="form-label">Nama Ketua / Kepala Biro</label>
                            <input type="text" 
                                   name="biro[{{ $biro->id }}][ketua]" 
                                   class="form-control" 
                                   value="{{ old('biro.' . $biro->id . '.ketua', $biro->ketua) }}" 
                                   placeholder="Nama Kepala Biro">
                            <div class="form-hint" style="margin-top: 8px;">
                                {{ $biro->keterangan }}
                            </div>
                        </div>

                        <div>
                            <label class="form-label">
                                Daftar Anggota Biro <span style="font-weight: 400; text-transform: none; color: var(--ink-muted);">(Tulis satu nama per baris)</span>
                            </label>
                            <textarea name="biro[{{ $biro->id }}][anggota]" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Contoh:&#10;Sahabat Ahmad Fauzi&#10;Sahabat Zainal Arifin&#10;Sahabat Nur Hidayat">{{ is_array($biro->anggota) ? implode("\n", $biro->anggota) : '' }}</textarea>
                            <div class="form-hint">Tekan [Enter] untuk menambahkan anggota baru.</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- ════════════════════════════════════════════════════════════════
             BAGIAN 4: 7 SATUAN KHUSUS (SATSUS - KETUA, WAKIL, & ANGGOTA)
        ════════════════════════════════════════════════════════════════ -->
        <div class="edit-section-card">
            <div class="edit-section-head">
                <h2>
                    <svg width="20" height="20" fill="none" stroke="var(--green)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>11 s/d 17. Satuan Khusus (SATSUS - 7 Satuan)</span>
                </h2>
                <span class="badge badge-green">KETUA, WAKIL & ANGGOTA</span>
            </div>
            <div class="edit-section-body">
                <p style="font-size: 0.85rem; color: var(--ink-muted); margin-bottom: 20px;">
                    Lengkapi nama Ketua / Komandan, Wakil Ketua / Wakil Komandan, serta daftar seluruh anggota Satsus (satu nama per baris).
                </p>

                @foreach($satsusList as $index => $satsus)
                <div class="unit-edit-block" style="border-left: 4px solid var(--green);">
                    <div class="unit-badge-title">
                        <span class="unit-name">
                            <span class="badge badge-green">{{ 11 + $index }}</span>
                            <span>{{ $satsus->nama_posisi }}</span>
                        </span>
                        <span class="badge badge-gray" style="font-family: monospace;">{{ $satsus->singkatan }}</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1.5fr; gap: 16px;">
                        <div>
                            <label class="form-label">Nama Ketua / Komandan Satsus</label>
                            <input type="text" 
                                   name="satsus[{{ $satsus->id }}][ketua]" 
                                   class="form-control" 
                                   value="{{ old('satsus.' . $satsus->id . '.ketua', $satsus->ketua) }}" 
                                   placeholder="Komandan {{ $satsus->singkatan }}">
                        </div>

                        <div>
                            <label class="form-label">Nama Wakil Ketua / Wakil Komandan</label>
                            <input type="text" 
                                   name="satsus[{{ $satsus->id }}][wakil]" 
                                   class="form-control" 
                                   value="{{ old('satsus.' . $satsus->id . '.wakil', $satsus->wakil) }}" 
                                   placeholder="Wakil Komandan {{ $satsus->singkatan }}">
                        </div>

                        <div>
                            <label class="form-label">
                                Daftar Anggota Satsus <span style="font-weight: 400; text-transform: none; color: var(--ink-muted);">(Satu per baris)</span>
                            </label>
                            <textarea name="satsus[{{ $satsus->id }}][anggota]" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Nama anggota {{ $satsus->singkatan }}, satu per baris...">{{ is_array($satsus->anggota) ? implode("\n", $satsus->anggota) : '' }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Sticky Bottom Save Bar -->
        <div class="bottom-save-bar">
            <div>
                <strong style="color: var(--ink); font-size: 0.95rem;">Simpan Perubahan Susunan Personel</strong>
                <div style="font-size: 0.78rem; color: var(--ink-muted);">Pastikan seluruh nama pejabat dan anggota telah sesuai SK Satkorcab.</div>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('struktur-organisasi.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-gold btn-lg">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Seluruh Struktur Organisasi</span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let wakasatCount = {{ $wakasatkorcabList->count() + 10 }};

    function tambahBarisWakasat() {
        wakasatCount++;
        const container = document.getElementById('wakasatContainer');
        const romanNum = (container.children.length + 1);

        const newRow = document.createElement('div');
        newRow.className = 'wakasat-row';
        newRow.innerHTML = `
            <div style="flex: 1;">
                <label class="form-label" style="font-size: 0.74rem;">Nama Posisi / Jabatan</label>
                <input type="text" name="wakasatkorcab[${wakasatCount}][nama_posisi]" class="form-control" value="Wakil Kasatkorcab ${romanNum}" required>
            </div>
            <div style="flex: 1.5;">
                <label class="form-label" style="font-size: 0.74rem;">Nama Personel Pejabat</label>
                <input type="text" name="wakasatkorcab[${wakasatCount}][ketua]" class="form-control" placeholder="Nama pejabat yang ditunjuk">
            </div>
            <div style="flex: 1.5;">
                <label class="form-label" style="font-size: 0.74rem;">Bidang / Keterangan</label>
                <input type="text" name="wakasatkorcab[${wakasatCount}][keterangan]" class="form-control" placeholder="Bidang koordinasi / tugas khusus">
            </div>
            <div style="padding-top: 24px;">
                <button type="button" class="btn-icon btn-icon-delete" onclick="hapusBarisWakasat(this)" title="Hapus baris wakil ini">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Hapus</span>
                </button>
            </div>
        `;
        container.appendChild(newRow);
    }

    function hapusBarisWakasat(btn) {
        const row = btn.closest('.wakasat-row');
        if (confirm('Hapus baris Wakil Kasatkorcab ini?')) {
            row.remove();
        }
    }
</script>
@endpush
@endsection
