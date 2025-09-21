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
                'icon' => '<i class="fas fa-user-shield"></i>'
            ],  
            'dosen' => [
                'desc' => 'Akses dosen & pengajaran',
                'icon' => '<i class="fas fa-chalkboard-teacher"></i>'
            ],
            'tendik' => [
                'desc' => 'Tenaga kependidikan (staff)',
                'icon' => '<i class="fas fa-user-tie"></i>'
            ],
            'alumni' => [
                'desc' => 'Akses khusus alumni',
                'icon' => '<i class="fas fa-user-graduate"></i>'
            ],
            'mahasiswa' => [
                'desc' => 'Akses mahasiswa aktif',
                'icon' => '<i class="fas fa-user-graduate"></i>'
            ],
            'peserta-pmb' => [
                'desc' => 'Peserta Penerimaan Mahasiswa Baru',
                'icon' => '<i class="fas fa-user-graduate"></i>'
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
            $remember = $request->has('remember');

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
                if (Auth::attempt([$fieldType => $login, 'password' => $request->input('password')], $remember)) {

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

    
    public function handleLogout(Request $request)
    {
        if (Auth::check()) {
            // hapus role aktif di session
            $request->session()->forget('active_role');

            // clear semua session biar aman
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // logout user
            Auth::logout();

            Alert::success('Berhasil!', 'Logout telah sukses!');
            return redirect()->route('auth.render-signin');
        } else {
            Alert::error('Gagal!', 'Logout gagal, Silahkan coba lagi!');
            return back();
        }
    }

}
