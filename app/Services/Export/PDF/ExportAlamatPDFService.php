<?php

namespace App\Services\Export\PDF;

use App\Models\User\Alamat;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportAlamatPDFService
{
    public function export()
    {
        $alamats = Alamat::with(['user'])->get();

        $data = [
            'title' => 'Data Alamat',
            'date' => date('d F Y H:i:s'),
            'alamats' => $alamats,
        ];

        $pdf = Pdf::loadView('exports.pdf.alamat', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('alamat_'.date('Y-m-d_His').'.pdf');
    }
}
