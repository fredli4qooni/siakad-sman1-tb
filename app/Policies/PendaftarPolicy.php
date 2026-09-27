<?php

namespace App\Policies;

use App\Models\Pendaftar;
use App\Models\User;

class PendaftarPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat daftar seluruh pendaftar.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAdminAccess();
    }

    /**
     * Tentukan apakah pengguna dapat melihat detail data pendaftar tertentu.
     */
    public function view(User $user, Pendaftar $pendaftar): bool
    {
        if ($user->hasAdminAccess()) {
            return true;
        }

        return $user->id === $pendaftar->user_id;
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui data formulir pendaftaran.
     */
    public function update(User $user, Pendaftar $pendaftar): bool
    {
        if ($user->hasAdminAccess()) {
            return true;
        }

        // Calon siswa hanya boleh mengubah datanya jika status masih draft/belum disidangkan
        return $user->id === $pendaftar->user_id && in_array($pendaftar->status_pendaftaran, ['draft', 'ditolak'], true);
    }

    /**
     * Tentukan apakah pengguna dapat memverifikasi berkas dan menetapkan kelulusan.
     */
    public function verifikasi(User $user): bool
    {
        return $user->hasAdminAccess();
    }
}
