<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Akademik\DataMahasiswa;
use App\Models\Referensi\StatusMahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class AktivitasMahasiswaController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Aktivitas Mahasiswa',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Aktivitas Lulus / Keluar Mahasiswa');

        $query = DataMahasiswa::with(['user', 'programStudi', 'statusMahasiswa']);

        if ($request->filled('status_mahasiswa_id')) {
            $query->where('status_mahasiswa_id', $request->status_mahasiswa_id);
        }
        if ($request->filled('program_studi_id')) {
            $query->where('program_studi_id', $request->program_studi_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $data['mahasiswas']      = $query->latest()->paginate(20);
        $data['statuses']        = StatusMahasiswa::all();
        $data['programStudis']   = ProgramStudi::where('is_active', true)->orderBy('name')->get();

        return view('master.akademik.aktivitas-mahasiswa-index', $data, compact('user'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_mahasiswa_id' => 'required|exists:status_mahasiswas,id',
            'tanggal_lulus'       => 'nullable|date',
            'ipk'                 => 'nullable|numeric|between:0,4.00',
            'sks_lulus'           => 'nullable|integer|min:0',
        ]);

        $mahasiswa = DataMahasiswa::findOrFail($id);

        $mahasiswa->update([
            'status_mahasiswa_id' => $request->status_mahasiswa_id,
            'tanggal_lulus'       => $request->tanggal_lulus,
            'ipk'                 => $request->ipk,
            'sks_lulus'           => $request->sks_lulus,
            'updated_by'          => Auth::id(),
        ]);

        // If status changes to graduate/lulus or drop out, reflect it on role if appropriate
        $statusName = StatusMahasiswa::find($request->status_mahasiswa_id)->name;
        $u = $mahasiswa->user;
        if ($u) {
            if (in_array(strtolower($statusName), ['lulus', 'alumni'])) {
                if ($u->hasRole('mahasiswa')) {
                    $u->removeRole('mahasiswa');
                }
                $u->assignRole('alumni');
            } elseif (in_array(strtolower($statusName), ['keluar', 'drop out', 'dikeluarkan'])) {
                if ($u->hasRole('mahasiswa')) {
                    $u->removeRole('mahasiswa');
                }
            } else {
                if (!$u->hasRole('mahasiswa')) {
                    $u->assignRole('mahasiswa');
                }
            }
        }

        Alert::success('Berhasil', 'Status akademik mahasiswa berhasil diperbarui.');
        return redirect()->back();
    }
}
