<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Akademik\DataAlumni;
use App\Models\Akademik\ProgramStudi;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class AlumniController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Alumni',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    // Tab 1: DATA ALUMNI / CARI ALUMNI
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Data & Cari Alumni');

        $query = DataAlumni::with(['user', 'programStudi']);

        if ($request->filled('program_studi_id')) {
            $query->where('program_studi_id', $request->program_studi_id);
        }
        if ($request->filled('tahun_lulus')) {
            $query->whereYear('tanggal_lulus', $request->tahun_lulus);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%")->orWhere('username', 'like', "%{$search}%");
                })->orWhere('nomor_alumni', 'like', "%{$search}%");
            });
        }

        $data['alumnis']      = $query->latest('tanggal_lulus')->paginate(20);
        $data['programStudis'] = ProgramStudi::where('is_active', true)->orderBy('name')->get();
        // Get students who are not yet alumni to populate dropdown
        $data['mahasiswas']   = User::role('mahasiswa')
            ->whereDoesntHave('dataAlumni')
            ->orderBy('name')
            ->get();
        
        $data['activeTab'] = 'cari';

        return view('master.akademik.alumni-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id|unique:data_alumni,user_id',
            'program_studi_id' => 'required|exists:program_studi,id',
            'nomor_alumni'     => 'required|string|max:50|unique:data_alumni,nomor_alumni',
            'tanggal_lulus'    => 'required|date',
            'ipk'              => 'required|numeric|between:0,4.00',
            'judul_skripsi'    => 'nullable|string',
        ]);

        DataAlumni::create([
            'user_id'          => $request->user_id,
            'program_studi_id' => $request->program_studi_id,
            'nomor_alumni'     => $request->nomor_alumni,
            'tanggal_lulus'    => $request->tanggal_lulus,
            'ipk'              => $request->ipk,
            'judul_skripsi'    => $request->judul_skripsi,
            'pekerjaan_sekarang'=> $request->pekerjaan_sekarang,
            'created_by'       => Auth::id(),
        ]);

        // Change user role from mahasiswa to alumni
        $alumniUser = User::find($request->user_id);
        if ($alumniUser->hasRole('mahasiswa')) {
            $alumniUser->removeRole('mahasiswa');
        }
        $alumniUser->assignRole('alumni');

        Alert::success('Berhasil', 'Alumni berhasil didaftarkan dan role diperbarui.');
        return redirect()->back();
    }

    // Tab 2: ALBUM WISUDA
    public function album(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Album Wisuda & Lulusan');

        $query = DataAlumni::with(['user', 'programStudi']);

        if ($request->filled('program_studi_id')) {
            $query->where('program_studi_id', $request->program_studi_id);
        }

        $data['alumnis'] = $query->latest('tanggal_lulus')->paginate(12);
        $data['programStudis'] = ProgramStudi::where('is_active', true)->orderBy('name')->get();
        $data['activeTab'] = 'album';

        return view('master.akademik.alumni-index', $data, compact('user'));
    }

    public function destroy($id)
    {
        $alumni = DataAlumni::findOrFail($id);
        
        // Restore role back to mahasiswa
        $alumniUser = User::find($alumni->user_id);
        if ($alumniUser) {
            if ($alumniUser->hasRole('alumni')) {
                $alumniUser->removeRole('alumni');
            }
            $alumniUser->assignRole('mahasiswa');
        }

        $alumni->delete();

        Alert::success('Dihapus', 'Data alumni berhasil dihapus, role dikembalikan ke mahasiswa.');
        return redirect()->back();
    }
}
