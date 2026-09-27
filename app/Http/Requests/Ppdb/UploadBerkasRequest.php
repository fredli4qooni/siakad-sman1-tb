<?php

namespace App\Http\Requests\Ppdb;

use Illuminate\Foundation\Http\FormRequest;

class UploadBerkasRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Aturan validasi file berkas.
     */
    public function rules(): array
    {
        return [
            'jenis_berkas' => [
                'required',
                'string',
                'in:kartu_keluarga,akta_kelahiran,ijazah_skl,rapor,sertifikat_prestasi',
            ],
            'file_berkas' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048', // 2MB dalam kilobyte
            ],
        ];
    }

    /**
     * Pesan validasi Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'jenis_berkas.required' => 'Jenis berkas harus ditentukan.',
            'jenis_berkas.in' => 'Jenis berkas tidak valid.',
            'file_berkas.required' => 'Silakan pilih file berkas yang akan diunggah.',
            'file_berkas.file' => 'Berkas yang diunggah harus berupa file yang valid.',
            'file_berkas.mimes' => 'Format berkas hanya boleh berupa PDF, JPG, JPEG, atau PNG.',
            'file_berkas.max' => 'Ukuran berkas tidak boleh melebihi 2MB.',
        ];
    }
}
