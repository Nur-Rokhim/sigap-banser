<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SuratKeluar extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'surat_keluar';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jenis_surat_id',
        'no_urut',
        'bulan',
        'tahun',
        'nomor_surat',
        'tujuan',
        'perihal',
        'tgl_surat',
        'status',
        'file_pdf',
        'dibuat_oleh',
    ];

    /**
     * Atribut casting tipe data.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jenis_surat_id' => 'integer',
        'no_urut' => 'integer',
        'bulan' => 'integer',
        'tahun' => 'integer',
        'tgl_surat' => 'date',
        'dibuat_oleh' => 'integer',
    ];

    /**
     * Relasi ke jenis surat.
     */
    public function jenis(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    /**
     * Alias relasi ke jenis surat.
     */
    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    /**
     * Relasi ke pembuat surat keluar.
     */
    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Alias relasi ke pengguna pembuat surat keluar.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Relasi one-to-one ke detail surat undangan.
     */
    public function detailUndangan(): HasOne
    {
        return $this->hasOne(DetailUndangan::class, 'surat_keluar_id');
    }

    /**
     * Relasi one-to-one ke detail surat perintah harian (SPH).
     */
    public function detailSph(): HasOne
    {
        return $this->hasOne(DetailSph::class, 'surat_keluar_id');
    }

    /**
     * Relasi one-to-one ke detail surat tugas / mandat.
     */
    public function detailTugas(): HasOne
    {
        return $this->hasOne(DetailTugas::class, 'surat_keluar_id');
    }

    /**
     * Relasi ke daftar personil penerima tugas.
     */
    public function penerimaTugas(): HasMany
    {
        return $this->hasMany(PenerimaTugas::class, 'surat_keluar_id');
    }
}
