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
        Schema::table('disposisi', function (Blueprint $table) {
            $table->string('tujuan')->nullable()->after('surat_masuk_id')->comment('Pejabat / Satuan tujuan tindak lanjut disposisi');
            $table->string('sifat', 50)->nullable()->default('Biasa')->after('instruksi')->comment('Sifat disposisi: Biasa, Penting, Segera, Rahasia');
            $table->unsignedBigInteger('kepada_user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisi', function (Blueprint $table) {
            $table->dropColumn(['tujuan', 'sifat']);
            $table->unsignedBigInteger('kepada_user_id')->nullable(false)->change();
        });
    }
};
