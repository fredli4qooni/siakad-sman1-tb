<?php

namespace App\Http\Requests\Siakad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MapelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        $mapelId = $this->route('mapel')?->id;

        return [
            'kode_mapel' => [
                'required',
                'string',
                'max:30',
                Rule::unique('mata_pelajaran', 'kode_mapel')->ignore($mapelId),
            ],
            'nama_mapel' => ['required', 'string', 'max:100'],
            'kkm' => ['required', 'integer', 'min:0', 'max:100'],
            'kelompok' => ['required', 'in:umum,peminatan,muatan_lokal'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi.',
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'kkm.required' => 'Nilai KKM wajib diisi (0–100).',
        ];
    }
}
