<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller autentikasi sederhana untuk login dan logout pengguna SIGAP BANSER.
 * Menggunakan kredensial username / email dan kata sandi dengan fasad Auth Laravel.
 */
class LoginController extends Controller
{
    /**
     * Tampilkan halaman formulir login aplikasi SIGAP BANSER.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        // Jika pengguna sudah dalam keadaan login, arahkan langsung ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Tangani permintaan autentikasi login pengguna ke sistem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi masukan pengguna
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Nama pengguna (username) atau alamat email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Cek apakah input berupa alamat email atau nama pengguna
        $loginField = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $authAttempt = [
            $loginField => $credentials['username'],
            'password'  => $credentials['password'],
        ];

        $remember = $request->boolean('remember');

        // Percobaan autentikasi dengan Auth Laravel
        if (Auth::attempt($authAttempt, $remember)) {
            $user = Auth::user();

            // Periksa status keaktifan akun pengguna jika kolom aktif tersedia
            if (isset($user->aktif) && !$user->aktif) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Akun Anda berstatus nonaktif. Silakan hubungi Administrator Satkorcab.',
                ])->onlyInput('username');
            }

            // Regenerasi sesi untuk mencegah serangan session fixation
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Jika autentikasi gagal
        return back()->withErrors([
            'username' => 'Kombinasi nama pengguna atau kata sandi tidak sesuai.',
        ])->onlyInput('username');
    }

    /**
     * Keluarkan pengguna dari sistem dan bersihkan sesi aktif.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem SIGAP BANSER.');
    }
}
