<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\LogAktivitas;
use App\Models\SuratMasuk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Controller untuk mengelola Surat Masuk SIGAP BANSER.
 * Mencakup registrasi buku agenda, pencarian, unggah berkas scan ke direktori privat,
 * tampilan detail, unduh scan berkas fisik, dan pembuatan lembar disposisi pimpinan.
 */
class SuratMasukController extends Controller
{
    /**
     * Tampilkan daftar surat masuk dengan fitur pencarian dan paginasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = SuratMasuk::query();

        // Pencarian berdasarkan nomor surat, pengirim, atau perihal
        $search = $request->input('search', $request->input('q'));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('dari', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status jika ditentukan
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Urutkan berdasarkan tanggal diterima terbaru lalu ID
        $suratMasuk = $query->orderBy('tgl_diterima', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Lengkapi alias atribut agar kompatibel dengan view Blade
        $suratMasuk->getCollection()->transform(function ($item) {
            $item->nomor_surat = $item->nomor_surat ?? $item->no_surat;
            $item->pengirim = $item->pengirim ?? $item->dari;
            $item->tanggal_surat = $item->tanggal_surat ?? $item->tgl_surat;
            $item->tanggal_diterima = $item->tanggal_diterima ?? $item->tgl_diterima;
            return $item;
        });

        return view('surat-masuk.index', compact('suratMasuk'));
    }

    /**
     * Tampilkan formulir pencatatan surat masuk baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('surat-masuk.create');
    }

    /**
     * Simpan data surat masuk baru ke basis data dan simpan berkas scan ke penyimpanan privat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Normalisasi input agar mendukung nama field konvensi formulir
        $request->merge([
            'no_surat'     => $request->input('no_surat', $request->input('nomor_surat')),
            'dari'         => $request->input('dari', $request->input('pengirim')),
            'tgl_surat'    => $request->input('tgl_surat', $request->input('tanggal_surat')),
            'tgl_diterima' => $request->input('tgl_diterima', $request->input('tanggal_diterima')),
        ]);

        // Cek berkas scan pada field file_scan atau scan_surat
        $file = $request->file('file_scan') ?? $request->file('scan_surat');
        if ($file && !$request->hasFile('file_scan')) {
            $request->files->set('file_scan', $file);
        }

        // Validasi data masukan
        $validated = $request->validate([
            'no_surat'     => 'required|string|max:100',
            'dari'         => 'required|string|max:150',
            'perihal'      => 'required|string|max:255',
            'tgl_surat'    => 'nullable|date',
            'tgl_diterima' => 'required|date',
            'file_scan'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'no_surat.required'     => 'Nomor surat wajib diisi.',
            'no_surat.max'          => 'Nomor surat tidak boleh lebih dari 100 karakter.',
            'dari.required'         => 'Nama instansi / pihak pengirim wajib diisi.',
            'dari.max'              => 'Nama pengirim tidak boleh lebih dari 150 karakter.',
            'perihal.required'      => 'Perihal surat wajib diisi.',
            'perihal.max'           => 'Perihal surat tidak boleh lebih dari 255 karakter.',
            'tgl_surat.date'        => 'Format tanggal surat tidak valid.',
            'tgl_diterima.required' => 'Tanggal diterima wajib diisi.',
            'tgl_diterima.date'     => 'Format tanggal diterima tidak valid.',
            'file_scan.file'        => 'Berkas scan harus berupa berkas dokumen yang valid.',
            'file_scan.mimes'       => 'Berkas scan harus berformat PDF, JPG, atau PNG.',
            'file_scan.max'         => 'Ukuran berkas scan tidak boleh lebih dari 5 MB.',
        ]);

        // Simpan berkas scan ke direktori privat (storage/app/private/surat-masuk/)
        $filePath = null;
        if ($file) {
            $filePath = $file->store('surat-masuk', 'local');
        }

        $userId = Auth::id() ?? 1;

        // Simpan data surat masuk
        $surat = SuratMasuk::create([
            'no_surat'     => $validated['no_surat'],
            'dari'         => $validated['dari'],
            'perihal'      => $validated['perihal'],
            'tgl_surat'    => $validated['tgl_surat'] ?? null,
            'tgl_diterima' => $validated['tgl_diterima'],
            'file_scan'    => $filePath,
            'status'       => 'baru',
            'dicatat_oleh' => $userId,
        ]);

        // Catat ke log aktivitas
        LogAktivitas::create([
            'user_id' => $userId,
            'aksi'    => "Mencatat surat masuk nomor {$surat->no_surat} dari {$surat->dari}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil dicatat ke dalam buku agenda.');
    }

    /**
     * Tampilkan detail surat masuk beserta riwayat disposisi.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $suratMasuk = SuratMasuk::with([
            'dicatatOleh',
            'disposisi.dariUser',
            'disposisi.kepadaUser',
        ])->findOrFail($id);

        // Alias atribut untuk Blade
        $suratMasuk->nomor_surat = $suratMasuk->no_surat;
        $suratMasuk->pengirim = $suratMasuk->dari;
        $suratMasuk->tanggal_surat = $suratMasuk->tgl_surat;
        $suratMasuk->tanggal_diterima = $suratMasuk->tgl_diterima;

        // Ambil daftar pengguna dan daftar tujuan resmi untuk pilihan penerima disposisi
        $users = User::orderBy('name')->get();
        $daftarTujuan = Disposisi::daftarTujuan();

        return view('surat-masuk.show', [
            'suratMasuk'   => $suratMasuk,
            'surat'        => $suratMasuk,
            'users'        => $users,
            'daftarTujuan' => $daftarTujuan,
        ]);
    }

    /**
     * Tampilkan formulir edit surat masuk.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);

        // Alias atribut untuk Blade
        $suratMasuk->nomor_surat    = $suratMasuk->no_surat;
        $suratMasuk->pengirim       = $suratMasuk->dari;
        $suratMasuk->tanggal_surat  = $suratMasuk->tgl_surat;
        $suratMasuk->tanggal_diterima = $suratMasuk->tgl_diterima;

        return view('surat-masuk.edit', compact('suratMasuk'));
    }

    /**
     * Simpan perubahan data surat masuk yang telah diedit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        // Normalisasi input field
        $request->merge([
            'no_surat'     => $request->input('no_surat', $request->input('nomor_surat')),
            'dari'         => $request->input('dari', $request->input('pengirim')),
            'tgl_surat'    => $request->input('tgl_surat', $request->input('tanggal_surat')),
            'tgl_diterima' => $request->input('tgl_diterima', $request->input('tanggal_diterima')),
        ]);

        // Cek file baru
        $file = $request->file('file_scan') ?? $request->file('scan_surat');

        $validated = $request->validate([
            'no_surat'     => 'required|string|max:100',
            'dari'         => 'required|string|max:150',
            'perihal'      => 'required|string|max:255',
            'tgl_surat'    => 'nullable|date',
            'tgl_diterima' => 'required|date',
            'status'       => 'nullable|in:baru,didisposisi,selesai',
        ], [
            'no_surat.required'     => 'Nomor surat wajib diisi.',
            'dari.required'         => 'Nama pengirim wajib diisi.',
            'perihal.required'      => 'Perihal surat wajib diisi.',
            'tgl_diterima.required' => 'Tanggal diterima wajib diisi.',
        ]);

        // Simpan file baru jika ada
        if ($file && $file->isValid()) {
            $filePath = $file->store('surat-masuk', 'local');
            $validated['file_scan'] = $filePath;
        }

        $surat->update([
            'no_surat'     => $validated['no_surat'],
            'dari'         => $validated['dari'],
            'perihal'      => $validated['perihal'],
            'tgl_surat'    => $validated['tgl_surat'] ?? null,
            'tgl_diterima' => $validated['tgl_diterima'],
            'status'       => $validated['status'] ?? $surat->status,
            'file_scan'    => $validated['file_scan'] ?? $surat->file_scan,
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Memperbarui data surat masuk nomor {$surat->no_surat}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-masuk.show', $surat->id)
            ->with('success', 'Data surat masuk berhasil diperbarui.');
    }

    /**
     * Unduh berkas scan surat masuk dari penyimpanan privat.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadScan($id)
    {
        $surat = SuratMasuk::findOrFail($id);

        if (!$surat->file_scan) {
            abort(404, 'Berkas scan untuk surat masuk ini tidak tersedia.');
        }

        // Cek ketersediaan berkas di disk local (storage/app/private atau storage/app)
        if (Storage::disk('local')->exists($surat->file_scan)) {
            return Storage::disk('local')->download($surat->file_scan);
        }

        // Jalur alternatif jika disimpan langsung di path storage/app/private
        $privatePath = storage_path('app/private/' . $surat->file_scan);
        if (file_exists($privatePath)) {
            return response()->download($privatePath);
        }

        $appPath = storage_path('app/' . $surat->file_scan);
        if (file_exists($appPath)) {
            return response()->download($appPath);
        }

        abort(404, 'Berkas fisik scan surat tidak ditemukan pada penyimpanan server.');
    }

    /**
     * Simpan lembar disposisi baru untuk surat masuk dan perbarui status surat menjadi 'didisposisi'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disposisi(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        $tujuan = $request->input('tujuan', $request->input('diteruskan_kepada'));
        $kepadaUserId = $request->input('kepada_user_id', $request->input('user_id'));

        // Jika tujuan diisi, coba hubungkan ke user jika ada yang cocok
        if (!empty($tujuan) && (empty($kepadaUserId) || !is_numeric($kepadaUserId))) {
            if (str_contains(strtolower($tujuan), 'adminpers')) {
                $user = User::where('role', 'adminpers')->orWhere('role', 'admin_adminpers')->first();
                if ($user) $kepadaUserId = $user->id;
            } elseif (str_contains(strtolower($tujuan), 'kasetma')) {
                $user = User::where('role', 'kasetma')->first();
                if ($user) $kepadaUserId = $user->id;
            } elseif (str_contains(strtolower($tujuan), 'kasatkorcab')) {
                $user = User::where('role', 'kasatkorcab')->first();
                if ($user) $kepadaUserId = $user->id;
            }
        }

        $request->merge([
            'tujuan'         => $tujuan,
            'kepada_user_id' => is_numeric($kepadaUserId) ? (int) $kepadaUserId : null,
        ]);

        $validated = $request->validate([
            'tujuan'         => 'required|string|max:255',
            'instruksi'      => 'required|string',
            'sifat'          => 'nullable|string|max:50',
            'kepada_user_id' => 'nullable|exists:users,id',
        ], [
            'tujuan.required'    => 'Tujuan / pejabat penerima disposisi wajib dipilih.',
            'instruksi.required' => 'Instruksi atau catatan disposisi wajib diisi.',
        ]);

        $userId = Auth::id() ?? 1;

        // Buat record disposisi
        Disposisi::create([
            'surat_masuk_id' => $surat->id,
            'tujuan'         => $validated['tujuan'],
            'dari_user_id'   => $userId,
            'kepada_user_id' => $validated['kepada_user_id'] ?? null,
            'instruksi'      => $validated['instruksi'],
            'sifat'          => $validated['sifat'] ?? 'Biasa',
            'status'         => 'diproses',
        ]);

        // Perbarui status surat masuk
        $surat->update([
            'status' => 'didisposisi',
        ]);

        // Catat ke log aktivitas
        LogAktivitas::create([
            'user_id' => $userId,
            'aksi'    => "Mendisposisikan surat masuk nomor {$surat->no_surat} kepada: {$validated['tujuan']}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-masuk.show', $surat->id)
            ->with('success', "Disposisi surat masuk berhasil diarahkan kepada {$validated['tujuan']}.");
    }

    /**
     * Hapus data surat masuk beserta file pindaian dan disposisi terkait.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $nomorSurat = $surat->no_surat ?: ('Surat ID #' . $surat->id);

        // Hapus file scan jika tersimpan
        if ($surat->file_scan) {
            if (Storage::disk('local')->exists($surat->file_scan)) {
                Storage::disk('local')->delete($surat->file_scan);
            } elseif (Storage::disk('public')->exists($surat->file_scan)) {
                Storage::disk('public')->delete($surat->file_scan);
            }
        }

        // Hapus disposisi terkait
        $surat->disposisi()->delete();
        $surat->delete();

        // Catat aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Menghapus data surat masuk nomor {$nomorSurat}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-masuk.index')
            ->with('success', "Surat masuk nomor {$nomorSurat} berhasil dihapus dari Buku Agenda.");
    }
}
