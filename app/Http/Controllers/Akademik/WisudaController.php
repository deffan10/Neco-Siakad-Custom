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
use App\Models\Akademik\KegiatanWisuda;
use App\Models\Akademik\PendaftaranWisuda;
use App\Models\Keuangan\TagihanMahasiswa;

class WisudaController extends Controller
{
    /**
     * ==========================================
     * PANEL ADMIN: KELOLA KEGIATAN & PENDAFTAR
     * ==========================================
     */

    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Wisuda Settings';
        $data['pages'] = 'Pengaturan Kegiatan Wisuda';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['kegiatans'] = KegiatanWisuda::with('tahunAkademik')
            ->withCount('pendaftarans')
            ->latest()
            ->get();

        $data['tahunAkademiks'] = TahunAkademik::orderBy('code', 'desc')->get();

        return view('master.akademik.wisuda-settings', $data, compact('user'));
    }

    public function storeKegiatan(Request $request)
    {
        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'nama' => 'required|string|max:255',
            'tanggal_mulai_daftar' => 'required|date',
            'tanggal_selesai_daftar' => 'required|date|after_or_equal:tanggal_mulai_daftar',
            'tanggal_pelaksanaan' => 'required|date|after_or_equal:tanggal_selesai_daftar',
            'kuota' => 'required|integer|min:1',
            'biaya' => 'required|numeric|min:0',
        ]);

        KegiatanWisuda::create([
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'nama' => $request->nama,
            'tanggal_mulai_daftar' => $request->tanggal_mulai_daftar,
            'tanggal_selesai_daftar' => $request->tanggal_selesai_daftar,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'kuota' => $request->kuota,
            'biaya' => $request->biaya,
            'status' => true,
            'created_by' => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Kegiatan wisuda baru berhasil dibuat');
        return redirect()->back();
    }

    public function toggleKegiatan($id)
    {
        $kegiatan = KegiatanWisuda::findOrFail($id);
        $kegiatan->update(['status' => !$kegiatan->status]);

        Alert::success('Berhasil', 'Status kegiatan berhasil diubah');
        return redirect()->back();
    }

    public function applicants($kegiatanId)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Wisuda Applicants';
        $data['pages'] = 'Pendaftar Wisuda';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['kegiatan'] = KegiatanWisuda::findOrFail($kegiatanId);
        $data['pendaftarans'] = PendaftaranWisuda::where('kegiatan_wisuda_id', $kegiatanId)
            ->with(['mahasiswa.user', 'verifiedBy'])
            ->latest()
            ->get();

        // Check payment status for each applicant
        foreach ($data['pendaftarans'] as $p) {
            $p->is_paid = TagihanMahasiswa::where('user_id', $p->mahasiswa->user_id)
                ->where('tahun_akademik_id', $data['kegiatan']->tahun_akademik_id)
                ->where('total_tagihan', $data['kegiatan']->biaya)
                ->where('status', 'Lunas')
                ->exists();
        }

        return view('master.akademik.wisuda-applicants', $data, compact('user'));
    }

    public function verifyApplicant(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan' => 'nullable|string',
        ]);

        $pendaftaran = PendaftaranWisuda::with('kegiatanWisuda', 'mahasiswa')->findOrFail($id);

        if ($request->status === 'Disetujui') {
            // Check if payment is settled
            $isPaid = TagihanMahasiswa::where('user_id', $pendaftaran->mahasiswa->user_id)
                ->where('tahun_akademik_id', $pendaftaran->kegiatanWisuda->tahun_akademik_id)
                ->where('total_tagihan', $pendaftaran->kegiatanWisuda->biaya)
                ->where('status', 'Lunas')
                ->exists();

            if (!$isPaid && $pendaftaran->kegiatanWisuda->biaya > 0) {
                Alert::error('Gagal', 'Mahasiswa belum melunasi biaya wisuda.');
                return redirect()->back();
            }
        }

        $pendaftaran->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        Alert::success('Berhasil', 'Status pendaftaran wisuda mahasiswa telah diperbarui');
        return redirect()->back();
    }

    /**
     * ==========================================
     * PANEL MAHASISWA: FORM PENDAFTARAN WISUDA
     * ==========================================
     */

    public function indexStudent(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = 'mahasiswa';
        $data['menus'] = 'Wisuda';
        $data['pages'] = 'Pendaftaran Wisuda';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $mhs = $user->dataMahasiswa;
        if (!$mhs) {
            Alert::error('Error', 'Data mahasiswa tidak ditemukan.');
            return redirect()->back();
        }

        // Find active graduation event
        $activeTa = TahunAkademik::where('is_active', true)->first();
        $data['kegiatan'] = KegiatanWisuda::where('status', true)
            ->where('tahun_akademik_id', $activeTa->id ?? 0)
            ->first();

        $data['pendaftaran'] = null;
        $data['tagihan'] = null;
        $data['is_paid'] = false;

        if ($data['kegiatan']) {
            $data['pendaftaran'] = PendaftaranWisuda::where('kegiatan_wisuda_id', $data['kegiatan']->id)
                ->where('mahasiswa_id', $mhs->id)
                ->first();

            // Check if student has a bill for this graduation
            $data['tagihan'] = TagihanMahasiswa::where('user_id', $user->id)
                ->where('tahun_akademik_id', $data['kegiatan']->tahun_akademik_id)
                ->where('total_tagihan', $data['kegiatan']->biaya)
                ->first();

            if ($data['tagihan'] && $data['tagihan']->status === 'Lunas') {
                $data['is_paid'] = true;
            }
        }

        return view('private.wisuda-index', $data, compact('user', 'mhs'));
    }

    public function storePendaftaran(Request $request)
    {
        $request->validate([
            'kegiatan_wisuda_id' => 'required|exists:kegiatan_wisuda,id',
            'judul_skripsi' => 'required|string',
            'ukuran_toga' => 'required|in:S,M,L,XL,XXL',
            'toefl_score' => 'nullable|integer|min:0|max:677',
            'berkas_photo' => 'nullable|image|max:2048',
            'berkas_bebas_pustaka' => 'nullable|file|mimes:pdf|max:4096',
            'berkas_skripsi' => 'nullable|file|mimes:pdf|max:4096',
            'berkas_toefl' => 'nullable|file|mimes:pdf|max:4096',
        ]);

        $user = Auth::user();
        $mhs = $user->dataMahasiswa;
        $kegiatan = KegiatanWisuda::findOrFail($request->kegiatan_wisuda_id);

        // Check if quota full
        $currentCount = PendaftaranWisuda::where('kegiatan_wisuda_id', $kegiatan->id)
            ->whereIn('status', ['Diajukan', 'Disetujui'])
            ->count();

        if ($currentCount >= $kegiatan->kuota) {
            Alert::error('Gagal', 'Kuota pendaftaran wisuda ini sudah penuh.');
            return redirect()->back();
        }

        // Find existing pendaftaran
        $pendaftaran = PendaftaranWisuda::where('kegiatan_wisuda_id', $kegiatan->id)
            ->where('mahasiswa_id', $mhs->id)
            ->first();

        if ($pendaftaran && $pendaftaran->status !== 'Draft' && $pendaftaran->status !== 'Ditolak') {
            Alert::error('Gagal', 'Pendaftaran Anda sudah diajukan/disetujui.');
            return redirect()->back();
        }

        $data = [
            'kegiatan_wisuda_id' => $kegiatan->id,
            'mahasiswa_id' => $mhs->id,
            'judul_skripsi' => $request->judul_skripsi,
            'ukuran_toga' => $request->ukuran_toga,
            'toefl_score' => $request->toefl_score,
            'status' => 'Draft',
        ];

        // File uploads
        if ($request->hasFile('berkas_photo')) {
            $data['berkas_photo'] = $request->file('berkas_photo')->store('wisuda/photo', 'public');
        }
        if ($request->hasFile('berkas_bebas_pustaka')) {
            $data['berkas_bebas_pustaka'] = $request->file('berkas_bebas_pustaka')->store('wisuda/pdf', 'public');
        }
        if ($request->hasFile('berkas_skripsi')) {
            $data['berkas_skripsi'] = $request->file('berkas_skripsi')->store('wisuda/pdf', 'public');
        }
        if ($request->hasFile('berkas_toefl')) {
            $data['berkas_toefl'] = $request->file('berkas_toefl')->store('wisuda/pdf', 'public');
        }

        if ($pendaftaran) {
            $pendaftaran->update($data);
        } else {
            PendaftaranWisuda::create($data);
        }

        Alert::success('Berhasil', 'Draft pendaftaran berhasil disimpan');
        return redirect()->back();
    }

    public function submitPendaftaran($id)
    {
        $pendaftaran = PendaftaranWisuda::with('kegiatanWisuda', 'mahasiswa')->findOrFail($id);

        if ($pendaftaran->status !== 'Draft' && $pendaftaran->status !== 'Ditolak') {
            Alert::error('Gagal', 'Pendaftaran tidak dalam status draft.');
            return redirect()->back();
        }

        // Check if payment tagihan already exists, if not create one
        $tagihan = TagihanMahasiswa::where('user_id', $pendaftaran->mahasiswa->user_id)
            ->where('tahun_akademik_id', $pendaftaran->kegiatanWisuda->tahun_akademik_id)
            ->where('total_tagihan', $pendaftaran->kegiatanWisuda->biaya)
            ->first();

        if (!$tagihan && $pendaftaran->kegiatanWisuda->biaya > 0) {
            TagihanMahasiswa::create([
                'user_id' => $pendaftaran->mahasiswa->user_id,
                'tahun_akademik_id' => $pendaftaran->kegiatanWisuda->tahun_akademik_id,
                'total_tagihan' => $pendaftaran->kegiatanWisuda->biaya,
                'status' => 'Belum Lunas',
            ]);
        }

        $pendaftaran->update(['status' => 'Diajukan']);

        Alert::success('Berhasil', 'Pendaftaran wisuda Anda telah diajukan ke admin');
        return redirect()->back();
    }
}
