<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncLog extends Model
{
    use HasFactory;

    protected $table = 'sync_log';

    protected $fillable = [
        'pendaftar_id',
        'siswa_id',
        'user_id',
        'waktu_sinkron',
        'status',
        'detail_payload',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'waktu_sinkron' => 'datetime',
            'detail_payload' => 'array',
        ];
    }

    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
