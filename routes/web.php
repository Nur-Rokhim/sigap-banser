<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SuratKeluarController;
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

    // Modul Surat Masuk
    Route::get('/surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::get('/surat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
    Route::post('/surat-masuk', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
    Route::get('/surat-masuk/{id}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
    Route::get('/surat-masuk/{id}/edit', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
    Route::put('/surat-masuk/{id}', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
    Route::get('/surat-masuk/{id}/download', [SuratMasukController::class, 'downloadScan'])->name('surat-masuk.download');
    Route::post('/surat-masuk/{id}/disposisi', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi');
    Route::post('/surat-masuk/{id}/disposisi/store', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi.store');

    // Modul Surat Keluar
    Route::get('/surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');
    Route::get('/surat-keluar/create', [SuratKeluarController::class, 'create'])->name('surat-keluar.create');
    Route::post('/surat-keluar', [SuratKeluarController::class, 'store'])->name('surat-keluar.store');
    Route::get('/surat-keluar/{id}/edit', [SuratKeluarController::class, 'edit'])->name('surat-keluar.edit');
    Route::get('/surat-keluar/{id}/pdf', [SuratKeluarController::class, 'pdf'])->name('surat-keluar.pdf');
    Route::get('/surat-keluar/{id}', [SuratKeluarController::class, 'show'])->name('surat-keluar.show');
    Route::put('/surat-keluar/{id}', [SuratKeluarController::class, 'update'])->name('surat-keluar.update');
    Route::post('/surat-keluar/{id}/ajukan', [SuratKeluarController::class, 'ajukan'])->name('surat-keluar.ajukan');
    Route::post('/surat-keluar/{id}/setujui', [SuratKeluarController::class, 'setujui'])
        ->name('surat-keluar.setujui');
    Route::post('/surat-keluar/{id}/kirim', [SuratKeluarController::class, 'kirim'])->name('surat-keluar.kirim');

    // Modul Profil Organisasi Satkorcab
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil', [ProfilController::class, 'update'])->name('profil.store');
    Route::get('/profil/berkas/{field}', [ProfilController::class, 'berkas'])->name('profil.berkas');
});

