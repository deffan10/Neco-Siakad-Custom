<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Akademik\AsistenLab;
use App\Models\Akademik\AsistenLabMatakuliah;
use App\Models\Akademik\MataKuliah;
use App\Models\Akademik\TahunAkademik;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class AsistenLabController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Asisten Lab',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Manajemen Asisten Lab');

        $query = AsistenLab::with(['user', 'assignments.mataKuliah', 'assignments.tahunAkademik']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('username', 'like', "%{$search}%");
            });
        }

        $data['assistants']   = $query->latest()->paginate(20);
        $data['mataKuliahs']  = MataKuliah::where('is_active', true)->orderBy('name')->get();
        $data['tahunAkademiks'] = TahunAkademik::orderBy('code', 'desc')->get();
        
        // Load candidates (students and other roles) to become lab assistants
        $data['candidates']   = User::whereDoesntHave('dataDosen')
            ->whereDoesntHave('dataKaryawan')
            ->whereDoesntHave('dataAlumni')
            ->orderBy('name')
            ->get();

        return view('master.akademik.asisten-lab-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:asisten_lab,user_id',
            'nim_nip' => 'required|string|max:50',
            'catatan' => 'nullable|string',
        ]);

        AsistenLab::create([
            'user_id'    => $request->user_id,
            'nim_nip'    => $request->nim_nip,
            'catatan'    => $request->catatan,
            'is_active'  => true,
            'created_by' => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Asisten laboratorium berhasil ditambahkan.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        AsistenLab::findOrFail($id)->delete();
        Alert::success('Dihapus', 'Data asisten lab berhasil dihapus.');
        return redirect()->back();
    }

    // Assign Mata Kuliah to Assistant
    public function assignMk(Request $request)
    {
        $request->validate([
            'asisten_lab_id'    => 'required|exists:asisten_lab,id',
            'mata_kuliah_id'    => 'required|exists:mata_kuliah,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
        ]);

        // Check if already assigned
        $exists = AsistenLabMatakuliah::where('asisten_lab_id', $request->asisten_lab_id)
            ->where('mata_kuliah_id', $request->mata_kuliah_id)
            ->where('tahun_akademik_id', $request->tahun_akademik_id)
            ->exists();

        if ($exists) {
            Alert::warning('Gagal', 'Asisten lab sudah ditugaskan ke mata kuliah tersebut pada semester ini.');
            return redirect()->back();
        }

        AsistenLabMatakuliah::create($request->only('asisten_lab_id', 'mata_kuliah_id', 'tahun_akademik_id'));

        Alert::success('Berhasil', 'Mata kuliah berhasil ditugaskan ke asisten lab.');
        return redirect()->back();
    }

    public function destroyAssignment($id)
    {
        AsistenLabMatakuliah::findOrFail($id)->delete();
        Alert::success('Dihapus', 'Penugasan mata kuliah asisten lab berhasil dihapus.');
        return redirect()->back();
    }
}
