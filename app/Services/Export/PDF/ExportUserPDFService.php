<?php

namespace App\Services\Export\PDF;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class ExportUserPDFService
{
    public function export()
    {
        $users = User::all();

        $filename = 'Data_Users_' . date('Y-m-d_H-i-s') . '.pdf';

        $pdf = Pdf::loadView('essentials.pdf.pages.export-users', compact('users'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }
}
