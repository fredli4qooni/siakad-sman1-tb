<?php

namespace App\Http\Requests\Ppdb;

use Illuminate\Foundation\Http\FormRequest;

class PeriodePpdbRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        return [
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'nama_gelombang' => ['required', 'string', 'max:100'],
            'tanggal_buka' => ['required', 'date'],
            'tanggal_tutup' => ['required', 'date', 'after_or_equal:tanggal_buka'],
            'kuota' => ['required', 'integer', 'min:1', 'max:2000'],
            'is_aktif' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi (contoh: 2026/2027).',
            'nama_gelombang.required' => 'Nama gelombang pendaftaran wajib diisi.',
            'tanggal_buka.required' => 'Tanggal buka pendaftaran wajib diisi.',
            'tanggal_tutup.required' => 'Tanggal tutup pendaftaran wajib diisi.',
            'tanggal_tutup.after_or_equal' => 'Tanggal tutup tidak boleh sebelum tanggal buka.',
            'kuota.required' => 'Jumlah kuota penerimaan wajib diisi.',
            'kuota.min' => 'Kuota minimal 1 siswa.',
        ];
    }
}
