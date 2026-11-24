<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Profil;
use App\Models\Satuan;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Tampilkan Halaman Utama / Landing Page Publik SIGAP BANSER.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $profil = Profil::first();

        // Hitung statistik untuk showcase di landing page
        $totalSm = SuratMasuk::count();
        $totalSk = SuratKeluar::count();
        $totalAnggota = Anggota::count();
        $totalSatuan = Satuan::where('tingkat', 'Satkoryon')->count();
        if ($totalSatuan === 0) {
            $totalSatuan = 22; // 22 Kecamatan se-Kabupaten Blitar
        }

        $stats = [
            'total_surat_masuk'  => $totalSm,
            'total_surat_keluar' => $totalSk,
            'total_arsip'        => $totalSm + $totalSk,
            'total_anggota'      => $totalAnggota,
            'total_satuan'       => $totalSatuan,
        ];

        return view('landing.index', compact('profil', 'stats'));
    }
}
