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

        // 4. Akun Kasatkorcab Selaku Verifikator
        User::firstOrCreate(
            ['username' => 'kasatkorcab'],
            [
                'name' => 'Kasatkorcab BANSER Blitar',
                'email' => 'kasatkorcab@sigap-banser.id',
                'password' => Hash::make('password'),
                'role' => 'kasatkorcab',
                'satuan_id' => $satkorcab->id,
                'aktif' => true,
            ]
        );

        // 5. Akun Kasetma Selaku User Edit
        User::firstOrCreate(
            ['username' => 'kasetma'],
            [
                'name' => 'Kasetma Satkorcab Blitar',
                'email' => 'kasetma@sigap-banser.id',
                'password' => Hash::make('password'),
                'role' => 'kasetma',
                'satuan_id' => $satkorcab->id,
                'aktif' => true,
            ]
        );

        // 6. Akun Ka. Biro Adminpers Selaku User Edit
        User::firstOrCreate(
            ['username' => 'adminpers'],
            [
                'name' => 'Ka. Biro Adminpers Satkorcab Blitar',
                'email' => 'adminpers@sigap-banser.id',
                'password' => Hash::make('password'),
                'role' => 'adminpers',
                'satuan_id' => $satkorcab->id,
                'aktif' => true,
            ]
        );
    }
}
