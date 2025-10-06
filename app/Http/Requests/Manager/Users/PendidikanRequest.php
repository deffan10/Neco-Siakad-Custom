<?php

namespace App\Http\Requests\Manager\Users;

use Illuminate\Foundation\Http\FormRequest;

class PendidikanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenjang' => 'required|in:Paket C,SMA,SMK,D3,S1,S2,S3',
            'nama_institusi' => 'required|string|max:255',
            'user_id' => 'required|integer',
            'jurusan' => 'nullable|string|max:255',
            'tahun_masuk' => 'nullable|integer|min:1900|max:'.(date('Y') + 5),
            'tahun_lulus' => 'nullable|integer|min:1900|max:'.(date('Y') + 10),
            'ipk' => 'nullable|string|max:10',
            'alamat' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'jenjang.required' => 'Jenjang pendidikan wajib dipilih',
            'jenjang.in' => 'Jenjang pendidikan tidak valid',
            'nama_institusi.required' => 'Nama institusi wajib diisi',
            'user_id.required' => 'Pemilik data wajib dipilih',
            'tahun_masuk.min' => 'Tahun masuk tidak valid',
            'tahun_masuk.max' => 'Tahun masuk tidak valid',
            'tahun_lulus.min' => 'Tahun lulus tidak valid',
            'tahun_lulus.max' => 'Tahun lulus tidak valid',
        ];
    }
}
