<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\User\DataDosen;
use App\Models\Referensi\Jabatan;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class DosenController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Dosen',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    public function index()
    {
        $user = Auth::user();
        $data = $this->baseData('Manajemen Dosen');

        // Fetch users who are lecturers with their DataDosen records
        $data['dosens'] = User::role('dosen')->with('dataDosen.jabatan')->get();

        // Fetch users who are not lecturers so they can be registered as lecturer
        $data['nonDosens'] = User::whereDoesntHave('roles', function($q) {
            $q->where('name', 'dosen');
        })->orderBy('name')->get();

        $data['jabatans'] = Jabatan::orderBy('name')->get();

        return view('master.akademik.dosen-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id|unique:data_dosen,user_id',
            'nidn'          => 'nullable|string|max:50|unique:data_dosen,nidn',
            'nip'           => 'nullable|string|max:50|unique:data_dosen,nip',
            'jabatan_id'    => 'nullable|exists:jabatans,id',
            'status_dosen'  => 'required|in:Tetap,Kontrak,Tidak Tetap,Emeritus',
            'jenis_dosen'   => 'required|in:Dosen Penuh,Dosen Luar Biasa,Doswal,Guest Lecturer',
            'gelar_akademik'=> 'nullable|string|max:100',
            'bidang_keahlian'=> 'nullable|string|max:255',
        ]);

        // Create data_dosen record
        DataDosen::create([
            'user_id'           => $request->user_id,
            'nidn'              => $request->nidn,
            'nip'               => $request->nip,
            'jabatan_id'        => $request->jabatan_id,
            'status_dosen'      => $request->status_dosen,
            'jenis_dosen'       => $request->jenis_dosen,
            'gelar_akademik'    => $request->gelar_akademik,
            'bidang_keahlian'   => $request->bidang_keahlian,
            'riwayat_pendidikan'=> $request->riwayat_pendidikan,
            'no_rekening'       => $request->no_rekening,
            'nama_bank'         => $request->nama_bank,
            'npwp'              => $request->npwp,
            'created_by'        => Auth::id()
        ]);

        // Assign 'dosen' role to user
        $lecturerUser = User::findOrFail($request->user_id);
        $lecturerUser->assignRole('dosen');

        Alert::success('Berhasil', 'Dosen baru berhasil didaftarkan.');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $dataDosen = DataDosen::findOrFail($id);

        $request->validate([
            'nidn'          => 'nullable|string|max:50|unique:data_dosen,nidn,' . $id,
            'nip'           => 'nullable|string|max:50|unique:data_dosen,nip,' . $id,
            'jabatan_id'    => 'nullable|exists:jabatans,id',
            'status_dosen'  => 'required|in:Tetap,Kontrak,Tidak Tetap,Emeritus',
            'jenis_dosen'   => 'required|in:Dosen Penuh,Dosen Luar Biasa,Doswal,Guest Lecturer',
            'gelar_akademik'=> 'nullable|string|max:100',
            'bidang_keahlian'=> 'nullable|string|max:255',
        ]);

        $dataDosen->update([
            'nidn'              => $request->nidn,
            'nip'               => $request->nip,
            'jabatan_id'        => $request->jabatan_id,
            'status_dosen'      => $request->status_dosen,
            'jenis_dosen'       => $request->jenis_dosen,
            'gelar_akademik'    => $request->gelar_akademik,
            'bidang_keahlian'   => $request->bidang_keahlian,
            'riwayat_pendidikan'=> $request->riwayat_pendidikan,
            'no_rekening'       => $request->no_rekening,
            'nama_bank'         => $request->nama_bank,
            'npwp'              => $request->npwp,
            'updated_by'        => Auth::id()
        ]);

        Alert::success('Berhasil', 'Data dosen berhasil diperbarui.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $dataDosen = DataDosen::findOrFail($id);
        $lecturerUser = User::findOrFail($dataDosen->user_id);

        // Remove role and soft delete data_dosen
        $lecturerUser->removeRole('dosen');
        $dataDosen->delete();

        Alert::success('Berhasil', 'Dosen berhasil dihapus dari sistem.');
        return redirect()->back();
    }
}
