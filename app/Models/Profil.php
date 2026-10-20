<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profil extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'profil';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'satkorcab',
        'kode_wilayah',
        'alamat_sekretariat',
        'nama_ketua_ansor',
        'nama_kasatkorcab',
        'alamat_kasatkorcab',
        'nama_kasetma',
        'email',
        'ttd_ketua_ansor',
        'ttd_kasatkorcab',
        'ttd_kasetma',
        'stempel_ansor',
        'stempel_banser',
    ];

    /**
     * Dapatkan instance profil tunggal organisasi (singleton record).
     *
     * @return self
     */
    public static function getProfil(): self
    {
        return self::firstOrCreate([], [
            'satkorcab'          => 'SATKORCAB BANSER KABUPATEN BLITAR',
            'kode_wilayah'       => 'X-28',
            'alamat_sekretariat' => 'Jl. Ahmad Yani No. 12, Blitar',
            'nama_ketua_ansor'   => 'H. Hermawan, S.Pd.I',
            'nama_kasatkorcab'   => 'Nur Rokhim',
            'alamat_kasatkorcab' => 'Kabupaten Blitar',
            'nama_kasetma'       => 'Sahabat Kasetma',
            'email'              => 'adminpers.satkorcab@banser-blitar.id',
        ]);
    }

    /**
     * Helper untuk mendapatkan URL berkas di storage publik.
     *
     * @param  string|null  $fieldOrPath
     * @return string|null
     */
    public function getFileUrl(?string $fieldOrPath): ?string
    {
        if (!$fieldOrPath) {
            return null;
        }

        $allowedFields = ['ttd_ketua_ansor', 'ttd_kasatkorcab', 'ttd_kasetma', 'stempel_ansor', 'stempel_banser'];

        // Jika parameter adalah nama kolom/field profil
        if (in_array($fieldOrPath, $allowedFields)) {
            return !empty($this->$fieldOrPath) ? route('profil.berkas', ['field' => $fieldOrPath]) : null;
        }

        // Jika path berkas yang tersimpan diberikan (misal: "profil/xxx.png")
        foreach ($allowedFields as $field) {
            if ($this->$field === $fieldOrPath) {
                return route('profil.berkas', ['field' => $field]);
            }
        }

        if (str_starts_with($fieldOrPath, 'http://') || str_starts_with($fieldOrPath, 'https://')) {
            return $fieldOrPath;
        }

        return asset('storage/' . ltrim($fieldOrPath, '/'));
    }
}
