<?php

namespace App\Http\Controllers\Master\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
// Use Models
use App\Models\Akademik\JadwalPerkuliahan;
use App\Models\Akademik\JadwalKelas;
use App\Models\Akademik\JadwalPertemuan;
use App\Models\Akademik\KelasPerkuliahan;
use App\Models\Akademik\TahunAkademik;
use App\Models\Akademik\MataKuliah;
use App\Models\User;
use App\Models\Infra\Ruangan;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class JadwalPerkuliahanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['spref'] = $user ? $user->prefix : '';
        $data['menus'] = 'Akademik Jadwal Perkuliahan';
        $data['pages'] = "Halaman Data Jadwal Perkuliahan";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['jadwalPerkuliahans'] = JadwalPerkuliahan::with(['tahunAkademik', 'mataKuliah', 'dosen', 'ruangan'])->orderBy('created_at', 'desc')->get();
        $data['tahunAkademiks'] = TahunAkademik::where('is_active', true)->orderBy('created_at', 'desc')->get();
        $data['mataKuliahs'] = MataKuliah::where('is_active', true)->orderBy('name')->get();
        $data['dosens'] = User::all();
        $data['ruangans'] = Ruangan::where('is_active', true)->orderBy('name')->get();
        $data['is_trash'] = false;

        return view('master.akademik.jadwal-perkuliahan-index', $data, compact('user'));
    }
    public function view($id)
    {
        $user = Auth::user();
        $data['spref'] = $user ? $user->prefix : '';
        $data['menus'] = 'Akademik Jadwal Perkuliahan';
        $data['pages'] = "Halaman Detail Jadwal Perkuliahan";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        
        // Get specific jadwal with relationships
        $data['jadwal'] = JadwalPerkuliahan::with([
            'tahunAkademik', 
            'mataKuliah', 
            'dosen', 
            'ruangan',
            'jadwalKelas',
            'jadwalPertemuan' => function($query) {
                $query->orderBy('pertemuan_ke');
            }
        ])->findOrFail($id);
        
        // Data for dropdowns
        $data['tahunAkademiks'] = TahunAkademik::where('is_active', true)->orderBy('created_at', 'desc')->get();
        $data['mataKuliahs'] = MataKuliah::where('is_active', true)->orderBy('name')->get();
        $data['dosens'] = User::all();
        $data['ruangans'] = Ruangan::where('is_active', true)->orderBy('name')->get();
        $data['kelasPerkuliahans'] = KelasPerkuliahan::with(['mataKuliah', 'programStudi'])->orderBy('name')->get();
        $data['is_trash'] = false;

        return view('master.akademik.jadwal-perkuliahan-view', $data, compact('user'));
    }

    public function trash()
    {
        $user = Auth::user();
        $data['spref'] = $user ? $user->prefix : '';
        $data['menus'] = 'Akademik Jadwal Perkuliahan';
        $data['pages'] = "Halaman Data Jadwal Perkuliahan yang Dihapus";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['jadwalPerkuliahans'] = JadwalPerkuliahan::onlyTrashed()->with(['tahunAkademik', 'mataKuliah', 'dosen', 'ruangan'])->get();
        $data['tahunAkademiks'] = TahunAkademik::where('is_active', true)->orderBy('created_at', 'desc')->get();
        $data['mataKuliahs'] = MataKuliah::where('is_active', true)->orderBy('name')->get();
        $data['dosens'] = User::all();
        $data['ruangans'] = Ruangan::where('is_active', true)->orderBy('name')->get();
        $data['is_trash'] = true;

        return view('master.akademik.jadwal-perkuliahan-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:users,id',
            'ruang_id' => 'nullable|exists:ruangan,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'metode' => 'required|in:Tatap Muka,Teleconference,Hybrid',
            'code' => 'required|string|unique:jadwal_perkuliahan,code'
        ], [
            'tahun_akademik_id.required' => 'Tahun akademik wajib dipilih',
            'tahun_akademik_id.exists' => 'Tahun akademik tidak valid',
            'mata_kuliah_id.required' => 'Mata kuliah wajib dipilih',
            'mata_kuliah_id.exists' => 'Mata kuliah tidak valid',
            'dosen_id.required' => 'Dosen wajib dipilih',
            'dosen_id.exists' => 'Dosen tidak valid',
            'ruang_id.exists' => 'Ruangan tidak valid',
            'hari.required' => 'Hari wajib dipilih',
            'hari.in' => 'Hari tidak valid',
            'jam_mulai.required' => 'Jam mulai wajib diisi',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid',
            'jam_selesai.required' => 'Jam selesai wajib diisi',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai',
            'metode.required' => 'Metode perkuliahan wajib dipilih',
            'metode.in' => 'Metode perkuliahan tidak valid',
            'code.required' => 'Kode jadwal wajib diisi',
            'code.unique' => 'Kode jadwal sudah ada'
        ]);

        try {
            $user = Auth::user();
            
            $jadwalPerkuliahan = JadwalPerkuliahan::create([
                'tahun_akademik_id' => $request->tahun_akademik_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $request->dosen_id,
                'ruang_id' => $request->ruang_id,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'metode' => $request->metode,
                'code' => $request->code,
                'created_by' => $user->id
            ]);

            Alert::success('Berhasil', 'Data jadwal perkuliahan berhasil ditambahkan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $jadwalPerkuliahan = JadwalPerkuliahan::findOrFail($id);
        
        // Preprocess pertemuan data to convert empty strings to null
        if ($request->has('pertemuan')) {
            $pertemuan = $request->input('pertemuan');
            foreach ($pertemuan as $key => $item) {
                if (isset($item['jam_mulai']) && $item['jam_mulai'] === '') {
                    $pertemuan[$key]['jam_mulai'] = null;
                }
                if (isset($item['jam_selesai']) && $item['jam_selesai'] === '') {
                    $pertemuan[$key]['jam_selesai'] = null;
                }
            }
            $request->merge(['pertemuan' => $pertemuan]);
        }
        
        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:users,id',
            'ruang_id' => 'nullable|exists:ruangan,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'metode' => 'required|in:Tatap Muka,Teleconference,Hybrid',
            'code' => 'required|string|unique:jadwal_perkuliahan,code,' . $id,
            'kelas' => 'nullable|array',
            'kelas.*.name' => 'required_with:kelas|string|max:255',
            'kelas.*.kapasitas' => 'required_with:kelas|integer|min:1',
            'kelas.*.is_active' => 'required_with:kelas|boolean',
            'kelas.*.keterangan' => 'nullable|string',
            'pertemuan' => 'nullable|array',
            'pertemuan.*.pertemuan_ke' => 'required_with:pertemuan|integer|min:1',
            'pertemuan.*.tanggal' => 'required_with:pertemuan|date',
            'pertemuan.*.jam_mulai' => 'nullable|date_format:H:i',
            'pertemuan.*.jam_selesai' => 'nullable|date_format:H:i',
            'pertemuan.*.materi' => 'nullable|string',
            'pertemuan.*.status' => 'required_with:pertemuan|in:Belum Terlaksana,Terlaksana,Dibatalkan',
            'pertemuan.*.link' => 'nullable|url'
        ], [
            'tahun_akademik_id.required' => 'Tahun akademik wajib dipilih',
            'tahun_akademik_id.exists' => 'Tahun akademik tidak valid',
            'mata_kuliah_id.required' => 'Mata kuliah wajib dipilih',
            'mata_kuliah_id.exists' => 'Mata kuliah tidak valid',
            'dosen_id.required' => 'Dosen wajib dipilih',
            'dosen_id.exists' => 'Dosen tidak valid',
            'ruang_id.exists' => 'Ruangan tidak valid',
            'hari.required' => 'Hari wajib dipilih',
            'hari.in' => 'Hari tidak valid',
            'jam_mulai.required' => 'Jam mulai wajib diisi',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid',
            'jam_selesai.required' => 'Jam selesai wajib diisi',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai',
            'metode.required' => 'Metode perkuliahan wajib dipilih',
            'metode.in' => 'Metode perkuliahan tidak valid',
            'code.required' => 'Kode jadwal wajib diisi',
            'code.unique' => 'Kode jadwal sudah ada',
            'kelas.*.name.required_with' => 'Nama kelas wajib diisi',
            'kelas.*.kapasitas.required_with' => 'Kapasitas kelas wajib diisi',
            'kelas.*.kapasitas.min' => 'Kapasitas minimal 1',
            'pertemuan.*.pertemuan_ke.required_with' => 'Nomor pertemuan wajib diisi',
            'pertemuan.*.tanggal.required_with' => 'Tanggal pertemuan wajib diisi',
            'pertemuan.*.status.required_with' => 'Status pertemuan wajib dipilih'
        ]);

        try {
            $user = Auth::user();
            
            // Update jadwal perkuliahan
            $jadwalPerkuliahan->update([
                'tahun_akademik_id' => $request->tahun_akademik_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $request->dosen_id,
                'ruang_id' => $request->ruang_id,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'metode' => $request->metode,
                'code' => $request->code,
                'updated_by' => $user->id
            ]);

            // Update kelas
            if ($request->has('kelas')) {
                // Delete existing jadwal kelas
                $jadwalPerkuliahan->jadwalKelas()->delete();
                
                // Create new kelas
                foreach ($request->kelas as $kelasData) {
                    if (!empty($kelasData['name'])) {
                        $jadwalPerkuliahan->jadwalKelas()->create([
                            'name' => $kelasData['name'],
                            'kapasitas' => $kelasData['kapasitas'],
                            'is_active' => $kelasData['is_active'] ?? true,
                            'keterangan' => $kelasData['keterangan'] ?? null,
                            'created_by' => $user->id
                        ]);
                    }
                }
            }

            // Update pertemuan
            if ($request->has('pertemuan')) {
                // Delete existing jadwal pertemuan
                $jadwalPerkuliahan->jadwalPertemuan()->delete();
                
                // Create new pertemuan
                foreach ($request->pertemuan as $pertemuanData) {
                    if (!empty($pertemuanData['pertemuan_ke']) && !empty($pertemuanData['tanggal'])) {
                        $jadwalPerkuliahan->jadwalPertemuan()->create([
                            'pertemuan_ke' => $pertemuanData['pertemuan_ke'],
                            'tanggal' => $pertemuanData['tanggal'],
                            'jam_mulai' => $pertemuanData['jam_mulai'] ?? null,
                            'jam_selesai' => $pertemuanData['jam_selesai'] ?? null,
                            'materi' => $pertemuanData['materi'] ?? null,
                            'status' => $pertemuanData['status'] ?? 'Belum Terlaksana',
                            'link' => $pertemuanData['link'] ?? null,
                            'created_by' => $user->id
                        ]);
                    }
                }
            }

            Alert::success('Berhasil', 'Data jadwal perkuliahan berhasil diperbarui');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $jadwalPerkuliahan = JadwalPerkuliahan::findOrFail($id);

            $user = Auth::user();
            $jadwalPerkuliahan->update(['deleted_by' => $user->id]);
            $jadwalPerkuliahan->delete();

            Alert::success('Berhasil', 'Data jadwal perkuliahan berhasil dihapus');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $jadwalPerkuliahan = JadwalPerkuliahan::onlyTrashed()->findOrFail($id);

            $user = Auth::user();
            $jadwalPerkuliahan->update(['updated_by' => $user->id, 'deleted_by' => null]);
            $jadwalPerkuliahan->restore();

            Alert::success('Berhasil', 'Data jadwal perkuliahan berhasil dikembalikan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }
}