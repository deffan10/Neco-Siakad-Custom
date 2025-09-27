<?php

namespace App\Http\Controllers\Master\Users;

use App\Http\Controllers\Controller;
// Use Systems
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Private\UpdateProfileRequest;
use App\Services\Private\UpdateProfileService;
use App\Services\Import\Excel\ImportUserExcelService;
use App\Services\Export\Excel\ExportUserExcelService;
use App\Services\Export\CSV\ExportUserCSVService;
use App\Services\Export\PDF\ExportUserPDFService;
// Use Plugins
use RealRashid\SweetAlert\Facades\Alert;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Mail\welcomeMail;
// Use Models
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\Referensi\Agama;
use App\Models\Referensi\GolonganDarah;
use App\Models\Referensi\JenisKelamin;
use App\Models\Referensi\Kewarganegaraan;
use App\Models\User;
use App\Models\User\Role;


class UsersController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Data Pengguna';
        $data['pages'] = "Halaman Data Pengguna";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::all();
        $data['roles'] = Role::all();
        $data['is_trash'] = false;

        return view('master.users.user-index', $data, compact('user'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['pages'] = "Halaman Data Pengguna";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::findOrFail($id);
        $data['menus'] = 'Data Pengguna ' . $data['users']->name;
        $data['jenisKelamins'] = JenisKelamin::all();
        $data['agamas'] = Agama::all();
        $data['kewarganegaraans'] = Kewarganegaraan::all();
        $data['golonganDarahs'] = GolonganDarah::all();
        $data['roles'] = Role::all();

        return view('master.users.user-view', $data, compact('user'));
    }

    public function trash()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Data Pengguna';
        $data['pages'] = "Halaman Data Pengguna";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::onlyTrashed()->get();
        $data['is_trash'] = true;

        return view('master.users.user-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ],
        [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.unique' => 'Username sudah ada.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.unique' => 'Nomor telepon sudah ada.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah ada.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
            'roles.required' => 'Role wajib dipilih.',
            'roles.*.exists' => 'Role tidak valid.',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username ?? $request->phone;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->code = uniqid();
        
        // Generate temporary password if not provided
        $temporaryPassword = $request->password ?? $user->code;
        $user->password = Hash::make($temporaryPassword);
        $user->save();


        // Assign Role(s) from form input 'roles' (array of role IDs)
        $roleInput = $request->input('roles', []);
        if (!empty($roleInput)) {
            $roleIds = is_array($roleInput) ? $roleInput : [$roleInput];
            $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

            if (count($roles) > 0) {
                $user->assignRole($roles);
            } else {
                Alert::error('Error', 'Role tidak valid.');
                return redirect()->back();
            }
        }

        // Send welcome email
        try {
            $academy = Kampus::first();
            Mail::to($user->email)->send(new welcomeMail($user, $academy, $temporaryPassword));
        } catch (\Exception $e) {
            // Log email error but don't stop the process
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        Alert::success('Sukses', 'Pengguna baru berhasil ditambahkan dan email selamat datang telah dikirim.');
        return redirect()->back();
    }

    public function update(UpdateProfileRequest $request, UpdateProfileService $service, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->setUser($user);

            $service->updateProfile($user, $request->validated());
            
            Alert::toast('Pengguna berhasil diperbarui', 'success');
            return redirect()->back();
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('User update error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            
            Alert::error('Error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            Alert::error('Error', 'Anda tidak dapat menghapus diri sendiri.');
            return redirect()->back();
        }
        $user->deleted_by = Auth::id();
        $user->save();
        $user->alamats()->delete();
        $user->keluargas()->delete();
        $user->pendidikans()->delete();
        $user->delete();

        Alert::toast('Pengguna berhasil dipindahkan ke tempat sampah', 'success');
        return redirect()->back();
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->alamats()->restore();
        $user->keluargas()->restore();
        $user->pendidikans()->restore();
        $user->restore();

        Alert::toast('Pengguna berhasil dikembalikan dari tempat sampah', 'success');
        return redirect()->back();
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        if ($user->id === Auth::id()) {
            Alert::error('Error', 'Anda tidak dapat menghapus diri sendiri.');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $user->alamats()->forceDelete();
            $user->keluargas()->forceDelete();
            $user->pendidikans()->forceDelete();
            $user->roles()->detach();
            $user->forceDelete();

            DB::commit();

            Alert::toast('Pengguna berhasil dihapus secara permanen', 'success');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Gagal menghapus pengguna: ' . $e->getMessage());
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
            'excel_file.max' => 'Ukuran file maksimal 10MB.'
        ]);

        try {
            $filePath = $request->file('excel_file')->getPathname();
            $options = [
                'skip_duplicates' => $request->boolean('skip_duplicates', true),
                'send_welcome' => $request->boolean('send_welcome', false)
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
            $errorMessage = 'Terjadi kesalahan saat import: ' . $e->getMessage();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => []
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
                    'Two Factor Auth' => 'Tidak'
                ]
            ]);

            $filename = 'Template_Import_Users_' . date('Y-m-d') . '.xlsx';

            return (new FastExcel($templateData))->download($filename);

        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mendownload template: ' . $e->getMessage());
            return redirect()->back();
        }
    }

}