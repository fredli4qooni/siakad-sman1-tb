<?php

namespace App\Http\Requests\Siakad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        $guruId = $this->route('guru')?->id;
        $userId = $this->route('guru')?->user_id;

        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'gelar' => ['nullable', 'string', 'max:50'],
            'nip' => [
                'required',
                'string',
                'max:30',
                Rule::unique('guru', 'nip')->ignore($guruId),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap guru wajib diisi.',
            'nip.required' => 'NIP guru wajib diisi.',
            'nip.unique' => 'NIP ini telah terdaftar dalam sistem.',
            'email.required' => 'Alamat email aktif guru wajib diisi.',
            'email.unique' => 'Alamat email ini telah terdaftar.',
        ];
    }
}
