<?php

namespace App\Http\Requests\Siakad;

use Illuminate\Foundation\Http\FormRequest;

class PengampuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        return [
            'guru_id' => ['required', 'exists:guru,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mapel_id' => ['required', 'exists:mata_pelajaran,id'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'guru_id.required' => 'Pilih guru pengampu.',
            'kelas_id.required' => 'Pilih rombongan belajar kelas.',
            'mapel_id.required' => 'Pilih mata pelajaran yang diampu.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
        ];
    }
}
