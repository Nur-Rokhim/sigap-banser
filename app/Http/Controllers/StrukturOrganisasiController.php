<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Profil;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StrukturOrganisasiController extends Controller
{
    /**
     * Tampilkan struktur dan bagan organisasi Satkorcab Banser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Pastikan struktur 17 unit terisi
        StrukturOrganisasi::seedDefaultStructure();

        $kasatkorcab        = StrukturOrganisasi::kasatkorcab()->first();
        $wakasatkorcabList  = StrukturOrganisasi::wakasatkorcab()->get();
        $kasetma            = StrukturOrganisasi::kasetma()->first();
        $biroList           = StrukturOrganisasi::biro()->get();
        $satsusList         = StrukturOrganisasi::satsus()->get();

        // Hitung total personel terisi
        $totalPersonel = 0;
        if (!empty($kasatkorcab?->ketua)) $totalPersonel++;
        foreach ($wakasatkorcabList as $w) {
            if (!empty($w->ketua)) $totalPersonel++;
        }
        if (!empty($kasetma?->ketua)) $totalPersonel++;

        foreach ($biroList as $b) {
            $totalPersonel += $b->total_personel;
        }
        foreach ($satsusList as $s) {
            $totalPersonel += $s->total_personel;
        }

        $profil = Profil::first();

        return view('struktur-organisasi.index', compact(
            'kasatkorcab',
            'wakasatkorcabList',
            'kasetma',
            'biroList',
            'satsusList',
            'totalPersonel',
            'profil'
        ));
    }

    /**
     * Tampilkan formulir kelola & edit seluruh personel struktur organisasi.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        StrukturOrganisasi::seedDefaultStructure();

        $kasatkorcab        = StrukturOrganisasi::kasatkorcab()->first();
        $wakasatkorcabList  = StrukturOrganisasi::wakasatkorcab()->get();
        $kasetma            = StrukturOrganisasi::kasetma()->first();
        $biroList           = StrukturOrganisasi::biro()->get();
        $satsusList         = StrukturOrganisasi::satsus()->get();

        return view('struktur-organisasi.edit', compact(
            'kasatkorcab',
            'wakasatkorcabList',
            'kasetma',
            'biroList',
            'satsusList'
        ));
    }

    /**
     * Simpan pembaruan menyeluruh struktur organisasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // 1. Kasatkorcab
        if ($request->has('kasatkorcab')) {
            $kasatData = $request->input('kasatkorcab');
            $kasat = StrukturOrganisasi::kasatkorcab()->first();
            if ($kasat) {
                $kasat->update([
                    'ketua'      => $kasatData['ketua'] ?? null,
                    'keterangan' => $kasatData['keterangan'] ?? null,
                ]);

                // Sinkronkan nama kasatkorcab ke tabel profil
                if (!empty($kasatData['ketua'])) {
                    Profil::query()->update(['nama_kasatkorcab' => $kasatData['ketua']]);
                }
            }
        }

        // 2. Kasetma
        if ($request->has('kasetma')) {
            $kasetmaData = $request->input('kasetma');
            $kasetma = StrukturOrganisasi::kasetma()->first();
            if ($kasetma) {
                $kasetma->update([
                    'ketua'      => $kasetmaData['ketua'] ?? null,
                    'keterangan' => $kasetmaData['keterangan'] ?? null,
                ]);

                // Sinkronkan nama kasetma ke tabel profil
                if (!empty($kasetmaData['ketua'])) {
                    Profil::query()->update(['nama_kasetma' => $kasetmaData['ketua']]);
                }
            }
        }

        // 3. Wakasatkorcab (Dinamis: edit existing, delete yang dihapus, tambah baru)
        if ($request->has('wakasatkorcab')) {
            $wakasatDataList = $request->input('wakasatkorcab');
            $existingIds = [];

            foreach ($wakasatDataList as $index => $wData) {
                $namaKetua = trim($wData['ketua'] ?? '');
                $namaPosisi = trim($wData['nama_posisi'] ?? ('Wakil Kasatkorcab ' . ($index + 1)));

                if (!empty($wData['id'])) {
                    $wItem = StrukturOrganisasi::where('kategori', 'wakasatkorcab')->find($wData['id']);
                    if ($wItem) {
                        $wItem->update([
                            'nama_posisi' => $namaPosisi,
                            'ketua'       => $namaKetua ?: null,
                            'keterangan'  => $wData['keterangan'] ?? null,
                            'urutan'      => $index + 2,
                        ]);
                        $existingIds[] = $wItem->id;
                    }
                } elseif (!empty($namaKetua) || !empty($namaPosisi)) {
                    // Item baru ditambahkan
                    $newItem = StrukturOrganisasi::create([
                        'kategori'    => 'wakasatkorcab',
                        'kode'        => 'wakasatkorcab_' . (time() . '_' . $index),
                        'nama_posisi' => $namaPosisi,
                        'singkatan'   => 'WAKASATKORCAB',
                        'ketua'       => $namaKetua ?: null,
                        'keterangan'  => $wData['keterangan'] ?? null,
                        'urutan'      => $index + 2,
                    ]);
                    $existingIds[] = $newItem->id;
                }
            }

            // Hapus wakasatkorcab yang tidak ada lagi di formulir (jika user menghapus baris)
            if (!empty($existingIds)) {
                StrukturOrganisasi::where('kategori', 'wakasatkorcab')
                    ->whereNotIn('id', $existingIds)
                    ->delete();
            }
        }

        // 4. Biro-Biro (Ketua & Anggota)
        if ($request->has('biro')) {
            foreach ($request->input('biro') as $id => $bData) {
                $biro = StrukturOrganisasi::where('kategori', 'biro')->find($id);
                if ($biro) {
                    $anggotaList = $this->parseAnggotaInput($bData['anggota'] ?? []);
                    $biro->update([
                        'ketua'      => trim($bData['ketua'] ?? '') ?: null,
                        'anggota'    => $anggotaList,
                        'keterangan' => $bData['keterangan'] ?? $biro->keterangan,
                    ]);
                }
            }
        }

        // 5. Satsus (Ketua, Wakil, & Anggota)
        if ($request->has('satsus')) {
            foreach ($request->input('satsus') as $id => $sData) {
                $satsus = StrukturOrganisasi::where('kategori', 'satsus')->find($id);
                if ($satsus) {
                    $anggotaList = $this->parseAnggotaInput($sData['anggota'] ?? []);
                    $satsus->update([
                        'ketua'      => trim($sData['ketua'] ?? '') ?: null,
                        'wakil'      => trim($sData['wakil'] ?? '') ?: null,
                        'anggota'    => $anggotaList,
                        'keterangan' => $sData['keterangan'] ?? $satsus->keterangan,
                    ]);
                }
            }
        }

        // Catat Log Aktivitas
        LogAktivitas::catat(
            'Memperbarui Struktur Organisasi',
            'Personel struktur organisasi Satkorcab Banser berhasil diperbarui oleh ' . (Auth::user()->name ?? 'Pengguna')
        );

        return redirect()->route('struktur-organisasi.index')
            ->with('success', 'Struktur organisasi dan personalia Satkorcab Banser berhasil disimpan.');
    }

    /**
     * Tambah satu Wakil Kasatkorcab secara cepat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeWakil(Request $request)
    {
        $request->validate([
            'nama_posisi' => 'required|string|max:100',
            'ketua'       => 'nullable|string|max:255',
            'keterangan'  => 'nullable|string|max:500',
        ]);

        $maxUrutan = StrukturOrganisasi::where('kategori', 'wakasatkorcab')->max('urutan') ?? 2;

        StrukturOrganisasi::create([
            'kategori'    => 'wakasatkorcab',
            'kode'        => 'wakasatkorcab_' . time(),
            'nama_posisi' => $request->input('nama_posisi'),
            'singkatan'   => 'WAKASATKORCAB',
            'ketua'       => $request->input('ketua') ?: null,
            'keterangan'  => $request->input('keterangan'),
            'urutan'      => $maxUrutan + 1,
        ]);

        LogAktivitas::catat(
            'Menambah Wakil Kasatkorcab',
            'Menambahkan ' . $request->input('nama_posisi') . ' (' . ($request->input('ketua') ?: 'Belum ditentukan') . ')'
        );

        return redirect()->back()->with('success', 'Wakil Kasatkorcab baru berhasil ditambahkan.');
    }

    /**
     * Hapus Wakil Kasatkorcab tertentu.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyWakil($id)
    {
        $wakil = StrukturOrganisasi::where('kategori', 'wakasatkorcab')->findOrFail($id);
        $nama = $wakil->nama_posisi . ' (' . ($wakil->ketua ?? 'Kosong') . ')';
        $wakil->delete();

        LogAktivitas::catat(
            'Menghapus Wakil Kasatkorcab',
            'Menghapus ' . $nama
        );

        return redirect()->back()->with('success', 'Wakil Kasatkorcab berhasil dihapus.');
    }

    /**
     * Update satu unit (Biro atau Satsus) secara mandiri / modal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUnit(Request $request, $id)
    {
        $unit = StrukturOrganisasi::findOrFail($id);

        $ketua = trim($request->input('ketua', ''));
        $wakil = trim($request->input('wakil', ''));
        $anggotaRaw = $request->input('anggota');

        $anggotaList = $this->parseAnggotaInput($anggotaRaw);

        $unit->update([
            'ketua'      => $ketua ?: null,
            'wakil'      => $wakil ?: null,
            'anggota'    => $anggotaList,
            'keterangan' => $request->input('keterangan', $unit->keterangan),
        ]);

        // Sinkronkan kasat/kasetma jika unit ini adalah kasat/kasetma
        if ($unit->kategori === 'kasatkorcab' && !empty($ketua)) {
            Profil::query()->update(['nama_kasatkorcab' => $ketua]);
        } elseif ($unit->kategori === 'kasetma' && !empty($ketua)) {
            Profil::query()->update(['nama_kasetma' => $ketua]);
        }

        LogAktivitas::catat(
            'Pembaruan Unit ' . $unit->nama_posisi,
            'Memperbarui susunan personel ' . $unit->nama_posisi
        );

        return redirect()->back()->with('success', 'Data personalia ' . $unit->nama_posisi . ' berhasil diperbarui.');
    }

    /**
     * Parsing input anggota baik dari array atau string teks per baris
     *
     * @param  mixed  $input
     * @return array
     */
    private function parseAnggotaInput($input): array
    {
        if (is_array($input)) {
            $filtered = [];
            foreach ($input as $item) {
                $name = trim(is_string($item) ? $item : ($item['nama'] ?? ''));
                if (!empty($name)) {
                    $filtered[] = $name;
                }
            }
            return array_values($filtered);
        }

        if (is_string($input)) {
            $lines = explode("\n", $input);
            $filtered = [];
            foreach ($lines as $line) {
                $trimmed = trim($line);
                // Bersihkan penomoran di depan seperti "1. ", "- ", "* "
                $clean = preg_replace('/^(\d+[\.\)]|-|\*)\s*/', '', $trimmed);
                if (!empty($clean)) {
                    $filtered[] = $clean;
                }
            }
            return array_values($filtered);
        }

        return [];
    }
}
