<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'siswa_id',
        'pengampu_id',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'capaian_kompetensi',
    ];

    protected function casts(): array
    {
        return [
            'nilai_tugas' => 'decimal:2',
            'nilai_uts' => 'decimal:2',
            'nilai_uas' => 'decimal:2',
            'nilai_akhir' => 'decimal:2',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function pengampu(): BelongsTo
    {
        return $this->belongsTo(Pengampu::class, 'pengampu_id');
    }

    /**
     * Hitung nilai akhir otomatis: Tugas 30%, UTS 30%, UAS 40% (atau bobot standar).
     */
    public function hitungNilaiAkhir(): float
    {
        $tugas = (float) ($this->nilai_tugas ?? 0);
        $uts = (float) ($this->nilai_uts ?? 0);
        $uas = (float) ($this->nilai_uas ?? 0);

        return round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 2);
    }
}
