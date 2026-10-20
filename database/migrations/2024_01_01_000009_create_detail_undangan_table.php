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
        Schema::create('detail_undangan', function (Blueprint $table) {
            $table->foreignId('surat_keluar_id')->primary()->constrained('surat_keluar')->cascadeOnDelete();
            $table->date('hari_tanggal');
            $table->time('waktu')->nullable();
            $table->string('tempat', 200)->nullable();
            $table->string('acara', 200)->nullable();
            $table->string('pakaian', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_undangan');
    }
};
