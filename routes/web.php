<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SuratMasukController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes SIGAP BANSER
|--------------------------------------------------------------------------
| Rute aplikasi Sistem Informasi dan Administrasi Persuratan Satkorcab
| Banser Kabupaten Blitar.
*/

// Pengalihan rute akar ke Beranda / Dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rute Autentikasi (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Rute Logout Pengguna
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute Terproteksi Autentikasi Pengguna
Route::middleware('auth')->group(function () {
    // Pusat Komando & Beranda
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Laporan Rekap Persuratan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');

    // Modul Surat Masuk
    Route::get('/surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::get('/surat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
    Route::post('/surat-masuk', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
    Route::get('/surat-masuk/{id}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
    Route::get('/surat-masuk/{id}/edit', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
    Route::put('/surat-masuk/{id}', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
    Route::get('/surat-masuk/{id}/download', [SuratMasukController::class, 'downloadScan'])->name('surat-masuk.download');
    Route::delete('/surat-masuk/{id}', [SuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');
    Route::post('/surat-masuk/{id}/disposisi', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi');
    Route::post('/surat-masuk/{id}/disposisi/store', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi.store');
    Route::get('/surat-masuk/{id}/disposisi/cetak', [SuratMasukController::class, 'cetakDisposisi'])->name('surat-masuk.disposisi.cetak');
    Route::get('/surat-masuk/{id}/disposisi/pdf', [SuratMasukController::class, 'pdfDisposisi'])->name('surat-masuk.disposisi.pdf');

    // Modul Surat Keluar
    Route::get('/surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');
    Route::get('/surat-keluar/create', [SuratKeluarController::class, 'create'])->name('surat-keluar.create');
    Route::post('/surat-keluar', [SuratKeluarController::class, 'store'])->name('surat-keluar.store');
    Route::get('/surat-keluar/{id}/edit', [SuratKeluarController::class, 'edit'])->name('surat-keluar.edit');
    Route::get('/surat-keluar/{id}/pdf', [SuratKeluarController::class, 'pdf'])->name('surat-keluar.pdf');
    Route::get('/surat-keluar/{id}', [SuratKeluarController::class, 'show'])->name('surat-keluar.show');
    Route::put('/surat-keluar/{id}', [SuratKeluarController::class, 'update'])->name('surat-keluar.update');
    Route::delete('/surat-keluar/{id}', [SuratKeluarController::class, 'destroy'])->name('surat-keluar.destroy');
    Route::post('/surat-keluar/{id}/ajukan', [SuratKeluarController::class, 'ajukan'])->name('surat-keluar.ajukan');
    Route::post('/surat-keluar/{id}/setujui', [SuratKeluarController::class, 'setujui'])
        ->name('surat-keluar.setujui');
    Route::post('/surat-keluar/{id}/kirim', [SuratKeluarController::class, 'kirim'])->name('surat-keluar.kirim');

    // Modul Surat Keputusan (SK)
    Route::get('/surat-keputusan', [\App\Http\Controllers\SuratKeputusanController::class, 'index'])->name('surat-keputusan.index');
    Route::get('/surat-keputusan/create', [\App\Http\Controllers\SuratKeputusanController::class, 'create'])->name('surat-keputusan.create');
    Route::post('/surat-keputusan', [\App\Http\Controllers\SuratKeputusanController::class, 'store'])->name('surat-keputusan.store');
    Route::get('/surat-keputusan/{id}', [\App\Http\Controllers\SuratKeputusanController::class, 'show'])->name('surat-keputusan.show');
    Route::get('/surat-keputusan/{id}/edit', [\App\Http\Controllers\SuratKeputusanController::class, 'edit'])->name('surat-keputusan.edit');
    Route::put('/surat-keputusan/{id}', [\App\Http\Controllers\SuratKeputusanController::class, 'update'])->name('surat-keputusan.update');
    Route::delete('/surat-keputusan/{id}', [\App\Http\Controllers\SuratKeputusanController::class, 'destroy'])->name('surat-keputusan.destroy');
    Route::get('/surat-keputusan/{id}/pdf', [\App\Http\Controllers\SuratKeputusanController::class, 'pdf'])->name('surat-keputusan.pdf');
    Route::post('/surat-keputusan/{id}/ajukan', [\App\Http\Controllers\SuratKeputusanController::class, 'ajukan'])->name('surat-keputusan.ajukan');
    Route::post('/surat-keputusan/{id}/setujui', [\App\Http\Controllers\SuratKeputusanController::class, 'setujui'])->name('surat-keputusan.setujui');
    Route::post('/surat-keputusan/{id}/kirim', [\App\Http\Controllers\SuratKeputusanController::class, 'kirim'])->name('surat-keputusan.kirim');

    // Modul Profil Organisasi Satkorcab
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil', [ProfilController::class, 'update'])->name('profil.store');
    Route::get('/profil/berkas/{field}', [ProfilController::class, 'berkas'])->name('profil.berkas');

    // Modul Pengaturan Akun & Ganti Kata Sandi
    Route::get('/pengaturan-akun', [\App\Http\Controllers\PengaturanAkunController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan-akun/password', [\App\Http\Controllers\PengaturanAkunController::class, 'updatePassword'])->name('pengaturan.password.update');
    Route::put('/pengaturan-akun/profil', [\App\Http\Controllers\PengaturanAkunController::class, 'updateProfil'])->name('pengaturan.profil.update');

    // Manajemen Pengguna Khusus Superadmin (Pengatur Semua Akun)
    Route::post('/pengaturan-akun/users', [\App\Http\Controllers\PengaturanAkunController::class, 'storeUser'])->name('pengaturan.users.store');
    Route::put('/pengaturan-akun/users/{id}', [\App\Http\Controllers\PengaturanAkunController::class, 'updateUser'])->name('pengaturan.users.update');
    Route::put('/pengaturan-akun/users/{id}/reset-password', [\App\Http\Controllers\PengaturanAkunController::class, 'resetPasswordUser'])->name('pengaturan.users.reset-password');
    Route::delete('/pengaturan-akun/users/{id}', [\App\Http\Controllers\PengaturanAkunController::class, 'destroyUser'])->name('pengaturan.users.destroy');

    // Modul Anggota BANSER
    Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/create', [AnggotaController::class, 'create'])->name('anggota.create');
    Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
    Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
    Route::get('/api/anggota', [AnggotaController::class, 'apiList'])->name('anggota.api');

    // Modul Notifikasi Pengguna
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/count', [NotifikasiController::class, 'count'])->name('notifikasi.count');
    Route::post('/notifikasi/tandai-semua-baca', [NotifikasiController::class, 'tandaiSemuaBaca'])->name('notifikasi.tandai-semua-baca');
    Route::get('/notifikasi/{id}', [NotifikasiController::class, 'show'])->name('notifikasi.show');
    Route::post('/notifikasi/{id}/tandai-baca', [NotifikasiController::class, 'tandaiBaca'])->name('notifikasi.tandai-baca');
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
});

