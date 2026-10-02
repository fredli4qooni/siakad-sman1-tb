<?php

namespace App\Http\Requests\Ppdb;

use Illuminate\Foundation\Http\FormRequest;

class AturJadwalFisikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        return [
            'tgl_verifikasi_fisik' => ['required', 'date'],
            'sesi_verifikasi_fisik' => ['required', 'string', 'max:100'],
            'lokasi_verifikasi_fisik' => ['required', 'string', 'max:150'],
            'catatan_verifikasi_fisik' => ['nullable', 'string', 'max:1000'],
            'status_verifikasi_fisik' => ['required', 'in:dijadwalkan,hadir_valid,tidak_hadir,batal,belum_dijadwalkan'],
        ];
    }

    public function messages(): array
    {
        return [
            'tgl_verifikasi_fisik.required' => 'Tanggal verifikasi berkas fisik wajib diisi.',
            'sesi_verifikasi_fisik.required' => 'Sesi / Jam verifikasi fisik wajib diisi.',
            'lokasi_verifikasi_fisik.required' => 'Lokasi / Ruangan verifikasi fisik wajib diisi.',
            'status_verifikasi_fisik.required' => 'Status verifikasi fisik wajib dipilih.',
        ];
    }
}
