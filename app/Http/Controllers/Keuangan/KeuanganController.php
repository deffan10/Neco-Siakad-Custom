<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;
use App\Models\Akademik\TahunAkademik;
use App\Models\Akademik\ProgramStudi;
use App\Models\Keuangan\KomponenBiaya;
use App\Models\Keuangan\TarifBiaya;
use App\Models\Keuangan\TagihanMahasiswa;
use App\Models\Keuangan\PembayaranMahasiswa;
use Illuminate\Support\Str;

class KeuanganController extends Controller
{
    /**
     * ==========================================
     * PANEL ADMIN: MANAJEMEN TAGIHAN & TARIF
     * ==========================================
     */

    // 1. Konfigurasi Komponen Biaya & Tarif (uangmhssetting)
    public function indexTarif()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Keuangan Tarif';
        $data['pages'] = 'Pengaturan Tarif Biaya';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['komponens'] = KomponenBiaya::with('tarifs.programStudi')->get();
        $data['programStudis'] = ProgramStudi::orderBy('name')->get();

        return view('master.keuangan.tarif-index', $data, compact('user'));
    }

    public function storeKomponen(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:komponen_biaya,code|max:50',
            'default_amount' => 'required|numeric|min:0',
        ]);

        KomponenBiaya::create($request->only('name', 'code', 'default_amount'));
        Alert::success('Berhasil', 'Komponen biaya berhasil ditambahkan');
        return redirect()->back();
    }

    public function storeTarif(Request $request)
    {
        $request->validate([
            'komponen_id' => 'required|exists:komponen_biaya,id',
            'program_studi_id' => 'required|exists:program_studi,id',
            'angkatan' => 'required|integer|min:2000|max:2100',
            'nominal' => 'required|numeric|min:0',
        ]);

        // Check duplicate
        $exists = TarifBiaya::where('komponen_id', $request->komponen_id)
            ->where('program_studi_id', $request->program_studi_id)
            ->where('angkatan', $request->angkatan)
            ->first();

        if ($exists) {
            Alert::error('Error', 'Tarif untuk komponen, prodi, dan angkatan ini sudah dikonfigurasi.');
            return redirect()->back();
        }

        TarifBiaya::create($request->only('komponen_id', 'program_studi_id', 'angkatan', 'nominal'));
        Alert::success('Berhasil', 'Tarif biaya berhasil disimpan');
        return redirect()->back();
    }

    // 2. Tagihan Per Mahasiswa (uangmhstm)
    public function indexTagihan(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Keuangan Tagihan';
        $data['pages'] = 'Data Tagihan Mahasiswa';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $query = TagihanMahasiswa::with(['mahasiswa.alamatKtp', 'tahunAkademik']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('mahasiswa', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $data['tagihans'] = $query->latest()->paginate(15);
        $data['tahunAkademiks'] = TahunAkademik::orderBy('code', 'desc')->get();
        // Get only student users for manual tagihan creation
        $data['mahasiswas'] = User::role('mahasiswa')->orderBy('name')->get();

        return view('master.keuangan.tagihan-index', $data, compact('user'));
    }

    public function storeTagihan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'total_tagihan' => 'required|numeric|min:0',
        ]);

        // Check if invoice already exists for this semester
        $exists = TagihanMahasiswa::where('user_id', $request->user_id)
            ->where('tahun_akademik_id', $request->tahun_akademik_id)
            ->first();

        if ($exists) {
            Alert::error('Error', 'Tagihan untuk mahasiswa ini di semester terpilih sudah ada.');
            return redirect()->back();
        }

        TagihanMahasiswa::create([
            'user_id' => $request->user_id,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'total_tagihan' => $request->total_tagihan,
            'status' => 'Belum Lunas'
        ]);

        Alert::success('Berhasil', 'Tagihan berhasil dibuat');
        return redirect()->back();
    }

    public function generateTagihanMassal(Request $request)
    {
        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'angkatan' => 'required|integer',
            'program_studi_id' => 'required|exists:program_studi,id'
        ]);

        // Fetch students matching cohort & major
        $students = User::role('mahasiswa')
            ->whereHas('dataMahasiswa', function($q) use ($request) {
                $q->where('angkatan', $request->angkatan)
                  ->where('program_studi_id', $request->program_studi_id);
            })->get();

        if ($students->isEmpty()) {
            Alert::warning('Peringatan', 'Tidak ada mahasiswa yang cocok dengan filter angkatan & prodi.');
            return redirect()->back();
        }

        // Calculate total amount based on configured TarifBiaya
        $tarifs = TarifBiaya::where('program_studi_id', $request->program_studi_id)
            ->where('angkatan', $request->angkatan)
            ->get();

        $totalTarif = $tarifs->sum('nominal');

        if ($totalTarif <= 0) {
            Alert::error('Error', 'Tarif biaya belum dikonfigurasi untuk angkatan & prodi ini.');
            return redirect()->back();
        }

        $created = 0;
        foreach ($students as $student) {
            // Check duplicate
            $exists = TagihanMahasiswa::where('user_id', $student->id)
                ->where('tahun_akademik_id', $request->tahun_akademik_id)
                ->first();

            if (!$exists) {
                TagihanMahasiswa::create([
                    'user_id' => $student->id,
                    'tahun_akademik_id' => $request->tahun_akademik_id,
                    'total_tagihan' => $totalTarif,
                    'status' => 'Belum Lunas'
                ]);
                $created++;
            }
        }

        Alert::success('Berhasil', "Berhasil menjana {$created} tagihan massal.");
        return redirect()->back();
    }

    // 3. Verifikasi Transaksi / Laporan & Rekonsiliasi (uangmhsbayar)
    public function indexPembayaran()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Keuangan Pembayaran';
        $data['pages'] = 'Verifikasi Pembayaran Mahasiswa';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['pembayarans'] = PembayaranMahasiswa::with('tagihan.mahasiswa')
            ->latest()
            ->paginate(15);

        return view('master.keuangan.pembayaran-index', $data, compact('user'));
    }

    public function verifikasiPembayaran($id, $action)
    {
        $pembayaran = PembayaranMahasiswa::findOrFail($id);
        $tagihan = $pembayaran->tagihan;

        if ($action === 'approve') {
            $pembayaran->update([
                'status' => 'Success',
                'paid_at' => now()
            ]);

            // Update Tagihan status
            $totalTerbayar = $tagihan->pembayarans()->where('status', 'Success')->sum('nominal');
            if ($totalTerbayar >= $tagihan->total_tagihan) {
                $tagihan->update(['status' => 'Lunas']);
            } else {
                $tagihan->update(['status' => 'Kurang Bayar']);
            }

            Alert::success('Berhasil', 'Pembayaran berhasil dikonfirmasi');
        } else {
            $pembayaran->update(['status' => 'Failed']);
            Alert::error('Ditolak', 'Pembayaran ditolak/dinyatakan gagal');
        }

        return redirect()->back();
    }


    /**
     * ==========================================
     * PANEL MAHASISWA: RIWAYAT & PEMBAYARAN
     * ==========================================
     */

    public function indexMahasiswaKeuangan()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'mahasiswa';
        $data['menus'] = 'Keuangan';
        $data['pages'] = 'Informasi Keuangan';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // Get student's bills
        $data['tagihans'] = TagihanMahasiswa::where('user_id', $user->id)
            ->with(['tahunAkademik', 'pembayarans'])
            ->latest()
            ->get();

        return view('private.keuangan-index', $data, compact('user'));
    }

    public function paySimulated(Request $request, $tagihanId)
    {
        $tagihan = TagihanMahasiswa::where('user_id', Auth::id())->findOrFail($tagihanId);

        if ($tagihan->status === 'Lunas') {
            Alert::info('Info', 'Tagihan ini sudah lunas.');
            return redirect()->back();
        }

        // Create transaction record (Pending state to simulate auto-verif or admin approval)
        $payment = PembayaranMahasiswa::create([
            'tagihan_id' => $tagihan->id,
            'nominal' => $tagihan->total_tagihan,
            'channel_pembayaran' => $request->channel ?? 'Virtual Account BNI',
            'referensi_transaksi' => 'TXS-' . Str::upper(Str::random(10)),
            'status' => 'Success', // Instantly success for simulator convenience
            'paid_at' => now()
        ]);

        // Mark invoice as paid
        $tagihan->update(['status' => 'Lunas']);

        Alert::success('Berhasil', 'Pembayaran Simulasi Berhasil! Tagihan Anda kini dinyatakan LUNAS.');
        return redirect()->back();
    }
}
