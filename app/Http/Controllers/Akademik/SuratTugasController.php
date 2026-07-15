<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\SuratTugasMengajar;
use App\Models\Akademik\TahunAkademik;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class SuratTugasController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole'     => session('active_role') ?? 'admin',
            'menus'          => 'Surat Tugas Mengajar',
            'pages'          => $pages,
            'system'         => System::first(),
            'academy'        => Kampus::first(),
            'tahunAkademiks' => TahunAkademik::orderBy('code', 'desc')->get(),
        ];
    }

    // Tab 1: DAFTAR SURAT TUGAS
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Surat Tugas Mengajar');

        $query = SuratTugasMengajar::with(['dosen', 'tahunAkademik']);

        if ($request->filled('tahun_akademik_id')) {
            $query->where('tahun_akademik_id', $request->tahun_akademik_id);
        }
        if ($request->filled('search')) {
            $query->whereHas('dosen', fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
            );
        }

        $data['surats']   = $query->latest()->paginate(20);
        $data['dosens']   = User::role('dosen')->orderBy('name')->get();
        $data['activeTab'] = 'cetak';

        return view('master.akademik.surat-tugas-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id'          => 'required|exists:users,id',
            'tahun_akademik_id' => 'nullable|exists:tahun_akademik,id',
            'nomor_surat'       => 'nullable|string|max:100',
            'tanggal_surat'     => 'nullable|date',
        ]);

        SuratTugasMengajar::create($request->only(
            'dosen_id', 'tahun_akademik_id', 'nomor_surat', 'perihal', 'tanggal_surat', 'status'
        ));

        Alert::success('Berhasil', 'Surat tugas berhasil dibuat.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        SuratTugasMengajar::findOrFail($id)->delete();
        Alert::success('Dihapus', 'Surat tugas berhasil dihapus.');
        return redirect()->back();
    }
}
