<?php

namespace App\Http\Requests\Private;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    protected $userToUpdate;

    public function authorize(): bool
    {
        return true;
    }

    public function getValidatorInstance()
    {
        // kalau dari management → id ada di route
        if ($this->route('id')) {
            $this->userToUpdate = \App\Models\User::find($this->route('id'));
        } else {
            // kalau update profile sendiri
            $this->userToUpdate = Auth::user();
        }

        return parent::getValidatorInstance();
    }

    public function rules(): array
    {
        $user = $this->userToUpdate ?? Auth::user();
        $table = $user->getTable();
        $activeRole = session('active_role') ?? '';

        $rules = [
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:{$table},email,{$user->id}",
            'phone' => "required|string|unique:{$table},phone,{$user->id}",
            'username' => "nullable|string|unique:{$table},username,{$user->id}",
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'tinggi_badan' => 'nullable|numeric',
            'berat_badan' => 'nullable|numeric',

            'link_fb' => 'nullable|string',
            'link_ig' => 'nullable|string',
            'link_in' => 'nullable|string',

            // referensi
            'agama_id' => 'nullable|exists:agamas,id',
            'golongan_darah_id' => 'nullable|exists:golongan_darahs,id',
            'jenis_kelamin_id' => 'nullable|exists:jenis_kelamins,id',
            'kewarganegaraan_id' => 'nullable|exists:kewarganegaraans,id',

            // identitas
            'nomor_kk' => "nullable|numeric|digits:16|unique:{$table},nomor_kk,{$user->id}",
            'nomor_ktp' => "nullable|numeric|digits:16|unique:{$table},nomor_ktp,{$user->id}",
            'nomor_npwp' => "nullable|numeric|digits:16|unique:{$table},nomor_npwp,{$user->id}",

            // password
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',

            // pendidikan
            'pendidikan.*.jenjang' => 'required|string',
            'pendidikan.*.nama_institusi' => 'required|string',
            'pendidikan.*.jurusan' => 'nullable|string',
            'pendidikan.*.tahun_masuk' => 'nullable|integer|min:1950|max:'.date('Y'),
            'pendidikan.*.tahun_lulus' => 'nullable|integer|min:1950|max:'.(date('Y') + 10),
            'pendidikan.*.ipk' => 'nullable|string',
            'pendidikan.*.alamat' => 'nullable|string',

            // keluarga
            'keluarga.*.hubungan' => 'required|string',
            'keluarga.*.nama' => 'required|string',
            'keluarga.*.pekerjaan' => 'nullable|string',
            'keluarga.*.telepon' => 'nullable|string',
            'keluarga.*.tempat_lahir' => 'nullable|string',
            'keluarga.*.tanggal_lahir' => 'nullable|date',
            'keluarga.*.penghasilan' => 'nullable|integer',
            'keluarga.*.alamat' => 'nullable|string',

            // alamat
            'alamat_ktp.alamat_lengkap' => 'nullable|string',
            'alamat_ktp.kelurahan' => 'nullable|string',
            'alamat_ktp.kecamatan' => 'nullable|string',
            'alamat_ktp.kota_kabupaten' => 'nullable|string',
            'alamat_ktp.provinsi' => 'nullable|string',
            'alamat_ktp.kode_pos' => 'nullable|string|max:10',
            'alamat_ktp.rt' => 'nullable|string|max:5',
            'alamat_ktp.rw' => 'nullable|string|max:5',

            'alamat_domisili.alamat_lengkap' => 'nullable|string',
            'alamat_domisili.kelurahan' => 'nullable|string',
            'alamat_domisili.kecamatan' => 'nullable|string',
            'alamat_domisili.kota_kabupaten' => 'nullable|string',
            'alamat_domisili.provinsi' => 'nullable|string',
            'alamat_domisili.kode_pos' => 'nullable|string|max:10',
            'alamat_domisili.rt' => 'nullable|string|max:5',
            'alamat_domisili.rw' => 'nullable|string|max:5',
        ];

        // Role-specific field validation
        if ($activeRole === 'mahasiswa') {
            $rules['nim'] = 'required|string|max:50|unique:data_mahasiswa,nim,'.($user->dataMahasiswa->id ?? 'NULL').',id';
            $rules['program_studi_id'] = 'nullable|exists:program_studi,id';
            $rules['angkatan'] = 'nullable|integer|min:1990|max:'.date('Y');
            $rules['tanggal_masuk'] = 'nullable|date';
            $rules['ipk'] = 'nullable|numeric|min:0|max:4';
            $rules['sks_lulus'] = 'nullable|integer|min:0';
            $rules['jenis_pembiayaan'] = 'nullable|in:Mandiri,Beasiswa,Beasiswa Penuh,Subsidi';
            $rules['asal_sekolah'] = 'nullable|string|max:255';
        } elseif ($activeRole === 'karyawan' || $activeRole === 'tendik') {
            $rules['nip'] = 'required|string|max:50|unique:data_karyawan,nip,'.($user->dataKaryawan->id ?? 'NULL').',id';
            $rules['nik'] = 'nullable|string|max:16|unique:data_karyawan,nik,'.($user->dataKaryawan->id ?? 'NULL').',id';
            $rules['status_kerja'] = 'nullable|in:Tetap,Kontrak,Honorer,Outsourcing';
            $rules['tanggal_bergabung'] = 'nullable|date';
            $rules['tanggal_berakhir_kontrak'] = 'nullable|date|after_or_equal:tanggal_bergabung';
            $rules['no_rekening'] = 'nullable|string|max:20';
            $rules['nama_bank'] = 'nullable|string|max:100';
            $rules['atas_nama_rekening'] = 'nullable|string|max:100';
            $rules['npwp'] = 'nullable|string|max:15|unique:data_karyawan,npwp,'.($user->dataKaryawan->id ?? 'NULL').',id';
        } elseif ($activeRole === 'dosen') {
            $rules['nidn'] = 'required|string|max:50|unique:data_dosen,nidn,'.($user->dataDosen->id ?? 'NULL').',id';
            $rules['nip'] = 'nullable|string|max:50|unique:data_dosen,nip,'.($user->dataDosen->id ?? 'NULL').',id';
            $rules['status_dosen'] = 'nullable|in:Tetap,Kontrak,Tidak Tetap,Emeritus';
            $rules['jenis_dosen'] = 'nullable|in:Dosen Penuh,Dosen Luar Biasa,Doswal,Guest Lecturer';
            $rules['tanggal_bergabung'] = 'nullable|date';
            $rules['tanggal_berakhir_kontrak'] = 'nullable|date|after_or_equal:tanggal_bergabung';
            $rules['bidang_keahlian'] = 'nullable|string|max:255';
            $rules['gelar_akademik'] = 'nullable|string|max:100';
            $rules['riwayat_pendidikan'] = 'nullable|string';
            $rules['sertifikasi'] = 'nullable|string';
            $rules['no_rekening'] = 'nullable|string|max:20';
            $rules['nama_bank'] = 'nullable|string|max:100';
            $rules['atas_nama_rekening'] = 'nullable|string|max:100';
            $rules['npwp'] = 'nullable|string|max:15|unique:data_dosen,npwp,'.($user->dataDosen->id ?? 'NULL').',id';
        } elseif ($activeRole === 'peserta-pmb') {
            $rules['nomor_pendaftaran'] = 'required|string|max:50|unique:data_peserta_pmb,nomor_pendaftaran,'.($user->dataPestaPMB->id ?? 'NULL').',id';
            $rules['program_pilihan_1'] = 'nullable|string|max:255';
            $rules['program_pilihan_2'] = 'nullable|string|max:255';
            $rules['jalur_masuk'] = 'nullable|in:SNMPTN,SBMPTN,Mandiri,Khusus,Transfer';
            $rules['tahun_masuk'] = 'nullable|integer|min:2000|max:'.(date('Y') + 5);
            $rules['status_pendaftaran'] = 'nullable|in:Menunggu,Lolos,Tidak Lolos,Daftar Ulang,Batal';
            $rules['tanggal_daftar'] = 'nullable|date';
            $rules['tanggal_pengumuman'] = 'nullable|date|after_or_equal:tanggal_daftar';
            $rules['tanggal_daftar_ulang'] = 'nullable|date|after_or_equal:tanggal_pengumuman';
            $rules['nilai_tes_tulis'] = 'nullable|numeric|min:0';
            $rules['nilai_wawancara'] = 'nullable|numeric|min:0';
            $rules['nilai_akhir'] = 'nullable|numeric|min:0';
            $rules['catatan'] = 'nullable|string';
        } elseif ($activeRole === 'alumni') {
            $rules['nomor_alumni'] = 'nullable|string|max:50|unique:data_alumni,nomor_alumni,'.($user->dataAlumni->id ?? 'NULL').',id';
            $rules['angkatan'] = 'nullable|integer|min:1990|max:'.date('Y');
            $rules['tahun_lulus'] = 'nullable|integer|min:1990|max:'.date('Y');
            $rules['tanggal_lulus'] = 'nullable|date';
            $rules['ipk_akhir'] = 'nullable|numeric|min:0|max:4';
            $rules['predikat_lulus'] = 'nullable|string|max:100';
            $rules['status_pekerjaan'] = 'nullable|in:Bekerja,Belum Bekerja,Melanjutkan Studi,Wiraswasta';
            $rules['bidang_pekerjaan'] = 'nullable|string|max:100';
            $rules['instansi_pekerjaan'] = 'nullable|string|max:255';
            $rules['jabatan_pekerjaan'] = 'nullable|string|max:100';
            $rules['lokasi_pekerjaan'] = 'nullable|string|max:100';
            $rules['tahun_mulai_bekerja'] = 'nullable|integer|min:1990|max:'.date('Y');
            $rules['melanjutkan_ke'] = 'nullable|string|max:255';
            $rules['catatan'] = 'nullable|string';
            $rules['tahun_pembaruan_data'] = 'nullable|date';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $ktp = $this->input('alamat_ktp', []);
            $adaFieldLain = ! empty(array_filter(Arr::except($ktp, ['id', 'tipe', 'alamat_lengkap'])));
            if ($adaFieldLain && empty($ktp['alamat_lengkap'])) {
                $v->errors()->add('alamat_ktp.alamat_lengkap', 'Alamat lengkap KTP wajib diisi jika data KTP lain diisi.');
            }
        });
    }

    public function prepareForValidation()
    {
        // Clean up empty arrays
        if ($this->pendidikan && is_array($this->pendidikan)) {
            $this->pendidikan = array_filter($this->pendidikan, function ($item) {
                return ! empty(array_filter($item));
            });
        }

        if ($this->keluarga && is_array($this->keluarga)) {
            $this->keluarga = array_filter($this->keluarga, function ($item) {
                return ! empty(array_filter($item));
            });
        }
    }
}
