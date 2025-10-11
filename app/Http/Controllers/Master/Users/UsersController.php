<?php

namespace App\Http\Controllers\Master\Users;

use App\DataTables\Manager\Users\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Users\UserRequest;
// Use Systems
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\Referensi\Agama;
use App\Models\Referensi\GolonganDarah;
use App\Models\Referensi\JenisKelamin;
use App\Models\Referensi\Kewarganegaraan;
// Use Plugins
use App\Models\User;
use App\Models\User\Role;
// Use Models
use App\Services\Export\CSV\ExportUserCSVService;
use App\Services\Export\Excel\ExportUserExcelService;
use App\Services\Export\PDF\ExportUserPDFService;
use App\Services\Import\Excel\ImportUserExcelService;
use App\Services\Manager\Users\UserService;
use App\Repositories\Manager\Users\UserRepository;
use App\Services\Private\UpdateProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;
// Use Others
use RealRashid\SweetAlert\Facades\Alert;

class UsersController extends Controller
{
    protected UserService $userService;
    protected UserRepository $userRepository;

    public function __construct(UserService $userService, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    public function index(UserDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Data Pengguna';
        $data['pages'] = 'Halaman Data Pengguna';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['roles'] = Role::all();
        $data['is_trash'] = false;

        $dataTable->setTrash(false);

        return $dataTable->render('master.users.user-index', $data, compact('user'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['pages'] = 'Halaman Data Pengguna';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::findOrFail($id);
        $data['menus'] = 'Data Pengguna '.$data['users']->name;
        $data['jenisKelamins'] = JenisKelamin::all();
        $data['agamas'] = Agama::all();
        $data['kewarganegaraans'] = Kewarganegaraan::all();
        $data['golonganDarahs'] = GolonganDarah::all();
        $data['roles'] = Role::all();

        return view('master.users.user-view', $data, compact('user'));
    }

    public function trash(UserDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Data Pengguna';
        $data['pages'] = 'Halaman Data Pengguna yang Dihapus';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['roles'] = Role::all();
        $data['is_trash'] = true;

        $dataTable->setTrash(true);

        return $dataTable->render('master.users.user-index', $data, compact('user'));
    }

    public function store(UserRequest $request)
    {
        try {
            $this->userService->createUser($request->validated());

            Alert::success('Berhasil', 'Pengguna baru berhasil ditambahkan dan email selamat datang telah dikirim');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function update(UserRequest $request, $id, UpdateProfileService $service)
    {
        try {

            $user = $this->userRepository->findById($id);
            $service->updateProfile($user, $request->validated());

            Alert::success('Berhasil', 'Data pengguna berhasil diperbarui');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);

            Alert::success('Berhasil', 'Pengguna berhasil dihapus');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $this->userService->restoreUser($id);

            Alert::success('Berhasil', 'Pengguna berhasil dikembalikan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function forceDelete($id)
    {
        try {
            $this->userService->forceDeleteUser($id);

            Alert::success('Berhasil', 'Pengguna berhasil dihapus permanen');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function exportExcel(ExportUserExcelService $exporter)
    {
        return $exporter->export();
    }

    public function exportCSV(ExportUserCSVService $exporter)
    {
        return $exporter->export();
    }

    public function exportPDF(ExportUserPDFService $exporter)
    {
        return $exporter->export();
    }

    public function importExcel(Request $request, ImportUserExcelService $importer)
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
                'send_welcome' => $request->boolean('send_welcome', false),
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
                    'Nama' => 'John Doe',
                    'Username' => 'johndoe',
                    'Email' => 'john@example.com',
                    'Phone' => '08123456789',
                    'Instagram' => '@johndoe',
                    'Facebook' => 'facebook.com/johndoe',
                    'LinkedIn' => 'linkedin.com/in/johndoe',
                    'Nomor KK' => '1234567890123456',
                    'Nomor KTP' => '1234567890123456',
                    'Nomor NPWP' => '12.345.678.9-012.000',
                    'Agama' => 'Islam',
                    'Golongan Darah' => 'A',
                    'Jenis Kelamin' => 'Laki-laki',
                    'Kewarganegaraan' => 'Indonesia',
                    'Tinggi Badan' => '170',
                    'Berat Badan' => '65',
                    'Tempat Lahir' => 'Jakarta',
                    'Tanggal Lahir' => '1990-01-15',
                    'Status Aktif' => 'Aktif',
                    'First Setup' => 'Tidak',
                    'Two Factor Auth' => 'Tidak',
                ],
            ]);

            $filename = 'Template_Import_Users_'.date('Y-m-d').'.xlsx';

            return (new FastExcel($templateData))->download($filename);

        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mendownload template: '.$e->getMessage());

            return redirect()->back();
        }
    }

    public function deletePendidikan($id)
    {
        try {
            $this->userService->deletePendidikan($id);

            return response()->json([
                'success' => true,
                'message' => 'Data pendidikan berhasil dihapus',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$th->getMessage(),
            ], 500);
        }
    }

    public function deleteKeluarga($id)
    {
        try {
            $this->userService->deleteKeluarga($id);

            return response()->json([
                'success' => true,
                'message' => 'Data keluarga berhasil dihapus',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$th->getMessage(),
            ], 500);
        }
    }
}
