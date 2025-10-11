<?php

namespace App\Services\Export\PDF;

use App\Models\User\Keluarga;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportKeluargaPDFService
{
    public function export()
    {
        $keluargas = Keluarga::with(['user'])->get();

        $data = [
            'title' => 'Data Keluarga',
            'date' => date('d F Y H:i:s'),
            'keluargas' => $keluargas,
        ];

        $pdf = Pdf::loadView('exports.pdf.keluarga', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('keluarga_'.date('Y-m-d_His').'.pdf');
    }
}
