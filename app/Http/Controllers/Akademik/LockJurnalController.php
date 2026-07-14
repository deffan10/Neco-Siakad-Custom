<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;
use App\Models\Akademik\JadwalPertemuan;
use App\Models\Akademik\PresensiMahasiswa;
use App\Models\Akademik\KelasMahasiswa;
use App\Models\Akademik\JadwalKelas;
use App\Models\Mahasiswa;

class LockJurnalController extends Controller
{
    /**
     * ==========================================
     * PANEL ADMIN: MANAJEMEN LOCK JURNAL
     * ==========================================
     */

    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Lock Jurnal';
        $data['pages'] = 'Kontrol Penguncian Jurnal Mengajar Dosen';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $query = JadwalPertemuan::with(['jadwal.mataKuliah', 'dosen', 'ruangan']);

        // Filter status (Kunci/Buka)
        if ($request->has('status') && $request->status != '') {
            $query->where('is_locked', $request->status === 'kunci');
        }

        // Filter search (Mata Kuliah / Dosen)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('jadwal.mataKuliah', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                })->orWhereHas('dosen', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        $data['pertemuans'] = $query->orderBy('tanggal', 'desc')->paginate(20);

        return view('master.akademik.lock-jurnal-admin', $data, compact('user'));
    }

    public function toggleLock($id)
    {
        $pertemuan = JadwalPertemuan::findOrFail($id);
        $newStatus = !$pertemuan->is_locked;
        $pertemuan->update(['is_locked' => $newStatus]);

        // Send Notification to Dosen
        try {
            if ($newStatus && $pertemuan->dosen_id) {
                \App\Helpers\NotifikasiHelper::send(
                    $pertemuan->dosen_id,
                    'Jurnal Mengajar Dikunci',
                    'Jurnal mengajar Anda untuk pertemuan ke-' . $pertemuan->pertemuan_ke . ' telah dikunci oleh admin.',
                    'warning',
                    'lock',
                    route('dosen.lock-jurnal.index')
                );
            }
        } catch (\Exception $e) {}

        Alert::success('Berhasil', 'Status kunci pertemuan berhasil diperbarui');
        return redirect()->back();
    }

    public function bulkLock(Request $request)
    {
        $request->validate([
            'tanggal_batas' => 'required|date',
            'action' => 'required|in:lock,unlock',
        ]);

        $status = $request->action === 'lock';
        
        $pertemuans = JadwalPertemuan::whereDate('tanggal', '<=', $request->tanggal_batas)->get();
        
        $count = 0;
        foreach ($pertemuans as $pertemuan) {
            $pertemuan->update(['is_locked' => $status]);
            $count++;

            // Notify Lecturer
            try {
                if ($status && $pertemuan->dosen_id) {
                    \App\Helpers\NotifikasiHelper::send(
                        $pertemuan->dosen_id,
                        'Jurnal Mengajar Dikunci (Bulk)',
                        'Jurnal mengajar untuk pertemuan ke-' . $pertemuan->pertemuan_ke . ' tanggal ' . $pertemuan->tanggal . ' telah dikunci masal oleh admin.',
                        'warning',
                        'lock',
                        route('dosen.lock-jurnal.index')
                    );
                }
            } catch (\Exception $e) {}
        }

        $msg = $request->action === 'lock' ? "mengunci" : "membuka kunci";
        Alert::success('Berhasil', "Telah berhasil {$msg} {$count} pertemuan kuliah.");
        return redirect()->back();
    }

    /**
     * ==========================================
     * PANEL DOSEN: PENGISIAN JURNAL MENGAJAR
     * ==========================================
     */

    public function indexDosen()
    {
        $user = Auth::user();
        $data['activeRole'] = 'dosen';
        $data['menus'] = 'Jurnal Mengajar';
        $data['pages'] = 'Realisasi Jurnal Mengajar Dosen';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // Get meetings assigned directly to this dosen, or through schedule
        $data['pertemuans'] = JadwalPertemuan::where(function($q) use ($user) {
                $q->where('dosen_id', $user->id)
                  ->orWhereHas('jadwal', function($q2) use ($user) {
                      $q2->where('dosen_id', $user->id);
                  });
            })
            ->with(['jadwal.mataKuliah', 'ruangan'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->get();

        return view('private.dosen-jurnal-index', $data, compact('user'));
    }

    public function updateJurnal(Request $request, $id)
    {
        $request->validate([
            'materi' => 'required|string',
            'link' => 'nullable|url',
        ]);

        $pertemuan = JadwalPertemuan::findOrFail($id);

        // Verify lock state
        if ($pertemuan->is_locked) {
            Alert::error('Gagal', 'Jurnal untuk pertemuan kuliah ini telah dikunci oleh bagian akademik.');
            return redirect()->back();
        }

        $pertemuan->update([
            'materi' => $request->materi,
            'link' => $request->link,
            'status' => 'Terlaksana',
            'is_realisasi' => true,
            'updated_by' => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Jurnal mengajar perkuliahan berhasil diperbarui');
        return redirect()->back();
    }

    public function presensiAdmin($id)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Lock Jurnal';
        $data['pages'] = 'Detail Presensi Pertemuan Kuliah';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $pertemuan = JadwalPertemuan::with(['jadwal.mataKuliah', 'dosen', 'ruangan'])->findOrFail($id);
        $data['pertemuan'] = $pertemuan;

        // Get class IDs mapped to this schedule
        $kelasIds = JadwalKelas::where('jadwal_id', $pertemuan->jadwal_id)->pluck('kelas_id');

        // Enrolled students
        $data['students'] = KelasMahasiswa::whereIn('kelas_id', $kelasIds)
            ->with('mahasiswa')
            ->get()
            ->pluck('mahasiswa')
            ->unique('id');

        // Existing attendance records
        $data['attendance'] = PresensiMahasiswa::where('jadwal_pertemuan_id', $id)
            ->pluck('status', 'mahasiswa_id')
            ->toArray();

        $data['catatan'] = PresensiMahasiswa::where('jadwal_pertemuan_id', $id)
            ->pluck('catatan', 'mahasiswa_id')
            ->toArray();

        return view('master.akademik.presensi-form', $data, compact('user'));
    }

    public function storePresensiAdmin(Request $request, $id)
    {
        $pertemuan = JadwalPertemuan::findOrFail($id);

        $statuses = $request->input('status', []);
        $catatans = $request->input('catatan', []);

        foreach ($statuses as $mahasiswaId => $status) {
            PresensiMahasiswa::updateOrCreate(
                [
                    'jadwal_pertemuan_id' => $id,
                    'mahasiswa_id' => $mahasiswaId
                ],
                [
                    'status' => $status,
                    'catatan' => $catatans[$mahasiswaId] ?? null,
                    'updated_by' => Auth::id()
                ]
            );
        }

        Alert::success('Berhasil', 'Data presensi mahasiswa berhasil diperbarui');
        return redirect()->route('admin.lock-jurnal.index');
    }

    public function presensiDosen($id)
    {
        $user = Auth::user();
        $data['activeRole'] = 'dosen';
        $data['menus'] = 'Jurnal Mengajar';
        $data['pages'] = 'Isi Presensi Pertemuan Kuliah';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $pertemuan = JadwalPertemuan::with(['jadwal.mataKuliah', 'ruangan'])->findOrFail($id);
        $data['pertemuan'] = $pertemuan;

        // Check if journal is locked
        if ($pertemuan->is_locked) {
            Alert::error('Gagal', 'Pertemuan ini telah dikunci. Anda tidak dapat mengisi presensi.');
            return redirect()->back();
        }

        // Get class IDs mapped to this schedule
        $kelasIds = JadwalKelas::where('jadwal_id', $pertemuan->jadwal_id)->pluck('kelas_id');

        // Enrolled students
        $data['students'] = KelasMahasiswa::whereIn('kelas_id', $kelasIds)
            ->with('mahasiswa')
            ->get()
            ->pluck('mahasiswa')
            ->unique('id');

        // Existing attendance records
        $data['attendance'] = PresensiMahasiswa::where('jadwal_pertemuan_id', $id)
            ->pluck('status', 'mahasiswa_id')
            ->toArray();

        $data['catatan'] = PresensiMahasiswa::where('jadwal_pertemuan_id', $id)
            ->pluck('catatan', 'mahasiswa_id')
            ->toArray();

        return view('master.akademik.presensi-form', $data, compact('user'));
    }

    public function storePresensiDosen(Request $request, $id)
    {
        $pertemuan = JadwalPertemuan::findOrFail($id);

        if ($pertemuan->is_locked) {
            Alert::error('Gagal', 'Pertemuan ini telah dikunci. Anda tidak dapat mengisi presensi.');
            return redirect()->back();
        }

        $statuses = $request->input('status', []);
        $catatans = $request->input('catatan', []);

        foreach ($statuses as $mahasiswaId => $status) {
            PresensiMahasiswa::updateOrCreate(
                [
                    'jadwal_pertemuan_id' => $id,
                    'mahasiswa_id' => $mahasiswaId
                ],
                [
                    'status' => $status,
                    'catatan' => $catatans[$mahasiswaId] ?? null,
                    'updated_by' => Auth::id()
                ]
            );
        }

        Alert::success('Berhasil', 'Data presensi mahasiswa berhasil disimpan');
        return redirect()->route('dosen.jurnal.index');
    }
}
