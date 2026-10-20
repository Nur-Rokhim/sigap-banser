@extends('layouts.app')

@section('title', 'Pengaturan Akun & Manajemen Pengguna')

@push('styles')
<style>
    .subnav-tabs {
        display: flex;
        gap: 8px;
        border-bottom: 2px solid var(--border);
        margin-bottom: 24px;
        overflow-x: auto;
    }

    .subnav-tab-btn {
        padding: 10px 18px;
        font-family: inherit;
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--ink-muted);
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .subnav-tab-btn:hover {
        color: var(--accent);
    }

    .subnav-tab-btn.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
        font-weight: 700;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }

    .pwd-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .pwd-input-group input {
        padding-right: 42px;
    }

    .pwd-toggle-btn {
        position: absolute;
        right: 10px;
        background: transparent;
        border: none;
        color: var(--ink-muted);
        cursor: pointer;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        transition: color 0.15s ease;
    }

    .pwd-toggle-btn:hover {
        color: var(--ink);
    }

    .pwd-meter {
        height: 4px;
        width: 100%;
        background-color: var(--border);
        border-radius: 999px;
        margin-top: 6px;
        overflow: hidden;
    }

    .pwd-meter-bar {
        height: 100%;
        width: 0%;
        transition: width 0.3s ease, background-color 0.3s ease;
    }

    .security-tips-box {
        background: rgba(14, 155, 74, 0.08);
        border: 1px solid rgba(14, 155, 74, 0.25);
        border-radius: var(--radius-md);
        padding: 14px 16px;
        margin-top: 20px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .user-profile-hero {
        text-align: center;
        padding: 24px 20px 20px;
        background: linear-gradient(135deg, rgba(90, 14, 20, 0.08) 0%, rgba(201, 162, 74, 0.08) 100%);
        border-radius: var(--radius-md);
        margin-bottom: 20px;
        position: relative;
    }

    .user-big-avatar {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--hero1) 0%, var(--accent) 100%);
        color: #FFFFFF;
        font-family: 'Archivo', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        box-shadow: 0 8px 16px rgba(90, 14, 20, 0.3);
        border: 3px solid #FFFFFF;
    }

    .spec-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed var(--border-light);
        font-size: 0.88rem;
    }

    .spec-item:last-child {
        border-bottom: none;
    }

    .spec-label {
        color: var(--ink-muted);
    }

    .spec-value {
        font-weight: 600;
        color: var(--ink);
    }

    /* Modal Form Styles */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 99990;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        animation: fadeInModal 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .custom-modal-card {
        background: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        max-width: 540px;
        width: 100%;
        overflow: hidden;
        animation: zoomInModal 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
</style>
@endpush

@section('content')
<div style="max-width: 1140px; margin: 0 auto;">
    <!-- Page Title -->
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2>Pusat Pengaturan Akun & Keamanan</h2>
            <p style="color: var(--ink-muted); font-size: 0.92rem; margin-top: 4px;">
                Kelola kata sandi akun pribadi Anda, serta pengaturan hak akses seluruh akun pengguna SIGAP BANSER.
            </p>
        </div>
        <div>
            @if($isSuperAdmin)
                <span class="badge" style="background-color: rgba(90, 14, 20, 0.12); color: var(--hero1); border: 1px solid rgba(90, 14, 20, 0.3); font-size: 0.82rem; padding: 6px 14px; font-weight: 800;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>SUPERADMINISTRATOR // AKSES PENUH</span>
                </span>
            @else
                <span class="badge badge-green" style="font-size: 0.8rem; padding: 6px 12px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Akun Terverifikasi</span>
                </span>
            @endif
        </div>
    </div>

    <!-- Sub-Tab Navigation for Superadmin -->
    @if($isSuperAdmin)
        @php
            $activeTab = request('tab', 'akun-saya');
        @endphp
        <div class="subnav-tabs">
            <button type="button" 
                    class="subnav-tab-btn {{ $activeTab === 'akun-saya' ? 'active' : '' }}" 
                    onclick="switchTab('tab-akun-saya', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Pengaturan Akun Saya</span>
            </button>

            <button type="button" 
                    class="subnav-tab-btn {{ $activeTab === 'kelola-akun' ? 'active' : '' }}" 
                    onclick="switchTab('tab-kelola-akun', this)">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Kelola Semua Akun Pengguna</span>
                <span class="badge" style="background-color: var(--accent); color: #fff; font-size: 0.7rem; padding: 1px 7px;">
                    {{ count($allUsers ?? []) }} Akun
                </span>
            </button>
        </div>
    @endif

    <!-- SECTION 1: TAB PENGATURAN AKUN SAYA -->
    <div id="tab-akun-saya" style="{{ ($isSuperAdmin && request('tab') === 'kelola-akun') ? 'display: none;' : '' }}">
        <div class="settings-grid">
            <!-- Kolom Kiri: Form Ganti Kata Sandi Pribadi -->
            <div class="card">
                <div class="card-header" style="background-color: rgba(90, 14, 20, 0.03);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(142, 27, 34, 0.12); color: var(--accent); display: flex; align-items: center; justify-content: center;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="card-title" style="font-size: 1.1rem;">Ganti Kata Sandi Pribadi</span>
                            <div style="font-size: 0.8rem; color: var(--ink-muted);">Perbarui kata sandi secara berkala demi keamanan akun</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('pengaturan.password.update') }}" method="POST" id="formGantiPassword">
                        @csrf
                        @method('PUT')

                        <!-- Kata Sandi Saat Ini -->
                        <div class="form-group">
                            <label for="current_password" class="form-label">
                                <span>Kata Sandi Saat Ini</span>
                                <span class="required">*</span>
                            </label>
                            <div class="pwd-input-group">
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       placeholder="Ketik kata sandi lama Anda saat ini"
                                       required>
                                <button type="button" class="pwd-toggle-btn" onclick="toggleInputVisibility('current_password', this)" title="Tampilkan / Sembunyikan Sandi" aria-label="Toggle password visibility">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kata Sandi Baru -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <span>Kata Sandi Baru</span>
                                <span class="required">*</span>
                            </label>
                            <div class="pwd-input-group">
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Minimal 8 karakter kombinasi aman" 
                                       oninput="checkPasswordStrength(this.value)"
                                       required>
                                <button type="button" class="pwd-toggle-btn" onclick="toggleInputVisibility('password', this)" title="Tampilkan / Sembunyikan Sandi" aria-label="Toggle password visibility">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="pwd-meter">
                                <div id="pwdStrengthBar" class="pwd-meter-bar"></div>
                            </div>
                            <div id="pwdStrengthText" style="font-size: 0.76rem; color: var(--ink-muted); margin-top: 4px;">
                                Kekuatan: Minimal 8 karakter
                            </div>
                            @error('password')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Konfirmasi Kata Sandi Baru -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <span>Ulangi Kata Sandi Baru</span>
                                <span class="required">*</span>
                            </label>
                            <div class="pwd-input-group">
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="form-control" 
                                       placeholder="Ketik ulang kata sandi baru untuk memastikan"
                                       required>
                                <button type="button" class="pwd-toggle-btn" onclick="toggleInputVisibility('password_confirmation', this)" title="Tampilkan / Sembunyikan Sandi" aria-label="Toggle password visibility">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Tips Keamanan -->
                        <div class="security-tips-box">
                            <svg width="22" height="22" fill="none" stroke="var(--green)" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 2px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <div style="font-size: 0.82rem; color: #166534; line-height: 1.45;">
                                <strong>Tips Keamanan Kata Sandi:</strong><br>
                                Gunakan kombinasi huruf besar, kecil, angka, dan simbol (misal: <code>BanserBlitar#2026!</code>). Mengganti kata sandi bawaan akan mencegah peringatan <em>Data Breach</em> dari peramban Chrome.
                            </div>
                        </div>

                        <div style="margin-top: 24px;">
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: 700;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Simpan & Perbarui Kata Sandi</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Informasi Akun Saya -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Profil Akun Saya</span>
                    </div>
                    <div class="card-body">
                        <!-- Hero Info -->
                        <div class="user-profile-hero">
                            <div class="user-big-avatar">
                                {{ strtoupper(substr($user->name ?? 'B', 0, 1)) }}
                            </div>
                            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--ink); margin-bottom: 4px;">
                                {{ $user->name }}
                            </h3>
                            <div style="font-size: 0.85rem; color: var(--ink-muted); margin-bottom: 10px;">
                                {{ '@' . $user->username }}
                            </div>
                            <div>
                                @php
                                    $r = strtolower($user->role ?? '');
                                    $badgeStyle = match($r) {
                                        'kasatkorcab', 'super_admin', 'pimpinan' => 'background-color: rgba(90, 14, 20, 0.1); color: var(--hero1); border: 1px solid rgba(90, 14, 20, 0.25);',
                                        'kasetma' => 'background-color: rgba(201, 162, 74, 0.15); color: #8A6516; border: 1px solid rgba(201, 162, 74, 0.35);',
                                        'adminpers', 'admin_adminpers' => 'background-color: rgba(14, 155, 74, 0.12); color: var(--green); border: 1px solid rgba(14, 155, 74, 0.3);',
                                        default => 'background-color: rgba(107, 114, 128, 0.12); color: var(--ink);',
                                    };
                                    $roleLabel = match($r) {
                                        'kasatkorcab' => 'Kasatkorcab (Verifikator)',
                                        'kasetma'     => 'Kasetma (Verifikator)',
                                        'adminpers', 'admin_adminpers' => 'Ka. Biro Adminpers (User Edit)',
                                        'super_admin' => 'Super Administrator (Verifikator)',
                                        'pimpinan'    => 'Pimpinan (Verifikator)',
                                        default       => ucfirst($user->role ?? 'Anggota'),
                                    };
                                @endphp
                                <span class="badge" style="{{ $badgeStyle }} font-size: 0.78rem; padding: 4px 10px;">
                                    {{ $roleLabel }}
                                </span>
                            </div>
                        </div>

                        <!-- Spesifikasi Akun -->
                        <div class="spec-item">
                            <span class="spec-label">Nama Pengguna (Username)</span>
                            <span class="spec-value" style="font-family: 'JetBrains Mono', monospace;">{{ $user->username }}</span>
                        </div>

                        <div class="spec-item">
                            <span class="spec-label">Alamat Email</span>
                            <span class="spec-value">{{ $user->email ?? '-' }}</span>
                        </div>

                        <div class="spec-item">
                            <span class="spec-label">Satuan Koordinasi</span>
                            <span class="spec-value">{{ $user->satuan->nama ?? 'SATKORCAB BANSER' }}</span>
                        </div>

                        <div class="spec-item">
                            <span class="spec-label">Status Akun</span>
                            <span class="spec-value" style="color: var(--green);">Aktif & Resmi</span>
                        </div>

                        <div class="spec-item">
                            <span class="spec-label">Terdaftar Sejak</span>
                            <span class="spec-value">{{ isset($user->created_at) ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form Edit Nama / Email Sendiri -->
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Perbarui Identitas Dasar</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengaturan.profil.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name" class="form-label">Nama Lengkap Petugas <span class="required">*</span></label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="nama@email.com">
                                @error('email')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-secondary" style="width: 100%;">
                                <span>Simpan Identitas</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: TAB KELOLA SEMUA AKUN (KHUSUS SUPERADMIN) -->
    @if($isSuperAdmin)
        <div id="tab-kelola-akun" style="{{ request('tab') === 'kelola-akun' ? 'display: block;' : 'display: none;' }}">
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <span class="card-title">Daftar Seluruh Akun Pengguna SIGAP BANSER</span>
                        <div style="font-size: 0.82rem; color: var(--ink-muted); margin-top: 2px;">
                            Superadmin memiliki wewenang penuh mendaftarkan akun baru, mengubah hak akses/peran, serta mereset kata sandi personil.
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" onclick="openTambahUserModal()" style="display: inline-flex; align-items: center; gap: 6px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            <span>Tambah Akun Baru</span>
                        </button>
                    </div>
                </div>

                <div class="card-body" style="padding: 0;">
                    <div style="overflow-x: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 45px; text-align: center;">No</th>
                                    <th>Nama & Username</th>
                                    <th>Email</th>
                                    <th>Peran / Role Akun</th>
                                    <th>Satuan</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: right; width: 170px;">Aksi Pengaturan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allUsers as $idx => $usr)
                                    <tr>
                                        <td style="text-align: center; color: var(--ink-muted);">{{ $idx + 1 }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, var(--hero1), var(--accent)); color: #fff; font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    {{ strtoupper(substr($usr->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div style="font-weight: 700; color: var(--ink);">
                                                        {{ $usr->name }}
                                                        @if($usr->id === Auth::id())
                                                            <span class="badge" style="background: rgba(14, 155, 74, 0.15); color: var(--green); font-size: 0.68rem; padding: 1px 6px;">Anda</span>
                                                        @endif
                                                    </div>
                                                    <div style="font-size: 0.78rem; font-family: 'JetBrains Mono', monospace; color: var(--ink-muted);">
                                                        {{ '@' . $usr->username }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="font-size: 0.85rem; color: var(--ink-muted);">
                                            {{ $usr->email ?? '-' }}
                                        </td>
                                        <td>
                                            @php
                                                $ur = strtolower($usr->role ?? '');
                                                $uBadge = match($ur) {
                                                    'super_admin' => 'background: rgba(90, 14, 20, 0.15); color: var(--hero1); border: 1px solid rgba(90, 14, 20, 0.3);',
                                                    'kasatkorcab' => 'background: rgba(90, 14, 20, 0.1); color: var(--accent); border: 1px solid rgba(142, 27, 34, 0.25);',
                                                    'kasetma'     => 'background: rgba(201, 162, 74, 0.18); color: #8A6516; border: 1px solid rgba(201, 162, 74, 0.35);',
                                                    'adminpers', 'admin_adminpers' => 'background: rgba(14, 155, 74, 0.12); color: var(--green); border: 1px solid rgba(14, 155, 74, 0.3);',
                                                    'pimpinan'    => 'background: rgba(30, 64, 175, 0.1); color: #1E40AF; border: 1px solid rgba(30, 64, 175, 0.25);',
                                                    default       => 'background: rgba(107, 114, 128, 0.12); color: var(--ink);',
                                                };
                                                $uText = match($ur) {
                                                    'super_admin' => 'Superadmin (Verifikator)',
                                                    'kasatkorcab' => 'Kasatkorcab (Verifikator)',
                                                    'kasetma'     => 'Kasetma (Verifikator)',
                                                    'adminpers', 'admin_adminpers' => 'Ka. Biro Adminpers (User Edit)',
                                                    'pimpinan'    => 'Pimpinan (Verifikator)',
                                                    default       => ucfirst($usr->role ?? 'Anggota'),
                                                };
                                            @endphp
                                            <span class="badge" style="{{ $uBadge }} font-size: 0.76rem; padding: 3px 8px;">
                                                {{ $uText }}
                                            </span>
                                        </td>
                                        <td style="font-size: 0.84rem; color: var(--ink);">
                                            {{ $usr->satuan->nama ?? 'SATKORCAB BANSER' }}
                                        </td>
                                        <td style="text-align: center;">
                                            @if($usr->aktif)
                                                <span class="badge badge-green" style="font-size: 0.72rem; padding: 2px 7px;">Aktif</span>
                                            @else
                                                <span class="badge badge-gray" style="font-size: 0.72rem; padding: 2px 7px; color: #DC2626;">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div style="display: inline-flex; gap: 4px; align-items: center; justify-content: flex-end;">
                                                <!-- Edit Akun -->
                                                <button type="button" 
                                                        class="btn-icon btn-icon-edit" 
                                                        title="Edit Data Akun"
                                                        onclick="openEditUserModal({{ json_encode($usr) }})">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                    </svg>
                                                    <span>Edit</span>
                                                </button>

                                                <!-- Reset Password -->
                                                <button type="button" 
                                                        class="btn-icon btn-icon-detail" 
                                                        title="Reset Kata Sandi Akun Ini"
                                                        onclick="openResetPasswordModal({{ $usr->id }}, '{{ addslashes($usr->name) }}', '{{ $usr->username }}')">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                    </svg>
                                                    <span>Sandi</span>
                                                </button>

                                                <!-- Hapus Akun -->
                                                @if($usr->id !== Auth::id())
                                                    <button type="button" 
                                                            class="btn-icon btn-icon-delete" 
                                                            title="Hapus Akun Pengguna"
                                                            onclick="openDeleteModal('{{ route('pengaturan.users.destroy', $usr->id) }}', 'Akun Pengguna {{ addslashes($usr->name) }} (@{{ $usr->username }})')">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 32px;">Tidak ada akun terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL 1: TAMBAH AKUN PENGGUNA BARU -->
        <div id="modalTambahUser" class="custom-modal-overlay" style="display: none;" onclick="if(event.target === this) closeTambahUserModal()">
            <div class="custom-modal-card">
                <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                    <span class="card-title">Tambah Akun Pengguna Baru</span>
                    <button type="button" class="delete-modal-close" onclick="closeTambahUserModal()">&times;</button>
                </div>
                <form action="{{ route('pengaturan.users.store') }}" method="POST">
                    @csrf
                    <div class="card-body" style="padding: 20px 24px;">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap Petugas <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Sahabat Ahmad Fauzi" required>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Username (ID Masuk) <span class="required">*</span></label>
                                <input type="text" name="username" class="form-control" placeholder="contoh: fauzi_banser" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Email</label>
                                <input type="email" name="email" class="form-control" placeholder="fauzi@banser.id">
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Peran / Role Akun <span class="required">*</span></label>
                                <select name="role" class="form-control select" required>
                                    <option value="anggota">Anggota Biasa</option>
                                    <option value="adminpers">Ka. Biro Adminpers (User Edit)</option>
                                    <option value="kasetma">Kasetma (Verifikator)</option>
                                    <option value="kasatkorcab">Kasatkorcab (Verifikator)</option>
                                    <option value="pimpinan">Pimpinan (Verifikator)</option>
                                    <option value="super_admin">Super Administrator</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Satuan Kerja</label>
                                <select name="satuan_id" class="form-control select">
                                    @foreach($satuans as $stn)
                                        <option value="{{ $stn->id }}">{{ $stn->nama }} ({{ $stn->tingkat }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Kata Sandi Awal <span class="required">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ulangi Sandi <span class="required">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang" required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.88rem;">
                                <input type="checkbox" name="aktif" value="1" checked style="accent-color: var(--accent);">
                                <span>Akun langsung diaktifkan untuk masuk sistem</span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer" style="display: flex; justify-content: flex-end; gap: 10px; background: #F8FAFC; padding: 14px 24px;">
                        <button type="button" class="btn btn-secondary" onclick="closeTambahUserModal()">Batal</button>
                        <button type="submit" class="btn btn-primary">Daftarkan Akun</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2: EDIT DATA AKUN PENGGUNA -->
        <div id="modalEditUser" class="custom-modal-overlay" style="display: none;" onclick="if(event.target === this) closeEditUserModal()">
            <div class="custom-modal-card">
                <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                    <span class="card-title">Edit Data Akun Pengguna</span>
                    <button type="button" class="delete-modal-close" onclick="closeEditUserModal()">&times;</button>
                </div>
                <form id="formEditUser" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body" style="padding: 20px 24px;">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap Petugas <span class="required">*</span></label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Username <span class="required">*</span></label>
                                <input type="text" id="edit_username" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Email</label>
                                <input type="email" id="edit_email" name="email" class="form-control">
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Peran / Role Akun <span class="required">*</span></label>
                                <select id="edit_role" name="role" class="form-control select" required>
                                    <option value="anggota">Anggota Biasa</option>
                                    <option value="adminpers">Ka. Biro Adminpers (User Edit)</option>
                                    <option value="kasetma">Kasetma (Verifikator)</option>
                                    <option value="kasatkorcab">Kasatkorcab (Verifikator)</option>
                                    <option value="pimpinan">Pimpinan (Verifikator)</option>
                                    <option value="super_admin">Super Administrator</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status Keaktifan <span class="required">*</span></label>
                                <select id="edit_aktif" name="aktif" class="form-control select" required>
                                    <option value="1">Aktif (Dapat Masuk)</option>
                                    <option value="0">Nonaktif (Diblokir)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Satuan Kerja</label>
                            <select id="edit_satuan_id" name="satuan_id" class="form-control select">
                                @foreach($satuans as $stn)
                                    <option value="{{ $stn->id }}">{{ $stn->nama }} ({{ $stn->tingkat }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer" style="display: flex; justify-content: flex-end; gap: 10px; background: #F8FAFC; padding: 14px 24px;">
                        <button type="button" class="btn btn-secondary" onclick="closeEditUserModal()">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 3: RESET KATA SANDI OLEH SUPERADMIN -->
        <div id="modalResetPasswordUser" class="custom-modal-overlay" style="display: none;" onclick="if(event.target === this) closeResetPasswordModal()">
            <div class="custom-modal-card" style="max-width: 460px;">
                <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="20" height="20" fill="none" stroke="var(--accent)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        <span class="card-title">Reset Kata Sandi Akun</span>
                    </div>
                    <button type="button" class="delete-modal-close" onclick="closeResetPasswordModal()">&times;</button>
                </div>
                <form id="formResetPasswordUser" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body" style="padding: 20px 24px;">
                        <p style="font-size: 0.88rem; color: var(--ink-muted); margin-bottom: 16px;">
                            Menetapkan kata sandi baru untuk akun <strong id="resetTargetUserLabel" style="color: var(--ink);"></strong>. Pengguna dapat langsung masuk menggunakan kata sandi baru ini.
                        </p>

                        <div class="form-group">
                            <label class="form-label">Kata Sandi Baru <span class="required">*</span></label>
                            <input type="password" name="new_password" class="form-control" placeholder="Minimal 8 karakter" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Konfirmasi Kata Sandi Baru <span class="required">*</span></label>
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ketik ulang kata sandi baru" required>
                        </div>
                    </div>
                    <div class="card-footer" style="display: flex; justify-content: flex-end; gap: 10px; background: #F8FAFC; padding: 14px 24px;">
                        <button type="button" class="btn btn-secondary" onclick="closeResetPasswordModal()">Batal</button>
                        <button type="submit" class="btn btn-primary">Reset Kata Sandi</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Tab switching for Superadmin
    function switchTab(tabId, btn) {
        document.getElementById('tab-akun-saya').style.display = (tabId === 'tab-akun-saya') ? 'block' : 'none';
        const kelolaTab = document.getElementById('tab-kelola-akun');
        if (kelolaTab) {
            kelolaTab.style.display = (tabId === 'tab-kelola-akun') ? 'block' : 'none';
        }
        document.querySelectorAll('.subnav-tab-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
    }

    // Toggle password text visibility
    function toggleInputVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        if (isPassword) {
            btn.innerHTML = `
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                </svg>
            `;
            btn.style.color = 'var(--accent)';
        } else {
            btn.innerHTML = `
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            `;
            btn.style.color = 'var(--ink-muted)';
        }
    }

    // Password strength meter
    function checkPasswordStrength(password) {
        const bar = document.getElementById('pwdStrengthBar');
        const text = document.getElementById('pwdStrengthText');
        if (!bar || !text) return;

        let score = 0;
        if (password.length >= 8) score += 25;
        if (/[A-Z]/.test(password)) score += 25;
        if (/[0-9]/.test(password)) score += 25;
        if (/[^A-Za-z0-9]/.test(password)) score += 25;

        bar.style.width = score + '%';

        if (score === 0) {
            bar.style.backgroundColor = 'transparent';
            text.textContent = 'Kekuatan: Minimal 8 karakter';
            text.style.color = 'var(--ink-muted)';
        } else if (score <= 25) {
            bar.style.backgroundColor = '#DC2626';
            text.textContent = 'Kekuatan: Lemah (Tambahkan huruf besar, angka, atau simbol)';
            text.style.color = '#DC2626';
        } else if (score <= 50) {
            bar.style.backgroundColor = '#F59E0B';
            text.textContent = 'Kekuatan: Cukup (Kombinasikan dengan angka & simbol)';
            text.style.color = '#D97706';
        } else if (score <= 75) {
            bar.style.backgroundColor = '#3B82F6';
            text.textContent = 'Kekuatan: Baik (Hampir sempurna)';
            text.style.color = '#2563EB';
        } else {
            bar.style.backgroundColor = '#10B981';
            text.textContent = 'Kekuatan: Sangat Kuat & Aman!';
            text.style.color = '#059669';
        }
    }

    // Superadmin User Modals Helpers
    function openTambahUserModal() {
        const m = document.getElementById('modalTambahUser');
        if (m) m.style.display = 'flex';
    }
    function closeTambahUserModal() {
        const m = document.getElementById('modalTambahUser');
        if (m) m.style.display = 'none';
    }

    function openEditUserModal(userObj) {
        const m = document.getElementById('modalEditUser');
        const form = document.getElementById('formEditUser');
        if (m && form) {
            form.action = `/pengaturan-akun/users/${userObj.id}`;
            document.getElementById('edit_name').value = userObj.name || '';
            document.getElementById('edit_username').value = userObj.username || '';
            document.getElementById('edit_email').value = userObj.email || '';
            document.getElementById('edit_role').value = userObj.role || 'anggota';
            document.getElementById('edit_aktif').value = userObj.aktif ? '1' : '0';
            if (userObj.satuan_id && document.getElementById('edit_satuan_id')) {
                document.getElementById('edit_satuan_id').value = userObj.satuan_id;
            }
            m.style.display = 'flex';
        }
    }
    function closeEditUserModal() {
        const m = document.getElementById('modalEditUser');
        if (m) m.style.display = 'none';
    }

    function openResetPasswordModal(userId, userName, username) {
        const m = document.getElementById('modalResetPasswordUser');
        const form = document.getElementById('formResetPasswordUser');
        const label = document.getElementById('resetTargetUserLabel');
        if (m && form && label) {
            form.action = `/pengaturan-akun/users/${userId}/reset-password`;
            label.textContent = `${userName} (@${username})`;
            m.style.display = 'flex';
        }
    }
    function closeResetPasswordModal() {
        const m = document.getElementById('modalResetPasswordUser');
        if (m) m.style.display = 'none';
    }
</script>
@endpush
