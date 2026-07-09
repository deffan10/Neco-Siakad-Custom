<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;
use App\Models\Akademik\DataMahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\AkumulasiIp;
use App\Models\Akademik\PendaftaranWisuda;
use App\Models\Akademik\Skl;

class SklController extends Controller
{
    /**
     * ==========================================
     * PANEL ADMIN: MANAJEMEN SKL
     * ==========================================
     */

    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'SKL';
        $data['pages'] = 'Daftar Surat Keterangan Lulus (SKL)';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $query = Skl::with(['mahasiswa.user', 'mahasiswa.programStudi']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('mahasiswa.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $data['skls'] = $query->latest()->paginate(15);

        return view('master.akademik.skl-index', $data, compact('user'));
    }

    public function createForm()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'SKL';
        $data['pages'] = 'Terbitkan SKL Baru';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // Get students who don't have an SKL yet
        $data['mahasiswas'] = DataMahasiswa::whereDoesntHave('user.dataAlumni')
            ->whereDoesntHave('user', function($q) {
                $q->whereHas('dataMahasiswa', function($q2) {
                    $q2->whereHas('skl');
                });
            })
            ->with('user')
            ->get()
            ->sortBy(function($m) {
                return $m->user->name ?? '';
            });

        return view('master.akademik.skl-create', $data, compact('user'));
    }

    public function getStudentData($id)
    {
        $mhs = DataMahasiswa::findOrFail($id);

        // Retrieve IPK from last calculation
        $ipkRecord = AkumulasiIp::where('user_id', $mhs->user_id)->orderBy('id', 'desc')->first();
        $ipk = $ipkRecord ? $ipkRecord->ipk : 0.00;

        // Retrieve Thesis Title from approved Wisuda application
        $wisuda = PendaftaranWisuda::where('mahasiswa_id', $id)
            ->whereIn('status', ['Diajukan', 'Disetujui'])
            ->first();
        $judulSkripsi = $wisuda ? $wisuda->judul_skripsi : '';

        return response()->json([
            'ipk' => number_format($ipk, 2),
            'judul_skripsi' => $judulSkripsi
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:data_mahasiswa,id|unique:skls,mahasiswa_id',
            'nomor_skl' => 'required|string|unique:skls,nomor_skl',
            'tanggal_lulus' => 'required|date',
            'ipk' => 'required|numeric|between:0,4.00',
            'yudisium' => 'required|string|max:255',
            'judul_skripsi' => 'required|string',
            'pejabat_penandatangan' => 'required|string|max:255',
            'jabatan_penandatangan' => 'required|string|max:255',
        ]);

        Skl::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'nomor_skl' => $request->nomor_skl,
            'tanggal_lulus' => $request->tanggal_lulus,
            'ipk' => $request->ipk,
            'yudisium' => $request->yudisium,
            'judul_skripsi' => $request->judul_skripsi,
            'pejabat_penandatangan' => $request->pejabat_penandatangan,
            'jabatan_penandatangan' => $request->jabatan_penandatangan,
            'created_by' => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Surat Keterangan Lulus (SKL) berhasil diterbitkan');
        return redirect()->route('admin.skl.index');
    }

    public function print($id)
    {
        $data['skl'] = Skl::with(['mahasiswa.user', 'mahasiswa.programStudi'])->findOrFail($id);
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        return view('master.akademik.skl-print', $data);
    }

    public function destroy($id)
    {
        $skl = Skl::findOrFail($id);
        $skl->delete();

        Alert::success('Berhasil', 'SKL berhasil dihapus');
        return redirect()->back();
    }

    /**
     * ==========================================
     * PANEL MAHASISWA: CETAK SKL MANDIRI
     * ==========================================
     */

    public function indexStudent()
    {
        $user = Auth::user();
        $data['activeRole'] = 'mahasiswa';
        $data['menus'] = 'SKL';
        $data['pages'] = 'Cetak Surat Keterangan Lulus';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $mhs = $user->dataMahasiswa;
        if (!$mhs) {
            Alert::error('Error', 'Data mahasiswa tidak ditemukan.');
            return redirect()->back();
        }

        $data['skl'] = Skl::where('mahasiswa_id', $mhs->id)->first();

        return view('private.student-skl', $data, compact('user', 'mhs'));
    }
}
