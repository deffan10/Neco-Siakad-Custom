<?php

namespace App\Services\Export\Excel;

use App\Models\User\Keluarga;
use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportKeluargaExcelService
{
    public function export()
    {
        $keluargas = Keluarga::with(['user'])->get();

        $data = $this->transformData($keluargas);

        $filename = 'keluarga_'.date('Y-m-d_His').'.xlsx';

        return (new FastExcel($data))->download($filename);
    }

    private function transformData(Collection $keluargas): Collection
    {
        return $keluargas->map(function ($keluarga) {
            return [
                'ID' => $keluarga->id,
                'User' => $keluarga->user->name ?? '-',
                'Email User' => $keluarga->user->email ?? '-',
                'Hubungan' => $keluarga->hubungan,
                'Nama' => $keluarga->nama,
                'Pekerjaan' => $keluarga->pekerjaan ?? '-',
                'Telepon' => $keluarga->telepon ?? '-',
                'Tempat Lahir' => $keluarga->tempat_lahir ?? '-',
                'Tanggal Lahir' => $keluarga->tanggal_lahir ?? '-',
                'Penghasilan' => $keluarga->penghasilan ?? 0,
                'Alamat' => $keluarga->alamat ?? '-',
                'Dibuat Pada' => $keluarga->created_at?->format('Y-m-d H:i:s'),
                'Diupdate Pada' => $keluarga->updated_at?->format('Y-m-d H:i:s'),
            ];
        });
    }
}
