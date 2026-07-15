<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\User\DataKaryawan;
use App\Models\Referensi\Jabatan;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class PegawaiController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Pegawai',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Manajemen Data Pegawai');

        $query = DataKaryawan::with(['user', 'jabatan']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $data['pegawais'] = $query->latest()->paginate(20);
        $data['jabatans'] = Jabatan::all();

        return view('master.akademik.pegawai-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'username'      => 'required|string|unique:users,username',
            'password'      => 'required|string|min:6',
            'nip'           => 'required|string|unique:data_karyawan,nip',
            'nik'           => 'nullable|string|unique:data_karyawan,nik',
            'jabatan_id'    => 'nullable|exists:jabatans,id',
            'status_kerja'  => 'required|in:Tetap,Kontrak,Honorer,Outsourcing',
            'tanggal_bergabung' => 'nullable|date',
        ]);

        // Create User
        $newUser = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Assign tendik role
        $newUser->assignRole('tendik');

        // Create Data Karyawan
        DataKaryawan::create([
            'user_id'           => $newUser->id,
            'nip'               => $request->nip,
            'nik'               => $request->nik,
            'jabatan_id'        => $request->jabatan_id,
            'status_kerja'      => $request->status_kerja,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'created_by'        => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Pegawai berhasil ditambahkan.');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $pegawai = DataKaryawan::findOrFail($id);
        
        $request->validate([
            'name'          => 'required|string|max:255',
            'nip'           => 'required|string|unique:data_karyawan,nip,' . $id,
            'nik'           => 'nullable|string|unique:data_karyawan,nik,' . $id,
            'jabatan_id'    => 'nullable|exists:jabatans,id',
            'status_kerja'  => 'required|in:Tetap,Kontrak,Honorer,Outsourcing',
            'tanggal_bergabung' => 'nullable|date',
        ]);

        // Update User Name
        $pegawai->user->update([
            'name' => $request->name,
        ]);

        // Update Karyawan Details
        $pegawai->update([
            'nip'               => $request->nip,
            'nik'               => $request->nik,
            'jabatan_id'        => $request->jabatan_id,
            'status_kerja'      => $request->status_kerja,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'updated_by'        => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Data pegawai berhasil diperbarui.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $pegawai = DataKaryawan::findOrFail($id);
        
        // Remove tendik role and delete user
        $user = $pegawai->user;
        if ($user) {
            $user->delete();
        }
        
        $pegawai->delete();

        Alert::success('Berhasil', 'Data pegawai berhasil dihapus.');
        return redirect()->back();
    }
}
