<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\LogAktivitas;
use App\Models\Satuan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola data Anggota BANSER Satkorcab.
 * Mencakup registrasi anggota, pencarian, update data, dan hapus data.
 * Data anggota digunakan sebagai referensi penerima Surat Tugas (ST)
 * dan personil dalam Surat Perintah Harian (SPH).
 */
class AnggotaController extends Controller
{
    /**
     * Tampilkan daftar anggota dengan pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $query = Anggota::with('satuan');

        // Pencarian berdasarkan NIA, nama, atau jabatan
        $search = $request->input('search', $request->input('q'));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nia', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan satuan
        if ($request->filled('satuan_id')) {
            $query->where('satuan_id', $request->input('satuan_id'));
        }

        $anggota = $query->orderBy('nama', 'asc')
            ->paginate(20)
            ->withQueryString();

        $satuanList = Satuan::orderBy('nama')->get();

        return view('anggota.index', [
            'anggota'    => $anggota,
            'satuanList' => $satuanList,
        ]);
    }

    /**
     * Tampilkan formulir tambah anggota baru.
     */
    public function create()
    {
        $satuanList = Satuan::orderBy('nama')->get();
        return view('anggota.create', ['satuanList' => $satuanList]);
    }

    /**
     * Simpan data anggota baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nia'       => 'nullable|string|max:30|unique:anggota,nia',
            'nama'      => 'required|string|max:100',
            'jabatan'   => 'nullable|string|max:100',
            'satuan_id' => 'nullable|exists:satuan,id',
        ], [
            'nia.unique'       => 'Nomor Induk Anggota (NIA) sudah terdaftar.',
            'nama.required'    => 'Nama lengkap anggota wajib diisi.',
        ]);

        $anggota = Anggota::create($validated);

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Mendaftarkan anggota baru: {$anggota->nama} (NIA: {$anggota->nia})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('anggota.index')
            ->with('success', "Anggota {$anggota->nama} berhasil ditambahkan.");
    }

    /**
     * Tampilkan detail seorang anggota.
     */
    public function show($id)
    {
        $anggota = Anggota::with(['satuan', 'penerimaTugas.suratKeluar'])->findOrFail($id);
        return view('anggota.show', ['anggota' => $anggota]);
    }

    /**
     * Tampilkan formulir edit data anggota.
     */
    public function edit($id)
    {
        $anggota    = Anggota::findOrFail($id);
        $satuanList = Satuan::orderBy('nama')->get();
        return view('anggota.edit', [
            'anggota'    => $anggota,
            'satuanList' => $satuanList,
        ]);
    }

    /**
     * Perbarui data anggota di database.
     */
    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $validated = $request->validate([
            'nia'       => 'nullable|string|max:30|unique:anggota,nia,' . $id,
            'nama'      => 'required|string|max:100',
            'jabatan'   => 'nullable|string|max:100',
            'satuan_id' => 'nullable|exists:satuan,id',
        ], [
            'nia.unique'    => 'Nomor Induk Anggota (NIA) sudah digunakan anggota lain.',
            'nama.required' => 'Nama lengkap anggota wajib diisi.',
        ]);

        $anggota->update($validated);

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Memperbarui data anggota: {$anggota->nama} (NIA: {$anggota->nia})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('anggota.index')
            ->with('success', "Data anggota {$anggota->nama} berhasil diperbarui.");
    }

    /**
     * Hapus data anggota dari database.
     */
    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $nama    = $anggota->nama;
        $nia     = $anggota->nia;

        // Cek apakah anggota memiliki penugasan terkait
        if ($anggota->penerimaTugas()->exists()) {
            return redirect()->back()
                ->with('error', "Anggota {$nama} tidak dapat dihapus karena memiliki data penugasan (Surat Tugas) yang terkait. Hapus data penugasan terlebih dahulu.");
        }

        $anggota->delete();

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Menghapus data anggota: {$nama} (NIA: {$nia})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('anggota.index')
            ->with('success', "Data anggota {$nama} berhasil dihapus.");
    }

    /**
     * Endpoint API: Daftar anggota untuk select2 / autocomplete
     * (digunakan oleh form Surat Tugas dan SPH).
     */
    public function apiList(Request $request)
    {
        $search = $request->input('q', '');
        $anggota = Anggota::with('satuan')
            ->when(!empty($search), function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nia', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->limit(50)
            ->get()
            ->map(function ($a) {
                return [
                    'id'      => $a->id,
                    'text'    => $a->nama . ($a->nia ? " ({$a->nia})" : ''),
                    'jabatan' => $a->jabatan ?? '-',
                    'satuan'  => $a->satuan?->nama ?? '-',
                ];
            });

        return response()->json(['results' => $anggota]);
    }
}
