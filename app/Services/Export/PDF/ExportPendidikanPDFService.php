<?php

namespace App\Services\Export\PDF;

use App\Models\User\Pendidikan;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportPendidikanPDFService
{
    public function export()
    {
        $pendidikans = Pendidikan::with(['user'])->get();

        $data = [
            'title' => 'Data Pendidikan',
            'date' => date('d F Y H:i:s'),
            'pendidikans' => $pendidikans,
        ];

        $pdf = Pdf::loadView('exports.pdf.pendidikan', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('pendidikan_'.date('Y-m-d_His').'.pdf');
    }
}
