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
        Schema::create('detail_tugas', function (Blueprint $table) {
            $table->foreignId('surat_keluar_id')->primary()->constrained('surat_keluar')->cascadeOnDelete();
            $table->text('dasar')->nullable();
            $table->text('uraian_tugas');
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->string('tempat', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_tugas');
    }
};
