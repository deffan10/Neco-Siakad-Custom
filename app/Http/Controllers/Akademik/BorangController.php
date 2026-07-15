<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Akademik\DataMahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class BorangController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Borang Akreditasi',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    public function index()
    {
        $user = Auth::user();
        $data = $this->baseData('Dashboard Borang Akreditasi Institusi & PS');

        // 1. Rekapitulasi Mahasiswa & Lulusan per Prodi
        $data['mahasiswaPerProdi'] = ProgramStudi::where('is_active', true)
            ->withCount([
                'mahasiswas as total_aktif' => fn($q) => $q->whereHas('statusMahasiswa', fn($s) => $s->where('name', 'like', '%aktif%')),
                'mahasiswas as total_lulus' => fn($q) => $q->whereHas('statusMahasiswa', fn($s) => $s->where('name', 'like', '%lulus%')),
                'mahasiswas as total_cuti'  => fn($q) => $q->whereHas('statusMahasiswa', fn($s) => $s->where('name', 'like', '%cuti%')),
            ])
            ->get();

        // 2. Rekapitulasi Mahasiswa per Angkatan
        $data['mahasiswaPerAngkatan'] = DataMahasiswa::selectRaw('angkatan, COUNT(*) as total_mhs')
            ->whereNotNull('angkatan')
            ->groupBy('angkatan')
            ->orderBy('angkatan', 'desc')
            ->get();

        // 3. Rekap Dosen Pembimbing Akademik & Jumlah Bimbingan (Advisees)
        $data['rekapDosenPa'] = DataMahasiswa::selectRaw('dosen_pa_id, COUNT(*) as total_bimbingan')
            ->whereNotNull('dosen_pa_id')
            ->groupBy('dosen_pa_id')
            ->with('dosenPa')
            ->get();

        // 4. Staffing Summary
        $data['totalDosen'] = User::role('dosen')->count();
        $data['totalStaff'] = User::role('tendik')->count();

        return view('master.akademik.borang-index', $data, compact('user'));
    }
}
