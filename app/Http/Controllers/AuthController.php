<?php

namespace App\Http\Controllers;

// Use Systems
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
// Use Models
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\User;
// Use Plugins
use RealRashid\SweetAlert\Facades\Alert;


class AuthController extends Controller
{
    public function renderSignin()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = null;
        $data['pages'] = "HomePage";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();

        return view('themes.auth.auth-signin', $data, compact('user'));
    }
    public function renderChooseRole()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = null;
        $data['pages'] = "HomePage";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['roles'] = $user->getRoleNames();
        $data['roleMeta'] = 
        [
            'admin' => [
                'desc' => 'Akses penuh ke sistem',
                'icon' => '<svg class="role-icon" xmlns="http://www.w3.org/2000/svg" ...><path d="M12 12a5 5 0 1 0-5-5a5 5 0 0 0 5 5z"></path><path d="M21 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path></svg>'
            ],
            'dosen' => [
                'desc' => 'Akses dosen & pengajaran',
                'icon' => '<svg class="role-icon" xmlns="http://www.w3.org/2000/svg" ...><path d="M12 2l4 4-4 4-4-4 4-4z"></path><path d="M6 14c-1 1-1 3 0 4s3 1 4 0 1-3 0-4-3-1-4 0z"></path></svg>'
            ],
            'tendik' => [
                'desc' => 'Tenaga kependidikan (staff)',
                'icon' => '<svg class="role-icon" xmlns="http://www.w3.org/2000/svg" ...><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>'
            ],
            'alumni' => [
                'desc' => 'Akses khusus alumni',
                'icon' => '<svg class="role-icon" xmlns="http://www.w3.org/2000/svg" ...><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14v7"></path></svg>'
            ],
            'mahasiswa' => [
                'desc' => 'Akses mahasiswa aktif',
                'icon' => '<svg class="role-icon" xmlns="http://www.w3.org/2000/svg" ...><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14v7"></path><path d="M7 20a2 2 0 0 0 4 0"></path></svg>'
            ],
            'peserta-pmb' => [
                'desc' => 'Peserta Penerimaan Mahasiswa Baru',
                'icon' => '<svg class="role-icon" xmlns="http://www.w3.org/2000/svg" ...><path d="M5 3v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3"></path><rect x="3" y="7" width="18" height="13" rx="2"></rect></svg>'
            ],
        ];

        return view('themes.auth.auth-role', $data, compact('user'));
    }

    public function handleSignin(Request $request)
    {
        try {
            $request->validate([
                'login' => 'required',
                'password' => 'required',
            ]);

            $login = $request->input('login');

            // ==== RATE LIMIT ====
            $pengaturan = System::first();
            $maxAttempts = $pengaturan->max_login_attempts ?? 5;
            $decaySeconds = $pengaturan->login_decay_seconds ?? 60;
            $key = 'login:'.Str::lower($login).'|'.$request->ip();

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                $seconds = RateLimiter::availableIn($key);
                Alert::error('Terlalu banyak percobaan', "Coba lagi dalam {$seconds} detik.");
                return back()
                    ->withErrors(['login' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."])
                    ->onlyInput('login');
            }

            // Cek login input (email/username)
            $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $checkUser = User::where($fieldType, $login)->first();

            if ($checkUser) {
                if (Auth::attempt([$fieldType => $login, 'password' => $request->input('password')])) {

                    $user = Auth::user();
                    $roles = $user->getRoleNames(); // Spatie

                    if ($roles->count() === 1) {
                        // Kalau cuma punya 1 role → langsung simpan ke session
                        session(['active_role' => $roles->first()]);
                        Alert::toast('Login sebagai ' . $user->name . ' (' . $roles->first() . ')', 'success');

                        // redirect sesuai role
                        return redirect()->route('dashboard.' . strtolower($roles->first()));
                    } else {
                        // Kalau lebih dari 1 role → arahkan ke halaman pilih role
                        return redirect()->route('auth.choose-role');
                    }

                } else {
                    RateLimiter::hit($key, $decaySeconds);
                    Alert::error('Error', 'Mohon Maaf, Username / Email atau password salah');
                    return back();
                }
            } else {
                Alert::error('Error', 'Mohon Maaf, Akun anda tidak terdaftar pada system kami.');
                return back();
            }

        } catch (\Throwable $e) {
            \Log::error('Login error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            Alert::error('Error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            return back();
        }
    }

    public function handleSetRole(Request $request) {
        $user = Auth::user();
        $roles = $user->getRoleNames();

        $selectedRole = $request->input('role');

        if (in_array($selectedRole, $roles->toArray())) {
            session(['active_role' => $selectedRole]);
            Alert::toast('Login sebagai ' . $user->name . ' (' . $selectedRole . ')', 'success');

            // redirect sesuai role
            return redirect()->route(strtolower($selectedRole) . '.dashboard-index');
        } else {
            Alert::error('Error', 'Role yang dipilih tidak valid.');
            return back();
        }
    }

    
    public function handleLogout(Request $request) {
        if (Auth::check()) {

            Auth::logout();
            Alert::success('Berhasil!', 'Logout telah sukses!');
            return redirect()->route('auth.render-signin');
        } else {

            Alert::error('Gagal!', 'Logout gagal, Silahkan coba lagi!');
            return back();
        }
    }
}
