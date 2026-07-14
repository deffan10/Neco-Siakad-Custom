<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\JadwalPertemuan;
use App\Models\Akademik\JadwalPerkuliahan;
use App\Models\Akademik\PresensiMahasiswa;
use App\Models\Akademik\TahunAkademik;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class PresensiKuliahAdminController extends Controller
{
    protected function baseData($menus, $pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => $menus,
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    // =====================================================
    // Tab 1: PRESENSI DOSEN - overview per jadwal perkuliahan
    // =====================================================
    public function indexDosen(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Presensi Kuliah', 'Presensi Dosen / Asisten');

        $query = JadwalPerkuliahan::with(['mataKuliah', 'dosen', 'tahunAkademik'])
            ->whereHas('tahunAkademik', fn($q) => $q->where('is_active', true));

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mataKuliah', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('dosen', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $data['jadwals'] = $query->latest()->paginate(20);

        return view('master.akademik.presensi-kuliah-dosen', $data, compact('user'));
    }

    // =====================================================
    // Tab 2: PRESENSI MAHASISWA - per jadwal pertemuan
    // =====================================================
    public function indexMahasiswa(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Presensi Kuliah', 'Rekap Presensi Mahasiswa');

        $query = PresensiMahasiswa::with(['mahasiswa', 'pertemuan.jadwalPerkuliahan.mataKuliah']);

        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        $data['presensis']  = $query->latest()->paginate(20);
        $data['mahasiswas'] = User::role('mahasiswa')->orderBy('name')->get();

        return view('master.akademik.presensi-kuliah-mahasiswa', $data, compact('user'));
    }

    // =====================================================
    // Tab 3: SETTING PRESENSI - toggle open/close per pertemuan
    // =====================================================
    public function settingPresensi(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Presensi Kuliah', 'Setting Presensi');

        $data['pertemuans'] = JadwalPertemuan::with(['jadwalPerkuliahan.mataKuliah', 'jadwalPerkuliahan.dosen'])
            ->latest()
            ->paginate(20);

        return view('master.akademik.presensi-kuliah-setting', $data, compact('user'));
    }

    public function toggleLockPertemuan($id)
    {
        $pertemuan = JadwalPertemuan::findOrFail($id);
        $pertemuan->update(['is_locked' => !$pertemuan->is_locked]);

        $status = $pertemuan->is_locked ? 'dikunci' : 'dibuka';
        Alert::success('Berhasil', "Pertemuan berhasil {$status}.");
        return redirect()->back();
    }
}
