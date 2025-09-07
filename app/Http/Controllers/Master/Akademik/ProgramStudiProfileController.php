<?php

namespace App\Http\Controllers\Master\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
// Use Models
use App\Models\Akademik\ProgramStudiProfile;
use App\Models\Akademik\ProgramStudi;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class ProgramStudiProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['spref'] = $user ? $user->prefix : '';
        $data['menus'] = 'Akademik Program Studi Profile';
        $data['pages'] = "Halaman Data Program Studi Profile";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['programStudiProfiles'] = ProgramStudiProfile::with('programStudi')->orderBy('created_at', 'desc')->get();
        $data['programStudis'] = ProgramStudi::with('fakultas')->orderBy('name')->get();
        $data['is_trash'] = false;

        return view('master.akademik.program-studi-profile-index', $data, compact('user'));
    }

    public function trash()
    {
        $user = Auth::user();
        $data['spref'] = $user ? $user->prefix : '';
        $data['menus'] = 'Akademik Program Studi Profile';
        $data['pages'] = "Halaman Data Program Studi Profile yang Dihapus";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['programStudiProfiles'] = ProgramStudiProfile::onlyTrashed()->with('programStudi')->get();
        $data['programStudis'] = ProgramStudi::with('fakultas')->orderBy('name')->get();
        $data['is_trash'] = true;

        return view('master.akademik.program-studi-profile-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_studi_id' => 'required|exists:program_studi,id',
            'slug' => 'required|string|unique:program_studi_profile,slug',
            'deskripsi' => 'nullable|string',
            'objektif' => 'nullable|string',
            'karir' => 'nullable|string',
            'logo' => 'nullable|string|max:255',
            'banner' => 'nullable|string|max:255',
        ], [
            'program_studi_id.required' => 'Program studi wajib dipilih',
            'program_studi_id.exists' => 'Program studi tidak valid',
            'slug.required' => 'Slug wajib diisi',
            'slug.unique' => 'Slug sudah digunakan',
        ]);

        try {
            $user = Auth::user();
            
            ProgramStudiProfile::create([
                'program_studi_id' => $request->program_studi_id,
                'slug' => $request->slug,
                'deskripsi' => $request->deskripsi,
                'objektif' => $request->objektif,
                'karir' => $request->karir,
                'logo' => $request->logo,
                'banner' => $request->banner,
                'created_by' => $user->id
            ]);

            Alert::success('Berhasil', 'Data program studi profile berhasil ditambahkan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $programStudiProfile = ProgramStudiProfile::findOrFail($id);
        
        $request->validate([
            'program_studi_id' => 'required|exists:program_studi,id',
            'slug' => 'required|string|unique:program_studi_profile,slug,' . $id,
            'deskripsi' => 'nullable|string',
            'objektif' => 'nullable|string',
            'karir' => 'nullable|string',
            'logo' => 'nullable|string|max:255',
            'banner' => 'nullable|string|max:255',
        ], [
            'program_studi_id.required' => 'Program studi wajib dipilih',
            'program_studi_id.exists' => 'Program studi tidak valid',
            'slug.required' => 'Slug wajib diisi',
            'slug.unique' => 'Slug sudah digunakan',
        ]);

        try {
            $user = Auth::user();
            
            $programStudiProfile->update([
                'program_studi_id' => $request->program_studi_id,
                'slug' => $request->slug,
                'deskripsi' => $request->deskripsi,
                'objektif' => $request->objektif,
                'karir' => $request->karir,
                'logo' => $request->logo,
                'banner' => $request->banner,
                'updated_by' => $user->id
            ]);

            Alert::success('Berhasil', 'Data program studi profile berhasil diperbarui');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $programStudiProfile = ProgramStudiProfile::findOrFail($id);

            $user = Auth::user();
            $programStudiProfile->update(['deleted_by' => $user->id]);
            $programStudiProfile->delete();

            Alert::success('Berhasil', 'Data program studi profile berhasil dihapus');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $programStudiProfile = ProgramStudiProfile::onlyTrashed()->findOrFail($id);

            $user = Auth::user();
            $programStudiProfile->update(['updated_by' => $user->id, 'deleted_by' => null]);
            $programStudiProfile->restore();

            Alert::success('Berhasil', 'Data program studi profile berhasil dikembalikan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }
}