<?php

namespace App\Http\Controllers\Master\Users;

use App\Http\Controllers\Controller;
// Use Systems
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Private\UpdateProfileRequest;
use App\Services\Private\UpdateProfileService;
// Use Plugins
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use App\Mail\welcomeMail;
// Use Models
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\User;
use App\Models\User\Role;


class UsersController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Data Pengguna';
        $data['pages'] = "Halaman Data Pengguna";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::all();
        $data['roles'] = Role::all();
        $data['is_trash'] = false;

        return view('master.users.user-index', $data, compact('user'));
    }

    public function trash()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Data Pengguna';
        $data['pages'] = "Halaman Data Pengguna";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::onlyTrashed();
        $data['is_trash'] = true;

        return view('master.users.user-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ],
        [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.unique' => 'Username sudah ada.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.unique' => 'Nomor telepon sudah ada.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah ada.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
            'roles.required' => 'Role wajib dipilih.',
            'roles.*.exists' => 'Role tidak valid.',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username ?? $request->phone;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->code = uniqid();
        
        // Generate temporary password if not provided
        $temporaryPassword = $request->password ?? $user->code;
        $user->password = Hash::make($temporaryPassword);
        $user->save();


        // Assign Role(s) from form input 'roles' (array of role IDs)
        $roleInput = $request->input('roles', []);
        if (!empty($roleInput)) {
            $roleIds = is_array($roleInput) ? $roleInput : [$roleInput];
            $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

            if (count($roles) > 0) {
                $user->assignRole($roles);
            } else {
                Alert::error('Error', 'Role tidak valid.');
                return redirect()->back();
            }
        }

        // Send welcome email
        try {
            $academy = Kampus::first();
            Mail::to($user->email)->send(new welcomeMail($user, $academy, $temporaryPassword));
        } catch (\Exception $e) {
            // Log email error but don't stop the process
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        Alert::success('Sukses', 'Pengguna baru berhasil ditambahkan dan email selamat datang telah dikirim.');
        return redirect()->back();
    }

}