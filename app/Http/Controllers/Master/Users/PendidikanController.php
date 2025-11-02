<?php

namespace App\Http\Controllers\Master\Users;

use App\DataTables\Manager\Users\PendidikanDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Users\PendidikanRequest;
// Use Models
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\User;
// Use Others
use App\Services\Export\CSV\ExportPendidikanCSVService;
use App\Services\Export\Excel\ExportPendidikanExcelService;
use App\Services\Export\PDF\ExportPendidikanPDFService;
use App\Services\Import\Excel\ImportPendidikanExcelService;
use App\Services\Manager\Users\PendidikanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use RealRashid\SweetAlert\Facades\Alert;

class PendidikanController extends Controller
{
    protected PendidikanService $pendidikanService;

    public function __construct(PendidikanService $pendidikanService)
    {
        $this->pendidikanService = $pendidikanService;
    }

    public function index(PendidikanDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Pendidikan';
        $data['pages'] = 'Halaman Data Pendidikan';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = false;

        $dataTable->setTrash(false);

        return $dataTable->render('master.users.pendidikan-index', $data, compact('user'));
    }

    public function trash(PendidikanDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Pendidikan';
        $data['pages'] = 'Halaman Data Pendidikan yang Dihapus';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = true;

        $dataTable->setTrash(true);

        return $dataTable->render('master.users.pendidikan-index', $data, compact('user'));
    }

    public function store(PendidikanRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->pendidikanService->createPendidikan($validated);

            Alert::success('Berhasil', 'Data pendidikan berhasil ditambahkan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function update(PendidikanRequest $request, $id)
    {
        try {
            $this->pendidikanService->updatePendidikan($id, $request->validated());

            Alert::success('Berhasil', 'Data pendidikan berhasil diperbarui');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $this->pendidikanService->deletePendidikan($id);

            Alert::success('Berhasil', 'Data pendidikan berhasil dihapus');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $this->pendidikanService->restorePendidikan($id);

            Alert::success('Berhasil', 'Data pendidikan berhasil dikembalikan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function forceDelete($id)
    {
        try {
            $this->pendidikanService->forceDeletePendidikan($id);

            Alert::success('Berhasil', 'Data pendidikan berhasil dihapus permanen');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function exportExcel(ExportPendidikanExcelService $exportService)
    {
        return $exportService->export();
    }

    public function exportCSV(ExportPendidikanCSVService $exportService)
    {
        return $exportService->export();
    }

    public function exportPDF(ExportPendidikanPDFService $exportService)
    {
        return $exportService->export();
    }

    public function importExcel(Request $request, ImportPendidikanExcelService $importService)
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
                'Jenjang' => 'S1',
                'Nama Sekolah' => 'Universitas Indonesia',
                'Jurusan' => 'Teknik Informatika',
                'Tahun Masuk' => 2018,
                'Tahun Lulus' => 2022,
                'Nilai Akhir' => 3.75,
                'Keterangan' => 'Lulus Cum Laude',
            ],
        ]);

        $filename = 'template_pendidikan.xlsx';

        return (new FastExcel($sampleData))->download($filename);
    }
}
