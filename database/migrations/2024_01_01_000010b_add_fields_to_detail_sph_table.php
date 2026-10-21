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
        Schema::table('detail_sph', function (Blueprint $table) {
            // Rincian kegiatan SPH (sama strukturnya dengan SU tapi konteks instruksi)
            $table->string('hari_tanggal', 150)->nullable()->after('untuk_satuan');
            $table->string('waktu', 100)->nullable()->after('hari_tanggal');
            $table->string('acara', 250)->nullable()->after('waktu');
            $table->string('tempat', 200)->nullable()->after('acara');
            $table->string('pakaian', 100)->nullable()->after('tempat');
            $table->string('jumlah_personel', 50)->nullable()->after('pakaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_sph', function (Blueprint $table) {
            $table->dropColumn(['hari_tanggal', 'waktu', 'acara', 'tempat', 'pakaian', 'jumlah_personel']);
        });
    }
};
