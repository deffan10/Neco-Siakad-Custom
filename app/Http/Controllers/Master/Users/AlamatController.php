<?php

namespace App\Http\Controllers\Master\Users;

use App\DataTables\Manager\Users\AlamatDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Users\AlamatRequest;
// Use Models
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\User;
// Use Others
use App\Services\Export\CSV\ExportAlamatCSVService;
use App\Services\Export\Excel\ExportAlamatExcelService;
use App\Services\Export\PDF\ExportAlamatPDFService;
use App\Services\Import\Excel\ImportAlamatExcelService;
use App\Services\Manager\Users\AlamatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use RealRashid\SweetAlert\Facades\Alert;

class AlamatController extends Controller
{
    protected AlamatService $alamatService;

    public function __construct(AlamatService $alamatService)
    {
        $this->alamatService = $alamatService;
    }

    public function index(AlamatDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Alamat';
        $data['pages'] = 'Halaman Data Alamat';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = false;

        $dataTable->setTrash(false);

        return $dataTable->render('master.users.alamat-index', $data, compact('user'));
    }

    public function trash(AlamatDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Alamat';
        $data['pages'] = 'Halaman Data Alamat yang Dihapus';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = true;

        $dataTable->setTrash(true);

        return $dataTable->render('master.users.alamat-index', $data, compact('user'));
    }

    public function store(AlamatRequest $request)
    {
        try {
            $this->alamatService->createAlamat($request->validated());

            Alert::success('Berhasil', 'Data alamat berhasil ditambahkan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function update(AlamatRequest $request, $id)
    {
        try {
            $this->alamatService->updateAlamat($id, $request->validated());

            Alert::success('Berhasil', 'Data alamat berhasil diperbarui');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $this->alamatService->deleteAlamat($id);

            Alert::success('Berhasil', 'Data alamat berhasil dihapus');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $this->alamatService->restoreAlamat($id);

            Alert::success('Berhasil', 'Data alamat berhasil dikembalikan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function forceDelete($id)
    {
        try {
            $this->alamatService->forceDeleteAlamat($id);

            Alert::success('Berhasil', 'Data alamat berhasil dihapus permanen');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function exportExcel(ExportAlamatExcelService $exporter)
    {
        return $exporter->export();
    }

    public function exportCSV(ExportAlamatCSVService $exporter)
    {
        return $exporter->export();
    }

    public function exportPDF(ExportAlamatPDFService $exporter)
    {
        return $exporter->export();
    }

    public function importExcel(Request $request, ImportAlamatExcelService $importer)
    {
        // Validasi file upload
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // max 10MB
        ], [
            'excel_file.required' => 'File Excel wajib diupload.',
            'excel_file.file' => 'File yang diupload harus berupa file.',
            'excel_file.mimes' => 'File harus berformat Excel (.xlsx atau .xls).',
            'excel_file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            $filePath = $request->file('excel_file')->getPathname();
            $options = [
                'skip_duplicates' => $request->boolean('skip_duplicates', true),
            ];

            $result = $importer->import($filePath, $options);

            if ($request->ajax()) {
                return response()->json($result);
            }

            if ($result['success']) {
                Alert::success('Import Berhasil', $result['message']);
            } else {
                Alert::error('Import Gagal', $result['message']);
            }

            return redirect()->back();

        } catch (\Exception $e) {
            $errorMessage = 'Terjadi kesalahan saat import: '.$e->getMessage();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => [],
                ], 500);
            }

            Alert::error('Error', $errorMessage);

            return redirect()->back();
        }
    }

    public function downloadTemplate()
    {
        try {
            // Buat template Excel dengan header yang sesuai
            $templateData = collect([
                [
                    'Email User' => 'user@example.com',
                    'Tipe' => 'ktp',
                    'Alamat Lengkap' => 'Jl. Contoh No. 123',
                    'RT' => '001',
                    'RW' => '002',
                    'Kelurahan' => 'Kelurahan Contoh',
                    'Kecamatan' => 'Kecamatan Contoh',
                    'Kota/Kabupaten' => 'Jakarta Selatan',
                    'Provinsi' => 'DKI Jakarta',
                    'Kode Pos' => '12345',
                ],
            ]);

            $filename = 'Template_Import_Alamat_'.date('Y-m-d').'.xlsx';

            return (new FastExcel($templateData))->download($filename);

        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mendownload template: '.$e->getMessage());

            return redirect()->back();
        }
    }
}
