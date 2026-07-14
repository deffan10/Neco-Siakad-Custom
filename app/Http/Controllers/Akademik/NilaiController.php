<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;
use App\Models\Akademik\TahunAkademik;
use App\Models\Akademik\KelasPerkuliahan;
use App\Models\Akademik\KelasMahasiswa;
use App\Models\Akademik\NilaiKuliah;
use App\Models\Akademik\AkumulasiIp;
use App\Models\Akademik\KrsMahasiswa;

class NilaiController extends Controller
{
    /**
     * ==========================================
     * PANEL DOSEN & ADMIN: INPUT NILAI KULIAH
     * ==========================================
     */

    public function indexKelas(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'dosen';
        $data['menus'] = 'Nilai Kelas';
        $data['pages'] = 'Input Nilai Kelas';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $activeTa = TahunAkademik::where('is_active', true)->first();
        if (!$activeTa) {
            Alert::error('Error', 'Tahun akademik aktif tidak ditemukan.');
            return redirect()->back();
        }

        // Admin sees all classes, lecturer sees only their classes
        // Note: mata_kuliah_dosen maps lecturers to courses, but we can also check classes.
        // Let's get classes. If admin, get all. If lecturer, filter by classes they teach.
        // We will assume admin can see all for now.
        $query = KelasPerkuliahan::where('tahun_akademik_id', $activeTa->id)
            ->with(['mataKuliah', 'programStudi']);

        $data['kelases'] = $query->latest()->get();

        return view('master.akademik.nilai-kelas-list', $data, compact('user'));
    }

    public function showFormNilai($kelasId)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'dosen';
        $data['menus'] = 'Nilai Kelas';
        $data['pages'] = 'Input Nilai';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['kelas'] = KelasPerkuliahan::with(['mataKuliah', 'programStudi'])->findOrFail($kelasId);
        
        // Fetch students in this class
        $data['students'] = KelasMahasiswa::where('kelas_id', $kelasId)
            ->with(['mahasiswa', 'nilai'])
            ->get();

