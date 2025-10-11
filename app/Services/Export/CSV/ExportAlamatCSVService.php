<?php

namespace App\Services\Export\CSV;

use App\Models\User\Alamat;
use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportAlamatCSVService
{
    public function export()
    {
        $alamats = Alamat::with(['user'])->get();

        $data = $this->transformData($alamats);

        $filename = 'alamat_'.date('Y-m-d_His').'.csv';

        return (new FastExcel($data))->download($filename);
    }

    private function transformData(Collection $alamats): Collection
    {
        return $alamats->map(function ($alamat) {
            return [
                'ID' => $alamat->id,
                'User' => $alamat->user->name ?? '-',
                'Email User' => $alamat->user->email ?? '-',
                'Tipe' => ucfirst($alamat->tipe),
                'Alamat Lengkap' => $alamat->alamat_lengkap,
                'RT' => $alamat->rt,
                'RW' => $alamat->rw,
                'Kelurahan' => $alamat->kelurahan,
                'Kecamatan' => $alamat->kecamatan,
                'Kota/Kabupaten' => $alamat->kota_kabupaten,
                'Provinsi' => $alamat->provinsi,
                'Kode Pos' => $alamat->kode_pos,
                'Dibuat Pada' => $alamat->created_at?->format('Y-m-d H:i:s'),
                'Diupdate Pada' => $alamat->updated_at?->format('Y-m-d H:i:s'),
            ];
        });
    }
}
