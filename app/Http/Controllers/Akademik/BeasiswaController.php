<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\JenisBeasiswa;
use App\Models\Akademik\BeasiswaMahasiswa;
use App\Models\Akademik\TahunAkademik;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class BeasiswaController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole'    => session('active_role') ?? 'admin',
            'menus'         => 'Beasiswa',
            'pages'         => $pages,
            'system'        => System::first(),
            'academy'       => Kampus::first(),
            'tahunAkademiks' => TahunAkademik::orderBy('code', 'desc')->get(),
        ];
    }

    // =========================================================
    // Tab 1: JENIS BEASISWA – manage beasiswa types
    // =========================================================
    public function indexJenis()
    {
        $user = Auth::user();
        $data = $this->baseData('Jenis Beasiswa');
        $data['jenisBeasiswas'] = JenisBeasiswa::withCount('penerimas')->latest()->get();

        return view('master.akademik.beasiswa-jenis', $data, compact('user'));
    }

    public function storeJenis(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'kode'  => 'required|string|max:50|unique:jenis_beasiswa,kode',
            'nominal' => 'nullable|numeric|min:0',
        ]);

        JenisBeasiswa::create($request->only('nama', 'kode', 'deskripsi', 'nominal'));
        Alert::success('Berhasil', 'Jenis beasiswa berhasil ditambahkan.');
        return redirect()->back();
    }

    public function destroyJenis($id)
    {
        JenisBeasiswa::findOrFail($id)->delete();
        Alert::success('Dihapus', 'Jenis beasiswa berhasil dihapus.');
        return redirect()->back();
    }

    // =========================================================
    // Tab 2: DATA BEASISWA MAHASISWA – entri & cari penerima
    // =========================================================
    public function indexData(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Data Penerima Beasiswa');

        $query = BeasiswaMahasiswa::with(['mahasiswa', 'jenisBeasiswa', 'tahunAkademik']);

        if ($request->filled('jenis_beasiswa_id')) {
            $query->where('jenis_beasiswa_id', $request->jenis_beasiswa_id);
        }
        if ($request->filled('tahun_akademik_id')) {
            $query->where('tahun_akademik_id', $request->tahun_akademik_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', fn($q) =>
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
            );
        }

        $data['beasiswas']       = $query->latest()->paginate(20);
        $data['jenisBeasiswas']  = JenisBeasiswa::where('is_active', true)->get();
        $data['mahasiswas']      = User::role('mahasiswa')->orderBy('name')->get();

        return view('master.akademik.beasiswa-data', $data, compact('user'));
    }

    public function storeData(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswa,id',
            'tahun_akademik_id' => 'nullable|exists:tahun_akademik,id',
            'tanggal_mulai'     => 'nullable|date',
            'tanggal_selesai'   => 'nullable|date|after_or_equal:tanggal_mulai',
            'nominal'           => 'nullable|numeric|min:0',
        ]);

        BeasiswaMahasiswa::create($request->only(
            'user_id', 'jenis_beasiswa_id', 'tahun_akademik_id',
            'tanggal_mulai', 'tanggal_selesai', 'nominal', 'status', 'keterangan'
        ));

        Alert::success('Berhasil', 'Data beasiswa mahasiswa berhasil disimpan.');
        return redirect()->back();
    }

    public function destroyData($id)
    {
        BeasiswaMahasiswa::findOrFail($id)->delete();
        Alert::success('Dihapus', 'Data beasiswa berhasil dihapus.');
        return redirect()->back();
    }

    // =========================================================
    // Tab 3: SALIN BEASISWA – copy from previous semester
    // =========================================================
    public function salin(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Salin Beasiswa Semester');
        $data['jenisBeasiswas'] = JenisBeasiswa::where('is_active', true)->get();

        return view('master.akademik.beasiswa-salin', $data, compact('user'));
    }

    public function processSalin(Request $request)
    {
        $request->validate([
            'dari_tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'ke_tahun_akademik_id'   => 'required|exists:tahun_akademik,id|different:dari_tahun_akademik_id',
        ]);

        $sumber = BeasiswaMahasiswa::where('tahun_akademik_id', $request->dari_tahun_akademik_id)->get();

        $count = 0;
        foreach ($sumber as $b) {
            $exists = BeasiswaMahasiswa::where('user_id', $b->user_id)
                ->where('jenis_beasiswa_id', $b->jenis_beasiswa_id)
                ->where('tahun_akademik_id', $request->ke_tahun_akademik_id)
                ->exists();

            if (!$exists) {
                BeasiswaMahasiswa::create([
                    'user_id'           => $b->user_id,
                    'jenis_beasiswa_id' => $b->jenis_beasiswa_id,
                    'tahun_akademik_id' => $request->ke_tahun_akademik_id,
                    'nominal'           => $b->nominal,
                    'status'            => 'Aktif',
                    'keterangan'        => 'Disalin dari semester sebelumnya',
                ]);
                $count++;
            }
        }

        Alert::success('Berhasil', "Berhasil menyalin {$count} data beasiswa ke semester tujuan.");
        return redirect()->back();
    }
}
