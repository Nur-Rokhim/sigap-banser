<?php

namespace App\Http\Controllers;

use App\Models\DetailSk;
use App\Models\JenisSurat;
use App\Models\LogAktivitas;
use App\Models\Profil;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Services\NomorSuratService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk mengelola Surat Keputusan (SK) Satkorcab BANSER.
 * Dilengkapi form KOP Surat resmi sama dengan Surat Perintah Harian (SPH),
 * konsiderans hukum baku (Menimbang, Mengingat, Memperhatikan),
 * serta diktum penetapan keputusan (Memutuskan).
 */
class SuratKeputusanController extends Controller
{
    /**
     * Dapatkan instance JenisSurat untuk SK.
     */
    private function getJenisSk(): JenisSurat
    {
        return JenisSurat::firstOrCreate(
            ['kode' => 'SK'],
            ['nama' => 'Surat Keputusan', 'aktif' => true]
        );
    }

    /**
     * Tampilkan daftar Surat Keputusan (SK) Satkorcab.
     */
    public function index(Request $request)
    {
        $jenisSk = $this->getJenisSk();

        $query = SuratKeluar::with(['jenis', 'pembuat', 'detailSk', 'suratMasuk'])
            ->where('jenis_surat_id', $jenisSk->id);

        // Filter pencarian berdasarkan nomor surat, tentang/perihal
        $search = $request->input('search', $request->input('q'));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhereHas('detailSk', function ($skQuery) use ($search) {
                      $skQuery->where('tentang', 'like', "%{$search}%")
                              ->orWhere('menimbang', 'like', "%{$search}%")
                              ->orWhere('diktum_pertama', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $suratKeputusan = $query->orderBy('tgl_surat', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('surat-keputusan.index', [
            'suratKeputusan' => $suratKeputusan,
            'jenisSk'        => $jenisSk,
        ]);
    }

    /**
     * Tampilkan formulir pembuatan Surat Keputusan (SK) baru.
     * Menggunakan form ber-KOP Surat resmi sama persis dengan SPH.
     */
    public function create()
    {
        $jenisSk = $this->getJenisSk();
        $profil  = Profil::getProfil();
        $suratMasukList = SuratMasuk::orderBy('tgl_diterima', 'desc')->get();

        // Template konten baku SK Satkorcab Banser pada umumnya
        $defaultMenimbang = "a. Bahwa demi menjaga kesinambungan kepemimpinan dan optimalisasi roda organisasi Barisan Ansor Serbaguna (BANSER) di wilayah kerja Satuan Koordinasi Cabang Kabupaten Blitar, dipandang perlu adanya penetapan keputusan.\n"
                          . "b. Bahwa sahabat-sahabat yang namanya tercantum dalam keputusan ini dinilai cakap, loyal, dan berdedikasi tinggi serta memenuhi syarat untuk mengemban amanah organisasi.\n"
                          . "c. Bahwa berdasarkan pertimbangan sebagaimana dimaksud pada huruf a dan b di atas, perlu diterbitkan Surat Keputusan Satuan Koordinasi Cabang Barisan Ansor Serbaguna Kabupaten Blitar.";

        $defaultMengingat = "1. Peraturan Dasar dan Peraturan Rumah Tangga (PD/PRT) Gerakan Pemuda Ansor.\n"
                          . "2. Peraturan Organisasi (PO) Gerakan Pemuda Ansor tentang Barisan Ansor Serbaguna (BANSER).\n"
                          . "3. Petunjuk Pelaksanaan dan Petunjuk Teknis Penyelenggaraan Administrasi BANSER.\n"
                          . "4. Hasil Konferensi Cabang Gerakan Pemuda Ansor Kabupaten Blitar.";

        $defaultMemperhatikan = "1. Hasil Rapat Pleno Pimpinan Satuan Koordinasi Cabang BANSER Kabupaten Blitar.\n"
                              . "2. Arahan dan petunjuk Pimpinan Cabang Gerakan Pemuda Ansor Kabupaten Blitar.";

        $defaultDiktum1 = "Mengesahkan dan menetapkan susunan pengurus/personil sebagaimana tercantum dalam lampiran keputusan ini sebagai bagian yang tidak terpisahkan dari surat keputusan ini.";

        $defaultDiktum2 = "Memberikan mandat, tugas, wewenang, dan tanggung jawab penuh kepada personil yang ditetapkan untuk menjalankan program kerja serta pengabdian sesuai dengan amanat organisasi.";

        $defaultDiktum3 = "Mewajibkan kepada yang bersangkutan untuk senantiasa berkoordinasi dan menyampaikan laporan pelaksanaan tugas secara berkala kepada Kepala Satuan Koordinasi Cabang (SATKORCAB) BANSER Kabupaten Blitar.";

        $defaultDiktum4 = "Keputusan ini berlaku sejak tanggal ditetapkan, dengan ketentuan apabila di kemudian hari terdapat kekeliruan atau kekurangan akan diadakan pembetulan sebagaimana mestinya.";

        $defaultTembusan = "1. Pimpinan Cabang Gerakan Pemuda Ansor Kabupaten Blitar\n"
                         . "2. Satuan Koordinasi Rayon (Satkoryon) terkait\n"
                         . "3. Arsip Satkorcab BANSER Kab. Blitar";

        return view('surat-keputusan.create', [
            'jenisSk'              => $jenisSk,
            'profil'               => $profil,
            'suratMasukList'       => $suratMasukList,
            'defaultMenimbang'     => $defaultMenimbang,
            'defaultMengingat'     => $defaultMengingat,
            'defaultMemperhatikan' => $defaultMemperhatikan,
            'defaultDiktum1'       => $defaultDiktum1,
            'defaultDiktum2'       => $defaultDiktum2,
            'defaultDiktum3'       => $defaultDiktum3,
            'defaultDiktum4'       => $defaultDiktum4,
            'defaultTembusan'      => $defaultTembusan,
        ]);
    }

    /**
     * Simpan Surat Keputusan (SK) baru ke database.
     */
    public function store(Request $request, NomorSuratService $nomorSuratService)
    {
        $validated = $request->validate([
            'tentang'        => 'required|string|max:255',
            'tgl_surat'      => 'required|date',
            'tgl_hijriyah'   => 'nullable|string|max:100',
            'ditetapkan_di'  => 'required|string|max:100',
            'surat_masuk_id' => 'nullable|exists:surat_masuk,id',
            'menimbang'      => 'required|string',
            'mengingat'      => 'required|string',
            'memperhatikan'  => 'nullable|string',
            'diktum_pertama' => 'required|string',
            'diktum_kedua'   => 'nullable|string',
            'diktum_ketiga'  => 'nullable|string',
            'diktum_keempat' => 'nullable|string',
            'diktum_kelima'  => 'nullable|string',
            'lampiran_sk'    => 'nullable|string',
            'tembusan'       => 'nullable|string',
        ], [
            'tentang.required'        => 'Judul penetapan / Tentang SK wajib diisi.',
            'tgl_surat.required'      => 'Tanggal penetapan SK wajib diisi.',
            'ditetapkan_di.required'  => 'Tempat penetapan SK wajib diisi.',
            'menimbang.required'      => 'Konsiderans Menimbang wajib diisi.',
            'mengingat.required'      => 'Konsiderans Mengingat wajib diisi.',
            'diktum_pertama.required' => 'Diktum Keputusan PERTAMA / KESATU wajib diisi.',
        ]);

        $jenisSk = $this->getJenisSk();
        $userId  = Auth::id() ?? 1;

        $surat = DB::transaction(function () use ($validated, $jenisSk, $userId, $request, $nomorSuratService) {
            $suratKeluar = SuratKeluar::create([
                'jenis_surat_id' => $jenisSk->id,
                'surat_masuk_id' => $validated['surat_masuk_id'] ?? null,
                'perihal'        => 'SURAT KEPUTUSAN TENTANG ' . mb_strtoupper($validated['tentang']),
                'tujuan'         => 'Seluruh Anggota & Jajaran Terkait',
                'tgl_surat'      => $validated['tgl_surat'],
                'status'         => 'draf',
                'dibuat_oleh'    => $userId,
            ]);

            DetailSk::create([
                'surat_keluar_id' => $suratKeluar->id,
                'tentang'         => $validated['tentang'],
                'menimbang'       => $validated['menimbang'],
                'mengingat'       => $validated['mengingat'],
                'memperhatikan'   => $validated['memperhatikan'] ?? null,
                'diktum_pertama'  => $validated['diktum_pertama'],
                'diktum_kedua'    => $validated['diktum_kedua'] ?? null,
                'diktum_ketiga'   => $validated['diktum_ketiga'] ?? null,
                'diktum_keempat'  => $validated['diktum_keempat'] ?? null,
                'diktum_kelima'   => $validated['diktum_kelima'] ?? null,
                'ditetapkan_di'   => $validated['ditetapkan_di'] ?? 'Blitar',
                'tgl_hijriyah'    => $validated['tgl_hijriyah'] ?? null,
                'tembusan'        => $validated['tembusan'] ?? null,
                'lampiran_sk'     => $validated['lampiran_sk'] ?? null,
            ]);

            LogAktivitas::create([
                'user_id' => $userId,
                'aksi'    => "Membuat draf Surat Keputusan (SK) tentang: {$validated['tentang']}",
                'waktu'   => Carbon::now(),
            ]);

            // Jika user memilih aksi langsung ajukan atau langsung terbitkan jika verifikator
            $aksi = $request->input('aksi_simpan', 'draf');
            if ($aksi === 'ajukan') {
                $suratKeluar->update(['status' => 'verifikasi']);
            } elseif ($aksi === 'setujui') {
                $userRole = strtolower(Auth::user()->role ?? '');
                if (in_array($userRole, ['super_admin', 'kasatkorcab', 'kasetma', 'pimpinan', 'admin_adminpers'])) {
                    $nomorSuratService->terbitkan($suratKeluar);
                }
            }

            return $suratKeluar;
        });

        return redirect()->route('surat-keputusan.show', $surat->id)
            ->with('success', 'Surat Keputusan (SK) berhasil disimpan.');
    }

    /**
     * Tampilkan detail Surat Keputusan (SK) dengan format dokumen resmi.
     */
    public function show($id)
    {
        $surat = SuratKeluar::with([
            'jenis',
            'pembuat',
            'detailSk',
            'suratMasuk'
        ])->findOrFail($id);

        $profil = Profil::getProfil();

        return view('surat-keputusan.show', [
            'surat'  => $surat,
            'sk'     => $surat->detailSk,
            'profil' => $profil,
        ]);
    }

    /**
     * Tampilkan formulir edit Surat Keputusan (SK).
     */
    public function edit($id)
    {
        $surat = SuratKeluar::with([
            'jenis',
            'detailSk',
            'suratMasuk'
        ])->findOrFail($id);

        $userRole = strtolower(Auth::user()->role ?? '');
        $canEdit  = in_array($userRole, ['super_admin', 'admin_adminpers', 'admin', 'kasetma', 'adminpers', 'kasatkorcab']);
        if ($surat->status !== 'draf' && !$canEdit) {
            return redirect()->route('surat-keputusan.show', $id)
                ->with('error', 'Surat Keputusan yang telah diverifikasi/disetujui tidak dapat diedit.');
        }

        $jenisSk = $this->getJenisSk();
        $profil  = Profil::getProfil();
        $suratMasukList = SuratMasuk::orderBy('tgl_diterima', 'desc')->get();

        return view('surat-keputusan.edit', [
            'surat'          => $surat,
            'sk'             => $surat->detailSk,
            'jenisSk'        => $jenisSk,
            'profil'         => $profil,
            'suratMasukList' => $suratMasukList,
        ]);
    }

    /**
     * Perbarui data Surat Keputusan (SK).
     */
    public function update(Request $request, $id)
    {
        $surat = SuratKeluar::with('detailSk')->findOrFail($id);

        $validated = $request->validate([
            'tentang'        => 'required|string|max:255',
            'tgl_surat'      => 'required|date',
            'tgl_hijriyah'   => 'nullable|string|max:100',
            'ditetapkan_di'  => 'required|string|max:100',
            'surat_masuk_id' => 'nullable|exists:surat_masuk,id',
            'menimbang'      => 'required|string',
            'mengingat'      => 'required|string',
            'memperhatikan'  => 'nullable|string',
            'diktum_pertama' => 'required|string',
            'diktum_kedua'   => 'nullable|string',
            'diktum_ketiga'  => 'nullable|string',
            'diktum_keempat' => 'nullable|string',
            'diktum_kelima'  => 'nullable|string',
            'lampiran_sk'    => 'nullable|string',
            'tembusan'       => 'nullable|string',
        ]);

        DB::transaction(function () use ($surat, $validated) {
            $surat->update([
                'surat_masuk_id' => $validated['surat_masuk_id'] ?? null,
                'perihal'        => 'SURAT KEPUTUSAN TENTANG ' . mb_strtoupper($validated['tentang']),
                'tgl_surat'      => $validated['tgl_surat'],
            ]);

            DetailSk::updateOrCreate(
                ['surat_keluar_id' => $surat->id],
                [
                    'tentang'         => $validated['tentang'],
                    'menimbang'       => $validated['menimbang'],
                    'mengingat'       => $validated['mengingat'],
                    'memperhatikan'   => $validated['memperhatikan'] ?? null,
                    'diktum_pertama'  => $validated['diktum_pertama'],
                    'diktum_kedua'    => $validated['diktum_kedua'] ?? null,
                    'diktum_ketiga'   => $validated['diktum_ketiga'] ?? null,
                    'diktum_keempat'  => $validated['diktum_keempat'] ?? null,
                    'diktum_kelima'   => $validated['diktum_kelima'] ?? null,
                    'ditetapkan_di'   => $validated['ditetapkan_di'] ?? 'Blitar',
                    'tgl_hijriyah'    => $validated['tgl_hijriyah'] ?? null,
                    'tembusan'        => $validated['tembusan'] ?? null,
                    'lampiran_sk'     => $validated['lampiran_sk'] ?? null,
                ]
            );

            LogAktivitas::create([
                'user_id' => Auth::id() ?? 1,
                'aksi'    => "Memperbarui Surat Keputusan (SK) ID #{$surat->id} tentang: {$validated['tentang']}",
                'waktu'   => Carbon::now(),
            ]);
        });

        return redirect()->route('surat-keputusan.show', $surat->id)
            ->with('success', 'Perubahan Surat Keputusan berhasil disimpan.');
    }

    /**
     * Hapus Surat Keputusan (SK).
     */
    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $nomor = $surat->nomor_surat ?? ('SK ID #' . $surat->id);

        DB::transaction(function () use ($surat) {
            if ($surat->detailSk) {
                $surat->detailSk->delete();
            }
            $surat->delete();
        });

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Menghapus data Surat Keputusan {$nomor}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-keputusan.index')
            ->with('success', "Surat Keputusan {$nomor} berhasil dihapus.");
    }

    /**
     * Ajukan SK ke pimpinan untuk verifikasi.
     */
    public function ajukan($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        if ($surat->status !== 'draf') {
            return redirect()->back()->with('error', 'Hanya draf yang dapat diajukan untuk verifikasi.');
        }

        $surat->update(['status' => 'verifikasi']);

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Mengajukan verifikasi SK ID #{$surat->id}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-keputusan.show', $surat->id)
            ->with('success', 'Surat Keputusan berhasil diajukan kepada pimpinan untuk diverifikasi.');
    }

    /**
     * Setujui SK dan terbitkan nomor resmi otomatis (.../SKC-SK/...).
     */
    public function setujui($id, NomorSuratService $nomorSuratService)
    {
        $userRole = strtolower(Auth::user()->role ?? '');
        $canApprove = in_array($userRole, ['super_admin', 'kasatkorcab', 'kasetma', 'pimpinan', 'admin_adminpers']);
        if (!$canApprove) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk menyetujui dan menerbitkan nomor SK.');
        }

        $surat = SuratKeluar::findOrFail($id);
        if ($surat->status === 'disetujui' && $surat->nomor_surat) {
            return redirect()->back()->with('info', 'Surat Keputusan ini sudah disetujui sebelumnya.');
        }

        $surat = $nomorSuratService->terbitkan($surat);

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Menyetujui dan menerbitkan nomor resmi SK: {$surat->nomor_surat}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-keputusan.show', $surat->id)
            ->with('success', "Surat Keputusan berhasil disetujui dengan nomor resmi: {$surat->nomor_surat}");
    }

    /**
     * Tandai status SK telah dikirim / ditetapkan.
     */
    public function kirim($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        if (!in_array($surat->status, ['disetujui', 'terkirim'])) {
            return redirect()->back()->with('error', 'Hanya SK yang telah disetujui dan bernomor resmi yang dapat ditandai telah dikirim/ditetapkan.');
        }

        $surat->update(['status' => 'terkirim']);

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Menandai pengiriman / penetapan resmi SK nomor {$surat->nomor_surat}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-keputusan.show', $surat->id)
            ->with('success', 'Status Surat Keputusan berhasil diperbarui menjadi terkirim / ditetapkan.');
    }

    /**
     * Hasilkan dan unduh / tampilkan PDF Surat Keputusan (SK)
     * Menggunakan format KOP Surat resmi sama persis dengan SPH.
     */
    public function pdf($id)
    {
        $surat = SuratKeluar::with([
            'jenis',
            'pembuat',
            'detailSk',
            'suratMasuk'
        ])->findOrFail($id);

        $sk = $surat->detailSk;
        $profil = Profil::getProfil();

        $pdf = Pdf::loadView('surat-keputusan.pdf', [
            'surat'  => $surat,
            'sk'     => $sk,
            'profil' => $profil,
        ])->setPaper([0, 0, 609.45, 935.43], 'portrait'); // Ukuran F4 / Folio (21.5cm x 33.0cm)

        $namaFile = 'SK-' . ($surat->nomor_surat
            ? str_replace(['/', ' '], '-', $surat->nomor_surat)
            : 'draf-' . $id) . '.pdf';

        return $pdf->stream($namaFile);
    }
}
