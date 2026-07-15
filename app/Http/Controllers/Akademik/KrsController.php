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
use App\Models\Akademik\WaktuKrs;
use App\Models\Akademik\SyaratSks;
use App\Models\Akademik\KrsMahasiswa;
use App\Models\Akademik\KrsDetail;
use App\Models\Akademik\KelasPerkuliahan;
use App\Models\Akademik\KelasMahasiswa;
use App\Models\Keuangan\TagihanMahasiswa;
use App\Models\Akademik\AkumulasiIp; // We will create this in Phase 3
use Carbon\Carbon;

class KrsController extends Controller
{
    /**
     * ==========================================
     * PANEL ADMIN: PENGATURAN KRS & SYARAT SKS
     * ==========================================
     */

    public function indexAdminKrs()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'KRS Settings';
        $data['pages'] = 'Pengaturan Sistem KRS';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['waktuKrs'] = WaktuKrs::with('tahunAkademik')->latest()->get();
        $data['syaratSks'] = SyaratSks::orderBy('ip_min')->get();
        $data['tahunAkademiks'] = TahunAkademik::orderBy('code', 'desc')->get();

        return view('master.akademik.krs-settings', $data, compact('user'));
    }

    public function storeWaktuKrs(Request $request)
    {
        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        // Deactivate older ones
        WaktuKrs::where('tahun_akademik_id', $request->tahun_akademik_id)->update(['is_active' => false]);

        WaktuKrs::create($request->all());
        Alert::success('Berhasil', 'Waktu KRS berhasil dikonfigurasi.');
        return redirect()->back();
    }

    public function storeSyaratSks(Request $request)
    {
        $request->validate([
            'ip_min' => 'required|numeric|between:0,4.00',
            'ip_max' => 'required|numeric|between:0,4.00|gte:ip_min',
            'max_sks' => 'required|integer|min:1|max:30',
        ]);

        SyaratSks::create($request->all());
        Alert::success('Berhasil', 'Batas syarat SKS berhasil disimpan.');
        return redirect()->back();
    }

    public function destroySyaratSks($id)
    {
        SyaratSks::findOrFail($id)->delete();
        Alert::success('Berhasil', 'Syarat batas SKS berhasil dihapus.');
        return redirect()->back();
    }



    /**
     * ==========================================
     * PANEL MAHASISWA: PENGISIAN KRS ONLINE
     * ==========================================
     */

    public function indexMahasiswaKrs()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'mahasiswa';
        $data['menus'] = 'KRS Online';
        $data['pages'] = 'KRS Online';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // 1. Get active academic year
        $activeTa = TahunAkademik::where('is_active', true)->first();
        if (!$activeTa) {
            $data['blocked'] = true;
            $data['message'] = 'Tahun akademik aktif belum ditentukan oleh administrator.';
            return view('private.krs-index', $data, compact('user'));
        }

        // 2. Check if student has paid active billing
        $bill = TagihanMahasiswa::where('user_id', $user->id)
            ->where('tahun_akademik_id', $activeTa->id)
            ->first();

        if (!$bill || $bill->status !== 'Lunas') {
            $data['blocked'] = true;
            $data['message'] = 'KRS Terkunci! Anda belum melunasi tagihan kuliah untuk semester aktif (' . $activeTa->name . '). Silakan lakukan pembayaran terlebih dahulu di menu Keuangan.';
            return view('private.krs-index', $data, compact('user'));
        }

        // 3. Check if KRS registration period is open
        $waktu = WaktuKrs::where('tahun_akademik_id', $activeTa->id)
            ->where('is_active', true)
            ->first();

        if (!$waktu || Carbon::now()->lt(Carbon::parse($waktu->tanggal_mulai)) || Carbon::now()->gt(Carbon::parse($waktu->tanggal_selesai))) {
            $data['blocked'] = true;
            $data['message'] = 'Waktu KRS Online untuk semester ini ditutup atau belum dimulai. Silakan hubungi operator akademik.';
            return view('private.krs-index', $data, compact('user'));
        }

        // 4. Calculate maximum allowed SKS based on previous semester's GPA
        // Look up prior semester
        $prevTa = TahunAkademik::where('code', '<', $activeTa->code)->orderBy('code', 'desc')->first();
        $maxSks = 20; // Default for new student
        if ($prevTa) {
            // Check if there is an IP score record
            $ipRecord = \DB::table('akumulasi_ip')
                ->where('user_id', $user->id)
                ->where('tahun_akademik_id', $prevTa->id)
                ->first();

            if ($ipRecord) {
                $ips = $ipRecord->ips;
                $syarat = SyaratSks::where('ip_min', '<=', $ips)->where('ip_max', '>=', $ips)->first();
                if ($syarat) {
                    $maxSks = $syarat->max_sks;
                }
            }
        }

        // 5. Get current KRS record or create draft
        $krs = KrsMahasiswa::firstOrCreate(
            ['mahasiswa_id' => $user->id, 'tahun_akademik_id' => $activeTa->id],
            [
                'status' => 'Draft',
                'dosen_pa_id' => $user->dataMahasiswa->dosen_pa_id ?? null
            ]
        );

        $data['maxSks'] = $maxSks;
        $data['krs'] = $krs;
        $data['activeTa'] = $activeTa;
        $data['kelasPerkuliahan'] = KelasPerkuliahan::where('tahun_akademik_id', $activeTa->id)
            ->where('program_studi_id', $user->dataMahasiswa->program_studi_id)
            ->with('mataKuliah')
            ->get();

        $data['selectedClassIds'] = $krs->details->pluck('kelas_perkuliahan_id')->toArray();
        $data['currentSks'] = $krs->details->sum(function($d) {
            return $d->kelasPerkuliahan->mataKuliah->beban_sks ?? 0;
        });

        return view('private.krs-index', $data, compact('user'));
    }

    public function submitKrsDraft(Request $request)
    {
        $user = Auth::user();
        $activeTa = TahunAkademik::where('is_active', true)->first();

        if (!$activeTa) {
            Alert::error('Error', 'Tahun akademik aktif tidak ditemukan.');
            return redirect()->back();
        }

        $krs = KrsMahasiswa::where('mahasiswa_id', $user->id)
            ->where('tahun_akademik_id', $activeTa->id)
            ->firstOrFail();

        if ($krs->status !== 'Draft') {
            Alert::warning('Peringatan', 'KRS Anda sudah diajukan atau disetujui, tidak bisa diedit lagi.');
            return redirect()->back();
        }

        // Validate SKS limit
        $kelasIds = $request->kelas_ids ?? [];
        $totalSks = 0;
        $classes = KelasPerkuliahan::whereIn('id', $kelasIds)->with('mataKuliah')->get();
        foreach ($classes as $c) {
            $totalSks += $c->mataKuliah->beban_sks;
        }

        // Get max SKS limit again
        $prevTa = TahunAkademik::where('code', '<', $activeTa->code)->orderBy('code', 'desc')->first();
        $maxSks = 20;
        if ($prevTa) {
            $ipRecord = \DB::table('akumulasi_ip')
                ->where('user_id', $user->id)
                ->where('tahun_akademik_id', $prevTa->id)
                ->first();
            if ($ipRecord) {
                $ips = $ipRecord->ips;
                $syarat = SyaratSks::where('ip_min', '<=', $ips)->where('ip_max', '>=', $ips)->first();
                if ($syarat) {
                    $maxSks = $syarat->max_sks;
                }
            }
        }

        if ($totalSks > $maxSks) {
            Alert::error('Gagal', "SKS yang diambil ({$totalSks} SKS) melebihi batas maksimum Anda ({$maxSks} SKS).");
            return redirect()->back();
        }

        // Sync KRS details
        KrsDetail::where('krs_id', $krs->id)->delete();
        foreach ($kelasIds as $cid) {
            KrsDetail::create([
                'krs_id' => $krs->id,
                'kelas_perkuliahan_id' => $cid
            ]);
        }

        // If action is submit, change status to Diajukan
        if ($request->action === 'submit') {
            $krs->update(['status' => 'Diajukan']);
            Alert::success('Berhasil', 'KRS berhasil diajukan ke Dosen Pembimbing Akademik.');
        } else {
            Alert::success('Berhasil', 'Draft KRS berhasil disimpan.');
        }

        return redirect()->back();
    }


    /**
     * ==========================================
     * PANEL DOSEN & ADMIN: PERWALIAN / APPROVAL
     * ==========================================
     */

    public function indexBimbinganPa(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'dosen';
        $data['menus'] = 'Bimbingan PA';
        $data['pages'] = 'Bimbingan Akademik (KRS)';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // Admin can view all, lecturers can only view their own advisees
        $query = KrsMahasiswa::with(['mahasiswa', 'tahunAkademik', 'details.kelasPerkuliahan.mataKuliah'])
            ->where('status', '!=', 'Draft');

        if ($data['activeRole'] === 'dosen') {
            $query->where('dosen_pa_id', $user->id);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $data['krsLists'] = $query->latest()->paginate(15);

        return view('master.akademik.krs-approval', $data, compact('user'));
    }

    public function approveKrs(Request $request, $id)
    {
        $krs = KrsMahasiswa::findOrFail($id);

        if ($request->action === 'approve') {
            $krs->update([
                'status' => 'Disetujui',
                'catatan_dosen' => $request->catatan_dosen
            ]);

            // Automatically register student to the KelasMahasiswa
            foreach ($krs->details as $detail) {
                KelasMahasiswa::firstOrCreate([
                    'kelas_id' => $detail->kelas_perkuliahan_id,
                    'mahasiswa_id' => $krs->mahasiswa_id
                ]);
            }

            Alert::success('Berhasil', 'KRS Mahasiswa berhasil disetujui.');
        } else {
            $krs->update([
                'status' => 'Draft', // Reject back to Draft for student revisions
                'catatan_dosen' => $request->catatan_dosen
            ]);
            Alert::warning('Ditolak', 'KRS ditolak dan dikembalikan ke mahasiswa untuk revisi.');
        }

        return redirect()->back();
    }
}
