<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\SharedFile;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class SharedFileController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Tukar File',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Hub Berbagi / Tukar File');

        // Build query based on user roles
        $query = SharedFile::with('user');

        if (!$user->hasRole('admin') && !$user->hasRole('superadmin')) {
            $allowedVisibilities = ['Public'];
            if ($user->hasRole('dosen')) {
                $allowedVisibilities[] = 'Dosen';
            }
            if ($user->hasRole('mahasiswa')) {
                $allowedVisibilities[] = 'Mahasiswa';
            }
            
            $query->where(function($q) use ($user, $allowedVisibilities) {
                $q->whereIn('visibility', $allowedVisibilities)
                  ->orWhere('user_id', $user->id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $data['files'] = $query->latest()->paginate(20);

        return view('master.akademik.shared-files-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'required|file|max:10240', // 10MB Limit
            'visibility'  => 'required|in:Public,Dosen,Mahasiswa,Private',
        ]);

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $originalName = $uploadedFile->getClientOriginalName();
            $fileSize     = $uploadedFile->getSize();
            $fileType     = $uploadedFile->getClientMimeType();

            $path = $uploadedFile->store('shared_files', 'public');

            SharedFile::create([
                'title'       => $request->title,
                'description' => $request->description,
                'file_path'   => $path,
                'file_name'   => $originalName,
                'file_size'   => $fileSize,
                'file_type'   => $fileType,
                'visibility'  => $request->visibility,
                'user_id'     => Auth::id(),
            ]);

            Alert::success('Berhasil', 'File berhasil diunggah ke hub.');
        } else {
            Alert::error('Gagal', 'File tidak ditemukan.');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $sharedFile = SharedFile::findOrFail($id);
        $user       = Auth::user();

        // Check ownership or admin status
        if ($sharedFile->user_id !== $user->id && !$user->hasRole('admin') && !$user->hasRole('superadmin')) {
            Alert::error('Akses Ditolak', 'Anda tidak memiliki hak untuk menghapus file ini.');
            return redirect()->back();
        }

        // Delete from storage
        if ($sharedFile->file_path) {
            Storage::disk('public')->delete($sharedFile->file_path);
        }

        $sharedFile->delete();

        Alert::success('Berhasil', 'File berhasil dihapus dari hub.');
        return redirect()->back();
    }
}
