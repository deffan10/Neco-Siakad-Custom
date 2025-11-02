<?php

namespace App\Http\Requests\Manager\Users;

use Illuminate\Foundation\Http\FormRequest;

class KeluargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hubungan' => 'required|in:Ayah,Ibu,Suami,Istri,Anak,Kakak,Adik,Wali',
            'nama' => 'required|string|max:255',
            'user_id' => 'required|integer',
            'pekerjaan' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:15',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'penghasilan' => 'nullable|integer|min:0',
            'alamat' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'hubungan.required' => 'Hubungan keluarga wajib dipilih',
            'hubungan.in' => 'Hubungan keluarga tidak valid',
            'nama.required' => 'Nama keluarga wajib diisi',
            'user_id.required' => 'Pemilik data wajib dipilih',
            'penghasilan.integer' => 'Penghasilan harus berupa angka',
            'penghasilan.min' => 'Penghasilan tidak boleh negatif',
        ];
    }
}
