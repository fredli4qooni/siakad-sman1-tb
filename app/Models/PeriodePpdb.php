<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodePpdb extends Model
{
    use HasFactory;

    protected $table = 'periode_ppdb';

    protected $fillable = [
        'tahun_ajaran',
        'nama_gelombang',
        'tanggal_buka',
        'tanggal_tutup',
        'kuota',
        'is_aktif',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_buka' => 'date',
            'tanggal_tutup' => 'date',
            'is_aktif' => 'boolean',
            'kuota' => 'integer',
        ];
    }

    public function pendaftar(): HasMany
    {
        return $this->hasMany(Pendaftar::class, 'periode_id');
    }

    /**
     * Scope untuk mengambil periode yang sedang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
