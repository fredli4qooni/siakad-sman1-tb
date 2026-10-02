<?php

namespace App\Http\Requests\Ppdb;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SimpanPendaftarRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     */
    public function rules(): array
    {
        $pendaftarId = $this->user()->pendaftar?->id;

        return [
            // Data Calon Siswa
            'no_peserta_ppdb_provinsi' => ['nullable', 'string', 'max:50'],
            'nisn' => [
                'required',
                'string',
                'digits:10',
                Rule::unique('pendaftar', 'nisn')->ignore($pendaftarId),
            ],
            'nik' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('pendaftar', 'nik')->ignore($pendaftarId),
            ],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'agama' => ['required', 'string', 'max:50'],
            'asal_sekolah' => ['required', 'string', 'max:150'],
            'alamat' => ['required', 'string', 'max:500'],
            'no_hp' => ['required', 'string', 'min:10', 'max:20'],

            // Data Orang Tua / Wali
            'nama_ayah' => ['required', 'string', 'max:255'],
            'pekerjaan_ayah' => ['required', 'string', 'max:100'],
            'nama_ibu' => ['required', 'string', 'max:255'],
            'pekerjaan_ibu' => ['required', 'string', 'max:100'],
            'nama_wali' => ['nullable', 'string', 'max:255'],
            'pekerjaan_wali' => ['nullable', 'string', 'max:100'],
            'no_hp_ortu' => ['required', 'string', 'min:10', 'max:20'],
        ];
    }

    /**
     * Kustomisasi pesan error validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits' => 'NISN harus tepat 10 digit angka.',
            'nisn.unique' => 'NISN ini sudah terdaftar oleh siswa lain.',
            'nik.required' => 'NIK wajib diisi sesuai Kartu Keluarga.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar dalam sistem.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi sesuai Akta Kelahiran.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'asal_sekolah.required' => 'Asal sekolah SMP/MTs wajib diisi.',
            'alamat.required' => 'Alamat lengkap tempat tinggal wajib diisi.',
            'no_hp.required' => 'Nomor WhatsApp/HP siswa wajib diisi.',
            'nama_ayah.required' => 'Nama ayah kandung wajib diisi.',
            'pekerjaan_ayah.required' => 'Pekerjaan ayah wajib diisi.',
            'nama_ibu.required' => 'Nama ibu kandung wajib diisi.',
            'pekerjaan_ibu.required' => 'Pekerjaan ibu wajib diisi.',
            'no_hp_ortu.required' => 'Nomor HP/WhatsApp orang tua wajib diisi.',
        ];
    }
}
