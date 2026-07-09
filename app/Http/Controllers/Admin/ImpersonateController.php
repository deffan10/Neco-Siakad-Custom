<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class ImpersonateController extends Controller
{
    public function impersonate($id)
    {
        $admin = Auth::user();

        // 1. Double check current user is actually an admin
        if (session('active_role') !== 'admin') {
            Alert::error('Unauthorized', 'Hanya administrator yang dapat menggunakan fitur impersonasi.');
            return redirect()->back();
        }

        // 2. Fetch target user
        $targetUser = User::findOrFail($id);

        // 3. Store original admin ID in session
        session(['admin_impersonator_id' => $admin->id]);

        // 4. Force login as the target user
        Auth::login($targetUser);

        // 5. Determine active role for target user
        // If target has student role, set to mahasiswa. If has lecturer role, set to dosen.
        $targetRole = 'mahasiswa';
        if ($targetUser->hasRole('dosen')) {
            $targetRole = 'dosen';
        } elseif ($targetUser->hasRole('admin')) {
            $targetRole = 'admin';
        }

        session(['active_role' => $targetRole]);

        Alert::success('Sesi Dialihkan', "Anda saat ini berhasil masuk sebagai: {$targetUser->name}.");
        
        // Redirect to their home dashboard
        return redirect()->route('root.home-index');
    }

    public function leave()
    {
        if (!session()->has('admin_impersonator_id')) {
            Alert::error('Error', 'Tidak ada sesi impersonasi aktif.');
            return redirect()->route('root.home-index');
        }

        $adminId = session('admin_impersonator_id');
        $admin = User::findOrFail($adminId);

        // Clear session impersonation trace
        session()->forget('admin_impersonator_id');

        // Log back in as admin
        Auth::login($admin);
        session(['active_role' => 'admin']);

        Alert::success('Kembali ke Admin', 'Anda kembali masuk sebagai Administrator.');
        return redirect()->route('root.home-index');
    }
}