        return view('master.akademik.nilai-input-form', $data, compact('user'));
    }

    public function storeNilai(Request $request, $kelasId)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.tugas' => 'required|numeric|between:0,100',
            'nilai.*.uts' => 'required|numeric|between:0,100',
            'nilai.*.uas' => 'required|numeric|between:0,100',
        ]);

        foreach ($request->nilai as $kelasMhsId => $scores) {
            $tugas = $scores['tugas'];
            $uts = $scores['uts'];
            $uas = $scores['uas'];

            // Calculate final score: e.g., 30% Tugas, 30% UTS, 40% UAS
            $akhir = ($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4);

            // Convert to letter grade and index
            if ($akhir >= 85) {
                $huruf = 'A';
                $indeks = 4.00;
            } elseif ($akhir >= 70) {
                $huruf = 'B';
                $indeks = 3.00;
            } elseif ($akhir >= 55) {
                $huruf = 'C';
                $indeks = 2.00;
            } elseif ($akhir >= 40) {
                $huruf = 'D';
                $indeks = 1.00;
            } else {
                $huruf = 'E';
                $indeks = 0.00;
            }

            // Save or update NilaiKuliah
            NilaiKuliah::updateOrCreate(
                ['kelas_mahasiswa_id' => $kelasMhsId],
                [
                    'nilai_tugas' => $tugas,
                    'nilai_uts' => $uts,
                    'nilai_uas' => $uas,
                    'nilai_akhir_angka' => $akhir,
                    'nilai_huruf' => $huruf,
                    'bobot_indeks' => $indeks
                ]
            );
        }

        Alert::success('Berhasil', 'Nilai mahasiswa berhasil disimpan.');
        return redirect()->route('admin.nilai.kelas-index');
    }


    /**
     * ==========================================
     * PANEL ADMIN: PROSES IPS/IPK MASSAL (prosesipkbaru)
     * ==========================================
     */

    public function showProsesIpk()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Proses IPS/IPK';
        $data['pages'] = 'Proses IPS / IPK Mahasiswa';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['tahunAkademiks'] = TahunAkademik::orderBy('code', 'desc')->get();
        $data['programStudis'] = ProgramStudi::orderBy('name')->get();

        return view('master.akademik.proses-ipk', $data, compact('user'));
    }

    public function hitungIpsIpkMassal(Request $request)
    {
        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
        ]);

        $ta = TahunAkademik::findOrFail($request->tahun_akademik_id);

        // Fetch all students who have classes in this semester
        $students = User::role('mahasiswa')
            ->whereHas('kelasEnrolled', function($q) use ($ta) {
                $q->whereHas('kelas', function($c) use ($ta) {
                    $c->where('tahun_akademik_id', $ta->id);
                });
            })->get();

        if ($students->isEmpty()) {
            Alert::warning('Peringatan', 'Tidak ada data nilai atau aktivitas perkuliahan pada semester terpilih.');
            return redirect()->back();
        }

        $processed = 0;
        foreach ($students as $student) {
            // 1. Calculate IPS for this semester
            $semesterEnrolled = KelasMahasiswa::where('mahasiswa_id', $student->id)
                ->whereHas('kelas', function($q) use ($ta) {
                    $q->where('tahun_akademik_id', $ta->id);
                })
                ->with(['kelas.mataKuliah', 'nilai'])
                ->get();

            $totalSksSemester = 0;
            $weightedGradeSemester = 0;

            foreach ($semesterEnrolled as $enroll) {
                $sks = $enroll->kelas->mataKuliah->beban_sks ?? 0;
                $indeks = $enroll->nilai->bobot_indeks ?? 0;

                $totalSksSemester += $sks;
                $weightedGradeSemester += ($indeks * $sks);
            }

            $ips = ($totalSksSemester > 0) ? ($weightedGradeSemester / $totalSksSemester) : 0.00;

            // 2. Calculate IPK (Cumulative from all semesters up to now)
            $allEnrolled = KelasMahasiswa::where('mahasiswa_id', $student->id)
                ->with(['kelas.mataKuliah', 'nilai'])
                ->get();

            $totalSksCumulative = 0;
            $weightedGradeCumulative = 0;

            foreach ($allEnrolled as $enroll) {
                $sks = $enroll->kelas->mataKuliah->beban_sks ?? 0;
                $indeks = $enroll->nilai->bobot_indeks ?? 0;

                $totalSksCumulative += $sks;
                $weightedGradeCumulative += ($indeks * $sks);
            }

            $ipk = ($totalSksCumulative > 0) ? ($weightedGradeCumulative / $totalSksCumulative) : 0.00;

            // Save to akumulasi_ip
            AkumulasiIp::updateOrCreate(
                [
                    'user_id' => $student->id,
                    'tahun_akademik_id' => $ta->id
                ],
                [
                    'ips' => $ips,
                    'ipk' => $ipk,
                    'total_sks' => $totalSksSemester
                ]
            );

            // Update user profile IPK field
            if ($student->dataMahasiswa) {
                $student->dataMahasiswa->update([
                    'ipk' => $ipk,
                    'sks_lulus' => $totalSksCumulative
                ]);
            }

            $processed++;
        }

        Alert::success('Berhasil', "Proses nilai selesai. Berhasil menghitung IPS/IPK untuk {$processed} mahasiswa.");
        return redirect()->back();
    }

    public function komponen()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Proses IPS/IPK';
        $data['pages'] = 'Komponen & Bobot Penilaian';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        return view('master.akademik.nilai-komponen', $data, compact('user'));
    }

    public function entri()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Proses IPS/IPK';
        $data['pages'] = 'Aturan Entri Nilai Kuliah';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        return view('master.akademik.nilai-entri-aturan', $data, compact('user'));
    }


    /**
     * ==========================================
     * PANEL MAHASISWA: KARTU HASIL STUDI (KHS) & TRANSKRIP
     * ==========================================
     */

    public function indexKhs(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'mahasiswa';
        $data['menus'] = 'KHS';
        $data['pages'] = 'Kartu Hasil Studi (KHS)';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['tahunAkademiks'] = TahunAkademik::orderBy('code', 'desc')->get();
        
        $selectedTaId = $request->tahun_akademik_id ?? (TahunAkademik::where('is_active', true)->first()->id ?? null);
        $data['selectedTaId'] = $selectedTaId;

        if ($selectedTaId) {
            $data['semesterGrades'] = KelasMahasiswa::where('mahasiswa_id', $user->id)
                ->whereHas('kelas', function($q) use ($selectedTaId) {
                    $q->where('tahun_akademik_id', $selectedTaId);
                })
                ->with(['kelas.mataKuliah', 'nilai'])
                ->get();

            $data['summary'] = AkumulasiIp::where('user_id', $user->id)
                ->where('tahun_akademik_id', $selectedTaId)
                ->first();
        } else {
            $data['semesterGrades'] = collect();
            $data['summary'] = null;
        }

        return view('private.khs-index', $data, compact('user'));
    }

    public function indexTranskrip()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'mahasiswa';
        $data['menus'] = 'Transkrip';
        $data['pages'] = 'Transkrip Nilai Akademik';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // Get all graded courses
        $data['grades'] = KelasMahasiswa::where('mahasiswa_id', $user->id)
            ->whereHas('nilai')
            ->with(['kelas.mataKuliah', 'kelas.tahunAkademik', 'nilai'])
            ->get();

        return view('private.transkrip-index', $data, compact('user'));
    }
}
