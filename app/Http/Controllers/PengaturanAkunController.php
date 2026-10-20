<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Satuan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Controller untuk mengelola Pengaturan Akun, Ganti Kata Sandi,
 * serta Pengelolaan Seluruh Akun Pengguna (Superadmin).
 */
class PengaturanAkunController extends Controller
{
    /**
     * Tampilkan halaman pengaturan akun dan manajemen akun bagi superadmin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = strtolower($user->role ?? '') === 'super_admin';

        $allUsers = null;
        $satuans = null;

        if ($isSuperAdmin) {
            $allUsers = User::with('satuan')->orderBy('id', 'asc')->get();
            $satuans = Satuan::orderBy('nama', 'asc')->get();
        }

        return view('pengaturan.index', compact('user', 'isSuperAdmin', 'allUsers', 'satuans'));
    }

    /**
     * Perbarui kata sandi akun pengguna yang sedang login.
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
            'aksi'    => "Memperbarui kata sandi akun pribadi: {$user->username} ({$user->name})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('pengaturan.index')
            ->with('success', 'Kata sandi akun Anda berhasil diperbarui dengan aman! Gunakan kata sandi baru ini untuk masuk ke sistem di sesi berikutnya.');
    }

    /**
     * Perbarui data profil akun dasar (nama dan email) yang sedang login.
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
            'aksi'    => "Memperbarui data profil akun pribadi: {$user->username}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('pengaturan.index')
            ->with('success', 'Informasi identitas akun berhasil diperbarui.');
    }

    // =========================================================================
    // FITUR PENGELOLAAN SEMUA AKUN KHUSUS SUPERADMIN
    // =========================================================================

    /**
     * Tambah akun pengguna baru oleh Superadmin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUser(Request $request)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'username'  => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email'     => ['nullable', 'email', 'max:150', 'unique:users,email'],
            'role'      => ['required', 'string', Rule::in(['super_admin', 'kasatkorcab', 'kasetma', 'adminpers', 'admin_adminpers', 'pimpinan', 'anggota'])],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'satuan_id' => ['nullable', 'exists:satuan,id'],
            'aktif'     => ['nullable', 'boolean'],
        ], [
            'name.required'      => 'Nama lengkap pengguna wajib diisi.',
            'username.required'  => 'Username akun wajib diisi.',
            'username.unique'    => 'Username ini sudah digunakan oleh akun lain.',
            'username.alpha_dash'=> 'Username hanya boleh berisi huruf, angka, garis hubung, dan garis bawah.',
            'email.unique'       => 'Email ini sudah terdaftar di sistem.',
            'role.required'      => 'Peran / Role akun wajib dipilih.',
            'password.required'  => 'Kata sandi awal wajib diisi.',
            'password.min'       => 'Kata sandi awal minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $satuanId = $validated['satuan_id'] ?? Satuan::first()->id ?? null;

        $newUser = User::create([
            'name'      => $validated['name'],
            'username'  => strtolower(trim($validated['username'])),
            'email'     => $validated['email'] ?? null,
            'role'      => $validated['role'],
            'password'  => Hash::make($validated['password']),
            'satuan_id' => $satuanId,
            'aktif'     => $request->boolean('aktif', true),
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi'    => "Superadmin mendaftarkan akun pengguna baru: {$newUser->username} (Peran: {$newUser->role})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('pengaturan.index', ['tab' => 'kelola-akun'])
            ->with('success', "Akun pengguna baru {$newUser->name} (@{$newUser->username}) berhasil didaftarkan ke sistem.");
    }

    /**
     * Perbarui data akun pengguna lain oleh Superadmin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $id)
    {
        $this->ensureSuperAdmin();

        $targetUser = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'username'  => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($targetUser->id)],
            'email'     => ['nullable', 'email', 'max:150', Rule::unique('users', 'email')->ignore($targetUser->id)],
            'role'      => ['required', 'string', Rule::in(['super_admin', 'kasatkorcab', 'kasetma', 'adminpers', 'admin_adminpers', 'pimpinan', 'anggota'])],
            'satuan_id' => ['nullable', 'exists:satuan,id'],
            'aktif'     => ['required', 'boolean'],
        ], [
            'name.required'      => 'Nama lengkap pengguna wajib diisi.',
            'username.required'  => 'Username akun wajib diisi.',
            'username.unique'    => 'Username ini sudah digunakan akun lain.',
            'email.unique'       => 'Email ini sudah terdaftar pada akun lain.',
            'role.required'      => 'Peran / Role akun wajib dipilih.',
        ]);

        // Cegah superadmin menonaktifkan atau mengubah role dirinya sendiri agar tidak terkunci
        if ($targetUser->id === Auth::id()) {
            $validated['role'] = 'super_admin';
            $validated['aktif'] = 1;
        }

        $targetUser->update([
            'name'      => $validated['name'],
            'username'  => strtolower(trim($validated['username'])),
            'email'     => $validated['email'] ?? null,
            'role'      => $validated['role'],
            'satuan_id' => $validated['satuan_id'] ?? $targetUser->satuan_id,
            'aktif'     => (bool)$validated['aktif'],
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi'    => "Superadmin memperbarui data akun: {$targetUser->username} (Peran: {$targetUser->role})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('pengaturan.index', ['tab' => 'kelola-akun'])
            ->with('success', "Data akun @{$targetUser->username} berhasil diperbarui.");
    }

    /**
     * Reset langsung kata sandi akun pengguna oleh Superadmin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPasswordUser(Request $request, $id)
    {
        $this->ensureSuperAdmin();

        $targetUser = User::findOrFail($id);

        $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'new_password.required'  => 'Kata sandi baru wajib diisi.',
            'new_password.min'       => 'Kata sandi baru minimal harus 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $targetUser->password = Hash::make($request->new_password);
        $targetUser->save();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi'    => "Superadmin mereset kata sandi akun pengguna: {$targetUser->username} ({$targetUser->name})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('pengaturan.index', ['tab' => 'kelola-akun'])
            ->with('success', "Kata sandi untuk akun @{$targetUser->username} ({$targetUser->name}) berhasil direset!");
    }

    /**
     * Hapus akun pengguna oleh Superadmin.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser($id)
    {
        $this->ensureSuperAdmin();

        if ((int)$id === (int)Auth::id()) {
            return redirect()->route('pengaturan.index', ['tab' => 'kelola-akun'])
                ->with('error', 'Tindakan ditolak! Anda tidak dapat menghapus akun Anda sendiri saat sedang masuk.');
        }

        $targetUser = User::findOrFail($id);
        $username = $targetUser->username;
        $name = $targetUser->name;

        $targetUser->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi'    => "Superadmin menghapus akun pengguna: {$username} ({$name})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('pengaturan.index', ['tab' => 'kelola-akun'])
            ->with('success', "Akun pengguna @{$username} ({$name}) berhasil dihapus dari sistem.");
    }

    /**
     * Pastikan pengguna saat ini memiliki hak akses Superadmin.
     */
    protected function ensureSuperAdmin(): void
    {
        if (strtolower(Auth::user()->role ?? '') !== 'super_admin') {
            abort(403, 'Akses ditolak. Fitur ini hanya dapat dikelola oleh Super Administrator.');
        }
    }
}
