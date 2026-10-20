<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk membatasi hak akses rute berdasarkan peran (role) pengguna SIGAP BANSER.
 * 
 * Penggunaan rute:
 * ->middleware('role:pimpinan,admin_adminpers')
 * 
 * Pengguna dengan peran 'super_admin' selalu diberikan hak akses penuh.
 */
class CheckRole
{
    /**
     * Tangani permintaan yang masuk dan periksa kesesuaian peran pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan pengguna telah terautentikasi / login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = strtolower($user->role ?? '');

        // Pengguna dengan role 'super_admin' memiliki izin akses penuh
        if ($userRole === 'super_admin') {
            return $next($request);
        }

        // Kumpulkan semua role yang diizinkan (mendukung pemisah koma atau parameter variadic)
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $r) {
                $trimmed = strtolower(trim($r));
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        // Jika tidak ada batasan role atau role pengguna cocok dengan salah satu role yang diizinkan
        if (empty($allowedRoles) || in_array($userRole, $allowedRoles, true)) {
            return $next($request);
        }

        // Batalkan permintaan dengan status 403 Forbidden
        abort(403, 'Akses ditolak. Anda tidak memiliki wewenang atau hak akses untuk tindakan ini.');
    }
}
