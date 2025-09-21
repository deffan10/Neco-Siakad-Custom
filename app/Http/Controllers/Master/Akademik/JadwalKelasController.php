<?php

namespace App\Http\Controllers\Master\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
// Use Models
use App\Models\Akademik\JadwalKelas;
use App\Models\Akademik\JadwalPerkuliahan;
use App\Models\Akademik\KelasPerkuliahan;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class JadwalKelasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Akademik Jadwal Kelas';
        $data['pages'] = "Halaman Data Jadwal Kelas";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['jadwalKelas'] = JadwalKelas::with(['jadwal', 'kelas'])->orderBy('created_at', 'desc')->get();
        $data['jadwalPerkuliahans'] = JadwalPerkuliahan::orderBy('code')->get();
        $data['kelasPerkuliahans'] = KelasPerkuliahan::orderBy('name')->get();
        $data['is_trash'] = false;

        return view('master.akademik.jadwal-kelas-index', $data, compact('user'));
    }

    public function trash()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Akademik Jadwal Kelas';
        $data['pages'] = "Halaman Data Jadwal Kelas yang Dihapus";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['jadwalKelas'] = JadwalKelas::onlyTrashed()->with(['jadwal', 'kelas'])->get();
        $data['jadwalPerkuliahans'] = JadwalPerkuliahan::orderBy('code')->get();
        $data['kelasPerkuliahans'] = KelasPerkuliahan::orderBy('name')->get();
        $data['is_trash'] = true;

        return view('master.akademik.jadwal-kelas-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_perkuliahan,id',
            'kelas_id' => 'required|exists:kelas_perkuliahan,id',
        ], [
            'jadwal_id.required' => 'Jadwal perkuliahan wajib dipilih',
            'jadwal_id.exists' => 'Jadwal perkuliahan tidak valid',
            'kelas_id.required' => 'Kelas perkuliahan wajib dipilih',
            'kelas_id.exists' => 'Kelas perkuliahan tidak valid',
        ]);

        try {
            $user = Auth::user();
            
            // Check if the relationship already exists
            $existing = JadwalKelas::where('jadwal_id', $request->jadwal_id)
                ->where('kelas_id', $request->kelas_id)
                ->first();
                
            if ($existing) {
                Alert::error('Error', 'Relasi jadwal kelas sudah ada');
                return redirect()->back();
            }
            
            JadwalKelas::create([
                'jadwal_id' => $request->jadwal_id,
                'kelas_id' => $request->kelas_id,
                'created_by' => $user->id
            ]);

            Alert::success('Berhasil', 'Data jadwal kelas berhasil ditambahkan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $jadwalKelas = JadwalKelas::findOrFail($id);
        
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_perkuliahan,id',
            'kelas_id' => 'required|exists:kelas_perkuliahan,id',
        ], [
            'jadwal_id.required' => 'Jadwal perkuliahan wajib dipilih',
            'jadwal_id.exists' => 'Jadwal perkuliahan tidak valid',
            'kelas_id.required' => 'Kelas perkuliahan wajib dipilih',
            'kelas_id.exists' => 'Kelas perkuliahan tidak valid',
        ]);

        try {
            $user = Auth::user();
            
            // Check if the relationship already exists
            $existing = JadwalKelas::where('jadwal_id', $request->jadwal_id)
                ->where('kelas_id', $request->kelas_id)
                ->where('id', '!=', $id)
                ->first();
                
            if ($existing) {
                Alert::error('Error', 'Relasi jadwal kelas sudah ada');
                return redirect()->back();
            }
            
            $jadwalKelas->update([
                'jadwal_id' => $request->jadwal_id,
                'kelas_id' => $request->kelas_id,
                'updated_by' => $user->id
            ]);

            Alert::success('Berhasil', 'Data jadwal kelas berhasil diperbarui');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $jadwalKelas = JadwalKelas::findOrFail($id);

            $user = Auth::user();
            $jadwalKelas->update(['deleted_by' => $user->id]);
            $jadwalKelas->delete();

            Alert::success('Berhasil', 'Data jadwal kelas berhasil dihapus');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $jadwalKelas = JadwalKelas::onlyTrashed()->findOrFail($id);

            $user = Auth::user();
            $jadwalKelas->update(['updated_by' => $user->id, 'deleted_by' => null]);
            $jadwalKelas->restore();

            Alert::success('Berhasil', 'Data jadwal kelas berhasil dikembalikan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }
}