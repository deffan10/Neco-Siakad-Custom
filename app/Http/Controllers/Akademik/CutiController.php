<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\PengajuanCuti;
use App\Models\Akademik\TahunAkademik;
use App\Models\Mahasiswa;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class CutiController extends Controller
{
    /**
     * ==========================================
     * PANEL MAHASISWA: PENGAJUAN CUTI
     * ==========================================
     */
    public function indexStudent()
    {
        $user = Auth::user();
        $data['activeRole'] = 'mahasiswa';
        $data['menus'] = 'Cuti';
        $data['pages'] = 'Pengajuan Cuti Akademik';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // Get student's cuti requests
        $data['cutis'] = PengajuanCuti::where('mahasiswa_id', $user->id)
            ->with('tahunAkademik')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get active academic year for submission form
        $data['activeTa'] = TahunAkademik::where('is_active', true)->first();

        return view('private.mahasiswa.cuti', $data, compact('user'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'alasan' => 'required|string|min:10',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id'
        ]);

        $user = Auth::user();

        // Check if already requested for this academic year
        $exists = PengajuanCuti::where('mahasiswa_id', $user->id)
            ->where('tahun_akademik_id', $request->tahun_akademik_id)
            ->exists();

        if ($exists) {
            Alert::error('Gagal', 'Anda sudah mengajukan cuti untuk semester/tahun akademik ini.');
            return redirect()->back();
        }

        PengajuanCuti::create([
            'mahasiswa_id' => $user->id,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'alasan' => $request->alasan,
            'status' => 'Diajukan',
            'created_by' => $user->id
        ]);

        Alert::success('Berhasil', 'Pengajuan cuti akademik berhasil dikirim.');
        return redirect()->back();
    }

    /**
     * ==========================================
     * PANEL ADMIN: MANAJEMEN CUTI
     * ==========================================
     */
    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Cuti Akademik';
        $data['pages'] = 'Persetujuan Cuti Akademik Mahasiswa';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $query = PengajuanCuti::with(['mahasiswa', 'tahunAkademik']);

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('mahasiswa', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $data['cutis'] = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('master.akademik.cuti-admin', $data, compact('user'));
    }

    public function approveAdmin($id)
    {
        $cuti = PengajuanCuti::findOrFail($id);
        $cuti->update([
            'status' => 'Disetujui',
            'updated_by' => Auth::id()
        ]);

        // Send Notification to Mahasiswa
        try {
            \App\Helpers\NotifikasiHelper::send(
                $cuti->mahasiswa_id,
                'Pengajuan Cuti Disetujui',
                'Pengajuan cuti akademik Anda untuk semester/tahun ' . ($cuti->tahunAkademik->name ?? '') . ' telah disetujui.',
                'success',
                'calendar-check',
                route('mahasiswa.cuti.index')
            );
        } catch (\Exception $e) {}

        Alert::success('Berhasil', 'Pengajuan cuti akademik disetujui.');
        return redirect()->back();
    }

    public function rejectAdmin(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string'
        ]);

        $cuti = PengajuanCuti::findOrFail($id);
        $cuti->update([
            'status' => 'Ditolak',
            'catatan_admin' => $request->catatan_admin,
            'updated_by' => Auth::id()
        ]);

        // Send Notification to Mahasiswa
        try {
            \App\Helpers\NotifikasiHelper::send(
                $cuti->mahasiswa_id,
                'Pengajuan Cuti Ditolak',
                'Pengajuan cuti akademik Anda ditolak. Catatan: ' . $request->catatan_admin,
                'danger',
                'calendar-times',
                route('mahasiswa.cuti.index')
            );
        } catch (\Exception $e) {}

        Alert::success('Berhasil', 'Pengajuan cuti akademik ditolak.');
        return redirect()->back();
    }
}
