<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;
use App\Models\Akademik\KelasMahasiswa;
use App\Models\Akademik\JadwalKelas;
use App\Models\Akademik\JadwalPertemuan;
use App\Models\Akademik\PresensiMahasiswa;

class JadwalPresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'mahasiswa';
        $data['menus'] = 'Jadwal & Presensi';
        $data['pages'] = 'Jadwal Kuliah & Presensi Mahasiswa';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // 1. Get student class enrollments
        $enrollments = KelasMahasiswa::where('mahasiswa_id', $user->id)
            ->with(['kelas.mataKuliah', 'kelas.tahunAkademik'])
            ->get();

        $kelasIds = $enrollments->pluck('kelas_id');

        // 2. Get schedules linked to these classes
        $jadwalKelas = JadwalKelas::whereIn('kelas_id', $kelasIds)
            ->with(['jadwal.mataKuliah', 'jadwal.dosen', 'jadwal.ruangan'])
            ->get();

        $jadwalIds = $jadwalKelas->pluck('jadwal_id');

        // Get schedules grouped by day for the timetable
        $timetable = [];
        foreach ($jadwalKelas as $jk) {
            $j = $jk->jadwal;
            if ($j) {
                $timetable[] = [
                    'hari' => $j->hari ?? 'N/A',
                    'jam_mulai' => $j->jam_mulai,
                    'jam_selesai' => $j->jam_selesai,
                    'mata_kuliah' => $j->mataKuliah->name ?? 'N/A',
                    'kelas' => $j->code,
                    'ruangan' => $j->ruangan->name ?? 'N/A',
                    'dosen' => $j->dosen->name ?? 'N/A'
                ];
            }
        }
        $data['timetable'] = $timetable;

        // 3. Get all scheduled meetings/sessions
        $pertemuans = JadwalPertemuan::whereIn('jadwal_id', $jadwalIds)
            ->with(['jadwal.mataKuliah', 'ruangan'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->get();
        $data['pertemuans'] = $pertemuans;

        // 4. Get all attendance logs of the student for these meetings
        $attendanceLogs = PresensiMahasiswa::where('mahasiswa_id', $user->id)
            ->whereIn('jadwal_pertemuan_id', $pertemuans->pluck('id'))
            ->get()
            ->keyBy('jadwal_pertemuan_id');
        $data['attendanceLogs'] = $attendanceLogs;

        // 5. Calculate attendance statistics per course/class
        $stats = [];
        foreach ($enrollments as $enr) {
            $classId = $enr->kelas_id;
            $courseName = $enr->kelas->mataKuliah->name ?? 'N/A';
            $classCode = $enr->kelas->code ?? 'N/A';
            
            $specificJadwalIds = $jadwalKelas->where('kelas_id', $classId)->pluck('jadwal_id');
            
            $classMeetings = $pertemuans->whereIn('jadwal_id', $specificJadwalIds);
            $totalHeld = $classMeetings->where('is_realisasi', true)->count();
            
            $hadir = 0;
            $sakit = 0;
            $izin = 0;
            $alpa = 0;
            
            foreach ($classMeetings as $meet) {
                if (isset($attendanceLogs[$meet->id])) {
                    $status = $attendanceLogs[$meet->id]->status;
                    if ($status === 'Hadir') $hadir++;
                    elseif ($status === 'Sakit') $sakit++;
                    elseif ($status === 'Izin') $izin++;
                    elseif ($status === 'Alpa') $alpa++;
                }
            }
            
            $pct = $totalHeld > 0 ? round(($hadir / $totalHeld) * 100, 1) : 100;
            
            $stats[] = [
                'course' => $courseName,
                'class' => $classCode,
                'total_held' => $totalHeld,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa,
                'percentage' => $pct
            ];
        }
        $data['stats'] = $stats;

        return view('private.mahasiswa.jadwal-presensi', $data, compact('user'));
    }
}
