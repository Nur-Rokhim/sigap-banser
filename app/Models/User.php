<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
        'satuan_id',
        'aktif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'aktif' => 'boolean',
            'satuan_id' => 'integer',
        ];
    }

    /**
     * Relasi ke satuan kerja pengguna.
     */
    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    /**
     * Relasi ke surat masuk yang dicatat oleh pengguna ini.
     */
    public function suratMasuk(): HasMany
    {
        return $this->hasMany(SuratMasuk::class, 'dicatat_oleh');
    }

    /**
     * Relasi ke surat keluar yang dibuat oleh pengguna ini.
     */
    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'dibuat_oleh');
    }

    /**
     * Relasi ke log aktivitas yang dicatat oleh pengguna ini.
     */
    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }

    /**
     * Relasi ke disposisi yang dikirimkan oleh pengguna ini.
     */
    public function disposisiDikirim(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'dari_user_id');
    }

    /**
     * Relasi ke disposisi yang diterima oleh pengguna ini.
     */
    public function disposisiDiterima(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'kepada_user_id');
    }

    /**
     * Helper cek peran super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Helper cek peran admin adminpers.
     */
    public function isAdminAdminpers(): bool
    {
        return $this->role === 'admin_adminpers';
    }

    /**
     * Helper cek peran pimpinan.
     */
    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    /**
     * Helper cek apakah pengguna berhak sebagai verifikator surat keluar.
     * (Superadmin, Kasatkorcab, Kasetma, Pimpinan, Adminpers)
     */
    public function isVerifikator(): bool
    {
        return in_array(strtolower($this->role ?? ''), [
            'super_admin',
            'kasatkorcab',
            'kasetma',
            'pimpinan',
            'admin_adminpers',
            'admin',
        ]);
    }

    /**
     * Helper cek status keaktifan user.
     */
    public function isAktif(): bool
    {
        return (bool) $this->aktif;
    }
}
