<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Pastikan jenis surat SK (Surat Keputusan) terdaftar di tabel jenis_surat
        if (Schema::hasTable('jenis_surat')) {
            $exists = DB::table('jenis_surat')->where('kode', 'SK')->exists();
            if (!$exists) {
                DB::table('jenis_surat')->insert([
                    'kode'       => 'SK',
                    'nama'       => 'Surat Keputusan',
                    'aktif'      => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 2. Buat tabel rincian detail_sk untuk menyimpan konsiderans dan diktum SK
        if (!Schema::hasTable('detail_sk')) {
            Schema::create('detail_sk', function (Blueprint $table) {
                $table->foreignId('surat_keluar_id')->primary()->constrained('surat_keluar')->cascadeOnDelete();
                $table->text('tentang')->nullable();
                $table->text('menimbang')->nullable();
                $table->text('mengingat')->nullable();
                $table->text('memperhatikan')->nullable();
                $table->text('diktum_pertama')->nullable();
                $table->text('diktum_kedua')->nullable();
                $table->text('diktum_ketiga')->nullable();
                $table->text('diktum_keempat')->nullable();
                $table->text('diktum_kelima')->nullable();
                $table->string('ditetapkan_di', 100)->default('Blitar');
                $table->string('tgl_hijriyah', 100)->nullable();
                $table->text('tembusan')->nullable();
                $table->longText('lampiran_sk')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sk');
    }
};
