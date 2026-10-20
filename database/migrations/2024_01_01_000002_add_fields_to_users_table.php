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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->unique()->after('email');
            $table->enum('role', [
                'super_admin',
                'admin_adminpers',
                'pimpinan',
                'admin_satkoryon',
                'anggota'
            ])->default('anggota')->after('username');
            $table->foreignId('satuan_id')->nullable()->after('role')->constrained('satuan')->nullOnDelete();
            $table->boolean('aktif')->default(true)->after('satuan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['satuan_id']);
            $table->dropColumn(['username', 'role', 'satuan_id', 'aktif']);
        });
    }
};
