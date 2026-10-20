<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat');
            $table->unsignedInteger('no_urut')->nullable();
            $table->unsignedTinyInteger('bulan')->nullable();
            $table->unsignedSmallInteger('tahun')->nullable();
            $table->string('nomor_surat', 60)->nullable()->unique();
            $table->string('tujuan', 150)->nullable();
            $table->string('perihal', 255);
            $table->date('tgl_surat');
            $table->enum('status', ['draf', 'verifikasi', 'disetujui', 'terkirim'])->default('draf');
            $table->string('file_pdf', 255)->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
