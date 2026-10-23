<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSk extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'detail_sk';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'surat_keluar_id';

    /**
     * Primary key bukan auto-increment.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tipe primary key.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'surat_keluar_id',
        'tentang',
        'menimbang',
        'mengingat',
        'memperhatikan',
        'diktum_pertama',
        'diktum_kedua',
        'diktum_ketiga',
        'diktum_keempat',
        'diktum_kelima',
        'ditetapkan_di',
        'tgl_hijriyah',
        'tembusan',
        'lampiran_sk',
    ];

    /**
     * Atribut casting tipe data.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'surat_keluar_id' => 'integer',
    ];

    /**
     * Relasi ke surat keluar induk.
     */
    public function suratKeluar(): BelongsTo
    {
        return $this->belongsTo(SuratKeluar::class, 'surat_keluar_id');
    }
}
