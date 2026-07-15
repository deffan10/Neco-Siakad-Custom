<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\BimbinganPa;
use App\Models\Akademik\DataMahasiswa;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class BimbinganPaController extends Controller
{
    /**
     * ==========================================
     * PANEL MAHASISWA: KONSULTASI PA ONLINE
     * ==========================================
     */
    public function indexStudent()
    {
        $user = Auth::user();
        $data['activeRole'] = 'mahasiswa';
        $data['menus'] = 'PA Online';
        $data['pages'] = 'Bimbingan & Konsultasi Akademik';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // Get student profile details
        $profile = DataMahasiswa::where('user_id', $user->id)->with('dosenPa')->first();
        $data['profile'] = $profile;

        if (!$profile || !$profile->dosen_pa_id) {
            $data['messages'] = collect();
            return view('private.mahasiswa.pa-online', $data, compact('user'));
        }

        // Get consultation chat logs
        $data['messages'] = BimbinganPa::where('mahasiswa_id', $user->id)
            ->where('dosen_id', $profile->dosen_pa_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('private.mahasiswa.pa-online', $data, compact('user'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string|min:2'
        ]);

        $user = Auth::user();
        $profile = DataMahasiswa::where('user_id', $user->id)->first();

        if (!$profile || !$profile->dosen_pa_id) {
            Alert::error('Gagal', 'Dosen Pembimbing Akademik (DPA) Anda belum diset.');
            return redirect()->back();
        }

        BimbinganPa::create([
            'mahasiswa_id' => $user->id,
            'dosen_id' => $profile->dosen_pa_id,
            'pesan' => $request->pesan,
            'pengirim' => 'Mahasiswa',
            'created_by' => $user->id
        ]);

        // Notify Dosen
        try {
            \App\Helpers\NotifikasiHelper::send(
                $profile->dosen_pa_id,
                'Bimbingan Baru dari Mahasiswa',
                $user->name . ' mengirimkan pesan bimbingan akademik baru.',
                'info',
                'comment-dots',
                route('dosen.perwalian.show', $user->id)
            );
        } catch (\Exception $e) {}

        return redirect()->back();
    }

    /**
     * ==========================================
     * PANEL DOSEN: MANAJEMEN BIMBINGAN PERWALIAN
     * ==========================================
     */
    public function indexDosen()
    {
        $user = Auth::user();
        $data['activeRole'] = 'dosen';
        $data['menus'] = 'Perwalian';
        $data['pages'] = 'Daftar Mahasiswa Perwalian (Advisees)';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // Get advisees list
        $data['advisees'] = DataMahasiswa::where('dosen_pa_id', $user->id)
            ->with(['user', 'programStudi'])
            ->get();

        return view('private.dosen.perwalian-list', $data, compact('user'));
    }

    public function showAdvising($mahasiswaId)
    {
        $user = Auth::user();
        $data['activeRole'] = 'dosen';
        $data['menus'] = 'Perwalian';
        $data['pages'] = 'Konsultasi Bimbingan Mahasiswa';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $studentUser = User::findOrFail($mahasiswaId);
        $data['student'] = $studentUser;

        // Get consultation chat logs
        $data['messages'] = BimbinganPa::where('mahasiswa_id', $mahasiswaId)
            ->where('dosen_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('private.dosen.perwalian-chat', $data, compact('user'));
    }

    public function storeDosen(Request $request, $mahasiswaId)
    {
        $request->validate([
            'pesan' => 'required|string|min:2'
        ]);

        $user = Auth::user();

        BimbinganPa::create([
            'mahasiswa_id' => $mahasiswaId,
            'dosen_id' => $user->id,
            'pesan' => $request->pesan,
            'pengirim' => 'Dosen',
            'created_by' => $user->id
        ]);

        // Notify Mahasiswa
        try {
            \App\Helpers\NotifikasiHelper::send(
                $mahasiswaId,
                'Balasan Bimbingan Akademik',
                'Dosen PA Anda memberikan tanggapan bimbingan akademik baru.',
                'info',
                'comment-dots',
                route('mahasiswa.pa-online.index')
            );
        } catch (\Exception $e) {}

        return redirect()->back();
    }

    /**
     * ==========================================
     * PANEL ADMIN: BIMBINGAN PA OVERVIEW
     * ==========================================
     */
    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Bimbingan PA Online';
        $data['pages'] = 'Laporan Bimbingan PA Online';
        $data['system'] = \App\Models\Pengaturan\System::first();
        $data['academy'] = \App\Models\Pengaturan\Kampus::first();

        // Group by dosen, count bimbingans
        $data['rekapDosen'] = BimbinganPa::selectRaw('dosen_id, COUNT(*) as total')
            ->with('dosen')
            ->groupBy('dosen_id')
            ->latest()
            ->get();

        $data['rekapMahasiswa'] = BimbinganPa::selectRaw('mahasiswa_id, COUNT(*) as total')
            ->with('mahasiswa')
            ->groupBy('mahasiswa_id')
            ->latest()
            ->take(20)
            ->get();

        return view('master.akademik.bimbingan-pa-admin', $data, compact('user'));
    }
}
