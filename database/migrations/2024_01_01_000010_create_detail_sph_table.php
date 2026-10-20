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
        Schema::create('detail_sph', function (Blueprint $table) {
            $table->foreignId('surat_keluar_id')->primary()->constrained('surat_keluar')->cascadeOnDelete();
            $table->text('dasar')->nullable();
            $table->text('isi_perintah');
            $table->string('untuk_satuan', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sph');
    }
};
