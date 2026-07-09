<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\SertifikasiMahasiswa;
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;

class SertifikasiController extends Controller
{
    /* =========================================================
     *  ADMIN PANEL
     * ========================================================= */

    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus']   = 'Sertifikasi';
        $data['pages']   = 'Verifikasi Sertifikasi Mahasiswa (SKPI)';
        $data['system']  = System::first();
        $data['academy'] = Kampus::first();

        $query = SertifikasiMahasiswa::with('mahasiswa')->latest();

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_sertifikat', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $data['sertifikasis'] = $query->paginate(20);

        return view('master.akademik.sertifikasi-admin', $data, compact('user'));
    }

    public function updateVerifikasi(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Menunggu,Disetujui,Ditolak',
        ]);

        $sertifikasi = SertifikasiMahasiswa::findOrFail($id);
        $sertifikasi->update([
            'status_verifikasi'    => $request->status_verifikasi,
            'catatan_verifikasi'   => $request->catatan_verifikasi,
        ]);

        Alert::success('Berhasil', 'Status verifikasi sertifikat berhasil diperbarui.');
        return redirect()->back();
    }

    /* =========================================================
     *  PORTAL MAHASISWA
     * ========================================================= */

    public function indexPortal()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'mahasiswa';
        $data['menus']   = 'Sertifikasi';
        $data['pages']   = 'Sertifikasi & Kompetensi Saya';
        $data['system']  = System::first();
        $data['academy'] = Kampus::first();

        $data['sertifikasis'] = SertifikasiMahasiswa::where('mahasiswa_id', $user->id)
            ->latest()
            ->get();

        return view('private.portal-sertifikasi-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sertifikat'    => 'required|string|max:255',
            'lembaga_penerbit'   => 'required|string|max:255',
            'tanggal_terbit'     => 'required|date',
            'kategori'           => 'required|in:Bahasa,Teknologi,Profesi,Soft Skill,Lainnya',
            'file_sertifikat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
        ]);

        $filePath = null;
        if ($request->hasFile('file_sertifikat')) {
            $filePath = $request->file('file_sertifikat')
                ->store('sertifikasi', 'public');
        }

        SertifikasiMahasiswa::create([
            'mahasiswa_id'      => Auth::id(),
            'nama_sertifikat'   => $request->nama_sertifikat,
            'lembaga_penerbit'  => $request->lembaga_penerbit,
            'nomor_sertifikat'  => $request->nomor_sertifikat,
            'tanggal_terbit'    => $request->tanggal_terbit,
            'tanggal_kadaluarsa'=> $request->tanggal_kadaluarsa,
            'kategori'          => $request->kategori,
            'file_sertifikat'   => $filePath,
            'status_verifikasi' => 'Menunggu',
            'created_by'        => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Sertifikat berhasil diunggah dan menunggu verifikasi admin.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $sertifikasi = SertifikasiMahasiswa::where('mahasiswa_id', Auth::id())
            ->findOrFail($id);

        if ($sertifikasi->file_sertifikat) {
            Storage::disk('public')->delete($sertifikasi->file_sertifikat);
        }

        $sertifikasi->delete();
        Alert::success('Berhasil', 'Sertifikat berhasil dihapus.');
        return redirect()->back();
    }
}
