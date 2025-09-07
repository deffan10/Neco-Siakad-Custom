<?php

namespace App\Http\Controllers\Master\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
// Use Models
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\Fakultas;
use App\Models\Akademik\ProgramStudiProfile;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['spref'] = $user ? $user->prefix : '';
        $data['menus'] = 'Akademik Program Studi';
        $data['pages'] = "Halaman Data Program Studi";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['programStudi'] = ProgramStudi::with(['fakultas', 'kaprodi', 'sekretaris'])->orderBy('name')->get();
        $data['fakultas'] = Fakultas::where('is_active', true)->orderBy('name')->get();
        $data['users'] = User::all();
        $data['is_trash'] = false;

        return view('master.akademik.program-studi-index', $data, compact('user'));
    }

    public function trash()
    {
        $user = Auth::user();
        $data['spref'] = $user ? $user->prefix : '';
        $data['menus'] = 'Akademik Program Studi';
        $data['pages'] = "Halaman Data Program Studi yang Dihapus";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['programStudi'] = ProgramStudi::onlyTrashed()->with(['fakultas', 'kaprodi', 'sekretaris'])->get();
        $data['fakultas'] = Fakultas::where('is_active', true)->orderBy('name')->get();
        $data['users'] = User::all();
        $data['is_trash'] = true;

        return view('master.akademik.program-studi-index', $data, compact('user'));
    }

    public function view($id)
    {
        $user = Auth::user();
        $data['spref'] = $user ? $user->prefix : '';
        $data['menus'] = 'Akademik Program Studi';
        $data['pages'] = "Detail Program Studi";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['programStudi'] = ProgramStudi::with(['fakultas', 'profile', 'kaprodi', 'sekretaris'])->findOrFail($id);
        $data['fakultas'] = Fakultas::where('is_active', true)->orderBy('name')->get();
        $data['users'] = User::all();

        return view('master.akademik.program-studi-view', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:program_studi,code',
            'nama_singkat' => 'nullable|string|max:20',
            'akreditasi' => 'nullable|string|max:10',
            'tanggal_akreditasi' => 'nullable|date',
            'sk_pendirian' => 'nullable|string|max:255',
            'tanggal_sk_pendirian' => 'nullable|date',
            'jenjang' => 'required|in:D3,D4,S1,S2,S3',
            'gelar_depan' => 'nullable|string|max:50',
            'gelar_belakang' => 'nullable|string|max:50',
            'kaprodi_id' => 'nullable|exists:users,id',
            'sekretaris_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean'
        ], [
            'fakultas_id.required' => 'Fakultas wajib dipilih',
            'fakultas_id.exists' => 'Fakultas tidak valid',
            'name.required' => 'Nama program studi wajib diisi',
            'code.required' => 'Kode program studi wajib diisi',
            'code.unique' => 'Kode program studi sudah ada',
            'jenjang.required' => 'Jenjang wajib dipilih',
            'jenjang.in' => 'Jenjang tidak valid',
            'kaprodi_id.exists' => 'Kaprodi tidak valid',
            'sekretaris_id.exists' => 'Sekretaris tidak valid'
        ]);

        try {
            $user = Auth::user();
            
            ProgramStudi::create([
                'fakultas_id' => $request->fakultas_id,
                'name' => $request->name,
                'code' => $request->code,
                'nama_singkat' => $request->nama_singkat,
                'akreditasi' => $request->akreditasi,
                'tanggal_akreditasi' => $request->tanggal_akreditasi,
                'sk_pendirian' => $request->sk_pendirian,
                'tanggal_sk_pendirian' => $request->tanggal_sk_pendirian,
                'jenjang' => $request->jenjang,
                'gelar_depan' => $request->gelar_depan,
                'gelar_belakang' => $request->gelar_belakang,
                'kaprodi_id' => $request->kaprodi_id,
                'sekretaris_id' => $request->sekretaris_id,
                'is_active' => $request->is_active,
                'created_by' => $user->id
            ]);

            Alert::success('Berhasil', 'Data program studi berhasil ditambahkan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $programStudi = ProgramStudi::findOrFail($id);
        
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:program_studi,code,' . $id,
            'nama_singkat' => 'nullable|string|max:20',
            'akreditasi' => 'nullable|string|max:10',
            'tanggal_akreditasi' => 'nullable|date',
            'sk_pendirian' => 'nullable|string|max:255',
            'tanggal_sk_pendirian' => 'nullable|date',
            'jenjang' => 'required|in:D3,D4,S1,S2,S3',
            'gelar_depan' => 'nullable|string|max:50',
            'gelar_belakang' => 'nullable|string|max:50',
            'kaprodi_id' => 'nullable|exists:users,id',
            'sekretaris_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean',
            // Profile fields
            'slug' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'objektif' => 'nullable|string',
            'karir' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'fakultas_id.required' => 'Fakultas wajib dipilih',
            'fakultas_id.exists' => 'Fakultas tidak valid',
            'name.required' => 'Nama program studi wajib diisi',
            'code.required' => 'Kode program studi wajib diisi',
            'code.unique' => 'Kode program studi sudah ada',
            'jenjang.required' => 'Jenjang wajib dipilih',
            'jenjang.in' => 'Jenjang tidak valid',
            'kaprodi_id.exists' => 'Kaprodi tidak valid',
            'sekretaris_id.exists' => 'Sekretaris tidak valid',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.mimes' => 'Logo harus berformat jpeg, png, jpg, gif, atau svg',
            'logo.max' => 'Ukuran logo maksimal 2MB',
            'banner.image' => 'Banner harus berupa gambar',
            'banner.mimes' => 'Banner harus berformat jpeg, png, jpg, gif, atau svg',
            'banner.max' => 'Ukuran banner maksimal 2MB'
        ]);

        try {
            $user = Auth::user();
            
            // Update program studi data
            $programStudi->update([
                'fakultas_id' => $request->fakultas_id,
                'name' => $request->name,
                'code' => $request->code,
                'nama_singkat' => $request->nama_singkat,
                'akreditasi' => $request->akreditasi,
                'tanggal_akreditasi' => $request->tanggal_akreditasi,
                'sk_pendirian' => $request->sk_pendirian,
                'tanggal_sk_pendirian' => $request->tanggal_sk_pendirian,
                'jenjang' => $request->jenjang,
                'gelar_depan' => $request->gelar_depan,
                'gelar_belakang' => $request->gelar_belakang,
                'kaprodi_id' => $request->kaprodi_id,
                'sekretaris_id' => $request->sekretaris_id,
                'is_active' => $request->is_active,
                'updated_by' => $user->id
            ]);

            // Handle profile data
            if ($request->filled(['slug', 'deskripsi', 'objektif', 'karir']) || $request->hasFile(['logo', 'banner'])) {
                $profileData = [
                    'program_studi_id' => $programStudi->id,
                    'slug' => $request->slug,
                    'deskripsi' => $request->deskripsi,
                    'objektif' => $request->objektif,
                    'karir' => $request->karir,
                    'updated_by' => $user->id
                ];

                // Handle logo upload
                if ($request->hasFile('logo')) {
                    $logoPath = $request->file('logo')->store('program-studi/logos', 'public');
                    $profileData['logo'] = $logoPath;
                }

                // Handle banner upload
                if ($request->hasFile('banner')) {
                    $bannerPath = $request->file('banner')->store('program-studi/banners', 'public');
                    $profileData['banner'] = $bannerPath;
                }

                // Update or create profile
                ProgramStudiProfile::updateOrCreate(
                    ['program_studi_id' => $programStudi->id],
                    $profileData
                );
            }

            Alert::success('Berhasil', 'Data program studi berhasil diperbarui');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $programStudi = ProgramStudi::findOrFail($id);

            $user = Auth::user();
            $programStudi->update(['deleted_by' => $user->id]);
            $programStudi->delete();

            Alert::success('Berhasil', 'Data program studi berhasil dihapus');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $programStudi = ProgramStudi::onlyTrashed()->findOrFail($id);

            $user = Auth::user();
            $programStudi->update(['updated_by' => $user->id, 'deleted_by' => null]);
            $programStudi->restore();

            Alert::success('Berhasil', 'Data program studi berhasil dikembalikan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }
}