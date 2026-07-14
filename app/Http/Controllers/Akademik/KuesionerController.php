<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;
use App\Models\Akademik\Kuesioner;
use App\Models\Akademik\KuesionerPertanyaan;
use App\Models\Akademik\KuesionerRespons;
use App\Models\Akademik\KuesionerResponsDetail;

class KuesionerController extends Controller
{
    /**
     * ==========================================
     * PANEL ADMIN: MANAJEMEN KUESIONER
     * ==========================================
     */

    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Kuesioner';
        $data['pages'] = 'Manajemen Kuesioner & Tracer Study';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $query = Kuesioner::withCount('responses');
        if ($request->input('target') === 'Alumni') {
            $query->where('target_responden', 'Alumni');
        }
        $data['kuesioners'] = $query->latest()->get();

        return view('master.akademik.kuesioner-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_responden' => 'required|in:Alumni,Mahasiswa,Dosen,Umum',
        ]);

        Kuesioner::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'target_responden' => $request->target_responden,
            'is_published' => false,
            'created_by' => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Kuesioner baru berhasil dibuat. Silakan tambahkan pertanyaan.');
        return redirect()->back();
    }

    public function togglePublish($id)
    {
        $kuesioner = Kuesioner::findOrFail($id);
        $newStatus = !$kuesioner->is_published;
        $kuesioner->update(['is_published' => $newStatus]);

        if ($newStatus) {
            // Target dispatch
            try {
                $roleTarget = strtolower($kuesioner->target_responden);
                if (in_array($roleTarget, ['mahasiswa', 'alumni', 'dosen'])) {
                    \App\Helpers\NotifikasiHelper::sendToRole(
                        $roleTarget,
                        'Kuesioner Baru Tersedia',
                        'Kuesioner "' . $kuesioner->judul . '" telah dibuka. Mohon kesediaannya mengisi kuesioner ini.',
                        'info',
                        'forms',
                        route($roleTarget . '.kuesioner.index')
                    );
                }
            } catch (\Exception $e) {}
        }

        Alert::success('Berhasil', 'Status publikasi kuesioner berhasil diperbarui');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $kuesioner = Kuesioner::findOrFail($id);
        $kuesioner->delete();

        Alert::success('Berhasil', 'Kuesioner berhasil dihapus');
        return redirect()->back();
    }

    public function questions($id)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Kuesioner';
        $data['pages'] = 'Kelola Pertanyaan Kuesioner';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['kuesioner'] = Kuesioner::with('pertanyaans')->findOrFail($id);

        return view('master.akademik.kuesioner-questions', $data, compact('user'));
    }

    public function storeQuestion(Request $request, $id)
    {
        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|in:text,radio,checkbox',
            'pilihan_pilihan' => 'nullable|string', // Comma separated choices
        ]);

        $kuesioner = Kuesioner::findOrFail($id);

        $pilihan = null;
        if (in_array($request->tipe, ['radio', 'checkbox']) && $request->pilihan_pilihan) {
            $pilihan = array_map('trim', explode(',', $request->pilihan_pilihan));
        }

        // Get max order
        $maxOrder = KuesionerPertanyaan::where('kuesioner_id', $kuesioner->id)->max('urutan') ?? 0;

        KuesionerPertanyaan::create([
            'kuesioner_id' => $kuesioner->id,
            'pertanyaan' => $request->pertanyaan,
            'tipe' => $request->tipe,
            'pilihan_jawaban' => $pilihan,
            'urutan' => $maxOrder + 1
        ]);

        Alert::success('Berhasil', 'Pertanyaan baru berhasil ditambahkan');
        return redirect()->back();
    }

    public function destroyQuestion($id)
    {
        $question = KuesionerPertanyaan::findOrFail($id);
        $question->delete();

        Alert::success('Berhasil', 'Pertanyaan berhasil dihapus');
        return redirect()->back();
    }

    public function results($id)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Kuesioner';
        $data['pages'] = 'Hasil Analisis Kuesioner';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $kuesioner = Kuesioner::with(['pertanyaans', 'responses.details'])->findOrFail($id);
        $data['kuesioner'] = $kuesioner;

        // Perform simple analytics for radio/checkbox questions
        $analysis = [];
        foreach ($kuesioner->pertanyaans as $pertanyaan) {
            if ($pertanyaan->tipe === 'text') {
                // Get all text responses
                $answers = KuesionerResponsDetail::where('pertanyaan_id', $pertanyaan->id)
                    ->pluck('jawaban')
                    ->filter()
                    ->toArray();
                $analysis[$pertanyaan->id] = $answers;
            } else {
                // Choice-based
                $counts = [];
                if (is_array($pertanyaan->pilihan_jawaban)) {
                    foreach ($pertanyaan->pilihan_jawaban as $opt) {
                        $counts[$opt] = 0;
                    }
                }

                $answers = KuesionerResponsDetail::where('pertanyaan_id', $pertanyaan->id)
                    ->pluck('jawaban')
                    ->toArray();

                foreach ($answers as $ans) {
                    // Check if it's a JSON array (checkbox multiple choices)
                    $decoded = json_decode($ans, true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $val) {
                            if (isset($counts[$val])) {
                                $counts[$val]++;
                            } else {
                                $counts[$val] = 1;
                            }
                        }
                    } else {
                        // Radio option (single choice string)
                        if (isset($counts[$ans])) {
                            $counts[$ans]++;
                        } else {
                            $counts[$ans] = 1;
                        }
                    }
                }
                $analysis[$pertanyaan->id] = $counts;
            }
        }

        $data['analysis'] = $analysis;

        return view('master.akademik.kuesioner-results', $data, compact('user'));
    }

    /**
     * ==========================================
     * PANEL MAHASISWA & ALUMNI: PORTAL KUESIONER
     * ==========================================
     */

    public function indexPortal()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'mahasiswa';
        $data['menus'] = 'Kuesioner';
        $data['pages'] = 'Tracer Study & Kuesioner';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        // Determine target
        $target = 'Mahasiswa';
        if ($user->hasRole('alumni') || $data['activeRole'] === 'alumni') {
            $target = 'Alumni';
        }

        // Fetch active questionnaires matching target or Umum
        $data['kuesioners'] = Kuesioner::where('is_published', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->whereIn('target_responden', [$target, 'Umum'])
            ->latest()
            ->get();

        // Attach answered status
        foreach ($data['kuesioners'] as $k) {
            $k->is_answered = KuesionerRespons::where('kuesioner_id', $k->id)
                ->where('user_id', $user->id)
                ->exists();
        }

        return view('private.portal-kuesioner-index', $data, compact('user'));
    }

    public function showForm($id)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'mahasiswa';
        $data['menus'] = 'Kuesioner';
        $data['pages'] = 'Pengisian Kuesioner';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['kuesioner'] = Kuesioner::with('pertanyaans')->findOrFail($id);

        // Check if already answered
        $exists = KuesionerRespons::where('kuesioner_id', $id)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            Alert::error('Gagal', 'Anda sudah mengisi kuesioner ini.');
            return redirect()->route('mahasiswa.kuesioner.index');
        }

        return view('private.portal-kuesioner-form', $data, compact('user'));
    }

    public function submitForm(Request $request, $id)
    {
        $user = Auth::user();
        $kuesioner = Kuesioner::with('pertanyaans')->findOrFail($id);

        // Double check submission
        $exists = KuesionerRespons::where('kuesioner_id', $id)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            Alert::error('Gagal', 'Anda sudah mengisi kuesioner ini.');
            return redirect()->route('mahasiswa.kuesioner.index');
        }

        // Create respons record
        $respons = KuesionerRespons::create([
            'kuesioner_id' => $kuesioner->id,
            'user_id' => $user->id,
            'submitted_at' => now()
        ]);

        // Loop through questions to save detail answers
        foreach ($kuesioner->pertanyaans as $pertanyaan) {
            $inputName = 'q_' . $pertanyaan->id;
            $answer = $request->input($inputName);

            // If array (checkboxes), encode to JSON string
            if (is_array($answer)) {
                $answer = json_encode($answer);
            }

            KuesionerResponsDetail::create([
                'respons_id' => $respons->id,
                'pertanyaan_id' => $pertanyaan->id,
                'jawaban' => $answer ?? ''
            ]);
        }

        Alert::success('Berhasil', 'Terima kasih atas partisipasi Anda dalam pengisian kuesioner.');
        return redirect()->route('mahasiswa.kuesioner.index');
    }
}
