<?php

namespace App\Services\Export\Excel;

use App\Models\User;
use App\Models\User\Keluarga;
use App\Models\User\Alamat;
use App\Models\User\Pendidikan;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use OpenSpout\Common\Entity\Style\Style;

class ExportUserExcelService
{
    public function export()
    {
        $headerStyle = (new Style())->setFontBold();
        $rowsStyle   = (new Style())->setFontSize(12);

        // Definisikan setiap sheet dengan query/collection masing-masing
        $sheets = new SheetCollection([
            'Users' => User::with(['agama', 'golonganDarah', 'jenisKelamin', 'kewarganegaraan'])->get()->map(function ($user) {
                return [
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
                ];
            }),
            'Keluarga' => Keluarga::with('user')->get()->map(function ($keluarga) {
                return [
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
                ];
            }),
            'Alamat' => Alamat::with('user')->get()->map(function ($alamat) {
                return [
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
                ];
            }),
            'Pendidikan' => Pendidikan::with('user')->get()->map(function ($pendidikan) {
                return [
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
                ];
            }),
        ]);

        // Export ke file Excel dengan multi-sheet dan download langsung
        $filename = 'Data_Users_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return (new FastExcel($sheets))
            ->headerStyle($headerStyle)
            ->rowsStyle($rowsStyle)
            ->download($filename);
    }

}
