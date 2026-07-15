<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\DispensasiMahasiswa;
use App\Models\Akademik\TahunAkademik;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class DispensasiController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole'     => session('active_role') ?? 'admin',
            'menus'          => 'Dispensasi Mahasiswa',
            'pages'          => $pages,
            'system'         => System::first(),
            'academy'        => Kampus::first(),
            'tahunAkademiks' => TahunAkademik::orderBy('code', 'desc')->get(),
        ];
    }

    // =====================================================
    // Tab 1: DAFTAR DISPENSASI
    // =====================================================
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Data Dispensasi Mahasiswa');

        $query = DispensasiMahasiswa::with(['mahasiswa', 'tahunAkademik', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tahun_akademik_id')) {
            $query->where('tahun_akademik_id', $request->tahun_akademik_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', fn($q) =>
                $q->where('name', 'like', "%{$search}%")->orWhere('username', 'like', "%{$search}%")
            );
        }

        $data['dispensasis'] = $query->latest()->paginate(20);
        $data['mahasiswas']  = User::role('mahasiswa')->orderBy('name')->get();

        return view('master.akademik.dispensasi-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'jenis'            => 'required|string|max:100',
            'alasan'           => 'required|string',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        DispensasiMahasiswa::create([
            'user_id'          => $request->user_id,
            'tahun_akademik_id' => $request->tahun_akademik_id ?: null,
            'jenis'            => $request->jenis,
            'alasan'           => $request->alasan,
            'tanggal_mulai'    => $request->tanggal_mulai,
            'tanggal_selesai'  => $request->tanggal_selesai,
            'status'           => 'Disetujui',
            'approved_by'      => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Dispensasi berhasil ditambahkan.');
        return redirect()->back();
    }

    // =====================================================
    // Tab 2: APPROVE / REJECT
    // =====================================================
    public function approve($id)
    {
        $d = DispensasiMahasiswa::findOrFail($id);
        $d->update(['status' => 'Disetujui', 'approved_by' => Auth::id()]);
        Alert::success('Disetujui', 'Dispensasi mahasiswa telah disetujui.');
        return redirect()->back();
    }

    public function reject(Request $request, $id)
    {
        $d = DispensasiMahasiswa::findOrFail($id);
        $d->update([
            'status'        => 'Ditolak',
            'catatan_admin' => $request->catatan ?? 'Ditolak oleh operator.',
            'approved_by'   => Auth::id(),
        ]);
        Alert::warning('Ditolak', 'Dispensasi mahasiswa telah ditolak.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        DispensasiMahasiswa::findOrFail($id)->delete();
        Alert::success('Dihapus', 'Data dispensasi berhasil dihapus.');
        return redirect()->back();
    }
}
