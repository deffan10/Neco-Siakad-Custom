<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Akademik\MataKuliah;
use App\Models\Akademik\KonversiNilai;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class KonversiNilaiController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Konversi Nilai',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Konversi Nilai Mahasiswa Transfer');

        $query = KonversiNilai::with(['mahasiswa', 'mataKuliah', 'creator']);

        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa', function($qm) use ($search) {
                    $qm->where('name', 'like', "%{$search}%")->orWhere('username', 'like', "%{$search}%");
                })->orWhere('mata_kuliah_asal', 'like', "%{$search}%");
            });
        }

        $data['konversiNilai'] = $query->latest()->paginate(25);
        $data['mahasiswas']    = User::role('mahasiswa')->orderBy('name')->get();
        $data['mataKuliahs']   = MataKuliah::where('is_active', true)->orderBy('name')->get();
        $data['activeTab']     = 'list';

        return view('master.akademik.konversi-nilai-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id'      => 'required|exists:users,id',
            'mata_kuliah_asal'  => 'required|string|max:255',
            'sks_asal'          => 'required|integer|min:1',
            'nilai_asal'        => 'required|string|max:5',
            'mata_kuliah_id'    => 'required|exists:mata_kuliah,id',
            'nilai_konversi'    => 'required|string|max:5',
            'sks_konversi'      => 'required|integer|min:1',
            'keterangan'        => 'nullable|string',
        ]);

        KonversiNilai::create([
            'mahasiswa_id'      => $request->mahasiswa_id,
            'mata_kuliah_asal'  => $request->mata_kuliah_asal,
            'sks_asal'          => $request->sks_asal,
            'nilai_asal'        => $request->nilai_asal,
            'mata_kuliah_id'    => $request->mata_kuliah_id,
            'nilai_konversi'    => $request->nilai_konversi,
            'sks_konversi'      => $request->sks_konversi,
            'keterangan'        => $request->keterangan,
            'created_by'        => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Konversi nilai berhasil disimpan.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $konversi = KonversiNilai::findOrFail($id);
        $konversi->delete();

        Alert::success('Berhasil', 'Data konversi nilai berhasil dihapus.');
        return redirect()->back();
    }
}
