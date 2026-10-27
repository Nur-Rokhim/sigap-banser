<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi tabel struktur organisasi Satkorcab Banser.
     */
    public function up(): void
    {
        Schema::create('struktur_organisasi', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 50)->comment('kasatkorcab, wakasatkorcab, kasetma, biro, satsus');
            $table->string('kode', 100)->nullable()->comment('Kode unik posisi/satuan');
            $table->string('nama_posisi')->comment('Nama resmi posisi atau unit');
            $table->string('singkatan', 50)->nullable()->comment('Singkatan / akronim unit');
            $table->string('ketua')->nullable()->comment('Nama Kasatkorcab / Kasetma / Ketua Biro / Ketua Satsus');
            $table->string('wakil')->nullable()->comment('Nama Wakil (khusus Satsus atau jabatan lain)');
            $table->json('anggota')->nullable()->comment('Daftar nama anggota (array JSON)');
            $table->text('keterangan')->nullable()->comment('Deskripsi tugas / catatan');
            $table->integer('urutan')->default(0)->comment('Urutan penampilan');
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('struktur_organisasi');
    }
};
