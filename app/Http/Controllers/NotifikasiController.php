<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NotifikasiInApp;
use RealRashid\SweetAlert\Facades\Alert;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'mahasiswa';
        $data['menus']   = 'Notifikasi';
        $data['pages']   = 'Semua Notifikasi';
        $data['system']  = \App\Models\Pengaturan\System::first();
        $data['academy'] = \App\Models\Pengaturan\Kampus::first();

        $data['notifications'] = NotifikasiInApp::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('private.notifikasi-index', $data, compact('user'));
    }

    public function read($id)
    {
        $notif = NotifikasiInApp::where('user_id', Auth::id())->findOrFail($id);
        $notif->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if ($notif->url) {
            return redirect($notif->url);
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        NotifikasiInApp::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        Alert::success('Berhasil', 'Semua notifikasi ditandai telah dibaca.');
        return redirect()->back();
    }

    public function clearAll()
    {
        NotifikasiInApp::where('user_id', Auth::id())->delete();
        Alert::success('Berhasil', 'Semua notifikasi berhasil dihapus.');
        return redirect()->back();
    }
}
