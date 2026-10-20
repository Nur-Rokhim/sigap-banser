<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Controller untuk mengelola Pengaturan Akun dan Ganti Kata Sandi Pengguna.
 */
class PengaturanAkunController extends Controller
{
    /**
     * Tampilkan halaman pengaturan akun dan ganti kata sandi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        return view('pengaturan.index', compact('user'));
    }

    /**
     * Perbarui kata sandi akun pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required'  => 'Kata sandi saat ini wajib diisi.',
            'password.required'          => 'Kata sandi baru wajib diisi.',
            'password.min'               => 'Kata sandi baru minimal harus terdiri dari 8 karakter.',
            'password.confirmed'         => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.different'         => 'Kata sandi baru harus berbeda dengan kata sandi saat ini.',
        ]);

        // Verifikasi kecocokan kata sandi lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan salah.'])
                ->withInput();
        }

        // Perbarui kata sandi
        $user->password = Hash::make($request->password);
        $user->save();

        // Catat ke log aktivitas
        LogAktivitas::create([
            'user_id' => $user->id,
            'aksi'    => "Memperbarui kata sandi akun pengguna: {$user->username} ({$user->name})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('pengaturan.index')
            ->with('success', 'Kata sandi akun Anda berhasil diperbarui dengan aman! Gunakan kata sandi baru ini untuk masuk ke sistem di sesi berikutnya.');
    }

    /**
     * Perbarui data profil akun dasar (nama dan email).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150', 'unique:users,email,' . $user->id],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.email'   => 'Format email tidak valid.',
            'email.unique'  => 'Alamat email ini sudah digunakan oleh akun lain.',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'] ?? $user->email,
        ]);

        // Catat ke log aktivitas
        LogAktivitas::create([
            'user_id' => $user->id,
            'aksi'    => "Memperbarui data identitas profil akun: {$user->username}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('pengaturan.index')
            ->with('success', 'Informasi identitas akun berhasil diperbarui.');
    }
}
