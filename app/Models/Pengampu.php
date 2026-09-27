<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengampu extends Model
{
    use HasFactory;

    protected $table = 'pengampu';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel_id',
        'tahun_ajaran',
        'semester',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'pengampu_id');
    }
}
