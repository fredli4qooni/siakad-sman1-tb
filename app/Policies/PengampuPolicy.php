<?php

namespace App\Policies;

use App\Models\Pengampu;
use App\Models\User;

class PengampuPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat daftar penugasan.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAdminAccess() || $user->isGuru();
    }

    /**
     * Tentukan apakah pengguna dapat melihat detail pengampu tertentu.
     */
    public function view(User $user, Pengampu $pengampu): bool
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
     * Tentukan apakah guru berwenang menginput nilai pada penugasan pengampu tertentu.
     */
    public function input(User $user, Pengampu $pengampu): bool
    {
        if ($user->hasAdminAccess()) {
            return true;
        }

        if ($user->isGuru() && $user->guru) {
            return $user->guru->id === $pengampu->guru_id;
        }

        return false;
    }

    public function inputNilai(User $user, Pengampu $pengampu): bool
    {
        return $this->input($user, $pengampu);
    }

    /**
     * Tentukan apakah pengguna dapat menghapus penugasan pengampu.
     */
    public function delete(User $user, Pengampu $pengampu): bool
    {
        return $user->hasAdminAccess();
    }
}
