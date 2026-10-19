<?php

namespace App\Http\Requests\Siakad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        $siswa = $this->route('siswa');
        $siswaId = $siswa ? (is_object($siswa) ? $siswa->id : $siswa) : null;
        $userId = $siswa && is_object($siswa) ? $siswa->user_id : null;

        return [
            'nama' => ['required', 'string', 'max:255'],
            'nisn' => [
                'required',
                'string',
                'digits:10',
                Rule::unique('siswa', 'nisn')->ignore($siswaId),
            ],
            'nis' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('siswa', 'nis')->ignore($siswaId),
            ],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => [$siswaId ? 'nullable' : 'nullable', 'string', 'min:6'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'tahun_masuk' => ['required', 'digits:4'],
            'status_aktif' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama lengkap siswa wajib diisi.',
            'nisn.required' => 'NISN siswa wajib diisi.',
            'nisn.digits' => 'NISN harus berjumlah tepat 10 digit angka.',
            'nisn.unique' => 'NISN ini telah terdaftar dalam sistem.',
            'nis.unique' => 'NIS ini telah digunakan siswa lain.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih (L atau P).',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
            'email.required' => 'Alamat email akun SSO siswa wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini telah terdaftar di sistem SSO.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'kelas_id.exists' => 'Rombel kelas yang dipilih tidak valid.',
            'tahun_masuk.required' => 'Tahun masuk siswa wajib diisi.',
            'tahun_masuk.digits' => 'Tahun masuk harus berupa 4 digit tahun (contoh: 2026).',
        ];
    }
}
