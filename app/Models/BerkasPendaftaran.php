<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BerkasPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'berkas_pendaftaran';

    protected $fillable = [
        'pendaftar_id',
        'jenis_berkas',
        'nama_file_asli',
        'file_path',
        'ukuran_file',
        'mime_type',
        'status_verifikasi',
        'catatan',
        'diverifikasi_oleh',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'diverifikasi_pada' => 'datetime',
            'ukuran_file' => 'integer',
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
}
