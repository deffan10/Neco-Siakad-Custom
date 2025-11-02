<?php

namespace App\Services\Export\CSV;

use App\Models\User\Pendidikan;
use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportPendidikanCSVService
{
    public function export()
    {
        $pendidikans = Pendidikan::with(['user'])->get();

        $data = $this->transformData($pendidikans);

        $filename = 'pendidikan_'.date('Y-m-d_His').'.csv';

        return (new FastExcel($data))->download($filename);
    }

    private function transformData(Collection $pendidikans): Collection
    {
        return $pendidikans->map(function ($pendidikan) {
            return [
                'ID' => $pendidikan->id,
                'User' => $pendidikan->user->name ?? '-',
                'Email User' => $pendidikan->user->email ?? '-',
                'Jenjang' => $pendidikan->jenjang,
                'Nama Sekolah' => $pendidikan->nama_sekolah,
                'Jurusan' => $pendidikan->jurusan ?? '-',
                'Tahun Masuk' => $pendidikan->tahun_masuk ?? '-',
                'Tahun Lulus' => $pendidikan->tahun_lulus ?? '-',
                'Nilai Akhir' => $pendidikan->nilai_akhir ?? '-',
                'Keterangan' => $pendidikan->keterangan ?? '-',
                'Dibuat Pada' => $pendidikan->created_at?->format('Y-m-d H:i:s'),
                'Diupdate Pada' => $pendidikan->updated_at?->format('Y-m-d H:i:s'),
            ];
        });
    }
}
