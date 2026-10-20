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
            'ttd_ketua_ansor'     => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:10240',
            'ttd_kasatkorcab'     => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:10240',
            'ttd_kasetma'         => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:10240',
            'stempel_ansor'       => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:10240',
            'stempel_banser'      => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:10240',
        ], [
            'satkorcab.required'     => 'Nama Satkorcab wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'ttd_ketua_ansor.mimes'  => 'Berkas TTD Ketua Ansor harus berupa gambar (JPG, PNG, WEBP, atau SVG).',
            'ttd_kasatkorcab.mimes'  => 'Berkas TTD Kasatkorcab harus berupa gambar (JPG, PNG, WEBP, atau SVG).',
            'ttd_kasetma.mimes'      => 'Berkas TTD Kasetma harus berupa gambar (JPG, PNG, WEBP, atau SVG).',
            'stempel_ansor.mimes'    => 'Berkas Stempel Ansor harus berupa gambar (JPG, PNG, WEBP, atau SVG).',
            'stempel_banser.mimes'   => 'Berkas Stempel Banser harus berupa gambar (JPG, PNG, WEBP, atau SVG).',
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
            // Cek opsi penghapusan berkas
            if ($request->boolean('hapus_' . $field)) {
                if ($profil->$field && Storage::disk('public')->exists($profil->$field)) {
                    Storage::disk('public')->delete($profil->$field);
                }
                $dataToUpdate[$field] = null;
                continue;
            }

            // Cek berkas hasil kompresi data URL / base64 dari client
            $b64Input = $request->input('b64_' . $field);
            if (!empty($b64Input) && str_starts_with($b64Input, 'data:image/')) {
                if ($profil->$field && Storage::disk('public')->exists($profil->$field)) {
                    Storage::disk('public')->delete($profil->$field);
                }

                $parts = explode(',', $b64Input);
                $imageData = base64_decode(end($parts));
                $filename = 'profil/' . $field . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.png';
                Storage::disk('public')->put($filename, $imageData);
                $dataToUpdate[$field] = $filename;
                continue;
            }

            // Cek unggahan berkas multipart standar
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                if ($profil->$field && Storage::disk('public')->exists($profil->$field)) {
                    Storage::disk('public')->delete($profil->$field);
                }

                $path = $request->file($field)->store('profil', 'public');
                $dataToUpdate[$field] = $path;
            }
        }

        $profil->update($dataToUpdate);

        // Catat aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => 'Memperbarui data profil organisasi Satkorcab dan berkas legalisasi (TTD/Stempel)',
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('profil.index')
            ->with('success', 'Data Profil Satkorcab dan berkas legalisasi berhasil disimpan.');
    }

    /**
     * Sajikan berkas gambar profil (TTD / Stempel) langsung ke browser tanpa dependensi symlink.
     *
     * @param  string  $field
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function berkas($field)
    {
        $allowed = [
            'ttd_ketua_ansor',
            'ttd_kasatkorcab',
            'ttd_kasetma',
            'stempel_ansor',
            'stempel_banser',
        ];

        if (!in_array($field, $allowed)) {
            abort(404, 'Jenis berkas profil tidak dikenali.');
        }

        $profil = Profil::first();
        if (!$profil || empty($profil->$field)) {
            abort(404, 'Berkas belum diunggah.');
        }

        $filePath = storage_path('app/public/' . $profil->$field);
        if (!file_exists($filePath)) {
            abort(404, 'Berkas fisik tidak ditemukan di penyimpanan server.');
        }

        $mime = mime_content_type($filePath) ?: 'image/png';

        return response()->file($filePath, [
            'Content-Type'  => $mime,
            'Cache-Control' => 'no-cache, private, must-revalidate',
        ]);
    }
}
