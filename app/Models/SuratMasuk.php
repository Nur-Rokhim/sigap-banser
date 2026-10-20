<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratMasuk extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'surat_masuk';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'no_surat',
        'dari',
        'perihal',
        'tgl_surat',
        'tgl_diterima',
        'file_scan',
        'status',
        'dicatat_oleh',
    ];

    /**
     * Atribut casting tipe data.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_surat' => 'date',
        'tgl_diterima' => 'date',
        'dicatat_oleh' => 'integer',
    ];

    /**
     * Relasi ke pengguna pencatat surat masuk.
     */
    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    /**
     * Relasi ke pengguna yang mencatat surat masuk ini.
     */
    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    /**
     * Alias relasi ke pengguna pencatat surat masuk.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    /**
     * Relasi ke riwayat disposisi surat masuk.
     */
    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id');
    }
}
