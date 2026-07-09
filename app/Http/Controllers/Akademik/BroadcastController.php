<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\BroadcastMail;
use App\Models\Akademik\BroadcastEmail;
use App\Models\Akademik\DataMahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\User;

class BroadcastController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Broadcast Email';
        $data['pages'] = 'Riwayat Broadcast Email';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['broadcasts'] = BroadcastEmail::with('creator')
            ->latest()
            ->paginate(15);

        return view('master.akademik.broadcast-index', $data, compact('user'));
    }

    public function createForm()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus'] = 'Broadcast Email';
        $data['pages'] = 'Kirim Broadcast Email Baru';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        $data['prodis'] = ProgramStudi::where('is_active', true)->orderBy('name')->get();
        $data['angkatans'] = DataMahasiswa::distinct()
            ->whereNotNull('angkatan')
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        return view('master.akademik.broadcast-create', $data, compact('user'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'target_type' => 'required|in:semua_dosen,semua_mahasiswa,prodi,angkatan',
            'prodi_id' => 'nullable|required_if:target_type,prodi|exists:program_studi,id',
            'angkatan' => 'nullable|required_if:target_type,angkatan|integer|digits:4',
        ]);

        $academy = Kampus::first();
        $academyName = $academy ? $academy->name : config('app.name');

        // --- Resolve recipients ---
        $recipients = collect();
        $targetLabel = '';

        switch ($request->target_type) {
            case 'semua_dosen':
                $recipients = User::role('dosen')
                    ->whereNotNull('email')
                    ->where('email', '!=', '')
                    ->get(['id', 'name', 'email']);
                $targetLabel = 'Semua Dosen';
                break;

            case 'semua_mahasiswa':
                $recipients = User::role('mahasiswa')
                    ->whereNotNull('email')
                    ->where('email', '!=', '')
                    ->get(['id', 'name', 'email']);
                $targetLabel = 'Semua Mahasiswa';
                break;

            case 'prodi':
                $prodi = ProgramStudi::find($request->prodi_id);
                $recipients = User::role('mahasiswa')
                    ->whereNotNull('email')
                    ->where('email', '!=', '')
                    ->whereHas('dataMahasiswa', fn($q) => $q->where('program_studi_id', $request->prodi_id))
                    ->get(['id', 'name', 'email']);
                $targetLabel = 'Prodi: ' . ($prodi ? $prodi->name : $request->prodi_id);
                break;

            case 'angkatan':
                $recipients = User::role('mahasiswa')
                    ->whereNotNull('email')
                    ->where('email', '!=', '')
                    ->whereHas('dataMahasiswa', fn($q) => $q->where('angkatan', $request->angkatan))
                    ->get(['id', 'name', 'email']);
                $targetLabel = 'Angkatan ' . $request->angkatan;
                break;
        }

        if ($recipients->isEmpty()) {
            Alert::warning('Perhatian', 'Tidak ada penerima yang ditemukan untuk kelompok target tersebut.');
            return redirect()->back()->withInput();
        }

        // --- Create log record first (Pending) ---
        $broadcast = BroadcastEmail::create([
            'subject'          => $request->subject,
            'content'          => $request->content,
            'target_audience'  => $targetLabel,
            'total_recipients' => $recipients->count(),
            'status'           => 'Pending',
            'created_by'       => Auth::id(),
        ]);

        // --- Send emails ---
        $failCount = 0;
        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient->email)->send(
                    new BroadcastMail($request->subject, $request->content, $academyName)
                );
            } catch (\Exception $e) {
                $failCount++;
            }
        }

        // --- Update status ---
        $status = ($failCount === 0) ? 'Terkirim' : ($failCount === $recipients->count() ? 'Gagal' : 'Terkirim');
        $broadcast->update([
            'status'  => $status,
            'sent_at' => now(),
        ]);

        $successCount = $recipients->count() - $failCount;
        Alert::success('Broadcast Terkirim', "Email berhasil dikirim ke {$successCount} dari {$recipients->count()} penerima ({$targetLabel}).");
        return redirect()->route('admin.broadcast.index');
    }
}
