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
        'no_peserta_ppdb_provinsi',
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
        'tgl_verifikasi_fisik',
        'sesi_verifikasi_fisik',
        'lokasi_verifikasi_fisik',
        'catatan_verifikasi_fisik',
        'status_verifikasi_fisik',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tgl_verifikasi_fisik' => 'date',
        ];
    }

    public function isDijadwalkanFisik(): bool
    {
        return !$this->isDiterima() &&
            !$this->isTidakLulus() &&
            !empty($this->tgl_verifikasi_fisik) &&
            $this->status_verifikasi_fisik === 'dijadwalkan';
    }

    public function isDiterima(): bool
    {
        return in_array(strtolower($this->status_pendaftaran ?? ''), ['lulus', 'diterima']) ||
            ($this->hasilSeleksi && in_array(strtoupper($this->hasilSeleksi->status ?? ''), ['LULUS', 'DITERIMA']));
    }

    public function isTidakLulus(): bool
    {
        return in_array(strtolower($this->status_pendaftaran ?? ''), ['tidak_lulus', 'tidak_diterima', 'ditolak']) ||
            ($this->hasilSeleksi && in_array(strtoupper($this->hasilSeleksi->status ?? ''), ['TIDAK_LULUS', 'TIDAK_DITERIMA']));
    }

    public function isTerverifikasi(): bool
    {
        return strtolower($this->status_pendaftaran ?? '') === 'terverifikasi' && !$this->isDiterima() && !$this->isTidakLulus();
    }

    public function isMenungguVerifikasi(): bool
    {
        return in_array(strtolower($this->status_pendaftaran ?? ''), ['menunggu_verifikasi', 'diajukan', 'terkirim']) &&
            !$this->isDiterima() && !$this->isTidakLulus();
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
