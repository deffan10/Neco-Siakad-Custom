<?php

namespace App\Http\Requests\Manager\Users;

use Illuminate\Foundation\Http\FormRequest;

class AlamatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipe' => 'required|in:ktp,domisili',
            'alamat_lengkap' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kota_kabupaten' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tipe.required' => 'Tipe alamat wajib dipilih',
            'tipe.in' => 'Tipe alamat harus KTP atau Domisili',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi',
            'user_id.required' => 'Pemilik alamat wajib dipilih',
            'user_id.exists' => 'User tidak ditemukan'
        ];
    }
}
