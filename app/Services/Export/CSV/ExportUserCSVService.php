<?php

namespace App\Services\Export\CSV;

use App\Models\User;
use App\Models\User\Keluarga;
use App\Models\User\Alamat;
use App\Models\User\Pendidikan;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ExportUserCSVService
{
    public function export()
    {
        // bikin folder temporary di storage
        $tempPath = storage_path('app/exports');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        // file CSV
        $files = [
            'Users.csv'       => User::all()->map(fn($user) => [
                'ID'                => $user->id,
                'Nama'              => $user->name,
                'Username'          => $user->username,
                'Email'             => $user->email,
                'Phone'             => $user->phone,
                'Instagram'         => $user->link_ig,
                'Facebook'          => $user->link_fb,
                'LinkedIn'          => $user->link_in,
                'Nomor KK'          => $user->nomor_kk,
                'Nomor KTP'         => $user->nomor_ktp,
                'Nomor NPWP'        => $user->nomor_npwp,
                'Agama'             => $user->agama?->nama,
                'Golongan Darah'    => $user->golonganDarah?->nama,
                'Jenis Kelamin'     => $user->jenisKelamin?->nama,
                'Kewarganegaraan'   => $user->kewarganegaraan?->nama,
                'Tinggi Badan'      => $user->tinggi_badan,
                'Berat Badan'       => $user->berat_badan,
                'Tempat Lahir'      => $user->tempat_lahir,
                'Tanggal Lahir'     => $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : null,
                'Status Aktif'      => $user->is_active ? 'Aktif' : 'Tidak Aktif',
                'First Setup'       => $user->fst_setup ? 'Ya' : 'Tidak',
                'Two Factor Auth'   => $user->tfa_setup ? 'Ya' : 'Tidak',
                'Tanggal Daftar'    => $user->created_at->format('Y-m-d H:i:s'),
            ]),
            'Keluarga.csv'    => Keluarga::with('user')->get()->map(fn($keluarga) => [
                'ID'            => $keluarga->id,
                'User ID'       => $keluarga->user_id,
                'Nama User'     => $keluarga->user?->name,
                'Hubungan'      => $keluarga->hubungan,
                'Nama'          => $keluarga->nama,
                'Pekerjaan'     => $keluarga->pekerjaan,
                'Telepon'       => $keluarga->telepon,
                'Tempat Lahir'  => $keluarga->tempat_lahir,
                'Tanggal Lahir' => $keluarga->tanggal_lahir ? $keluarga->tanggal_lahir->format('Y-m-d') : null,
                'Penghasilan'   => $keluarga->penghasilan,
                'Alamat'        => $keluarga->alamat,
            ]),
            'Alamat.csv'      => Alamat::with('user')->get()->map(fn($alamat) => [
                'ID'                => $alamat->id,
                'User ID'           => $alamat->user_id,
                'Nama User'         => $alamat->user?->name,
                'Tipe'              => ucfirst($alamat->tipe),
                'Alamat Lengkap'    => $alamat->alamat_lengkap,
                'Kelurahan'         => $alamat->kelurahan,
                'Kecamatan'         => $alamat->kecamatan,
                'Kota/Kabupaten'    => $alamat->kota_kabupaten,
                'Provinsi'          => $alamat->provinsi,
                'Kode Pos'          => $alamat->kode_pos,
                'RT'                => $alamat->rt,
                'RW'                => $alamat->rw,
            ]),
            'Pendidikan.csv'  => Pendidikan::with('user')->get()->map(fn($pendidikan) => [
                'ID'                => $pendidikan->id,
                'User ID'           => $pendidikan->user_id,
                'Nama User'         => $pendidikan->user?->name,
                'Jenjang'           => $pendidikan->jenjang,
                'Nama Institusi'    => $pendidikan->nama_institusi,
                'Jurusan'           => $pendidikan->jurusan,
                'Tahun Masuk'       => $pendidikan->tahun_masuk,
                'Tahun Lulus'       => $pendidikan->tahun_lulus,
                'IPK'               => $pendidikan->ipk,
                'Alamat'            => $pendidikan->alamat,
            ]),
        ];

        $generatedFiles = [];

        // simpan masing-masing CSV ke folder temp
        foreach ($files as $name => $data) {
            $filePath = $tempPath . '/' . $name;
            (new FastExcel($data))->export($filePath);
            $generatedFiles[] = $filePath;
        }

        // bikin ZIP
        $zipFilename = 'Data_Users_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = $tempPath . '/' . $zipFilename;

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($generatedFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        // hapus CSV sementara
        foreach ($generatedFiles as $file) {
            unlink($file);
        }

        // download ZIP
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
