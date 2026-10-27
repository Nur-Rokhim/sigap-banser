@extends('layouts.app')

@section('title', 'Tambah Anggota BANSER - Satkorcab Kab. Blitar')

@push('styles')
<style>
    .form-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        padding: 32px 36px;
        max-width: 680px;
        margin: 0 auto;
    }
    @media (max-width: 640px) {
        .form-card { padding: 20px 16px; }
    }
    .form-section-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-group {
        margin-bottom: 18px;
    }
    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--ink-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
    }
    .form-label .required {
        color: var(--red);
        margin-left: 2px;
    }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        background: var(--surface-alt);
        color: var(--ink);
        font-size: 0.92rem;
        font-family: inherit;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(142, 27, 34, 0.12);
    }
    .form-control.is-invalid {
        border-color: var(--red);
    }
    .invalid-feedback {
        color: var(--red);
        font-size: 0.8rem;
        margin-top: 4px;
    }
    .form-hint {
        font-size: 0.8rem;
        color: var(--ink-muted);
        margin-top: 4px;
    }
    .form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="page-container">
    {{-- Back Link --}}
    <a href="{{ route('anggota.index') }}"
       style="display:inline-flex; align-items:center; gap:6px; font-size:0.88rem; color:var(--ink-muted); text-decoration:none; margin-bottom:20px;">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Anggota
    </a>

    <div class="form-card">
        <div class="form-section-title">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Tambah Anggota BANSER Baru
        </div>

        <form method="POST" action="{{ route('anggota.store') }}" id="form-tambah-anggota">
            @csrf

            <div class="form-group">
                <label for="nama" class="form-label">
                    Nama Lengkap <span class="required">*</span>
                </label>
                <input type="text" name="nama" id="nama"
                       class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                       value="{{ old('nama') }}"
                       placeholder="Nama lengkap anggota"
                       autocomplete="off"
                       required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nia" class="form-label">Nomor Induk Anggota (NIA)</label>
                <input type="text" name="nia" id="nia"
                       class="form-control {{ $errors->has('nia') ? 'is-invalid' : '' }}"
                       value="{{ old('nia') }}"
                       placeholder="Contoh: 3505010001"
                       autocomplete="off">
                @error('nia')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-hint">Opsional, namun disarankan diisi untuk identifikasi anggota.</div>
            </div>

            <div class="form-group">
                <label for="jabatan" class="form-label">Jabatan / Fungsi</label>
                <input type="text" name="jabatan" id="jabatan"
                       class="form-control {{ $errors->has('jabatan') ? 'is-invalid' : '' }}"
                       value="{{ old('jabatan') }}"
                       placeholder="Contoh: Anggota, Danru, Ka. Satsus, Kasatkorcab, dsb."
                       autocomplete="off">
                @error('jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="satuan_id" class="form-label">Satuan</label>
                <select name="satuan_id" id="satuan_id"
                        class="form-control {{ $errors->has('satuan_id') ? 'is-invalid' : '' }}">
                    <option value="">-- Pilih Satuan (opsional) --</option>
                    @foreach($satuanList as $sat)
                        <option value="{{ $sat->id }}" {{ old('satuan_id') == $sat->id ? 'selected' : '' }}>
                            {{ $sat->nama }} ({{ $sat->tingkat }})
                        </option>
                    @endforeach
                </select>
                @error('satuan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="btn-simpan-anggota">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Anggota
                </button>
                <a href="{{ route('anggota.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
