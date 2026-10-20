<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi tabel profil organisasi.
     */
    public function up(): void
    {
        Schema::create('profil', function (Blueprint $table) {
            $table->id();
            $table->string('satkorcab')->nullable()->comment('Nama Satkorcab BANSER');
            $table->string('kode_wilayah')->nullable()->comment('Kode Wilayah / Nomor Wilayah');
            $table->text('alamat_sekretariat')->nullable()->comment('Alamat Sekretariat');
            $table->string('nama_ketua_ansor')->nullable()->comment('Nama Ketua GP Ansor');
            $table->string('nama_kasatkorcab')->nullable()->comment('Nama Kasatkorcab BANSER');
            $table->text('alamat_kasatkorcab')->nullable()->comment('Alamat Kasatkorcab');
            $table->string('nama_kasetma')->nullable()->comment('Nama Kepala Sekretariat Markas (Kasetma)');
            $table->string('email')->nullable()->comment('Email resmi persuratan');
            
            // Upload Tanda Tangan
            $table->string('ttd_ketua_ansor')->nullable()->comment('Path berkas TTD Ketua Ansor');
            $table->string('ttd_kasatkorcab')->nullable()->comment('Path berkas TTD Kasatkorcab');
            $table->string('ttd_kasetma')->nullable()->comment('Path berkas TTD Kasetma');
            
            // Upload Stempel
            $table->string('stempel_ansor')->nullable()->comment('Path berkas stempel resmi Ansor');
            $table->string('stempel_banser')->nullable()->comment('Path berkas stempel resmi Banser');

            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil');
    }
};
