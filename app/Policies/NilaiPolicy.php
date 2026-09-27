<?php

namespace App\Policies;

use App\Models\Nilai;
use App\Models\Pengampu;
use App\Models\User;

class NilaiPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat daftar nilai.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAdminAccess() || $user->isGuru();
    }

    /**
     * Tentukan apakah pengguna dapat melihat nilai spesifik.
     */
    public function view(User $user, Nilai $nilai): bool
    {
        if ($user->hasAdminAccess()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->guru && $user->guru->id === $nilai->pengampu->guru_id;
        }

        if ($user->isSiswa()) {
            return $user->siswa && $user->siswa->id === $nilai->siswa_id;
        }

        return false;
    }

    /**
     * Tentukan apakah guru berwenang menginput nilai pada penugasan pengampu tertentu.
     */
    public function inputNilai(User $user, Pengampu $pengampu): bool
    {
        if ($user->hasAdminAccess()) {
            return true;
        }

        if ($user->isGuru() && $user->guru) {
            return $user->guru->id === $pengampu->guru_id;
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui record nilai.
     */
    public function update(User $user, Nilai $nilai): bool
    {
        if ($user->hasAdminAccess()) {
            return true;
        }

        if ($user->isGuru() && $user->guru) {
            return $user->guru->id === $nilai->pengampu->guru_id;
        }

        return false;
    }
}
