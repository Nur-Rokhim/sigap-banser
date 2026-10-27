<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Controller Laporan Rekap Persuratan SIGAP BANSER.
 * Menghasilkan laporan statistik bulanan/tahunan surat masuk dan keluar
 * serta nomor surat yang telah diterbitkan per jenis.
 */
class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan rekap persuratan.
     */
    public function index(Request $request)
    {
        $tahun  = (int)$request->input('tahun', Carbon::now()->year);
        $bulan  = $request->input('bulan', ''); // kosong = semua bulan
        $jenis  = $request->input('jenis', '');

        // Rentang tahun yang tersedia (dari data terlama hingga sekarang + 1)
        $tahunMin  = SuratKeluar::min('tahun') ?? Carbon::now()->year;
        $tahunMin  = min($tahunMin, (int)SuratMasuk::selectRaw('YEAR(tgl_diterima) as y')->orderBy('y')->value('y') ?? Carbon::now()->year);
        $listTahun = range(Carbon::now()->year + 1, $tahunMin);

        $daftarJenis = JenisSurat::where('aktif', true)->get();

        // ── Surat Masuk ──────────────────────────────────────────
        $qMasuk = SuratMasuk::whereYear('tgl_diterima', $tahun);
        if ($bulan !== '') {
            $qMasuk->whereMonth('tgl_diterima', (int)$bulan);
        }

        $totalMasuk = $qMasuk->count();
        $masukBaru  = (clone $qMasuk)->where('status', 'baru')->count();
        $masukDisposisi = (clone $qMasuk)->where('status', 'didisposisi')->count();
        $masukSelesai   = (clone $qMasuk)->where('status', 'selesai')->count();

        // Bulanan surat masuk
        $masukPerBulan = [];
        for ($b = 1; $b <= 12; $b++) {
            $masukPerBulan[$b] = SuratMasuk::whereYear('tgl_diterima', $tahun)
                ->whereMonth('tgl_diterima', $b)->count();
        }

        // ── Surat Keluar ──────────────────────────────────────────
        $qKeluar = SuratKeluar::whereYear('tgl_surat', $tahun);
        if ($bulan !== '') {
            $qKeluar->whereMonth('tgl_surat', (int)$bulan);
        }
        if ($jenis !== '') {
            $jenisObj = JenisSurat::where('kode', $jenis)->first();
            if ($jenisObj) {
                $qKeluar->where('jenis_surat_id', $jenisObj->id);
            }
        }

        $totalKeluar  = $qKeluar->count();
        $keluarDraf   = (clone $qKeluar)->where('status', 'draf')->count();
        $keluarVerifikasi = (clone $qKeluar)->where('status', 'verifikasi')->count();
        $keluarDisetujui  = (clone $qKeluar)->where('status', 'disetujui')->count();
        $keluarTerkirim   = (clone $qKeluar)->where('status', 'terkirim')->count();

        // Keluar per jenis
        $keluarPerJenis = [];
        foreach ($daftarJenis as $j) {
            $q = SuratKeluar::whereYear('tgl_surat', $tahun)->where('jenis_surat_id', $j->id);
            if ($bulan !== '') {
                $q->whereMonth('tgl_surat', (int)$bulan);
            }
            $keluarPerJenis[$j->kode] = [
                'nama'      => $j->nama,
                'total'     => $q->count(),
                'bernomor'  => (clone $q)->whereNotNull('nomor_surat')->count(),
            ];
        }

        // Bulanan surat keluar
        $keluarPerBulan = [];
        for ($b = 1; $b <= 12; $b++) {
            $keluarPerBulan[$b] = SuratKeluar::whereYear('tgl_surat', $tahun)
                ->whereMonth('tgl_surat', $b)->count();
        }

        // Daftar surat keluar terakhir bernomor
        $suratTerakhir = SuratKeluar::with('jenis')
            ->whereYear('tgl_surat', $tahun)
            ->whereNotNull('nomor_surat')
            ->when($bulan !== '', fn($q) => $q->whereMonth('tgl_surat', (int)$bulan))
            ->when($jenis !== '', fn($q) => $q->whereHas('jenis', fn($j) => $j->where('kode', $jenis)))
            ->orderBy('tahun', 'desc')
            ->orderBy('no_urut', 'desc')
            ->limit(20)
            ->get();

        $namaBulan = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
        ];

        return view('laporan.index', compact(
            'tahun', 'bulan', 'jenis',
            'listTahun', 'daftarJenis', 'namaBulan',
            'totalMasuk', 'masukBaru', 'masukDisposisi', 'masukSelesai', 'masukPerBulan',
            'totalKeluar', 'keluarDraf', 'keluarVerifikasi', 'keluarDisetujui', 'keluarTerkirim',
            'keluarPerJenis', 'keluarPerBulan',
            'suratTerakhir'
        ));
    }

    /**
     * Cetak laporan rekap ke PDF.
     */
    public function pdf(Request $request)
    {
        $tahun = (int)$request->input('tahun', Carbon::now()->year);
        $bulan = $request->input('bulan', '');
        $jenis = $request->input('jenis', '');

        $daftarJenis = JenisSurat::where('aktif', true)->get();

        $qMasuk = SuratMasuk::whereYear('tgl_diterima', $tahun);
        if ($bulan !== '') { $qMasuk->whereMonth('tgl_diterima', (int)$bulan); }
        $totalMasuk  = $qMasuk->count();
        $masukBaru   = (clone $qMasuk)->where('status', 'baru')->count();
        $masukSelesai= (clone $qMasuk)->where('status', 'selesai')->count();

        $qKeluar = SuratKeluar::whereYear('tgl_surat', $tahun);
        if ($bulan !== '') { $qKeluar->whereMonth('tgl_surat', (int)$bulan); }
        if ($jenis !== '') {
            $jenisObj = JenisSurat::where('kode', $jenis)->first();
            if ($jenisObj) { $qKeluar->where('jenis_surat_id', $jenisObj->id); }
        }
        $totalKeluar     = $qKeluar->count();
        $keluarBernomor  = (clone $qKeluar)->whereNotNull('nomor_surat')->count();

        $keluarPerJenis = [];
        foreach ($daftarJenis as $j) {
            $q = SuratKeluar::whereYear('tgl_surat', $tahun)->where('jenis_surat_id', $j->id);
            if ($bulan !== '') { $q->whereMonth('tgl_surat', (int)$bulan); }
            $keluarPerJenis[$j->kode] = [
                'nama'     => $j->nama,
                'total'    => $q->count(),
                'bernomor' => (clone $q)->whereNotNull('nomor_surat')->count(),
            ];
        }

        $suratKeluar = SuratKeluar::with('jenis')
            ->whereYear('tgl_surat', $tahun)
            ->when($bulan !== '', fn($q) => $q->whereMonth('tgl_surat', (int)$bulan))
            ->when($jenis !== '', fn($q) => $q->whereHas('jenis', fn($j) => $j->where('kode', $jenis)))
            ->orderBy('tahun', 'desc')
            ->orderBy('no_urut', 'desc')
            ->get();

        $suratMasuk = SuratMasuk::whereYear('tgl_diterima', $tahun)
            ->when($bulan !== '', fn($q) => $q->whereMonth('tgl_diterima', (int)$bulan))
            ->orderBy('tgl_diterima', 'desc')
            ->get();

        $namaBulan = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
        ];

        $judulBulan = $bulan !== '' ? ($namaBulan[(int)$bulan] ?? '') . ' ' : '';
        $judulPeriode = $judulBulan . $tahun;
        $profil = \App\Models\Profil::getProfil();

        $pdf = Pdf::loadView('laporan.pdf', compact(
            'tahun', 'bulan', 'jenis', 'judulPeriode',
            'daftarJenis', 'namaBulan', 'profil',
            'totalMasuk', 'masukBaru', 'masukSelesai',
            'totalKeluar', 'keluarBernomor',
            'keluarPerJenis', 'suratKeluar', 'suratMasuk'
        ))->setPaper([0, 0, 609.45, 935.43], 'portrait');

        return $pdf->stream('Laporan-Persuratan-' . str_replace(' ', '-', $judulPeriode) . '.pdf');
    }
}
