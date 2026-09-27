<?php

namespace App\Http\Requests\Ppdb;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiBerkasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        return [
            'status_verifikasi' => ['required', 'in:valid,tidak_valid'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'status_verifikasi.required' => 'Status verifikasi berkas wajib dipilih.',
            'status_verifikasi.in' => 'Status verifikasi hanya boleh Valid atau Ditolak.',
        ];
    }
}
