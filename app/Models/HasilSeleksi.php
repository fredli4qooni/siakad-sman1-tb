<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilSeleksi extends Model
{
    use HasFactory;

    protected $table = 'hasil_seleksi';

    protected $fillable = [
        'pendaftar_id',
        'status',
        'catatan',
        'diverifikasi_oleh',
        'tanggal_pengumuman',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengumuman' => 'datetime',
        ];
    }

    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function isLulus(): bool
    {
        return in_array(strtoupper($this->status), ['LULUS', 'DITERIMA']);
    }

    public function isDiterima(): bool
    {
        return in_array(strtoupper($this->status), ['LULUS', 'DITERIMA']);
    }
}
