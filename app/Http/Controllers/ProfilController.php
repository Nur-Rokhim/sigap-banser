<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Profil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    /**
     * Tampilkan formulir profil & data organisasi Satkorcab Banser.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $profil = Profil::getProfil();

        return view('profil.index', compact('profil'));
    }

    /**
     * Simpan perubahan profil organisasi, tanda tangan, dan stempel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $profil = Profil::getProfil();

        $validated = $request->validate([
            'satkorcab'           => 'required|string|max:255',
            'kode_wilayah'        => 'nullable|string|max:100',
            'alamat_sekretariat'  => 'nullable|string',
            'nama_ketua_ansor'    => 'nullable|string|max:255',
            'nama_kasatkorcab'    => 'nullable|string|max:255',
            'alamat_kasatkorcab'  => 'nullable|string',
            'nama_kasetma'        => 'nullable|string|max:255',
            'email'               => 'nullable|email|max:255',
            'ttd_ketua_ansor'     => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'ttd_kasatkorcab'     => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'ttd_kasetma'         => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'stempel_ansor'       => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'stempel_banser'      => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
        ], [
            'satkorcab.required'     => 'Nama Satkorcab wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'ttd_ketua_ansor.image'  => 'Berkas TTD Ketua Ansor harus berupa gambar.',
            'ttd_kasatkorcab.image'  => 'Berkas TTD Kasatkorcab harus berupa gambar.',
            'ttd_kasetma.image'      => 'Berkas TTD Kasetma harus berupa gambar.',
            'stempel_ansor.image'    => 'Berkas Stempel Ansor harus berupa gambar.',
            'stempel_banser.image'   => 'Berkas Stempel Banser harus berupa gambar.',
        ]);

        $fileFields = [
            'ttd_ketua_ansor',
            'ttd_kasatkorcab',
            'ttd_kasetma',
            'stempel_ansor',
            'stempel_banser',
        ];

        $dataToUpdate = [
            'satkorcab'          => $validated['satkorcab'],
            'kode_wilayah'       => $validated['kode_wilayah'] ?? null,
            'alamat_sekretariat' => $validated['alamat_sekretariat'] ?? null,
            'nama_ketua_ansor'   => $validated['nama_ketua_ansor'] ?? null,
            'nama_kasatkorcab'   => $validated['nama_kasatkorcab'] ?? null,
            'alamat_kasatkorcab' => $validated['alamat_kasatkorcab'] ?? null,
            'nama_kasetma'       => $validated['nama_kasetma'] ?? null,
            'email'              => $validated['email'] ?? null,
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Hapus berkas lama jika ada di disk public
                if ($profil->$field && Storage::disk('public')->exists($profil->$field)) {
                    Storage::disk('public')->delete($profil->$field);
                }

                // Simpan berkas baru
                $path = $request->file($field)->store('profil', 'public');
                $dataToUpdate[$field] = $path;
            }
        }

        $profil->update($dataToUpdate);

        // Catat aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => 'Memperbarui data profil organisasi Satkorcab',
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('profil.index')
            ->with('success', 'Data Profil Satkorcab berhasil disimpan dan diperbarui.');
    }
}
