<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurat = [
            [
                'kode' => 'SU',
                'nama' => 'Surat Undangan',
                'aktif' => true,
            ],
            [
                'kode' => 'SPH',
                'nama' => 'Surat Perintah Harian',
                'aktif' => true,
            ],
            [
                'kode' => 'ST',
                'nama' => 'Surat Tugas / Mandat',
                'aktif' => true,
            ],
        ];

        foreach ($jenisSurat as $data) {
            JenisSurat::updateOrCreate(
                ['kode' => $data['kode']],
                $data
            );
        }
    }
}
