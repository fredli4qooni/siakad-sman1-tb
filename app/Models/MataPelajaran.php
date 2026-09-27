<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kkm',
        'kelompok',
    ];

    protected function casts(): array
    {
        return [
            'kkm' => 'integer',
        ];
    }

    public function pengampu(): HasMany
    {
        return $this->hasMany(Pengampu::class, 'mapel_id');
    }
}
