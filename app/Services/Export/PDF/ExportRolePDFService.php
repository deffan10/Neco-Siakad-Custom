<?php

namespace App\Services\Export\PDF;

use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Role;

class ExportRolePDFService
{
    public function export()
    {
        $roles = Role::withCount('users')->get();

        $data = [
            'title' => 'Data Role',
            'date' => date('d F Y H:i:s'),
            'roles' => $roles,
        ];

        $pdf = Pdf::loadView('exports.pdf.role', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('role_'.date('Y-m-d_His').'.pdf');
    }
}
