<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Super Admin
        User::updateOrCreate(
            ['email' => 'admin@sman1tb.sch.id'],
            [
                'nama' => 'Administrator Sekolah',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status_aktif' => true,
            ]
        );

        // 2. Akun Operator PPDB & SIAKAD
        User::updateOrCreate(
            ['email' => 'operator@sman1tb.sch.id'],
            [
                'nama' => 'Operator Sekolah',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'status_aktif' => true,
            ]
        );

        // 3. Tiga Akun Guru
        User::updateOrCreate(
            ['email' => 'ahmad.fauzi@sman1tb.sch.id'],
            [
                'nama' => 'Drs. Ahmad Fauzi, M.Pd.',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'status_aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti.aminah@sman1tb.sch.id'],
            [
                'nama' => 'Siti Aminah, S.Pd.',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'status_aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'hendra.wijaya@sman1tb.sch.id'],
            [
                'nama' => 'Hendra Wijaya, S.Si.',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'status_aktif' => true,
            ]
        );

        // 4. Akun Siswa Aktif SIAKAD (Hasil Lulus PPDB)
        User::updateOrCreate(
            ['email' => 'budi.santoso@siswa.sman1tb.sch.id'],
            [
                'nama' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'status_aktif' => true,
            ]
        );

        // 5. Akun Calon Siswa (Sedang Mendaftar PPDB)
        User::updateOrCreate(
            ['email' => 'rina.marlina@gmail.com'],
            [
                'nama' => 'Rina Marlina',
                'password' => Hash::make('password'),
                'role' => 'calon_siswa',
                'status_aktif' => true,
            ]
        );
    }
}
