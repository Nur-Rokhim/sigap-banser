<?php

namespace App\Services;

use App\Models\NomorCounter;
use App\Models\SuratKeluar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Layanan penomoran otomatis surat keluar SIGAP BANSER Satkorcab Kab. Blitar.
 * Format penomoran baku: {no_urut}/SKC-{kode_jenis}/{bulan_romawi}/{tahun}
 * Penomoran diproses dalam transaksi database dengan lockForUpdate untuk mencegah duplikasi.
 */
class NomorSuratService
{
    private const ROMAWI = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
        7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
    ];

    /**
     * Terbitkan nomor resmi untuk surat keluar yang disetujui.
     *
     * @param  SuratKeluar  $surat
     * @return SuratKeluar
     */
    public function terbitkan(SuratKeluar $surat): SuratKeluar
    {
        return DB::transaction(function () use ($surat) {
            $surat = SuratKeluar::with('jenis')->whereKey($surat->id)->lockForUpdate()->firstOrFail();
            if ($surat->nomor_surat) {
                return $surat;
            }
            $tgl = Carbon::parse($surat->tgl_surat);
            NomorCounter::firstOrCreate(
                ['jenis_surat_id' => $surat->jenis_surat_id, 'tahun' => $tgl->year],
                ['nomor_terakhir' => 0]
            );
            $counter = NomorCounter::where('jenis_surat_id', $surat->jenis_surat_id)
                ->where('tahun', $tgl->year)->lockForUpdate()->first();
            $counter->increment('nomor_terakhir');
            $surat->update([
                'no_urut'     => $counter->nomor_terakhir,
                'bulan'       => $tgl->month,
                'tahun'       => $tgl->year,
                'nomor_surat' => sprintf('%03d/SKC-%s/%s/%d',
                    $counter->nomor_terakhir, $surat->jenis->kode,
                    self::ROMAWI[$tgl->month], $tgl->year),
                'status'      => 'disetujui',
            ]);
            return $surat->refresh();
        });
    }
}
