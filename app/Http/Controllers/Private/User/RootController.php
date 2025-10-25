<?php

namespace App\Http\Controllers\Private\User;

use App\Http\Controllers\Controller;
// Use Systems
use App\Http\Requests\Private\UpdateProfileRequest;
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
// Use Plugins
use App\Models\Referensi\Agama;
// Use Models
use App\Models\Referensi\GolonganDarah;
use App\Models\Referensi\JenisKelamin;
use App\Models\Referensi\Kewarganegaraan;
use App\Services\Private\UpdateProfileService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class RootController extends Controller
{
    public function renderDashboard()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Dashboard';
        $data['pages'] = 'HomePage';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        return view('default-bcontent', $data, compact('user'));
    }

    public function renderProfile()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Profile';
        $data['pages'] = 'Halaman Profile';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        // Models
        $data['jenisKelamins'] = JenisKelamin::all();
        $data['agamas'] = Agama::all();
        $data['kewarganegaraans'] = Kewarganegaraan::all();
        $data['golonganDarahs'] = GolonganDarah::all();

        // Role-specific data
        if ($data['activeRole'] === 'mahasiswa') {
            $data['programStudis'] = \App\Models\Akademik\ProgramStudi::all();
            $data['statusMahasiswas'] = \App\Models\Referensi\StatusMahasiswa::all();
        } elseif ($data['activeRole'] === 'karyawan' || $data['activeRole'] === 'tendik') {
            $data['jabatans'] = \App\Models\Referensi\Jabatan::all();
        } elseif ($data['activeRole'] === 'alumni') {
            $data['programStudis'] = \App\Models\Akademik\ProgramStudi::all();
        }

        // Load user with related data including addresses
        $user->load(['alamats', 'pendidikans', 'keluargas', 'dataMahasiswa', 'dataKaryawan', 'dataDosen', 'dataPestaPMB', 'dataAlumni']);

        return view('private.profile-index', $data, compact('user'));
    }

    public function handleProfile(UpdateProfileRequest $request, UpdateProfileService $service)
    {
        try {
            $user = Auth::user();

            // $request->setUser($user);

            $service->updateProfile($user, $request->validated());

            Alert::toast('Profil berhasil diperbarui', 'success');

            return redirect()->back();

        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Profile update error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            Alert::error('Error', 'Gagal memperbarui profil: '.$e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    public function deletePendidikan($id)
    {
        try {
            $user = Auth::user();
            $pendidikan = $user->pendidikans()->findOrFail($id);
            $pendidikan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pendidikan berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data pendidikan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function deleteKeluarga($id)
    {
        try {
            $user = Auth::user();
            $keluarga = $user->keluargas()->findOrFail($id);
            $keluarga->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data keluarga berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data keluarga: '.$e->getMessage(),
            ], 500);
        }
    }
}
