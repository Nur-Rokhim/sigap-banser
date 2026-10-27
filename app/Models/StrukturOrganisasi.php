<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'struktur_organisasi';

    protected $fillable = [
        'kategori',
        'kode',
        'nama_posisi',
        'singkatan',
        'ketua',
        'wakil',
        'anggota',
        'keterangan',
        'urutan',
    ];

    protected $casts = [
        'anggota' => 'array',
        'urutan'  => 'integer',
    ];

    /**
     * Scope untuk masing-masing kategori
     */
    public function scopeKasatkorcab($query)
    {
        return $query->where('kategori', 'kasatkorcab');
    }

    public function scopeWakasatkorcab($query)
    {
        return $query->where('kategori', 'wakasatkorcab')->orderBy('urutan', 'asc')->orderBy('id', 'asc');
    }

    public function scopeKasetma($query)
    {
        return $query->where('kategori', 'kasetma');
    }

    public function scopeBiro($query)
    {
        return $query->where('kategori', 'biro')->orderBy('urutan', 'asc')->orderBy('id', 'asc');
    }

    public function scopeSatsus($query)
    {
        return $query->where('kategori', 'satsus')->orderBy('urutan', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Hitung total personel dalam unit (ketua + wakil + anggota)
     */
    public function getTotalPersonelAttribute(): int
    {
        $count = 0;
        if (!empty($this->ketua)) $count++;
        if (!empty($this->wakil)) $count++;
        if (is_array($this->anggota)) {
            $count += count(array_filter($this->anggota, fn($a) => !empty(trim($a))));
        }
        return $count;
    }

    /**
     * Inisialisasi struktur standar 17 unit jika tabel kosong
     */
    public static function seedDefaultStructure(): void
    {
        if (self::count() > 0) {
            return;
        }

        // Ambil nama dari profil jika ada
        $profil = Profil::first();
        $namaKasat = $profil->nama_kasatkorcab ?? 'Kasatkorcab BANSER';
        $namaKasetma = $profil->nama_kasetma ?? 'Kasetma Satkorcab';

        $defaults = [
            // 1. KASATKORCAB
            [
                'kategori'    => 'kasatkorcab',
                'kode'        => 'kasatkorcab',
                'nama_posisi' => 'KASATKORCAB',
                'singkatan'   => 'KASATKORCAB',
                'ketua'       => $namaKasat,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Kepala Satuan Koordinasi Cabang (Kasatkorcab) Banser Kabupaten Blitar',
                'urutan'      => 1,
            ],
            // 2. WAKASATKORCAB
            [
                'kategori'    => 'wakasatkorcab',
                'kode'        => 'wakasatkorcab_1',
                'nama_posisi' => 'Wakil Kasatkorcab I',
                'singkatan'   => 'WAKASATKORCAB I',
                'ketua'       => 'Wakil Kasatkorcab',
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Wakil Kepala Satkorcab Banser Kabupaten Blitar',
                'urutan'      => 2,
            ],
            // 3. KASETMA
            [
                'kategori'    => 'kasetma',
                'kode'        => 'kasetma',
                'nama_posisi' => 'KASETMA',
                'singkatan'   => 'KASETMA',
                'ketua'       => $namaKasetma,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Kepala Sekretariat Markas (Kasetma) Satkorcab Banser Kabupaten Blitar',
                'urutan'      => 3,
            ],

            // 4-10: 7 BIRO
            [
                'kategori'    => 'biro',
                'kode'        => 'biro_adminpers',
                'nama_posisi' => 'Biro Adminpers',
                'singkatan'   => 'ADMINPERS',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Biro Administrasi dan Personalia Satkorcab Banser',
                'urutan'      => 4,
            ],
            [
                'kategori'    => 'biro',
                'kode'        => 'biro_asrendiklat',
                'nama_posisi' => 'Biro Asrendiklat',
                'singkatan'   => 'ASRENDIKLAT',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Biro Perencanaan, Pendidikan dan Pelatihan Satkorcab Banser',
                'urutan'      => 5,
            ],
            [
                'kategori'    => 'biro',
                'kode'        => 'biro_kegiatan',
                'nama_posisi' => 'Biro Kegiatan',
                'singkatan'   => 'KEGIATAN',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Biro Operasional Kegiatan Satkorcab Banser',
                'urutan'      => 6,
            ],
            [
                'kategori'    => 'biro',
                'kode'        => 'biro_infokom',
                'nama_posisi' => 'Biro Informasi Dan Komunikasi',
                'singkatan'   => 'INFOKOM',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Biro Informasi dan Komunikasi Satkorcab Banser',
                'urutan'      => 7,
            ],
            [
                'kategori'    => 'biro',
                'kode'        => 'biro_kerjasama',
                'nama_posisi' => 'Biro Kerjasama',
                'singkatan'   => 'KERJASAMA',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Biro Kerjasama Antar Lembaga Satkorcab Banser',
                'urutan'      => 8,
            ],
            [
                'kategori'    => 'biro',
                'kode'        => 'biro_litbang',
                'nama_posisi' => 'Biro Penelitian dan Pengembangan',
                'singkatan'   => 'LITBANG',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Biro Penelitian dan Pengembangan Satkorcab Banser',
                'urutan'      => 9,
            ],
            [
                'kategori'    => 'biro',
                'kode'        => 'biro_logistik',
                'nama_posisi' => 'Biro Logistik',
                'singkatan'   => 'LOGISTIK',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Biro Perlengkapan dan Logistik Satkorcab Banser',
                'urutan'      => 10,
            ],

            // 11-17: 7 SATUAN KHUSUS (SATSUS)
            [
                'kategori'    => 'satsus',
                'kode'        => 'satsus_provost',
                'nama_posisi' => 'Satuan Khusus (SATSUS) PROVOST',
                'singkatan'   => 'PROVOST',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Satuan Penegak Disiplin, Ketertiban, dan Etika Anggota Banser',
                'urutan'      => 11,
            ],
            [
                'kategori'    => 'satsus',
                'kode'        => 'satsus_protokoler',
                'nama_posisi' => 'Satuan Khusus (SATSUS) PROTOKOLER',
                'singkatan'   => 'PROTOKOLER',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Satuan Pelayanan Keprotokolan, Acara Resmi, dan Pengawalan Ulama/Masyayikh',
                'urutan'      => 12,
            ],
            [
                'kategori'    => 'satsus',
                'kode'        => 'satsus_bagana',
                'nama_posisi' => 'Satuan Khusus (SATSUS) BAGANA',
                'singkatan'   => 'BAGANA',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Banser Tanggap Bencana: Evakuasi, Mitigasi, dan Penanganan Bencana Alam',
                'urutan'      => 13,
            ],
            [
                'kategori'    => 'satsus',
                'kode'        => 'satsus_basada',
                'nama_posisi' => 'Satuan Khusus (SATSUS) BASADA',
                'singkatan'   => 'BASADA',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Banser Husada: Bantuan Medis, Pertolongan Pertama, dan Kemanusiaan',
                'urutan'      => 14,
            ],
            [
                'kategori'    => 'satsus',
                'kode'        => 'satsus_balantas',
                'nama_posisi' => 'Satuan Khusus (SATSUS) BALANTAS',
                'singkatan'   => 'BALANTAS',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Banser Lalu Lintas: Pengaturan dan Pengamanan Lalu Lintas Jalan Raya',
                'urutan'      => 15,
            ],
            [
                'kategori'    => 'satsus',
                'kode'        => 'satsus_baritim',
                'nama_posisi' => 'Satuan Khusus (SATSUS) BARITIM',
                'singkatan'   => 'BARITIM',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Banser Maritim: Pengamanan dan Konservasi Wilayah Pesisir dan Perairan',
                'urutan'      => 16,
            ],
            [
                'kategori'    => 'satsus',
                'kode'        => 'satsus_balakar',
                'nama_posisi' => 'Satuan Khusus (SATSUS) BALAKAR',
                'singkatan'   => 'BALAKAR',
                'ketua'       => null,
                'wakil'       => null,
                'anggota'     => [],
                'keterangan'  => 'Banser Relawan Kebakaran: Pencegahan dan Pemadaman Bahaya Kebakaran',
                'urutan'      => 17,
            ],
        ];

        foreach ($defaults as $item) {
            self::create($item);
        }
    }
}
