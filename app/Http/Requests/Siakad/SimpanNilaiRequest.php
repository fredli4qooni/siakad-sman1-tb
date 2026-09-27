<?php

namespace App\Http\Requests\Siakad;

use Illuminate\Foundation\Http\FormRequest;

class SimpanNilaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nilai' => ['required', 'array'],
            'nilai.*.tugas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.uts' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.uas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.capaian_kompetensi' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nilai.required' => 'Data nilai siswa wajib dikirimkan.',
            'nilai.*.tugas.numeric' => 'Nilai tugas harus berupa angka antara 0 dan 100.',
            'nilai.*.uts.numeric' => 'Nilai UTS harus berupa angka antara 0 dan 100.',
            'nilai.*.uas.numeric' => 'Nilai UAS harus berupa angka antara 0 dan 100.',
        ];
    }
}
