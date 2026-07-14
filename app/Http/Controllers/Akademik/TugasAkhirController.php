<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\TugasAkhir;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class TugasAkhirController extends Controller
{
    /**
     * ==========================================
     * PANEL MAHASISWA: PENGAJUAN TUGAS AKHIR
     * ==========================================
     */
    public function indexStudent()
    {
        $user = Auth::user();
        $data['activeRole'] = 'mahasiswa';
        $data['menus'] = 'Tugas Akhir';
        $data['pages'] = 'Pendaftaran Tugas Akhir / Proposal';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // Get student's submissions
        $data['items'] = TugasAkhir::where('mahasiswa_id', $user->id)
            ->with('dosenPembimbing')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get Dosen list for supervisor dropdown
        $data['lecturers'] = User::orderBy('name', 'asc')->get();

        return view('private.mahasiswa.tugas-akhir', $data, compact('user'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:Proposal,Skripsi',
            'judul' => 'required|string|max:255',
            'abstrak' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,zip,docx|max:10240', // Max 10MB
            'dosen_pembimbing_id' => 'required|exists:users,id'
        ]);

        $user = Auth::user();

        // Check if student has already an active/approved/pending submission of this type
        $exists = TugasAkhir::where('mahasiswa_id', $user->id)
            ->where('tipe', $request->tipe)
            ->whereIn('status', ['Diajukan', 'Disetujui'])
            ->exists();

        if ($exists) {
            Alert::error('Gagal', 'Anda sudah memiliki pengajuan ' . $request->tipe . ' yang sedang aktif.');
            return redirect()->back();
        }

        $filePath = $request->file('file')->store('tugas_akhir_drafts', 'public');

        TugasAkhir::create([
            'mahasiswa_id' => $user->id,
            'tipe' => $request->tipe,
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'file_draft' => $filePath,
            'dosen_pembimbing_id' => $request->dosen_pembimbing_id,
            'status' => 'Diajukan',
            'created_by' => $user->id
        ]);

        // Notify DPA / Supervisor
        try {
            \App\Helpers\NotifikasiHelper::send(
                $request->dosen_pembimbing_id,
                'Pengajuan Bimbingan Tugas Akhir',
                $user->name . ' mengajukan ' . $request->tipe . ' baru dengan judul: ' . $request->judul,
                'info',
                'book',
                route('admin.tugas-akhir.index')
            );
        } catch (\Exception $e) {}

        Alert::success('Berhasil', 'Pengajuan tugas akhir / proposal berhasil dikirim.');
        return redirect()->back();
    }

    /**
     * ==========================================
     * PANEL ADMIN: MANAJEMEN TUGAS AKHIR
     * ==========================================
     */
    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Tugas Akhir';
        $data['pages'] = 'Persetujuan Tugas Akhir & Proposal';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $query = TugasAkhir::with(['mahasiswa', 'dosenPembimbing']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        $data['items'] = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('master.akademik.tugas-akhir-admin', $data, compact('user'));
    }

    public function approveAdmin($id)
    {
        $ta = TugasAkhir::findOrFail($id);
        $ta->update([
            'status' => 'Disetujui',
            'updated_by' => Auth::id()
        ]);

        // Notify student
        try {
            \App\Helpers\NotifikasiHelper::send(
                $ta->mahasiswa_id,
                'Pengajuan Tugas Akhir Disetujui',
                'Pengajuan ' . $ta->tipe . ' Anda dengan judul "' . $ta->judul . '" telah disetujui.',
                'success',
                'check-circle',
                route('mahasiswa.tugas-akhir.index')
            );
        } catch (\Exception $e) {}

        Alert::success('Berhasil', 'Pengajuan tugas akhir disetujui.');
        return redirect()->back();
    }

    public function rejectAdmin(Request $request, $id)
    {
        $request->validate([
            'catatan_review' => 'required|string'
        ]);

        $ta = TugasAkhir::findOrFail($id);
        $ta->update([
            'status' => 'Ditolak',
            'catatan_review' => $request->catatan_review,
            'updated_by' => Auth::id()
        ]);

        // Notify student
        try {
            \App\Helpers\NotifikasiHelper::send(
                $ta->mahasiswa_id,
                'Pengajuan Tugas Akhir Ditolak',
                'Pengajuan ' . $ta->tipe . ' Anda ditolak. Catatan: ' . $request->catatan_review,
                'danger',
                'times-circle',
                route('mahasiswa.tugas-akhir.index')
            );
        } catch (\Exception $e) {}

        Alert::success('Berhasil', 'Pengajuan tugas akhir ditolak.');
        return redirect()->back();
    }
}
