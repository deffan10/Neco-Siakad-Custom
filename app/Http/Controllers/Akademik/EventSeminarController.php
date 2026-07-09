<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\EventSeminar;
use App\Models\Akademik\EventSeminarPeserta;
use App\Models\Akademik\TahunAkademik;
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;

class EventSeminarController extends Controller
{
    /* =========================================================
     *  ADMIN PANEL
     * ========================================================= */

    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus']   = 'Event Seminar';
        $data['pages']   = 'Manajemen Event Seminar';
        $data['system']  = System::first();
        $data['academy'] = Kampus::first();

        $query = EventSeminar::withCount('pesertaMahasiswas')->latest('tanggal');

        if ($request->filled('search')) {
            $query->where('nama_event', 'like', '%' . $request->search . '%');
        }

        $data['events']       = $query->paginate(15);
        $data['tahunAkademiks'] = TahunAkademik::orderBy('tanggal_mulai', 'desc')->get();

        return view('master.akademik.event-seminar-admin', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_event'        => 'required|string|max:255',
            'tipe_event'        => 'required|in:Proposal,Hasil,Umum,Webinar,Workshop',
            'tanggal'           => 'required|date',
            'tahun_akademik_id' => 'nullable|exists:tahun_akademik,id',
            'jam_mulai'         => 'nullable|date_format:H:i',
            'jam_selesai'       => 'nullable|date_format:H:i',
            'kuota'             => 'nullable|integer|min:1',
        ]);

        EventSeminar::create(array_merge($request->except('_token'), [
            'created_by' => Auth::id(),
            'is_open'    => true,
        ]));

        Alert::success('Berhasil', 'Event seminar berhasil ditambahkan.');
        return redirect()->back();
    }

    public function toggleOpen($id)
    {
        $event = EventSeminar::findOrFail($id);
        $event->update(['is_open' => !$event->is_open]);
        Alert::success('Berhasil', 'Status pendaftaran event berhasil diperbarui.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        EventSeminar::findOrFail($id)->delete();
        Alert::success('Berhasil', 'Event seminar berhasil dihapus.');
        return redirect()->back();
    }

    public function peserta($id)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['system']  = System::first();
        $data['academy'] = Kampus::first();

        $data['event']    = EventSeminar::findOrFail($id);
        $data['menus']    = 'Event Seminar';
        $data['pages']    = 'Peserta: ' . $data['event']->nama_event;
        $data['pesertaList'] = EventSeminarPeserta::where('event_id', $id)
            ->with('mahasiswa')
            ->get();

        return view('master.akademik.event-seminar-peserta', $data, compact('user'));
    }

    public function updateStatusPeserta(Request $request, $pesertaId)
    {
        $request->validate([
            'status' => 'required|in:Mendaftar,Hadir,Tidak Hadir',
        ]);

        $peserta = EventSeminarPeserta::findOrFail($pesertaId);
        $peserta->update([
            'status'  => $request->status,
            'catatan' => $request->catatan,
        ]);

        Alert::success('Berhasil', 'Status kehadiran mahasiswa berhasil diperbarui.');
        return redirect()->back();
    }

    /* =========================================================
     *  PORTAL MAHASISWA
     * ========================================================= */

    public function indexPortal()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'mahasiswa';
        $data['menus']   = 'Seminar';
        $data['pages']   = 'Daftar Event Seminar';
        $data['system']  = System::first();
        $data['academy'] = Kampus::first();

        $data['events'] = EventSeminar::where('is_open', true)
            ->withCount('pesertaMahasiswas')
            ->latest('tanggal')
            ->get()
            ->map(function ($event) use ($user) {
                $event->is_registered = EventSeminarPeserta::where('event_id', $event->id)
                    ->where('mahasiswa_id', $user->id)
                    ->exists();
                return $event;
            });

        return view('private.portal-seminar-index', $data, compact('user'));
    }

    public function daftar($id)
    {
        $user  = Auth::user();
        $event = EventSeminar::findOrFail($id);

        if (!$event->is_open) {
            Alert::error('Gagal', 'Pendaftaran untuk event ini sudah ditutup.');
            return redirect()->back();
        }

        // Check kuota
        if ($event->kuota && $event->pesertaMahasiswas()->count() >= $event->kuota) {
            Alert::warning('Penuh', 'Kuota pendaftaran event ini sudah penuh.');
            return redirect()->back();
        }

        // Check already registered
        $alreadyRegistered = EventSeminarPeserta::where('event_id', $id)
            ->where('mahasiswa_id', $user->id)
            ->exists();

        if ($alreadyRegistered) {
            Alert::info('Info', 'Anda sudah terdaftar di event seminar ini.');
            return redirect()->back();
        }

        EventSeminarPeserta::create([
            'event_id'     => $id,
            'mahasiswa_id' => $user->id,
            'status'       => 'Mendaftar',
        ]);

        Alert::success('Berhasil', 'Anda berhasil mendaftar ke event seminar: ' . $event->nama_event);
        return redirect()->back();
    }
}
