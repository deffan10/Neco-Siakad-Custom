<?php

namespace App\Http\Controllers\Master\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
// Use Models
use App\Models\Akademik\KelasPerkuliahan;
use App\Models\Akademik\TahunAkademik;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\MataKuliah;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;
use App\Models\Akademik\KelasMahasiswa;
use App\Models\Akademik\JadwalKelas;
use App\Models\Mahasiswa;
use App\Models\Akademik\JadwalPerkuliahan;

class KelasPerkuliahanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Akademik Kelas Perkuliahan';
        $data['pages'] = "Halaman Data Kelas Perkuliahan";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['kelasPerkuliahans'] = KelasPerkuliahan::with(['tahunAkademik', 'programStudi', 'mataKuliah'])->orderBy('name')->get();
        $data['tahunAkademiks'] = TahunAkademik::where('is_active', true)->orderBy('created_at', 'desc')->get();
        $data['programStudis'] = ProgramStudi::where('is_active', true)->orderBy('name')->get();
        $data['mataKuliahs'] = MataKuliah::where('is_active', true)->orderBy('name')->get();
        $data['is_trash'] = false;

        return view('master.akademik.kelas-perkuliahan-index', $data, compact('user'));
    }

    public function trash()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Akademik Kelas Perkuliahan';
        $data['pages'] = "Halaman Data Kelas Perkuliahan yang Dihapus";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['kelasPerkuliahan'] = KelasPerkuliahan::onlyTrashed()->with(['tahunAkademik', 'programStudi', 'mataKuliah'])->get();
        $data['tahunAkademiks'] = TahunAkademik::where('is_active', true)->orderBy('created_at', 'desc')->get();
        $data['programStudis'] = ProgramStudi::where('is_active', true)->orderBy('name')->get();
        $data['mataKuliahs'] = MataKuliah::where('is_active', true)->orderBy('name')->get();
        $data['is_trash'] = true;

        return view('master.akademik.kelas-perkuliahan-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'program_studi_id' => 'required|exists:program_studi,id',
            'mata_kuliah_id' => 'nullable|exists:mata_kuliah,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:kelas_perkuliahan,code',
            'kapasitas' => 'nullable|integer|min:1|max:1000'
        ], [
            'tahun_akademik_id.required' => 'Tahun akademik wajib dipilih',
            'tahun_akademik_id.exists' => 'Tahun akademik tidak valid',
            'program_studi_id.required' => 'Program studi wajib dipilih',
            'program_studi_id.exists' => 'Program studi tidak valid',
            'mata_kuliah_id.exists' => 'Mata kuliah tidak valid',
            'name.required' => 'Nama kelas wajib diisi',
            'code.required' => 'Kode kelas wajib diisi',
            'code.unique' => 'Kode kelas sudah ada',
            'kapasitas.integer' => 'Kapasitas harus berupa angka'
        ]);

        try {
            $user = Auth::user();
            
            KelasPerkuliahan::create([
                'tahun_akademik_id' => $request->tahun_akademik_id,
                'program_studi_id' => $request->program_studi_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'name' => $request->name,
                'code' => $request->code,
                'kapasitas' => $request->kapasitas,
                'created_by' => $user->id
            ]);

            Alert::success('Berhasil', 'Data kelas perkuliahan berhasil ditambahkan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $kelasPerkuliahan = KelasPerkuliahan::findOrFail($id);
        
        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'program_studi_id' => 'required|exists:program_studi,id',
            'mata_kuliah_id' => 'nullable|exists:mata_kuliah,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:kelas_perkuliahan,code,' . $id,
            'kapasitas' => 'nullable|integer|min=1|max=1000'
        ], [
            'tahun_akademik_id.required' => 'Tahun akademik wajib dipilih',
            'tahun_akademik_id.exists' => 'Tahun akademik tidak valid',
            'program_studi_id.required' => 'Program studi wajib dipilih',
            'program_studi_id.exists' => 'Program studi tidak valid',
            'mata_kuliah_id.exists' => 'Mata kuliah tidak valid',
            'name.required' => 'Nama kelas wajib diisi',
            'code.required' => 'Kode kelas wajib diisi',
            'code.unique' => 'Kode kelas sudah ada',
            'kapasitas.integer' => 'Kapasitas harus berupa angka'
        ]);

        try {
            $user = Auth::user();
            
            $kelasPerkuliahan->update([
                'tahun_akademik_id' => $request->tahun_akademik_id,
                'program_studi_id' => $request->program_studi_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'name' => $request->name,
                'code' => $request->code,
                'kapasitas' => $request->kapasitas,
                'updated_by' => $user->id
            ]);

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kelas perkuliahan berhasil diperbarui',
                    'data' => $kelasPerkuliahan
                ]);
            }

            Alert::success('Berhasil', 'Data kelas perkuliahan berhasil diperbarui');
            return redirect()->back();

        } catch (\Throwable $th) {
            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $th->getMessage()
                ], 500);
            }

            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $kelasPerkuliahan = KelasPerkuliahan::findOrFail($id);

            $user = Auth::user();
            $kelasPerkuliahan->update(['deleted_by' => $user->id]);
            $kelasPerkuliahan->delete();

            Alert::success('Berhasil', 'Data kelas perkuliahan berhasil dihapus');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $kelasPerkuliahan = KelasPerkuliahan::onlyTrashed()->findOrFail($id);

            $user = Auth::user();
            $kelasPerkuliahan->update(['updated_by' => $user->id, 'deleted_by' => null]);
            $kelasPerkuliahan->restore();

            Alert::success('Berhasil', 'Data kelas perkuliahan berhasil dikembalikan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function view($id)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Akademik Kelas Perkuliahan';
        $data['pages'] = "Detail Kelas Perkuliahan";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        
        $kelasPerkuliahan = KelasPerkuliahan::with([
            'tahunAkademik', 
            'programStudi', 
            'mataKuliah',
            'kelasMahasiswa.mahasiswa',
            'jadwalKelas'
        ])->findOrFail($id);
        
        $data['kelasPerkuliahan'] = $kelasPerkuliahan;
        $data['tahunAkademiks'] = TahunAkademik::where('is_active', true)->orderBy('created_at', 'desc')->get();
        $data['programStudis'] = ProgramStudi::where('is_active', true)->orderBy('name')->get();
        $data['mataKuliahs'] = MataKuliah::where('is_active', true)->orderBy('name')->get();
        
        // Data for dropdowns in modals
        $data['mahasiswas'] = Mahasiswa::orderBy('name')->get();
        $data['jadwalPerkuliahans'] = JadwalPerkuliahan::with(['mataKuliah', 'dosen', 'ruangan'])
                                                      ->where('tahun_akademik_id', $kelasPerkuliahan->tahun_akademik_id)
                                                      ->where('mata_kuliah_id', $kelasPerkuliahan->mata_kuliah_id)
                                                      ->orderBy('hari')
                                                      ->orderBy('jam_mulai')
                                                      ->get();

        return view('master.akademik.kelas-perkuliahan-view', $data, compact('user'));
    }

    public function storeMahasiswa(Request $request, $kelasId)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
        ], [
            'mahasiswa_id.required' => 'Mahasiswa wajib dipilih',
            'mahasiswa_id.exists' => 'Mahasiswa tidak valid'
        ]);

        try {
            $user = Auth::user();

            // Check if mahasiswa already in class
            $existing = KelasMahasiswa::where('kelas_id', $kelasId)
                                     ->where('mahasiswa_id', $request->mahasiswa_id)
                                     ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahasiswa sudah terdaftar di kelas ini'
                ], 422);
            }

            KelasMahasiswa::create([
                'kelas_id' => $kelasId,
                'mahasiswa_id' => $request->mahasiswa_id,
                'created_by' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mahasiswa berhasil ditambahkan ke kelas'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function removeMahasiswa($kelasId, $mahasiswaId)
    {
        try {
            $kelasMahasiswa = KelasMahasiswa::where('kelas_id', $kelasId)
                                           ->where('mahasiswa_id', $mahasiswaId)
                                           ->firstOrFail();

            $user = Auth::user();
            $kelasMahasiswa->update(['deleted_by' => $user->id]);
            $kelasMahasiswa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mahasiswa berhasil dihapus dari kelas'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function storeJadwal(Request $request, $kelasId)
    {
        $request->validate([
            'jadwal_perkuliahan_id' => 'required|exists:jadwal_perkuliahan,id',
        ], [
            'jadwal_perkuliahan_id.required' => 'Jadwal perkuliahan wajib dipilih',
            'jadwal_perkuliahan_id.exists' => 'Jadwal perkuliahan tidak valid'
        ]);

        try {
            $user = Auth::user();

            // Check if jadwal already assigned to class
            $existing = JadwalKelas::where('kelas_id', $kelasId)
                                  ->where('jadwal_id', $request->jadwal_perkuliahan_id)
                                  ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal sudah ditetapkan untuk kelas ini'
                ], 422);
            }

            JadwalKelas::create([
                'kelas_id' => $kelasId,
                'jadwal_id' => $request->jadwal_perkuliahan_id,
                'created_by' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil ditambahkan ke kelas'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function removeJadwal($kelasId, $jadwalId)
    {
        try {
            $jadwalKelas = JadwalKelas::where('kelas_id', $kelasId)
                                     ->where('jadwal_id', $jadwalId)
                                     ->firstOrFail();

            $user = Auth::user();
            $jadwalKelas->update(['deleted_by' => $user->id]);
            $jadwalKelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus dari kelas'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }
}