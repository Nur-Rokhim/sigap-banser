<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Satuan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'satuan';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'tingkat',
        'parent_id',
    ];

    /**
     * Atribut casting tipe data.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'parent_id' => 'integer',
    ];

    /**
     * Relasi ke satuan induk (parent satuan).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'parent_id');
    }

    /**
     * Relasi ke satuan bawahan (anak satuan).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Satuan::class, 'parent_id');
    }

    /**
     * Relasi ke daftar user dalam satuan ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'satuan_id');
    }

    /**
     * Relasi ke daftar anggota Banser dalam satuan ini.
     */
    public function anggota(): HasMany
    {
        return $this->hasMany(Anggota::class, 'satuan_id');
    }
}
