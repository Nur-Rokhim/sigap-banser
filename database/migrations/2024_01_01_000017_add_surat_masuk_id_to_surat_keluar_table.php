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
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->foreignId('surat_masuk_id')
                ->nullable()
                ->after('jenis_surat_id')
                ->constrained('surat_masuk')
                ->nullOnDelete()
                ->comment('Acuan surat masuk (pemohon dan perihal) yang menjadi dasar surat keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropForeign(['surat_masuk_id']);
            $table->dropColumn('surat_masuk_id');
        });
    }
};
