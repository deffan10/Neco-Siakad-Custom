<?php

namespace App\Http\Controllers\Master\Users;

use App\Http\Controllers\Controller;
// Use Systems
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Private\UpdateProfileRequest;
use App\Services\Private\UpdateProfileService;
// Use Plugins
use RealRashid\SweetAlert\Facades\Alert;
// Use Models
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\User;


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

}