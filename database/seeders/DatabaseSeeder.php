<?php

namespace Database\Seeders;

use App\Models\Satuan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Jenis Surat (SU, SPH, ST)
        $this->call([
            JenisSuratSeeder::class,
        ]);

        // 2. Buat Satuan Induk (Satkorcab) awal jika belum ada
        $satkorcab = Satuan::firstOrCreate(
            ['nama' => 'SATKORCAB BANSER'],
            [
                'tingkat' => 'Satkorcab',
                'parent_id' => null,
            ]
        );

        // 3. Buat default user super_admin
        User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@sigap-banser.id',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'satuan_id' => $satkorcab->id,
                'aktif' => true,
            ]
        );
    }
}
