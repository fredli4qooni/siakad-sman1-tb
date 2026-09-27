<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftar';

    protected $fillable = [
        'user_id',
        'periode_id',
        'no_pendaftaran',
        'nisn',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'asal_sekolah',
        'alamat',
        'no_hp',
        'status_pendaftaran',
        'catatan_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePpdb::class, 'periode_id');
    }

    public function orangTua(): HasOne
    {
        return $this->hasOne(OrangTua::class, 'pendaftar_id');
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(BerkasPendaftaran::class, 'pendaftar_id');
    }

    public function hasilSeleksi(): HasOne
    {
        return $this->hasOne(HasilSeleksi::class, 'pendaftar_id');
    }

    public function syncLog(): HasOne
    {
        return $this->hasOne(SyncLog::class, 'pendaftar_id');
    }
}
