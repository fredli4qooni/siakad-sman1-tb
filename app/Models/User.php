<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'status_aktif',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi (JSON/Array).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * Aksesor name untuk kompatibilitas framework bawaan Laravel.
     */
    public function getNameAttribute(): ?string
    {
        return $this->nama;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nama'] = $value;
    }

    /**
     * Relasi ke profil Pendaftar (PPDB).
     */
    public function pendaftar(): HasOne
    {
        return $this->hasOne(Pendaftar::class, 'user_id');
    }

    /**
     * Relasi ke profil Siswa aktif (SIAKAD).
     */
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    /**
     * Relasi ke profil Guru (SIAKAD).
     */
    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    // ==========================================
    // Helper Peran (Role Checks)
    // ==========================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    public function hasAdminAccess(): bool
    {
        return in_array($this->role, ['admin', 'operator'], true);
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isCalonSiswa(): bool
    {
        return $this->role === 'calon_siswa';
    }
}
