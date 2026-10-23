<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\DetailSph;
use App\Models\DetailTugas;
use App\Models\DetailUndangan;
use App\Models\JenisSurat;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use App\Models\PenerimaTugas;
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
 * Controller untuk mengelola Surat Keluar SIGAP BANSER.
 * Mencakup perancangan draf, pengisian rincian spesifik jenis surat (SU, SPH, ST),
 * pengajuan verifikasi ke pimpinan, persetujuan dan penerbitan nomor surat resmi,
 * serta pencatatan status pengiriman surat.
 */
class SuratKeluarController extends Controller
{
    /**
     * Tampilkan buku registrasi surat keluar dengan filter pencarian dan paginasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = SuratKeluar::with(['jenis', 'pembuat', 'suratMasuk']);

        // Filter pencarian berdasarkan nomor surat, tujuan, perihal, atau acuan surat masuk
        $search = $request->input('search', $request->input('q'));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhereHas('suratMasuk', function ($sm) use ($search) {
                      $sm->where('dari', 'like', "%{$search}%")
                         ->orWhere('perihal', 'like', "%{$search}%")
                         ->orWhere('no_surat', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan jenis surat (SU, SPH, ST)
        if ($request->filled('jenis')) {
            $kodeJenis = strtoupper($request->input('jenis'));
            $query->whereHas('jenis', function ($q) use ($kodeJenis) {
                $q->where('kode', $kodeJenis);
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $suratKeluar = $query->orderBy('tgl_surat', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Lengkapi alias jenis_surat untuk Blade
        $suratKeluar->getCollection()->transform(function ($item) {
            $item->jenis_surat = $item->jenis ? $item->jenis->kode : 'SU';
            $item->tanggal_surat = $item->tgl_surat;
            return $item;
        });

        return view('surat-keluar.index', compact('suratKeluar'));
    }

    /**
     * Tampilkan formulir pembuatan surat keluar baru beserta opsi jenis surat dan daftar anggota.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $jenisSurat = JenisSurat::where('aktif', true)->get();
        $anggotas = Anggota::orderBy('nama', 'asc')->get();
        $suratMasukList = SuratMasuk::orderBy('tgl_diterima', 'desc')->get();

        return view('surat-keluar.create', [
            'jenisSurat'     => $jenisSurat,
            'anggotas'       => $anggotas,
            'suratMasukList' => $suratMasukList,
        ]);
    }

    /**
     * Tampilkan formulir edit surat keluar (hanya untuk draf).
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $suratKeluar = SuratKeluar::with([
            'jenis',
            'suratMasuk',
            'detailUndangan',
            'detailSph',
            'detailTugas',
            'penerimaTugas.anggota',
        ])->findOrFail($id);

        // Hanya draf yang boleh diedit; super_admin, adminpers, kasetma, kasatkorcab boleh edit
        $userRole = strtolower(Auth::user()->role ?? '');
        $canEdit  = in_array($userRole, ['super_admin', 'admin_adminpers', 'admin', 'kasetma', 'adminpers', 'kasatkorcab']);

        if ($suratKeluar->jenis && $suratKeluar->jenis->kode === 'SK') {
            return redirect()->route('surat-keputusan.edit', $id);
        }

        if (!$canEdit && $suratKeluar->status !== 'draf') {
            return redirect()->route('surat-keluar.show', $id)
                ->with('error', 'Surat keluar yang sudah diajukan / disetujui tidak dapat diedit lagi.');
        }

        // Alias atribut untuk view
        $suratKeluar->jenis_surat = $suratKeluar->jenis ? $suratKeluar->jenis->kode : 'SU';
        $suratKeluar->tanggal_surat = $suratKeluar->tgl_surat;

        if ($suratKeluar->detailUndangan) {
            $suratKeluar->acara_tanggal = $suratKeluar->detailUndangan->hari_tanggal;
            $suratKeluar->acara_waktu   = $suratKeluar->detailUndangan->waktu;
            $suratKeluar->acara_tempat  = $suratKeluar->detailUndangan->tempat;
            $suratKeluar->acara_pakaian = $suratKeluar->detailUndangan->pakaian;
            $suratKeluar->acara_nama    = $suratKeluar->detailUndangan->acara;
        }

        if ($suratKeluar->detailSph) {
            $suratKeluar->dasar            = $suratKeluar->detailSph->dasar;
            $suratKeluar->isi_perintah     = $suratKeluar->detailSph->isi_perintah;
            $suratKeluar->untuk_satuan     = $suratKeluar->detailSph->untuk_satuan;
            $suratKeluar->jumlah_personel  = $suratKeluar->detailSph->jumlah_personel;
            $suratKeluar->sph_hari_tanggal = $suratKeluar->detailSph->hari_tanggal;
            $suratKeluar->sph_waktu        = $suratKeluar->detailSph->waktu;
            $suratKeluar->sph_acara        = $suratKeluar->detailSph->acara;
            $suratKeluar->sph_tempat       = $suratKeluar->detailSph->tempat;
            $suratKeluar->sph_pakaian      = $suratKeluar->detailSph->pakaian;
        }

        if ($suratKeluar->detailTugas) {
            $suratKeluar->st_dasar      = $suratKeluar->detailTugas->dasar;
            $suratKeluar->dasar         = $suratKeluar->detailTugas->dasar;
            $suratKeluar->uraian_tugas  = $suratKeluar->detailTugas->uraian_tugas;
            $suratKeluar->tgl_mulai     = $suratKeluar->detailTugas->tgl_mulai;
            $suratKeluar->tgl_selesai   = $suratKeluar->detailTugas->tgl_selesai;
            $suratKeluar->st_tempat     = $suratKeluar->detailTugas->tempat;
        }

        if ($suratKeluar->penerimaTugas && $suratKeluar->penerimaTugas->isNotEmpty()) {
            $suratKeluar->penerima_tugas = $suratKeluar->penerimaTugas->map(function ($pt) {
                return $pt->anggota ? $pt->anggota->nama . ' (NIA: ' . ($pt->anggota->nia ?? '-') . ')' : 'Anggota #' . $pt->anggota_id;
            })->all();
        }

        $jenisSurat     = JenisSurat::where('aktif', true)->get();
        $anggotas       = Anggota::orderBy('nama', 'asc')->get();
        $suratMasukList = SuratMasuk::orderBy('tgl_diterima', 'desc')->get();

        return view('surat-keluar.edit', [
            'suratKeluar'    => $suratKeluar,
            'jenisSurat'     => $jenisSurat,
            'anggotas'       => $anggotas,
            'suratMasukList' => $suratMasukList,
        ]);
    }

    /**
     * Hasilkan dan tampilkan / unduh PDF surat keluar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdf($id)
    {
        $suratKeluar = SuratKeluar::with([
            'jenis',
            'pembuat',
            'detailUndangan',
            'detailSph',
            'detailTugas',
            'penerimaTugas.anggota',
        ])->findOrFail($id);

        // Jika surat bertipe SK, arahkan ke modul PDF SK khusus
        if ($suratKeluar->jenis && $suratKeluar->jenis->kode === 'SK') {
            return redirect()->route('surat-keputusan.pdf', $id);
        }

        // Siapkan alias
        $suratKeluar->jenis_surat    = $suratKeluar->jenis ? $suratKeluar->jenis->kode : 'SU';
        $suratKeluar->tanggal_surat  = $suratKeluar->tgl_surat;

        if ($suratKeluar->detailUndangan) {
            $suratKeluar->acara_tanggal = $suratKeluar->detailUndangan->hari_tanggal;
            $suratKeluar->acara_waktu   = $suratKeluar->detailUndangan->waktu;
            $suratKeluar->acara_tempat  = $suratKeluar->detailUndangan->tempat;
            $suratKeluar->acara         = $suratKeluar->detailUndangan->acara;
            $suratKeluar->pakaian       = $suratKeluar->detailUndangan->pakaian;
        }
        if ($suratKeluar->detailSph) {
            $suratKeluar->dasar           = $suratKeluar->detailSph->dasar;
            $suratKeluar->isi_perintah    = $suratKeluar->detailSph->isi_perintah;
            $suratKeluar->untuk_satuan    = $suratKeluar->detailSph->untuk_satuan;
            $suratKeluar->jumlah_personel = $suratKeluar->detailSph->jumlah_personel;
            // Field terstruktur kegiatan SPH
            $suratKeluar->sph_hari_tanggal = $suratKeluar->detailSph->hari_tanggal;
            $suratKeluar->sph_waktu        = $suratKeluar->detailSph->waktu;
            $suratKeluar->sph_acara        = $suratKeluar->detailSph->acara;
            $suratKeluar->sph_tempat       = $suratKeluar->detailSph->tempat;
            $suratKeluar->sph_pakaian      = $suratKeluar->detailSph->pakaian;
        }
        if ($suratKeluar->detailTugas) {
            $suratKeluar->dasar         = $suratKeluar->detailTugas->dasar;
            $suratKeluar->uraian_tugas  = $suratKeluar->detailTugas->uraian_tugas;
            $suratKeluar->tgl_mulai     = $suratKeluar->detailTugas->tgl_mulai;
            $suratKeluar->tgl_selesai   = $suratKeluar->detailTugas->tgl_selesai;
            $suratKeluar->tempat_tugas  = $suratKeluar->detailTugas->tempat;
        }
        if ($suratKeluar->penerimaTugas && $suratKeluar->penerimaTugas->isNotEmpty()) {
            $suratKeluar->penerima_list = $suratKeluar->penerimaTugas->map(fn ($pt) =>
                $pt->anggota ? $pt->anggota->nama . ' (NIA: ' . ($pt->anggota->nia ?? '-') . ')' : 'Anggota #' . $pt->anggota_id
            )->all();
        }

        $profil = Profil::getProfil();

        $pdf = Pdf::loadView('surat-keluar.pdf', [
            'surat'       => $suratKeluar,
            'suratKeluar' => $suratKeluar,
            'profil'      => $profil,
        ])->setPaper([0, 0, 609.45, 935.43], 'portrait'); // Ukuran F4 / Folio (21.5cm x 33.0cm)

        $namaFile = 'surat-keluar-' . ($suratKeluar->nomor_surat
            ? str_replace('/', '-', $suratKeluar->nomor_surat)
            : 'draf-' . $id) . '.pdf';

        return $pdf->stream($namaFile);
    }

    /**
     * Simpan data surat keluar baru dan rincian jenis surat ke basis data dalam satu transaksi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\NomorSuratService  $nomorSuratService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, NomorSuratService $nomorSuratService)
    {
        // 1. Tentukan jenis surat yang dipilih (bisa berupa ID numerik atau kode 'SU'/'SPH'/'ST')
        $jenisInput = $request->input('jenis_surat_id', $request->input('jenis_surat'));
        if (is_numeric($jenisInput)) {
            $jenis = JenisSurat::findOrFail($jenisInput);
        } else {
            $kode = strtoupper($jenisInput ?? 'SU');
            $jenis = JenisSurat::where('kode', $kode)->firstOrFail();
        }

        // Normalisasi field umum
        $request->merge([
            'jenis_surat_id' => $jenis->id,
            'tgl_surat'      => $request->input('tgl_surat', $request->input('tanggal_surat')),
        ]);

        // 2. Siapkan aturan validasi dasar
        $rules = [
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'surat_masuk_id' => 'nullable|exists:surat_masuk,id',
            'perihal'        => 'required|string|max:255',
            'tgl_surat'      => 'required|date',
            'tujuan'         => 'nullable|string|max:150',
        ];

        $messages = [
            'jenis_surat_id.required'     => 'Jenis surat wajib dipilih.',
            'jenis_surat_id.exists'       => 'Jenis surat yang dipilih tidak terdaftar.',
            'perihal.required'            => 'Perihal surat wajib diisi.',
            'perihal.max'                 => 'Perihal surat tidak boleh lebih dari 255 karakter.',
            'tgl_surat.required'          => 'Tanggal surat wajib diisi.',
            'tgl_surat.date'              => 'Format tanggal surat tidak valid.',
            'hari_tanggal.required'       => 'Hari dan tanggal acara wajib diisi untuk Surat Undangan.',
            'isi_perintah.required'       => 'Isi perintah wajib diisi untuk Surat Perintah Harian.',
            'uraian_tugas.required'       => 'Uraian tugas wajib diisi untuk Surat Tugas.',
            'tgl_selesai.after_or_equal'  => 'Tanggal selesai tugas harus sama atau setelah tanggal mulai.',
            'penerima.required'           => 'Daftar penerima tugas wajib diisi minimal 1 anggota.',
            'penerima.min'                => 'Daftar penerima tugas wajib diisi minimal 1 anggota.',
            'penerima.*.exists'           => 'Anggota penerima tugas tidak terdaftar dalam pangkalan data.',
        ];

        // 3. Tambahkan aturan validasi spesifik berdasarkan kode jenis surat
        $kode = strtoupper($jenis->kode);

        if ($kode === 'SU') {
            $request->merge([
                'hari_tanggal' => $request->input('hari_tanggal', $request->input('acara_tanggal')),
                'waktu'        => $request->input('waktu', $request->input('acara_waktu')),
                'tempat'       => $request->input('tempat', $request->input('acara_tempat')),
                'acara'        => $request->input('acara', $request->input('acara_nama')),
                'pakaian'      => $request->input('pakaian', $request->input('acara_pakaian')),
            ]);

            $rules['hari_tanggal'] = 'required';
            $rules['waktu']        = 'nullable|string|max:100';
            $rules['tempat']       = 'nullable|string|max:200';
            $rules['acara']        = 'nullable|string|max:200';
            $rules['pakaian']      = 'nullable|string|max:100';
        } elseif ($kode === 'SPH') {
            // Normalisasi field SPH terstruktur
            $request->merge([
                'sph_hari_tanggal' => $request->input('sph_hari_tanggal', $request->input('hari_tanggal')),
                'sph_waktu'        => $request->input('sph_waktu', $request->input('waktu')),
                'sph_acara'        => $request->input('sph_acara', $request->input('acara')),
                'sph_tempat'       => $request->input('sph_tempat', $request->input('tempat')),
                'sph_pakaian'      => $request->input('sph_pakaian', $request->input('pakaian')),
            ]);
            $rules['dasar']            = 'nullable|string';
            $rules['isi_perintah']     = 'nullable|string';
            $rules['untuk_satuan']     = 'nullable|string|max:255';
            $rules['jumlah_personel']  = 'nullable|string|max:50';
            $rules['sph_hari_tanggal'] = 'nullable|string|max:150';
            $rules['sph_waktu']        = 'nullable|string|max:100';
            $rules['sph_acara']        = 'nullable|string|max:250';
            $rules['sph_tempat']       = 'nullable|string|max:200';
            $rules['sph_pakaian']      = 'nullable|string|max:100';
        } elseif ($kode === 'ST') {
            // Normalisasi penerima tugas
            $penerima = $request->input('penerima', $request->input('penerima_tugas'));
            if (!is_array($penerima) && !empty($penerima)) {
                $penerima = array_filter(array_map('trim', explode(',', $penerima)));
            }
            $request->merge([
                'dasar'     => $request->input('dasar', $request->input('st_dasar')),
                'tempat'    => $request->input('tempat', $request->input('st_tempat')),
                'penerima'  => $penerima,
            ]);

            $rules['dasar']        = 'nullable|string';
            $rules['uraian_tugas'] = 'required|string';
            $rules['tgl_mulai']    = 'nullable|date';
            $rules['tgl_selesai']  = 'nullable|date|after_or_equal:tgl_mulai';
            $rules['tempat']       = 'nullable|string|max:200';
            $rules['penerima']     = 'required|array|min:1';

            // Jika penerima berupa ID numerik anggota, validasi ke tabel anggota
            if (is_array($penerima) && count($penerima) > 0 && is_numeric(reset($penerima))) {
                $rules['penerima.*'] = 'exists:anggota,id';
            }
        }

        $validated = $request->validate($rules, $messages);

        $userId = Auth::id() ?? 1;

        // 4. Simpan induk dan detail dalam transaksi database
        $surat = DB::transaction(function () use ($validated, $kode, $userId, $jenis, $request) {
            $surat = SuratKeluar::create([
                'jenis_surat_id' => $validated['jenis_surat_id'],
                'surat_masuk_id' => $request->filled('surat_masuk_id') ? (int)$request->input('surat_masuk_id') : null,
                'tujuan'         => $validated['tujuan'] ?? null,
                'perihal'        => $validated['perihal'],
                'tgl_surat'      => $validated['tgl_surat'],
                'status'         => 'draf',
                'dibuat_oleh'    => $userId,
            ]);

            if ($kode === 'SU') {
                DetailUndangan::create([
                    'surat_keluar_id' => $surat->id,
                    'hari_tanggal'    => $validated['hari_tanggal'],
                    'waktu'           => $validated['waktu'] ?? null,
                    'tempat'          => $validated['tempat'] ?? null,
                    'acara'           => $validated['acara'] ?? null,
                    'pakaian'         => $validated['pakaian'] ?? null,
                ]);
            } elseif ($kode === 'SPH') {
                DetailSph::create([
                    'surat_keluar_id' => $surat->id,
                    'dasar'           => $validated['dasar'] ?? null,
                    'isi_perintah'    => $validated['isi_perintah'] ?? null,
                    'untuk_satuan'    => $validated['untuk_satuan'] ?? null,
                    'hari_tanggal'    => $validated['sph_hari_tanggal'] ?? null,
                    'waktu'           => $validated['sph_waktu'] ?? null,
                    'acara'           => $validated['sph_acara'] ?? null,
                    'tempat'          => $validated['sph_tempat'] ?? null,
                    'pakaian'         => $validated['sph_pakaian'] ?? null,
                    'jumlah_personel' => $validated['jumlah_personel'] ?? null,
                ]);
            } elseif ($kode === 'ST') {
                DetailTugas::create([
                    'surat_keluar_id' => $surat->id,
                    'dasar'           => $validated['dasar'] ?? null,
                    'uraian_tugas'    => $validated['uraian_tugas'],
                    'tgl_mulai'       => $validated['tgl_mulai'] ?? null,
                    'tgl_selesai'     => $validated['tgl_selesai'] ?? null,
                    'tempat'          => $validated['tempat'] ?? null,
                ]);

                if (!empty($validated['penerima']) && is_array($validated['penerima'])) {
                    foreach ($validated['penerima'] as $itemPenerima) {
                        $anggotaId = is_numeric($itemPenerima) ? (int)$itemPenerima : null;
                        if ($anggotaId) {
                            PenerimaTugas::create([
                                'surat_keluar_id' => $surat->id,
                                'anggota_id'      => $anggotaId,
                                'peran'           => 'Anggota Pelaksana',
                            ]);
                        }
                    }
                }
            }

            // Catat log aktivitas pembuatan draf
            LogAktivitas::create([
                'user_id' => $userId,
                'aksi'    => "Membuat draf surat keluar {$jenis->kode}: {$surat->perihal}",
                'waktu'   => Carbon::now(),
            ]);

            return $surat;
        });

        // 5. Cek opsi tindakan tombol formulir
        $actionType = $request->input('action_type', 'draft');

        if ($actionType === 'ajukan') {
            $surat->update(['status' => 'verifikasi']);
            LogAktivitas::create([
                'user_id' => $userId,
                'aksi'    => "Mengajukan verifikasi surat keluar: {$surat->perihal}",
                'waktu'   => Carbon::now(),
            ]);
            return redirect()->route('surat-keluar.show', $surat->id)
                ->with('success', 'Draf surat keluar berhasil disimpan dan diajukan untuk verifikasi pimpinan.');
        }

        if ($actionType === 'approve') {
            $userRole = strtolower(Auth::user()->role ?? '');
            if (in_array($userRole, ['pimpinan', 'admin_adminpers', 'kasatkorcab', 'super_admin', 'admin', 'kasetma'])) {
                $surat = $nomorSuratService->terbitkan($surat);
                LogAktivitas::create([
                    'user_id' => $userId,
                    'aksi'    => "Menyetujui dan menerbitkan nomor surat keluar: {$surat->nomor_surat}",
                    'waktu'   => Carbon::now(),
                ]);
                return redirect()->route('surat-keluar.show', $surat->id)
                    ->with('success', "Surat keluar berhasil disetujui dengan nomor resmi: {$surat->nomor_surat}");
            }
        }

        return redirect()->route('surat-keluar.show', $surat->id)
            ->with('success', 'Draf surat keluar berhasil disimpan.');
    }

    /**
     * Tampilkan lembar detail register surat keluar beserta detail jenis surat.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $suratKeluar = SuratKeluar::with([
            'jenis',
            'pembuat',
            'suratMasuk',
            'detailUndangan',
            'detailSph',
            'detailTugas',
            'penerimaTugas.anggota',
        ])->findOrFail($id);

        if ($suratKeluar->jenis && $suratKeluar->jenis->kode === 'SK') {
            return redirect()->route('surat-keputusan.show', $id);
        }

        // Alias atribut untuk kemudahan tampilan di view Blade
        $suratKeluar->jenis_surat = $suratKeluar->jenis ? $suratKeluar->jenis->kode : 'SU';
        $suratKeluar->tanggal_surat = $suratKeluar->tgl_surat;

        if ($suratKeluar->detailUndangan) {
            $suratKeluar->acara_tanggal = $suratKeluar->detailUndangan->hari_tanggal;
            $suratKeluar->acara_waktu = $suratKeluar->detailUndangan->waktu;
            $suratKeluar->acara_tempat = $suratKeluar->detailUndangan->tempat;
            $suratKeluar->acara_pakaian = $suratKeluar->detailUndangan->pakaian;
            $suratKeluar->acara_nama = $suratKeluar->detailUndangan->acara;
        }

        if ($suratKeluar->detailSph) {
            $suratKeluar->dasar            = $suratKeluar->detailSph->dasar;
            $suratKeluar->isi_perintah     = $suratKeluar->detailSph->isi_perintah;
            $suratKeluar->untuk_satuan     = $suratKeluar->detailSph->untuk_satuan;
            $suratKeluar->jumlah_personel  = $suratKeluar->detailSph->jumlah_personel;
            $suratKeluar->sph_hari_tanggal = $suratKeluar->detailSph->hari_tanggal;
            $suratKeluar->sph_waktu        = $suratKeluar->detailSph->waktu;
            $suratKeluar->sph_acara        = $suratKeluar->detailSph->acara;
            $suratKeluar->sph_tempat       = $suratKeluar->detailSph->tempat;
            $suratKeluar->sph_pakaian      = $suratKeluar->detailSph->pakaian;
        }

        if ($suratKeluar->detailTugas) {
            $suratKeluar->dasar = $suratKeluar->detailTugas->dasar;
            $suratKeluar->uraian_tugas = $suratKeluar->detailTugas->uraian_tugas;
            $suratKeluar->tgl_mulai = $suratKeluar->detailTugas->tgl_mulai;
            $suratKeluar->tgl_selesai = $suratKeluar->detailTugas->tgl_selesai;
            $suratKeluar->st_tempat = $suratKeluar->detailTugas->tempat;
        }

        if ($suratKeluar->penerimaTugas && $suratKeluar->penerimaTugas->isNotEmpty()) {
            $suratKeluar->penerima_tugas = $suratKeluar->penerimaTugas->map(function ($pt) {
                return $pt->anggota ? $pt->anggota->nama . ' (NIA: ' . ($pt->anggota->nia ?? '-') . ')' : 'Anggota #' . $pt->anggota_id;
            })->all();
        }

        return view('surat-keluar.show', [
            'suratKeluar' => $suratKeluar,
            'surat'       => $suratKeluar,
        ]);
    }

    /**
     * Ubah status surat keluar dari 'draf' menjadi 'verifikasi' untuk diajukan ke pimpinan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ajukan($id)
    {
        $surat = SuratKeluar::findOrFail($id);

        if ($surat->status !== 'draf') {
            return redirect()->route('surat-keluar.show', $surat->id)
                ->with('error', 'Hanya surat keluar berstatus draf yang dapat diajukan ke pimpinan.');
        }

        $surat->update(['status' => 'verifikasi']);

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Mengajukan verifikasi surat keluar: {$surat->perihal}",
            'waktu'   => Carbon::now(),
        ]);

        // Kirim notifikasi ke pimpinan/verifikator
        try {
            $pengaju = Auth::user()->name ?? 'Petugas';
            Notifikasi::kirimKeVerifikator([
                'dari_user_id' => Auth::id(),
                'tipe'         => 'surat_keluar_diajukan',
                'judul'        => 'Surat Keluar Perlu Verifikasi',
                'pesan'        => "{$pengaju} mengajukan surat keluar untuk diverifikasi:\n"
                               . "Perihal : {$surat->perihal}\n"
                               . "Jenis   : " . ($surat->jenis->nama ?? 'Surat Keluar') . "\n"
                               . "Tanggal : " . \Carbon\Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y') . "\n\n"
                               . "Harap segera diperiksa dan disetujui.",
                'url_tujuan'   => route('surat-keluar.show', $surat->id),
                'entitas_tipe' => 'surat_keluar',
                'entitas_id'   => $surat->id,
            ]);
        } catch (\Throwable $e) {}

        return redirect()->route('surat-keluar.show', $surat->id)
            ->with('success', 'Surat keluar berhasil diajukan untuk verifikasi pimpinan.');
    }

    /**
     * Setujui surat keluar dan terbitkan nomor surat resmi.
     * Khusus hanya dapat diakses oleh peran 'pimpinan' dan 'admin_adminpers'.
     *
     * @param  int  $id
     * @param  \App\Services\NomorSuratService  $nomorSuratService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setujui($id, NomorSuratService $nomorSuratService)
    {
        $user = Auth::user();
        $role = strtolower($user->role ?? '');

        // Hak akses pengesahan nomor: Kasatkorcab, Superadmin, Kasetma, Pimpinan, Adminpers
        if (!in_array($role, ['pimpinan', 'admin_adminpers', 'kasatkorcab', 'super_admin', 'admin', 'kasetma'])) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses untuk menyetujui surat keluar.');
        }

        $surat = SuratKeluar::findOrFail($id);

        // Jika surat telah disetujui sebelumnya, hindari penomoran ganda
        if ($surat->status === 'disetujui' && !empty($surat->nomor_surat)) {
            return redirect()->route('surat-keluar.show', $surat->id)
                ->with('info', "Surat keluar telah disetujui sebelumnya dengan nomor {$surat->nomor_surat}.");
        }

        // Panggil layanan penomoran otomatis
        $surat = $nomorSuratService->terbitkan($surat);

        // Catat ke log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Menyetujui dan menerbitkan nomor resmi surat keluar: {$surat->nomor_surat}",
            'waktu'   => Carbon::now(),
        ]);

        // Notifikasi ke pembuat surat bahwa surat telah disetujui
        try {
            if ($surat->dibuat_oleh) {
                Notifikasi::create([
                    'user_id'      => $surat->dibuat_oleh,
                    'dari_user_id' => Auth::id(),
                    'tipe'         => 'sk_disetujui',
                    'judul'        => 'Surat Keluar Disetujui: ' . $surat->nomor_surat,
                    'pesan'        => "Surat keluar yang Anda ajukan telah disetujui oleh pimpinan dan mendapatkan nomor resmi:\n"
                                   . "Nomor  : {$surat->nomor_surat}\n"
                                   . "Perihal: {$surat->perihal}\n"
                                   . "Surat siap untuk dicetak dan dikirim.",
                    'url_tujuan'   => route('surat-keluar.show', $surat->id),
                    'entitas_tipe' => 'surat_keluar',
                    'entitas_id'   => $surat->id,
                ]);
            }
        } catch (\Throwable $e) {}

        return redirect()->route('surat-keluar.show', $surat->id)
            ->with('success', "Surat keluar berhasil disetujui dengan nomor resmi: {$surat->nomor_surat}");
    }

    /**
     * Tandai surat keluar telah dikirimkan ke pihak penerima.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function kirim($id)
    {
        $surat = SuratKeluar::findOrFail($id);

        if ($surat->status !== 'disetujui') {
            return redirect()->route('surat-keluar.show', $surat->id)
                ->with('error', 'Surat keluar belum dapat dikirim karena belum disetujui oleh pimpinan.');
        }

        $surat->update(['status' => 'terkirim']);

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Mengirimkan surat keluar resmi nomor {$surat->nomor_surat}",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-keluar.show', $surat->id)
            ->with('success', 'Surat keluar berhasil ditandai sebagai terkirim.');
    }





    /**
     * Dukungan untuk pembaruan status surat keluar atau data form melalui permintaan PUT (RESTful).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  \App\Services\NomorSuratService  $nomorSuratService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id, NomorSuratService $nomorSuratService)
    {
        $status = $request->input('status');
        $generateNomor = $request->boolean('generate_nomor');

        // Aksi transisi status
        if ($status === 'verifikasi' || $request->input('action') === 'ajukan') {
            return $this->ajukan($id);
        }

        if ($status === 'disetujui' || $generateNomor || $request->input('action') === 'setujui') {
            return $this->setujui($id, $nomorSuratService);
        }

        if ($status === 'terkirim' || $request->input('action') === 'kirim') {
            return $this->kirim($id);
        }

        // Pembaruan data isian surat keluar dari form edit
        $surat = SuratKeluar::findOrFail($id);

        $validated = $request->validate([
            'tujuan'         => 'required|string|max:255',
            'tanggal_surat'  => 'required|date',
            'perihal'        => 'required|string',
            'surat_masuk_id' => 'nullable|exists:surat_masuk,id',
        ]);

        $surat->update([
            'tujuan'         => $validated['tujuan'],
            'tgl_surat'      => $validated['tanggal_surat'],
            'perihal'        => $validated['perihal'],
            'surat_masuk_id' => $request->filled('surat_masuk_id') ? (int)$request->input('surat_masuk_id') : null,
        ]);

        $kodeJenis = strtoupper($surat->jenis ? $surat->jenis->kode : 'SU');

        if ($kodeJenis === 'SU') {
            \App\Models\DetailUndangan::updateOrCreate(
                ['surat_keluar_id' => $surat->id],
                [
                    'hari_tanggal' => $request->input('acara_tanggal'),
                    'waktu'        => $request->input('acara_waktu'),
                    'tempat'       => $request->input('acara_tempat'),
                    'pakaian'      => $request->input('acara_pakaian'),
                    'acara'        => $request->input('acara_nama'),
                ]
            );
        } elseif ($kodeJenis === 'SPH') {
            \App\Models\DetailSph::updateOrCreate(
                ['surat_keluar_id' => $surat->id],
                [
                    'dasar'           => $request->input('dasar'),
                    'isi_perintah'    => $request->input('isi_perintah'),
                    'untuk_satuan'    => $request->input('untuk_satuan'),
                    'hari_tanggal'    => $request->input('sph_hari_tanggal', $request->input('hari_tanggal')),
                    'waktu'           => $request->input('sph_waktu', $request->input('waktu')),
                    'acara'           => $request->input('sph_acara', $request->input('acara')),
                    'tempat'          => $request->input('sph_tempat', $request->input('tempat')),
                    'pakaian'         => $request->input('sph_pakaian', $request->input('pakaian')),
                    'jumlah_personel' => $request->input('sph_jumlah_personel', $request->input('jumlah_personel')),
                ]
            );
        } elseif ($kodeJenis === 'ST') {
            \App\Models\DetailTugas::updateOrCreate(
                ['surat_keluar_id' => $surat->id],
                [
                    'dasar'        => $request->input('st_dasar'),
                    'uraian_tugas' => $request->input('uraian_tugas'),
                    'tgl_mulai'    => $request->input('tgl_mulai'),
                    'tgl_selesai'  => $request->input('tgl_selesai'),
                    'tempat'       => $request->input('st_tempat'),
                ]
            );
        }

        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Memperbarui data surat keluar ID #{$surat->id} ({$surat->perihal})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-keluar.show', $surat->id)
            ->with('success', 'Data rincian surat keluar berhasil diperbarui.');
    }

    /**
     * Hapus data surat keluar beserta rincian lampiran dan mandat penugasan terkait.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $nomorSurat = $surat->nomor_surat ?: ('Draf ID #' . $surat->id);
        $perihal = $surat->perihal;

        // Hapus detail terkait
        if ($surat->detailUndangan) $surat->detailUndangan->delete();
        if ($surat->detailSph) $surat->detailSph->delete();
        if ($surat->detailTugas) $surat->detailTugas->delete();
        $surat->penerimaTugas()->delete();

        $surat->delete();

        // Catat aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id() ?? 1,
            'aksi'    => "Menghapus data surat keluar: {$nomorSurat} ({$perihal})",
            'waktu'   => Carbon::now(),
        ]);

        return redirect()->route('surat-keluar.index')
            ->with('success', "Surat keluar {$nomorSurat} berhasil dihapus dari Buku Registrasi.");
    }
}
