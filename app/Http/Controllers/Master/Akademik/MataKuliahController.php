<?php

namespace App\Http\Controllers\Master\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
// Use Models
use App\Models\Akademik\MataKuliah;
use App\Models\Akademik\MataKuliahDetail;
use App\Models\Akademik\MataKuliahPrasyarat;
use App\Models\Akademik\MataKuliahDosen;
use App\Models\Referensi\Semester;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;
use App\Models\User;

class MataKuliahController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Akademik Mata Kuliah';
        $data['pages'] = "Halaman Data Mata Kuliah";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['mataKuliahs'] = MataKuliah::with('semester')->orderBy('name')->get();
        $data['semesters'] = Semester::all();
        $data['is_trash'] = false;

        return view('master.akademik.matakuliah-index', $data, compact('user'));
    }

    public function trash()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Akademik Mata Kuliah';
        $data['pages'] = "Halaman Data Mata Kuliah yang Dihapus";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['mataKuliahs'] = MataKuliah::onlyTrashed()->with('semester')->get();
        $data['semesters'] = Semester::orderBy('name')->get();
        $data['is_trash'] = true;

        return view('master.akademik.matakuliah-index', $data, compact('user'));
    }
    public function view($id)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Mata Kuliah';
        $data['pages'] = "Halaman Detail Mata Kuliah";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        
        // Load mata kuliah with all related data
        $data['mataKuliah'] = MataKuliah::with([
            'semester',
            'detail',
            'prasyarat' => function($query) {
                $query->join('mata_kuliah as mk_prasyarat', 'mata_kuliah_prasyarat.prasyarat_id', '=', 'mk_prasyarat.id')
                      ->select('mata_kuliah_prasyarat.*', 'mk_prasyarat.id', 'mk_prasyarat.name', 'mk_prasyarat.code');
            },
            'dosenPengampu' => function($query) {
                $query->join('users', 'mata_kuliah_dosen.dosen_pengampu_id', '=', 'users.id')
                      ->select('mata_kuliah_dosen.*', 'users.id', 'users.name', 'users.email');
            }
        ])->findOrFail($id);
        
        // Additional data for dropdowns
        $data['semesters'] = Semester::orderBy('name')->get();
        $data['allMataKuliah'] = MataKuliah::where('id', '!=', $id)->orderBy('name')->get();
        $data['allDosen'] = User::all();
        
        $data['is_trash'] = false;

        return view('master.akademik.matakuliah-view', $data, compact('user'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'required|string|unique:mata_kuliah,code',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'beban_sks' => 'required|integer|min:1|max:10',
            'sks_teori' => 'nullable|integer|min:0|max:10',
            'sks_praktik' => 'nullable|integer|min:0|max:10',
            'sks_lapangan' => 'nullable|integer|min:0|max:10',
            'jenis' => 'required|in:Wajib,Pilihan,MKWU,MKU',
            'min_semester' => 'nullable|integer|min:1|max:14',
            'is_active' => 'required|boolean',
            'deskripsi' => 'required|string',
            'capaian_pembelajaran' => 'nullable|string',
            'materi_pokok' => 'nullable|string',
            'metode_pembelajaran' => 'nullable|array',
            'metode_penilaian' => 'nullable|array',
            'prasyarat_ids' => 'nullable|array',
            'prasyarat_ids.*' => 'exists:mata_kuliah,id',
            'dosen_pengampu_ids' => 'nullable|array',
            'dosen_pengampu_ids.*' => 'exists:users,id'
        ], [
            'semester_id.required' => 'Semester wajib dipilih',
            'semester_id.exists' => 'Semester tidak valid',
            'name.required' => 'Nama mata kuliah wajib diisi',
            'code.required' => 'Kode mata kuliah wajib diisi',
            'code.unique' => 'Kode mata kuliah sudah ada',
            'beban_sks.required' => 'Beban SKS wajib diisi',
            'beban_sks.integer' => 'Beban SKS harus berupa angka',
            'jenis.required' => 'Jenis mata kuliah wajib dipilih',
            'jenis.in' => 'Jenis mata kuliah tidak valid',
            'deskripsi.required' => 'Deskripsi wajib diisi'
        ]);

        try {
            $user = Auth::user();
            
            // Handle file upload
            $coverPath = 'default-mk.jpg';
            if ($request->hasFile('cover')) {
                $coverPath = $request->file('cover')->store('mata-kuliah-covers', 'public');
            }
            
            // Create mata kuliah
            $mataKuliah = MataKuliah::create([
                'semester_id' => $request->semester_id,
                'name' => $request->name,
                'name_en' => $request->name_en,
                'code' => $request->code,
                'cover' => $coverPath,
                'beban_sks' => $request->beban_sks,
                'sks_teori' => $request->sks_teori ?? 0,
                'sks_praktik' => $request->sks_praktik ?? 0,
                'sks_lapangan' => $request->sks_lapangan ?? 0,
                'jenis' => $request->jenis,
                'min_semester' => $request->min_semester,
                'is_active' => $request->is_active,
                'created_by' => $user->id
            ]);
            
            // Create mata kuliah detail
            MataKuliahDetail::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'deskripsi' => $request->deskripsi,
                'capaian_pembelajaran' => $request->capaian_pembelajaran,
                'materi_pokok' => $request->materi_pokok,
                'metode_pembelajaran' => json_encode($request->metode_pembelajaran ?? []),
                'metode_penilaian' => json_encode($request->metode_penilaian ?? []),
                'created_by' => $user->id
            ]);
            
            // Create prasyarat
            if ($request->prasyarat_ids) {
                foreach ($request->prasyarat_ids as $prasyaratId) {
                    if (!empty($prasyaratId)) {
                        MataKuliahPrasyarat::create([
                            'mata_kuliah_id' => $mataKuliah->id,
                            'prasyarat_id' => $prasyaratId,
                            'created_by' => $user->id
                        ]);
                    }
                }
            }
            
            // Create dosen pengampu
            if ($request->dosen_pengampu_ids) {
                foreach ($request->dosen_pengampu_ids as $dosenId) {
                    if (!empty($dosenId)) {
                        MataKuliahDosen::create([
                            'mata_kuliah_id' => $mataKuliah->id,
                            'dosen_pengampu_id' => $dosenId,
                            'created_by' => $user->id
                        ]);
                    }
                }
            }

            Alert::success('Berhasil', 'Data mata kuliah berhasil ditambahkan');
            return redirect()->route('master.akademik.matakuliah.view', $mataKuliah->id);

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        
        $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'required|string|unique:mata_kuliah,code,' . $id,
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'beban_sks' => 'required|integer|min:1|max:10',
            'sks_teori' => 'nullable|integer|min:0|max:10',
            'sks_praktik' => 'nullable|integer|min:0|max:10',
            'sks_lapangan' => 'nullable|integer|min:0|max:10',
            'jenis' => 'required|in:Wajib,Pilihan,MKWU,MKU',
            'min_semester' => 'nullable|integer|min:1|max:14',
            'is_active' => 'required|boolean',
            'deskripsi' => 'required|string',
            'capaian_pembelajaran' => 'nullable|string',
            'materi_pokok' => 'nullable|string',
            'metode_pembelajaran' => 'nullable|array',
            'metode_penilaian' => 'nullable|array',
            'prasyarat_ids' => 'nullable|array',
            'prasyarat_ids.*' => 'exists:mata_kuliah,id',
            'dosen_pengampu_ids' => 'nullable|array',
            'dosen_pengampu_ids.*' => 'exists:users,id'
        ], [
            'semester_id.required' => 'Semester wajib dipilih',
            'semester_id.exists' => 'Semester tidak valid',
            'name.required' => 'Nama mata kuliah wajib diisi',
            'code.required' => 'Kode mata kuliah wajib diisi',
            'code.unique' => 'Kode mata kuliah sudah ada',
            'beban_sks.required' => 'Beban SKS wajib diisi',
            'beban_sks.integer' => 'Beban SKS harus berupa angka',
            'jenis.required' => 'Jenis mata kuliah wajib dipilih',
            'jenis.in' => 'Jenis mata kuliah tidak valid',
            'deskripsi.required' => 'Deskripsi wajib diisi'
        ]);

        try {
            $user = Auth::user();
            
            // Handle file upload
            $coverPath = $mataKuliah->cover;
            if ($request->hasFile('cover')) {
                $coverPath = $request->file('cover')->store('mata-kuliah-covers', 'public');
            }
            
            // Update mata kuliah data
            $mataKuliah->update([
                'semester_id' => $request->semester_id,
                'name' => $request->name,
                'name_en' => $request->name_en,
                'code' => $request->code,
                'cover' => $coverPath,
                'beban_sks' => $request->beban_sks,
                'sks_teori' => $request->sks_teori ?? 0,
                'sks_praktik' => $request->sks_praktik ?? 0,
                'sks_lapangan' => $request->sks_lapangan ?? 0,
                'jenis' => $request->jenis,
                'min_semester' => $request->min_semester,
                'is_active' => $request->is_active,
                'updated_by' => $user->id
            ]);
            
            // Update or create mata kuliah detail
            MataKuliahDetail::updateOrCreate(
                ['mata_kuliah_id' => $id],
                [
                    'deskripsi' => $request->deskripsi,
                    'capaian_pembelajaran' => $request->capaian_pembelajaran,
                    'materi_pokok' => $request->materi_pokok,
                    'metode_pembelajaran' => json_encode($request->metode_pembelajaran ?? []),
                    'metode_penilaian' => json_encode($request->metode_penilaian ?? []),
                    'updated_by' => $user->id
                ]
            );
            
            // Update prasyarat
            MataKuliahPrasyarat::where('mata_kuliah_id', $id)->delete();
            if ($request->prasyarat_ids) {
                foreach ($request->prasyarat_ids as $prasyaratId) {
                    if (!empty($prasyaratId)) {
                        MataKuliahPrasyarat::create([
                            'mata_kuliah_id' => $id,
                            'prasyarat_id' => $prasyaratId,
                            'created_by' => $user->id
                        ]);
                    }
                }
            }
            
            // Update dosen pengampu
            MataKuliahDosen::where('mata_kuliah_id', $id)->delete();
            if ($request->dosen_pengampu_ids) {
                foreach ($request->dosen_pengampu_ids as $dosenId) {
                    if (!empty($dosenId)) {
                        MataKuliahDosen::create([
                            'mata_kuliah_id' => $id,
                            'dosen_pengampu_id' => $dosenId,
                            'created_by' => $user->id
                        ]);
                    }
                }
            }

            Alert::success('Berhasil', 'Data mata kuliah berhasil diperbarui');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $mataKuliah = MataKuliah::findOrFail($id);

            $user = Auth::user();
            $mataKuliah->update(['deleted_by' => $user->id]);
            $mataKuliah->delete();

            Alert::success('Berhasil', 'Data mata kuliah berhasil dihapus');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $mataKuliah = MataKuliah::onlyTrashed()->findOrFail($id);

            $user = Auth::user();
            $mataKuliah->update(['updated_by' => $user->id, 'deleted_by' => null]);
            $mataKuliah->restore();

            Alert::success('Berhasil', 'Data mata kuliah berhasil dikembalikan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }
}