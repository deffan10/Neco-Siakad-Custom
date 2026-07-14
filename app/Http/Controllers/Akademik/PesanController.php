<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\PesanInternal;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class PesanController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Pesan Internal',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    // =====================================================
    // Tab 1: PESAN MASUK - daftar pesan masuk user ini
    // =====================================================
    public function masuk()
    {
        $user = Auth::user();
        $data = $this->baseData('Pesan Masuk');

        $data['pesans'] = PesanInternal::with('pengirim')
            ->where('penerima_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('master.akademik.pesan-masuk', $data, compact('user'));
    }

    // =====================================================
    // Tab 2: BUAT PESAN - form kirim pesan baru
    // =====================================================
    public function buat()
    {
        $user = Auth::user();
        $data = $this->baseData('Buat Pesan Baru');
        $data['users'] = User::where('id', '!=', $user->id)->orderBy('name')->get();

        return view('master.akademik.pesan-buat', $data, compact('user'));
    }

    public function kirim(Request $request)
    {
        $request->validate([
            'penerima_id' => 'required|exists:users,id',
            'subjek'      => 'required|string|max:255',
            'isi'         => 'required|string',
        ]);

        PesanInternal::create([
            'pengirim_id'  => Auth::id(),
            'penerima_id'  => $request->penerima_id,
            'subjek'       => $request->subjek,
            'isi'          => $request->isi,
            'dibaca'       => false,
        ]);

        Alert::success('Terkirim', 'Pesan berhasil dikirim!');
        return redirect()->route('admin.pesan.terkirim');
    }

    // =====================================================
    // Tab 3: PESAN TERKIRIM - daftar pesan yang dikirim user ini
    // =====================================================
    public function terkirim()
    {
        $user = Auth::user();
        $data = $this->baseData('Pesan Terkirim');

        $data['pesans'] = PesanInternal::with('penerima')
            ->where('pengirim_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('master.akademik.pesan-terkirim', $data, compact('user'));
    }

    // Tandai pesan masuk sebagai sudah dibaca
    public function markRead($id)
    {
        $pesan = PesanInternal::where('penerima_id', Auth::id())->findOrFail($id);
        $pesan->update(['dibaca' => true]);
        return redirect()->route('admin.pesan.masuk');
    }
}
