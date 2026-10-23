<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'dari_user_id',
        'tipe',
        'judul',
        'pesan',
        'url_tujuan',
        'dibaca',
        'dibaca_pada',
        'entitas_tipe',
        'entitas_id',
    ];

    protected $casts = [
        'user_id'       => 'integer',
        'dari_user_id'  => 'integer',
        'dibaca'        => 'boolean',
        'dibaca_pada'   => 'datetime',
        'entitas_id'    => 'integer',
        'created_at'    => 'datetime',
    ];

    /**
     * Relasi ke penerima notifikasi.
     */
    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke pengirim / pemicu notifikasi.
     */
    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    /**
     * Scope hanya notifikasi yang belum dibaca.
     */
    public function scopeBelumDibaca($query)
    {
        return $query->where('dibaca', false);
    }

    /**
     * Scope untuk user tertentu (termasuk broadcast ke semua / user_id null).
     */
    public function scopeUntukUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        });
    }

    /**
     * Helper: buat notifikasi untuk semua pimpinan/verifikator.
     *
     * @param array $data   ['tipe', 'judul', 'pesan', 'url_tujuan', 'dari_user_id', 'entitas_tipe', 'entitas_id']
     */
    public static function kirimKeVerifikator(array $data): void
    {
        $verifikatorRoles = ['super_admin', 'kasatkorcab', 'kasetma', 'pimpinan', 'admin_adminpers'];
        $users = User::whereIn('role', $verifikatorRoles)->where('aktif', true)->get();

        foreach ($users as $user) {
            static::create(array_merge($data, ['user_id' => $user->id]));
        }
    }

    /**
     * Helper: buat notifikasi untuk semua admin/operator.
     */
    public static function kirimKeAdmin(array $data): void
    {
        $adminRoles = ['super_admin', 'admin', 'admin_adminpers', 'adminpers'];
        $users = User::whereIn('role', $adminRoles)->where('aktif', true)->get();

        foreach ($users as $user) {
            static::create(array_merge($data, ['user_id' => $user->id]));
        }
    }

    /**
     * Helper: buat notifikasi untuk semua user aktif.
     */
    public static function siaran(array $data): void
    {
        $users = User::where('aktif', true)->get();
        foreach ($users as $user) {
            static::create(array_merge($data, ['user_id' => $user->id]));
        }
    }

    /**
     * Warna badge berdasarkan tipe notifikasi.
     */
    public function getWarnaBadgeAttribute(): string
    {
        return match($this->tipe) {
            'surat_masuk_baru'        => '#1D68BD',
            'surat_keluar_diajukan'   => '#B45309',
            'sk_diajukan'             => '#7C3AED',
            'sk_disetujui'            => '#0E9B4A',
            'disposisi_baru'          => '#0891B2',
            'perlu_respon'            => '#DC2626',
            'pengingat'               => '#92400E',
            'info'                    => '#6B7280',
            default                   => '#6B7280',
        };
    }

    /**
     * Ikon SVG berdasarkan tipe notifikasi.
     */
    public function getIkonTipeAttribute(): string
    {
        return match($this->tipe) {
            'surat_masuk_baru'        => 'inbox',
            'surat_keluar_diajukan'   => 'send',
            'sk_diajukan'             => 'document',
            'sk_disetujui'            => 'check',
            'disposisi_baru'          => 'annotation',
            'perlu_respon'            => 'exclamation',
            'pengingat'               => 'clock',
            default                   => 'bell',
        };
    }
}
