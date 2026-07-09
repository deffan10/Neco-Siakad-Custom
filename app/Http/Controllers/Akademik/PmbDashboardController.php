<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Akademik\DataPestaPMB;
use App\Models\Akademik\DataMahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\User;

class PmbDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus']   = 'PMB';
        $data['pages']   = 'Dashboard PMB (Penerimaan Mahasiswa Baru)';
        $data['system']  = System::first();
        $data['academy'] = Kampus::first();

        // --- Filter tahun masuk ---
        $tahunList = DataPestaPMB::distinct()
            ->whereNotNull('tahun_masuk')
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk');

        $selectedTahun = $request->input('tahun', $tahunList->first());
        $data['tahunList']     = $tahunList;
        $data['selectedTahun'] = $selectedTahun;

        // --- Base query ---
        $baseQuery = DataPestaPMB::query();
        if ($selectedTahun) {
            $baseQuery->where('tahun_masuk', $selectedTahun);
        }

        // --- Statistik Utama ---
        $data['totalPendaftar']    = (clone $baseQuery)->count();
        $data['totalLolos']        = (clone $baseQuery)->where('status_pendaftaran', 'Lolos')->count();
        $data['totalDaftarUlang']  = (clone $baseQuery)->where('status_pendaftaran', 'Daftar Ulang')->count();
        $data['totalTidakLolos']   = (clone $baseQuery)->where('status_pendaftaran', 'Tidak Lolos')->count();
        $data['totalBatal']        = (clone $baseQuery)->where('status_pendaftaran', 'Batal')->count();
        $data['totalMenunggu']     = (clone $baseQuery)->where('status_pendaftaran', 'Menunggu')->count();

        // --- Distribusi Status (untuk chart Doughnut) ---
        $statusDistrib = (clone $baseQuery)
            ->select('status_pendaftaran', DB::raw('count(*) as total'))
            ->groupBy('status_pendaftaran')
            ->pluck('total', 'status_pendaftaran');

        $data['chartStatus'] = [
            'labels' => $statusDistrib->keys()->toArray(),
            'data'   => $statusDistrib->values()->toArray(),
        ];

        // --- Distribusi Jalur Masuk (untuk chart Bar) ---
        $jalurDistrib = (clone $baseQuery)
            ->select('jalur_masuk', DB::raw('count(*) as total'))
            ->groupBy('jalur_masuk')
            ->pluck('total', 'jalur_masuk');

        $data['chartJalur'] = [
            'labels' => $jalurDistrib->keys()->toArray(),
            'data'   => $jalurDistrib->values()->toArray(),
        ];

        // --- Distribusi Jenis Sekolah (jika sudah di data_peserta_pmb, else dari data_mahasiswa) ---
        // Note: jenis_sekolah is on data_mahasiswa table for existing students
        $jenisSekolahDistrib = DataMahasiswa::select('jenis_sekolah', DB::raw('count(*) as total'))
            ->when($selectedTahun, fn($q) => $q->where('angkatan', $selectedTahun))
            ->groupBy('jenis_sekolah')
            ->pluck('total', 'jenis_sekolah');

        $data['chartJenisSekolah'] = [
            'labels' => $jenisSekolahDistrib->keys()->toArray(),
            'data'   => $jenisSekolahDistrib->values()->toArray(),
        ];

        // --- Distribusi Jenis Kelamin (dari tabel users join) ---
        $genderDistrib = (clone $baseQuery)
            ->join('users', 'data_peserta_pmb.user_id', '=', 'users.id')
            ->join('jenis_kelamins', 'users.jenis_kelamin_id', '=', 'jenis_kelamins.id')
            ->select('jenis_kelamins.name as gender', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamins.name')
            ->pluck('total', 'gender');

        $data['chartGender'] = [
            'labels' => $genderDistrib->keys()->toArray(),
            'data'   => $genderDistrib->values()->toArray(),
        ];

        // --- Tren Pendaftaran Per Bulan ---
        $trenBulan = (clone $baseQuery)
            ->whereNotNull('tanggal_daftar')
            ->select(
                DB::raw('MONTH(tanggal_daftar) as bulan'),
                DB::raw('count(*) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $bulanLabels = collect(range(1, 12))->map(fn($m) => \Carbon\Carbon::create()->month($m)->translatedFormat('M'));
        $trenData    = collect(range(1, 12))->map(fn($m) => $trenBulan->get($m, 0));

        $data['chartTren'] = [
            'labels' => $bulanLabels->toArray(),
            'data'   => $trenData->toArray(),
        ];

        // --- Rekap Per Prodi & Jalur Masuk ---
        $data['rekapProdi'] = (clone $baseQuery)
            ->select('program_pilihan_1 as prodi', 'jalur_masuk', DB::raw('count(*) as total'))
            ->groupBy('program_pilihan_1', 'jalur_masuk')
            ->orderBy('prodi')
            ->get()
            ->groupBy('prodi');

        // --- Rekap Lolos Seleksi Per Prodi ---
        $data['rekapLolosProdi'] = (clone $baseQuery)
            ->where('status_pendaftaran', 'Lolos')
            ->select('program_pilihan_1 as prodi', DB::raw('count(*) as total'))
            ->groupBy('program_pilihan_1')
            ->orderBy('total', 'desc')
            ->get();

        return view('master.akademik.pmb-dashboard', $data, compact('user'));
    }
}
