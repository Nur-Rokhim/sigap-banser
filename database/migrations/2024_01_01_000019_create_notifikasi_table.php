<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel notifikasi untuk sistem pemberitahuan real-time SIGAP BANSER.
     * Notifikasi dikirim ke user tertentu atau semua verifikator terkait aksi surat.
     */
    public function up(): void
    {
        if (!Schema::hasTable('notifikasi')) {
            Schema::create('notifikasi', function (Blueprint $table) {
                $table->id();

                // Penerima notifikasi (NULL = siaran ke semua pimpinan/verifikator)
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

                // Pengirim / pemicu notifikasi (siapa yang melakukan aksi)
                $table->foreignId('dari_user_id')->nullable()->constrained('users')->nullOnDelete();

                // Jenis / kategori notifikasi
                $table->string('tipe', 60)->default('info');
                // Contoh tipe: surat_masuk_baru, surat_keluar_diajukan, disposisi_baru,
                //              sk_diajukan, sk_disetujui, perlu_respon, pengingat

                // Judul dan isi notifikasi
                $table->string('judul', 255);
                $table->text('pesan');

                // URL tujuan saat notifikasi diklik
                $table->string('url_tujuan', 512)->nullable();

                // Status baca
                $table->boolean('dibaca')->default(false);
                $table->timestamp('dibaca_pada')->nullable();

                // Referensi opsional ke entitas terkait
                $table->string('entitas_tipe', 60)->nullable();  // 'surat_masuk', 'surat_keluar', 'disposisi'
                $table->unsignedBigInteger('entitas_id')->nullable();

                $table->timestamps();

                // Index untuk performa query
                $table->index(['user_id', 'dibaca', 'created_at']);
                $table->index(['tipe', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
