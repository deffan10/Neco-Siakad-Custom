<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\SesiKuliah;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class SesiKuliahController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Sesi Kuliah',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    // Tab 1: SESI KULIAH
    public function indexKuliah()
    {
        $user = Auth::user();
        $data = $this->baseData('Sesi Kuliah');
        $data['sesis'] = SesiKuliah::where('tipe', 'Kuliah')->orderBy('jam_mulai')->get();
        $data['activeTab'] = 'kuliah';

        return view('master.akademik.sesi-kuliah-index', $data, compact('user'));
    }

    // Tab 2: SESI UJIAN
    public function indexUjian()
    {
        $user = Auth::user();
        $data = $this->baseData('Sesi Ujian');
        $data['sesis'] = SesiKuliah::where('tipe', 'Ujian')->orderBy('jam_mulai')->get();
        $data['activeTab'] = 'ujian';

        return view('master.akademik.sesi-kuliah-index', $data, compact('user'));
    }

    // Tab 3: KELOMPOK SESI
    public function indexKelompok()
    {
        $user = Auth::user();
        $data = $this->baseData('Kelompok Sesi');
        $data['sesis'] = SesiKuliah::whereNotNull('kelompok')->orderBy('kelompok')->get();
        $data['activeTab'] = 'kelompok';

        return view('master.akademik.sesi-kuliah-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'        => 'required|string|max:20',
            'nama'        => 'required|string|max:100',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'tipe'        => 'required|in:Kuliah,Ujian',
        ]);

        SesiKuliah::create($request->only(
            'kode', 'nama', 'jam_mulai', 'jam_selesai', 'tipe', 'kelompok', 'durasi_menit'
        ));

        Alert::success('Berhasil', 'Sesi berhasil ditambahkan.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        SesiKuliah::findOrFail($id)->delete();
        Alert::success('Dihapus', 'Sesi berhasil dihapus.');
        return redirect()->back();
    }
}
