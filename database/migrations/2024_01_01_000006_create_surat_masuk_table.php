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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 100);
            $table->string('dari', 150);
            $table->string('perihal', 255);
            $table->date('tgl_surat')->nullable();
            $table->date('tgl_diterima');
            $table->string('file_scan', 255)->nullable();
            $table->enum('status', ['baru', 'didisposisi', 'selesai'])->default('baru');
            $table->foreignId('dicatat_oleh')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
