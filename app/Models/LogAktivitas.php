<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitas extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'log_aktivitas';

    /**
     * Menonaktifkan updated_at otomatis karena hanya menggunakan kolom waktu.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'aksi',
        'waktu',
    ];

    /**
     * Atribut casting tipe data.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'waktu' => 'datetime',
    ];

    /**
     * Relasi ke user pelaku aktivitas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper untuk mencatat aktivitas secara ringkas.
     */
    public static function rekam(int $userId, string $aksi): self
    {
        return self::create([
            'user_id' => $userId,
            'aksi' => $aksi,
            'waktu' => now(),
        ]);
    }
}
