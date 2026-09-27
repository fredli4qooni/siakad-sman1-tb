<?php

namespace App\Http\Requests\Siakad;

use Illuminate\Foundation\Http\FormRequest;

class KelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        return [
            'nama_kelas' => ['required', 'string', 'max:50'],
            'tingkat' => ['required', 'in:X,XI,XII'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'wali_kelas_id' => ['nullable', 'exists:guru,id'],
            'kapasitas' => ['required', 'integer', 'min:10', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kelas.required' => 'Nama rombongan belajar wajib diisi (contoh: X-A atau X MIPA 1).',
            'tingkat.required' => 'Tingkat kelas wajib dipilih.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'kapasitas.required' => 'Kapasitas maksimal siswa per kelas wajib diisi.',
        ];
    }
}
