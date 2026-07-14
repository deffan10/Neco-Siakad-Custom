<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\JadwalPertemuan;
use App\Models\Akademik\BahanTugasPertemuan;
use App\Models\Akademik\TugasMahasiswa;
use App\Models\Akademik\KelasMahasiswa;
use App\Models\Akademik\JadwalKelas;
use App\Models\Akademik\KelasPerkuliahan;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class BahanTugasController extends Controller
{
    /**
     * ==========================================
     * PANEL DOSEN: MANAJEMEN BAHAN & TUGAS PERTEMUAN
     * ==========================================
     */
    public function indexDosen($meetingId)
    {
        $user = Auth::user();
        $data['activeRole'] = 'dosen';
        $data['menus'] = 'Jurnal Mengajar';
        $data['pages'] = 'Bahan Ajar & Tugas Pertemuan Kuliah';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $pertemuan = JadwalPertemuan::with(['jadwal.mataKuliah', 'ruangan'])->findOrFail($meetingId);
        $data['pertemuan'] = $pertemuan;

        $data['items'] = BahanTugasPertemuan::where('jadwal_pertemuan_id', $meetingId)
            ->withCount('submisi')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('private.dosen.bahan-tugas', $data, compact('user'));
    }

    public function storeDosen(Request $request, $meetingId)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:Materi,Tugas',
            'file' => 'nullable|file|mimes:pdf,docx,xlsx,pptx,zip,jpg,png|max:10240', // Max 10MB
            'deadline' => 'nullable|date_format:Y-m-d\TH:i'
        ]);

        $pertemuan = JadwalPertemuan::findOrFail($meetingId);

        if ($pertemuan->is_locked) {
            Alert::error('Gagal', 'Pertemuan ini dikunci oleh admin. Tidak dapat menambah bahan/tugas.');
            return redirect()->back();
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('bahan_ajar', 'public');
        }

        BahanTugasPertemuan::create([
            'jadwal_pertemuan_id' => $meetingId,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'file_path' => $filePath,
            'deadline' => $request->deadline,
            'created_by' => Auth::id()
        ]);

        Alert::success('Berhasil', 'Bahan ajar / tugas berhasil ditambahkan.');
        return redirect()->back();
    }

    public function indexSubmissions($taskId)
    {
        $user = Auth::user();
        $data['activeRole'] = 'dosen';
        $data['menus'] = 'Jurnal Mengajar';
        $data['pages'] = 'Submisi Tugas Mahasiswa';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $task = BahanTugasPertemuan::with('pertemuan.jadwal.mataKuliah')->findOrFail($taskId);
        $data['task'] = $task;

        $kelasIds = JadwalKelas::where('jadwal_id', $task->pertemuan->jadwal_id)->pluck('kelas_id');
        $data['enrollments'] = KelasMahasiswa::whereIn('kelas_id', $kelasIds)
            ->with('mahasiswa')
            ->get();

        $data['submissions'] = TugasMahasiswa::where('bahan_tugas_pertemuan_id', $taskId)
            ->get()
            ->keyBy('mahasiswa_id');

        return view('private.dosen.tugas-submisi', $data, compact('user'));
    }

    public function gradeSubmission(Request $request, $submissionId)
    {
        $request->validate([
            'nilai' => 'required|integer|min:0|max:100'
        ]);

        $submission = TugasMahasiswa::findOrFail($submissionId);
        $submission->update([
            'nilai' => $request->nilai,
            'updated_by' => Auth::id()
        ]);

        Alert::success('Berhasil', 'Nilai tugas mahasiswa berhasil disimpan.');
        return redirect()->back();
    }

    /**
     * ==========================================
     * PANEL MAHASISWA: PORTAL BAHAN & TUGAS
     * ==========================================
     */
    public function indexStudent()
    {
        $user = Auth::user();
        $data['activeRole'] = 'mahasiswa';
        $data['menus'] = 'Bahan & Tugas';
        $data['pages'] = 'Mata Kuliah Saya — Bahan & Tugas';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['enrollments'] = KelasMahasiswa::where('mahasiswa_id', $user->id)
            ->with(['kelas.mataKuliah', 'kelas.tahunAkademik'])
            ->get();

        return view('private.mahasiswa.bahan-tugas-classes', $data, compact('user'));
    }

    public function showClassMaterials($classId)
    {
        $user = Auth::user();
        $data['activeRole'] = 'mahasiswa';
        $data['menus'] = 'Bahan & Tugas';
        $data['pages'] = 'Daftar Bahan & Tugas Kuliah';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $kelas = KelasPerkuliahan::with('mataKuliah')->findOrFail($classId);
        $data['kelas'] = $kelas;

        $jadwalIds = JadwalKelas::where('kelas_id', $classId)->pluck('jadwal_id');
        $meetingIds = JadwalPertemuan::whereIn('jadwal_id', $jadwalIds)->pluck('id');

        $data['items'] = BahanTugasPertemuan::whereIn('jadwal_pertemuan_id', $meetingIds)
            ->with('pertemuan')
            ->orderBy('created_at', 'desc')
            ->get();

        $data['submissions'] = TugasMahasiswa::where('mahasiswa_id', $user->id)
            ->whereIn('bahan_tugas_pertemuan_id', $data['items']->pluck('id'))
            ->get()
            ->keyBy('bahan_tugas_pertemuan_id');

        return view('private.mahasiswa.bahan-tugas-list', $data, compact('user'));
    }

    public function submitAssignment(Request $request, $taskId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,xlsx,zip,jpg,png|max:5120', // Max 5MB
            'catatan' => 'nullable|string'
        ]);

        $task = BahanTugasPertemuan::findOrFail($taskId);
        
        if ($task->deadline && now()->gt($task->deadline)) {
            Alert::error('Gagal', 'Batas waktu pengumpulan tugas ini sudah berakhir.');
            return redirect()->back();
        }

        $user = Auth::user();
        $filePath = $request->file('file')->store('submisi_tugas', 'public');

        TugasMahasiswa::updateOrCreate(
            [
                'bahan_tugas_pertemuan_id' => $taskId,
                'mahasiswa_id' => $user->id
            ],
            [
                'file_path' => $filePath,
                'catatan' => $request->catatan,
                'updated_by' => $user->id
            ]
        );

        Alert::success('Berhasil', 'Tugas kuliah berhasil dikumpulkan.');
        return redirect()->back();
    }
}
