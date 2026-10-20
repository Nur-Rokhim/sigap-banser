<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Controller untuk Pusat Komando & Beranda (Dashboard) SIGAP BANSER.
 * Menyajikan statistik persuratan bulanan, daftar surat yang menunggu tindakan,
 * serta nomor resmi surat keluar terakhir yang diterbitkan per jenis surat.
 */
class DashboardController extends Controller
{
    /**
     * Tampilkan halaman beranda / dashboard SIGAP BANSER.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $now = Carbon::now();

        // 1. Hitung surat masuk pada bulan dan tahun berjalan
        $suratMasukBulanIni = SuratMasuk::whereMonth('tgl_diterima', $now->month)
            ->whereYear('tgl_diterima', $now->year)
            ->count();

        // 2. Hitung surat masuk berstatus 'baru' atau 'didisposisi' (menunggu disposisi / tindak lanjut pimpinan)
        $menungguDisposisi = SuratMasuk::whereIn('status', ['baru', 'didisposisi'])
            ->count();

        // 3. Hitung surat keluar pada bulan dan tahun berjalan
        $suratKeluarBulanIni = SuratKeluar::whereMonth('tgl_surat', $now->month)
            ->whereYear('tgl_surat', $now->year)
            ->count();

        // 4. Ambil daftar surat masuk terbaru yang memerlukan tindakan (status 'baru' atau 'didisposisi')
        $perluTindakan = SuratMasuk::whereIn('status', ['baru', 'didisposisi'])
            ->orderBy('tgl_diterima', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        // Siapkan atribut alias agar kompatibel dengan view Blade
        $perluTindakan->transform(function ($item) {
            $item->nomor_surat = $item->nomor_surat ?? $item->no_surat ?? '-';
            $item->pengirim = $item->pengirim ?? $item->dari ?? '-';
            $item->tanggal_diterima = $item->tanggal_diterima ?? $item->tgl_diterima ?? null;
            return $item;
        });

        // 5. Ambil nomor terakhir terbit per jenis surat (SU, SPH, ST)
        $nomorTerakhir = [
            'SU'  => 'Belum ada nomor terbit',
            'SPH' => 'Belum ada nomor terbit',
            'ST'  => 'Belum ada nomor terbit',
        ];

        $tanggalNomorTerakhir = [
            'SU'  => 'Tahun ' . $now->year,
            'SPH' => 'Tahun ' . $now->year,
            'ST'  => 'Tahun ' . $now->year,
        ];

        // Ambil jenis surat yang terdaftar di basis data
        $daftarJenis = JenisSurat::all();

        foreach ($daftarJenis as $jenis) {
            $kode = strtoupper($jenis->kode);

            $suratTerakhir = SuratKeluar::where('jenis_surat_id', $jenis->id)
                ->whereNotNull('nomor_surat')
                ->whereIn('status', ['disetujui', 'terkirim'])
                ->orderBy('tahun', 'desc')
                ->orderBy('no_urut', 'desc')
                ->first();

            if ($suratTerakhir) {
                $nomorTerakhir[$kode] = $suratTerakhir->nomor_surat;
                $tanggalNomorTerakhir[$kode] = Carbon::parse($suratTerakhir->tgl_surat)->translatedFormat('d F Y');
            }
        }

        $viewName = view()->exists('dashboard') ? 'dashboard' : 'beranda';

        return view($viewName, [
            'suratMasukBulanIni'     => $suratMasukBulanIni,
            'totalSuratMasukBulanIni'=> $suratMasukBulanIni,
            'menungguDisposisi'      => $menungguDisposisi,
            'suratKeluarBulanIni'    => $suratKeluarBulanIni,
            'totalSuratKeluarBulanIni'=> $suratKeluarBulanIni,
            'perluTindakan'          => $perluTindakan,
            'nomorTerakhir'          => $nomorTerakhir,
            'tanggalNomorTerakhir'   => $tanggalNomorTerakhir,
        ]);
    }
}
