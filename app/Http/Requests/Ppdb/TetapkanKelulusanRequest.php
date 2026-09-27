<?php

namespace App\Http\Requests\Ppdb;

use Illuminate\Foundation\Http\FormRequest;

class TetapkanKelulusanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:LULUS,TIDAK_LULUS,CADANGAN,MENUNGGU'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status kelulusan harus dipilih.',
            'status.in' => 'Pilihan status kelulusan tidak valid.',
        ];
    }
}
