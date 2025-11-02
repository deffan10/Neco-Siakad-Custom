<?php

namespace App\Http\Controllers\Master\Users;

use App\DataTables\Manager\Users\KeluargaDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Users\KeluargaRequest;
// Use Models
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\User;
use App\Models\User\Keluarga;
// Use Others
use App\Services\Export\CSV\ExportKeluargaCSVService;
use App\Services\Export\Excel\ExportKeluargaExcelService;
use App\Services\Export\PDF\ExportKeluargaPDFService;
use App\Services\Import\Excel\ImportKeluargaExcelService;
use App\Services\Manager\Users\KeluargaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use RealRashid\SweetAlert\Facades\Alert;

class KeluargaController extends Controller
{
    protected KeluargaService $keluargaService;

    public function __construct(KeluargaService $keluargaService)
    {
        $this->keluargaService = $keluargaService;
    }

    public function index(KeluargaDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Keluarga';
        $data['pages'] = 'Halaman Data Keluarga';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = false;

        $dataTable->setTrash(false);

        return $dataTable->render('master.users.keluarga-index', $data, compact('user'));
    }

    public function trash(KeluargaDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Keluarga';
        $data['pages'] = 'Halaman Data Keluarga yang Dihapus';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = true;

        $dataTable->setTrash(true);

        return $dataTable->render('master.users.keluarga-index', $data, compact('user'));
    }

    public function store(KeluargaRequest $request)
    {
        try {
            $this->keluargaService->createKeluarga($request->validated());

            Alert::success('Berhasil', 'Data keluarga berhasil ditambahkan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function update(KeluargaRequest $request, $id)
    {
        try {
            $this->keluargaService->updateKeluarga($id, $request->validated());

            Alert::success('Berhasil', 'Data keluarga berhasil diperbarui');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $this->keluargaService->deleteKeluarga($id);

            Alert::success('Berhasil', 'Data keluarga berhasil dihapus');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function restore(Keluarga $keluarga): RedirectResponse
    {
        $this->keluargaService->restoreKeluarga($keluarga->id);

        return redirect()->back()->with('success', 'Keluarga berhasil dipulihkan.');
    }

    public function forceDelete(Keluarga $keluarga): RedirectResponse
    {
        $this->keluargaService->forceDeleteKeluarga($keluarga->id);

        return redirect()->back()->with('success', 'Keluarga berhasil dihapus permanen.');
    }

    public function exportExcel(ExportKeluargaExcelService $exportService)
    {
        return $exportService->export();
    }

    public function exportCSV(ExportKeluargaCSVService $exportService)
    {
        return $exportService->export();
    }

    public function exportPDF(ExportKeluargaPDFService $exportService)
    {
        return $exportService->export();
    }

    public function importExcel(Request $request, ImportKeluargaExcelService $importService)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        $options = [
            'skip_duplicates' => $request->boolean('skip_duplicates', true),
        ];

        $results = $importService->import($filePath, $options);

        if ($request->expectsJson()) {
            return response()->json($results);
        }

        if ($results['success']) {
            Alert::success('Sukses', $results['message']);
        } else {
            Alert::error('Error', $results['message']);
        }

        return redirect()->back();
    }

    public function downloadTemplate()
    {
        $sampleData = collect([
            [
                'Email User' => 'user@example.com',
                'Hubungan' => 'Ayah',
                'Nama' => 'John Doe',
                'Pekerjaan' => 'Wiraswasta',
                'Telepon' => '081234567890',
                'Tempat Lahir' => 'Jakarta',
                'Tanggal Lahir' => '1970-01-01',
                'Penghasilan' => 5000000,
                'Alamat' => 'Jl. Contoh No. 123',
            ],
        ]);

        $filename = 'template_keluarga.xlsx';

        return (new FastExcel($sampleData))->download($filename);
    }
}
