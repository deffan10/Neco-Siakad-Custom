<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Akademik\TahunAkademik;
use App\Models\Akademik\KrsMahasiswa;
use App\Models\Akademik\KelasPerkuliahan;
use App\Models\Akademik\KelasMahasiswa;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class CetakKartuController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Cetak Kartu',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    public function index()
    {
        $data = $this->baseData('Cetak Kartu & Absensi');
        $data['tahunAkademiks'] = TahunAkademik::orderBy('code', 'desc')->get();
        $data['mahasiswas'] = User::role('mahasiswa')->orderBy('name')->get();
        $data['kelasPerkuliahans'] = KelasPerkuliahan::with(['mataKuliah', 'tahunAkademik', 'programStudi'])->latest()->get();

        return view('master.akademik.cetak-kartu-index', $data);
    }

    // 1. Cetak KRS
    public function printKrs(Request $request)
    {
        $request->validate([
            'mahasiswa_id'      => 'required|exists:users,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
        ]);

        $student = User::findOrFail($request->mahasiswa_id);
        $ta = TahunAkademik::findOrFail($request->tahun_akademik_id);
        
        $krs = KrsMahasiswa::where('mahasiswa_id', $request->mahasiswa_id)
            ->where('tahun_akademik_id', $request->tahun_akademik_id)
            ->with(['details.kelas.mataKuliah', 'dosenPa'])
            ->first();

        $system = System::first();
        $academy = Kampus::first();

        return view('master.akademik.prints.krs', compact('student', 'ta', 'krs', 'system', 'academy'));
    }

    // 2. Cetak Kartu Ujian
    public function printKartuUjian(Request $request)
    {
        $request->validate([
            'mahasiswa_id'      => 'required|exists:users,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'jenis_ujian'       => 'required|in:UTS,UAS',
        ]);

        $student = User::findOrFail($request->mahasiswa_id);
        $ta = TahunAkademik::findOrFail($request->tahun_akademik_id);
        $jenisUjian = $request->jenis_ujian;

        $krs = KrsMahasiswa::where('mahasiswa_id', $request->mahasiswa_id)
            ->where('tahun_akademik_id', $request->tahun_akademik_id)
            ->with('details.kelas.mataKuliah')
            ->first();

        $system = System::first();
        $academy = Kampus::first();

        return view('master.akademik.prints.kartu-ujian', compact('student', 'ta', 'jenisUjian', 'krs', 'system', 'academy'));
    }

    // 3. Cetak Absensi Kuliah
    public function printAbsensiKuliah(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas_perkuliahan,id',
        ]);

        $kelas = KelasPerkuliahan::with(['mataKuliah', 'tahunAkademik', 'programStudi'])->findOrFail($request->kelas_id);
        $mahasiswas = User::whereHas('kelasMahasiswa', function($q) use ($request) {
            $q->where('kelas_id', $request->kelas_id);
        })->orderBy('name')->get();

        $system = System::first();
        $academy = Kampus::first();

        return view('master.akademik.prints.absensi-kuliah', compact('kelas', 'mahasiswas', 'system', 'academy'));
    }

    // 4. Cetak Absensi Ujian
    public function printAbsensiUjian(Request $request)
    {
        $request->validate([
            'kelas_id'    => 'required|exists:kelas_perkuliahan,id',
            'jenis_ujian' => 'required|in:UTS,UAS',
        ]);

        $kelas = KelasPerkuliahan::with(['mataKuliah', 'tahunAkademik', 'programStudi'])->findOrFail($request->kelas_id);
        $mahasiswas = User::whereHas('kelasMahasiswa', function($q) use ($request) {
            $q->where('kelas_id', $request->kelas_id);
        })->orderBy('name')->get();

        $jenisUjian = $request->jenis_ujian;
        $system = System::first();
        $academy = Kampus::first();

        return view('master.akademik.prints.absensi-ujian', compact('kelas', 'mahasiswas', 'jenisUjian', 'system', 'academy'));
    }
}
