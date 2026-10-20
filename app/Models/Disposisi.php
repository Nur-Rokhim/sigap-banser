<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposisi extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'disposisi';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'surat_masuk_id',
        'tujuan',
        'dari_user_id',
        'kepada_user_id',
        'instruksi',
        'sifat',
        'status',
    ];

    /**
     * Atribut casting tipe data.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'surat_masuk_id' => 'integer',
        'dari_user_id' => 'integer',
        'kepada_user_id' => 'integer',
    ];

    /**
     * Relasi ke surat masuk yang didisposisikan.
     */
    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    /**
     * Relasi ke user pemberi disposisi (atasan / pimpinan).
     */
    public function dariUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    /**
     * Relasi ke user penerima disposisi.
     */
    public function kepadaUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepada_user_id');
    }

    /**
     * Dapatkan nama/label penerima disposisi secara konsisten.
     */
    public function getPenerimaLabelAttribute(): string
    {
        if (!empty($this->tujuan)) {
            return $this->tujuan;
        }

        return $this->kepadaUser ? $this->kepadaUser->name : 'Penerima Disposisi';
    }

    /**
     * Daftar referensi resmi tujuan tindak lanjut disposisi surat masuk.
     *
     * @return array<string, array<string>>
     */
    public static function daftarTujuan(): array
    {
        return [
            'Komando Teritorial Keseluruhan' => [
                'Seluruh Kasatkoryon Se- Kab. Blitar',
            ],
            'Satuan Khusus (Kasatsus)' => [
                'Kasatsus Provost',
                'Kasatsus Protokoler',
                'Kasatsus Bagana',
                'Kasatsus Basada',
                'Kasatsus Baritim',
                'Kasatsus Balakar',
                'Kasatsus Balantas',
            ],
            'Biro-Biro Satkorcab' => [
                'Ka. Biro Adminpers',
                'Ka. Biro Asrendiklat',
                'Ka. Biro Giat',
                'Ka. Biro Logistik',
            ],
            'Kasatkoryon Kecamatan di Area Kab. Blitar' => [
                'Kasatkoryon Kec. Bakung',
                'Kasatkoryon Kec. Binangun',
                'Kasatkoryon Kec. Doko',
                'Kasatkoryon Kec. Gandusari',
                'Kasatkoryon Kec. Garum',
                'Kasatkoryon Kec. Kademangan',
                'Kasatkoryon Kec. Kanigoro',
                'Kasatkoryon Kec. Kesamben',
                'Kasatkoryon Kec. Nglegok',
                'Kasatkoryon Kec. Panggungrejo',
                'Kasatkoryon Kec. Ponggok',
                'Kasatkoryon Kec. Sanankulon',
                'Kasatkoryon Kec. Selorejo',
                'Kasatkoryon Kec. Selopuro',
                'Kasatkoryon Kec. Srengat',
                'Kasatkoryon Kec. Sutojayan',
                'Kasatkoryon Kec. Talun',
                'Kasatkoryon Kec. Udanawu',
                'Kasatkoryon Kec. Wates',
                'Kasatkoryon Kec. Wlingi',
                'Kasatkoryon Kec. Wonodadi',
                'Kasatkoryon Kec. Wonotirto',
            ],
        ];
    }
}
