<?php

namespace App\Http\Controllers\Master\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
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
            'jadwalKelas' => function($query) {
                $query->with(['kelas' => function($q) {
                    $q->with('programStudi');
                }]);
            },
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
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
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
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'jam_mulai.required' => 'Jam mulai wajib diisi',
            'jam_selesai.required' => 'Jam selesai wajib diisi',
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
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'metode' => $request->metode,
                'code' => $request->code,
                'created_by' => $user->id
            ]);

            // Auto-generate jadwal pertemuan
            $this->generateJadwalPertemuan($jadwalPerkuliahan, $request->tanggal_selesai ?? null);

            Alert::success('Berhasil', 'Data jadwal perkuliahan berhasil ditambahkan dan jadwal pertemuan otomatis dibuat');
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
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'metode' => 'required|in:Tatap Muka,Teleconference,Hybrid',
            'code' => 'required|string|unique:jadwal_perkuliahan,code,' . $id,
            // 'kelas' => 'nullable|array', // DISABLED: jadwal_kelas table structure changed
            // 'kelas.*.name' => 'required_with:kelas|string|max:255',
            // 'kelas.*.kapasitas' => 'required_with:kelas|integer|min:1',
            // 'kelas.*.is_active' => 'required_with:kelas|boolean',
            // 'kelas.*.keterangan' => 'nullable|string',
            'kelas_assign' => 'nullable|array',
            'kelas_assign.*.kelas_perkuliahan_id' => 'required_with:kelas_assign|exists:kelas_perkuliahan,id',
            'pertemuan' => 'nullable|array',
            'pertemuan.*.pertemuan_ke' => 'required_with:pertemuan|integer|min:1',
            'pertemuan.*.tanggal' => 'required_with:pertemuan|date',
            'pertemuan.*.jam_mulai' => 'nullable',
            'pertemuan.*.jam_selesai' => 'nullable',
            'pertemuan.*.ruang_id' => 'nullable|exists:ruangan,id',
            'pertemuan.*.dosen_id' => 'nullable|exists:users,id',
            'pertemuan.*.metode' => 'nullable|in:Tatap Muka,Teleconference,Hybrid',
            'pertemuan.*.materi' => 'nullable|string',
            'pertemuan.*.status' => 'required_with:pertemuan|in:Terjadwal,Terlaksana,Ditunda,Dibatalkan',
            'pertemuan.*.link' => 'nullable|url',
            'pertemuan.*.is_realisasi' => 'nullable|boolean'
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
            'jam_selesai.required' => 'Jam selesai wajib diisi',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai',
            'metode.required' => 'Metode perkuliahan wajib dipilih',
            'metode.in' => 'Metode perkuliahan tidak valid',
            'code.required' => 'Kode jadwal wajib diisi',
            'code.unique' => 'Kode jadwal sudah ada',
            // 'kelas.*.name.required_with' => 'Nama kelas wajib diisi', // DISABLED
            // 'kelas.*.kapasitas.required_with' => 'Kapasitas kelas wajib diisi', // DISABLED
            // 'kelas.*.kapasitas.min' => 'Kapasitas minimal 1', // DISABLED
            'kelas_assign.*.kelas_perkuliahan_id.required_with' => 'Kelas perkuliahan wajib dipilih',
            'kelas_assign.*.kelas_perkuliahan_id.exists' => 'Kelas perkuliahan tidak valid',
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
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'metode' => $request->metode,
                'code' => $request->code,
                'updated_by' => $user->id
            ]);

            // Don't regenerate jadwal pertemuan automatically on every update
            // Only update what's provided in the form
            // $this->regenerateJadwalPertemuan($jadwalPerkuliahan);

            // Update kelas assign - don't delete all, only add new ones and remove unselected
            if ($request->has('kelas_assign')) {
                // Get existing kelas IDs
                $existingKelasIds = $jadwalPerkuliahan->jadwalKelas()->pluck('kelas_id')->toArray();
                
                // Get requested kelas IDs - filter duplicates and empty values
                $requestedKelasIds = [];
                foreach ($request->kelas_assign as $kelasAssignData) {
                    $kelasId = $kelasAssignData['kelas_perkuliahan_id'] ?? null;
                    if (!empty($kelasId) && !in_array($kelasId, $requestedKelasIds)) {
                        $requestedKelasIds[] = $kelasId;
                    }
                }
                
                // Remove kelas that are no longer selected (permanent delete)
                $kelasToRemove = array_diff($existingKelasIds, $requestedKelasIds);
                if (!empty($kelasToRemove)) {
                    $jadwalPerkuliahan->jadwalKelas()->whereIn('kelas_id', $kelasToRemove)->forceDelete();
                }
                
                // Add new kelas that don't exist yet
                foreach ($requestedKelasIds as $kelasId) {
                    if (!in_array($kelasId, $existingKelasIds)) {
                        // Check if kelas exists
                        $kelasExists = \App\Models\Akademik\KelasPerkuliahan::find($kelasId);
                        if ($kelasExists) {
                            $jadwalPerkuliahan->jadwalKelas()->create([
                                'kelas_id' => $kelasId,
                                'created_by' => $user->id
                            ]);
                        }
                    }
                }
            } else {
                // If no kelas_assign provided, remove all existing (permanent delete)
                $jadwalPerkuliahan->jadwalKelas()->forceDelete();
            }

            // Update kelas (legacy support) - DISABLED: jadwal_kelas table only has jadwal_id and kelas_id
            // if ($request->has('kelas')) {
            //     // This legacy code is no longer valid as jadwal_kelas table structure has changed
            // }

            // Update pertemuan - don't regenerate all, only update what's provided
            if ($request->has('pertemuan')) {
                // Get existing pertemuan_ke from database
                $existingPertemuanKes = $jadwalPerkuliahan->jadwalPertemuan()->pluck('pertemuan_ke')->toArray();

                // Get pertemuan_ke from form data
                $formPertemuanKes = [];
                foreach ($request->pertemuan as $pertemuanData) {
                    if (!empty($pertemuanData['pertemuan_ke'])) {
                        $formPertemuanKes[] = $pertemuanData['pertemuan_ke'];
                    }
                }

                // Find pertemuan to delete (exist in DB but not in form)
                $pertemuanToDelete = array_diff($existingPertemuanKes, $formPertemuanKes);
                if (!empty($pertemuanToDelete)) {
                    $jadwalPerkuliahan->jadwalPertemuan()
                        ->whereIn('pertemuan_ke', $pertemuanToDelete)
                        ->forceDelete();
                }

                // Update or create pertemuan from form
                foreach ($request->pertemuan as $pertemuanData) {
                    if (!empty($pertemuanData['pertemuan_ke']) && !empty($pertemuanData['tanggal'])) {
                        // Check if pertemuan already exists
                        $existingPertemuan = $jadwalPerkuliahan->jadwalPertemuan()
                            ->where('pertemuan_ke', $pertemuanData['pertemuan_ke'])
                            ->first();

                        if ($existingPertemuan) {
                            // Update existing pertemuan
                            $existingPertemuan->update([
                                'tanggal' => $pertemuanData['tanggal'],
                                'jam_mulai' => !empty($pertemuanData['jam_mulai']) ? $pertemuanData['jam_mulai'] . ':00' : null,
                                'jam_selesai' => !empty($pertemuanData['jam_selesai']) ? $pertemuanData['jam_selesai'] . ':00' : null,
                                'ruang_id' => $pertemuanData['ruang_id'] ?? null,
                                'dosen_id' => $pertemuanData['dosen_id'] ?? null,
                                'metode' => $pertemuanData['metode'] ?? null,
                                'materi' => $pertemuanData['materi'] ?? null,
                                'status' => $pertemuanData['status'] ?? 'Terjadwal',
                                'link' => $pertemuanData['link'] ?? null,
                                'is_realisasi' => isset($pertemuanData['is_realisasi']) && $pertemuanData['is_realisasi'] == '1' ? true : false,
                                'updated_by' => $user->id
                            ]);
                        } else {
                            // Create new pertemuan
                            $jadwalPerkuliahan->jadwalPertemuan()->create([
                                'pertemuan_ke' => $pertemuanData['pertemuan_ke'],
                                'tanggal' => $pertemuanData['tanggal'],
                                'jam_mulai' => !empty($pertemuanData['jam_mulai']) ? $pertemuanData['jam_mulai'] . ':00' : null,
                                'jam_selesai' => !empty($pertemuanData['jam_selesai']) ? $pertemuanData['jam_selesai'] . ':00' : null,
                                'ruang_id' => $pertemuanData['ruang_id'] ?? null,
                                'dosen_id' => $pertemuanData['dosen_id'] ?? null,
                                'metode' => $pertemuanData['metode'] ?? null,
                                'materi' => $pertemuanData['materi'] ?? null,
                                'status' => $pertemuanData['status'] ?? 'Terjadwal',
                                'link' => $pertemuanData['link'] ?? null,
                                'is_realisasi' => isset($pertemuanData['is_realisasi']) && $pertemuanData['is_realisasi'] == '1' ? true : false,
                                'created_by' => $user->id
                            ]);
                        }
                    }
                }
            } else {
                // If no pertemuan provided, delete all existing (permanent delete)
                $jadwalPerkuliahan->jadwalPertemuan()->forceDelete();
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

            // Permanently delete all related records first
            $jadwalPerkuliahan->jadwalKelas()->forceDelete();
            $jadwalPerkuliahan->jadwalPertemuan()->forceDelete();

            // Permanently delete the main jadwal record
            $jadwalPerkuliahan->forceDelete();

            Alert::success('Berhasil', 'Data jadwal perkuliahan berhasil dihapus permanen');
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

    /**
     * Generate jadwal pertemuan otomatis berdasarkan hari dan tanggal mulai
     */
    private function generateJadwalPertemuan($jadwalPerkuliahan, $tanggalSelesai = null)
    {
        $user = Auth::user();

        // Mapping hari ke nomor hari dalam seminggu (0 = Minggu, 1 = Senin, dst)
        $hariMapping = [
            'Minggu' => 0,
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6
        ];

        $hariTarget = $hariMapping[$jadwalPerkuliahan->hari];
        $tanggalMulai = Carbon::parse($jadwalPerkuliahan->tanggal_mulai);

        // Jika tidak ada tanggal selesai, gunakan 15 minggu ke depan sebagai default
        if (!$tanggalSelesai) {
            $tanggalSelesai = $tanggalMulai->copy()->addWeeks(15);
        } else {
            $tanggalSelesai = Carbon::parse($tanggalSelesai);
        }

        // Cari tanggal pertama yang sesuai dengan hari target
        $tanggalPertama = $tanggalMulai->copy();
        if ($tanggalPertama->dayOfWeek !== $hariTarget) {
            // Jika tanggal mulai bukan hari target, cari hari target berikutnya
            $tanggalPertama = $tanggalPertama->next($hariTarget);
        }

        $pertemuanKe = 1;
        $tanggalSaatIni = $tanggalPertama->copy();

        // Generate jadwal pertemuan mingguan sampai tanggal selesai
        while ($tanggalSaatIni->lte($tanggalSelesai)) {
            // Buat jadwal pertemuan
            $jadwalPerkuliahan->jadwalPertemuan()->create([
                'pertemuan_ke' => $pertemuanKe,
                'tanggal' => $tanggalSaatIni->format('Y-m-d'),
                'jam_mulai' => $jadwalPerkuliahan->jam_mulai,
                'jam_selesai' => $jadwalPerkuliahan->jam_selesai,
                'ruang_id' => $jadwalPerkuliahan->ruang_id,
                'dosen_id' => $jadwalPerkuliahan->dosen_id,
                'metode' => $jadwalPerkuliahan->metode,
                'materi' => null,
                'status' => 'Terjadwal',
                'link' => null,
                'is_realisasi' => false,
                'created_by' => $user->id
            ]);

            // Lanjut ke minggu berikutnya
            $tanggalSaatIni->addWeek();
            $pertemuanKe++;
        }
    }

    /**
     * Regenerate jadwal pertemuan otomatis berdasarkan hari dan tanggal mulai
     */
    private function regenerateJadwalPertemuan($jadwalPerkuliahan)
    {
        // Hapus semua jadwal pertemuan yang ada (permanent delete)
        $jadwalPerkuliahan->jadwalPertemuan()->forceDelete();

        // Generate ulang jadwal pertemuan
        $this->generateJadwalPertemuan($jadwalPerkuliahan, $jadwalPerkuliahan->tanggal_selesai);
    }
}