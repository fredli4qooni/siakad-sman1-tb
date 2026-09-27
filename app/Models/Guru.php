<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'gelar',
        'no_hp',
        'alamat',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelasWali(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    public function pengampu(): HasMany
    {
        return $this->hasMany(Pengampu::class, 'guru_id');
    }

    /**
     * Nama lengkap beserta gelar.
     */
    public function getNamaGelarAttribute(): string
    {
        return $this->gelar ? "{$this->nama_lengkap}, {$this->gelar}" : $this->nama_lengkap;
    }
}
