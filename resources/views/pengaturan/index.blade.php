@extends('layouts.app')

@section('title', 'Pengaturan Akun & Ganti Kata Sandi')

@push('styles')
<style>
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
</style>
@endpush

@section('content')
<div style="max-width: 1080px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2>Pengaturan Akun & Keamanan</h2>
            <p style="color: var(--ink-muted); font-size: 0.92rem; margin-top: 4px;">
                Kelola informasi akun Anda dan perbarui kata sandi untuk melindungi akses sistem SIGAP BANSER.
            </p>
        </div>
        <div>
            <span class="badge badge-green" style="font-size: 0.8rem; padding: 6px 12px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Akun Terverifikasi</span>
            </span>
        </div>
    </div>

    <div class="settings-grid">
        <!-- Kolom Kiri: Form Ganti Kata Sandi -->
        <div class="card">
            <div class="card-header" style="background-color: rgba(90, 14, 20, 0.03);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(142, 27, 34, 0.12); color: var(--accent); display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="card-title" style="font-size: 1.1rem;">Ganti Kata Sandi</span>
                        <div style="font-size: 0.8rem; color: var(--ink-muted);">Perbarui kata sandi secara berkala demi keamanan komando</div>
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
                            Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol (misal: <code>BanserBlitar#2026!</code>). Mengganti kata sandi bawaan akan mencegah munculnya peringatan <em>Data Breach</em> pada peramban Google Chrome.
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

        <!-- Kolom Kanan: Informasi Akun & Profil Pengguna -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Informasi Akun Anda</span>
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

            <!-- Form Edit Nama / Email -->
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
@endsection

@push('scripts')
<script>
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
</script>
@endpush
