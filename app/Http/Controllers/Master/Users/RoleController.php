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
use App\Models\User\Role;
use App\Models\User;

class RoleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Data Role';
        $data['pages'] = "Halaman Data Role";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['roles'] = Role::all();
        $data['is_trash'] = false;

        return view('master.users.role-index', $data, compact('user'));
    }

    public function trash()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Data Role';
        $data['pages'] = "Halaman Data Role";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['roles'] = Role::onlyTrashed()->get();
        $data['is_trash'] = true;

        return view('master.users.role-index', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|string|max:255',
        ],
        [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique' => 'Nama role sudah ada.',
            'guard_name.required' => 'Guard name wajib diisi.',
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'created_by' => Auth::id(),
        ]);

        Alert::toast('Role berhasil ditambahkan', 'success');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name' => 'required|string|max:255',
        ],
        [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique' => 'Nama role sudah ada.',
            'guard_name.required' => 'Guard name wajib diisi.',
        ]);

        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'updated_by' => Auth::id(),
        ]);

        Alert::toast('Role berhasil diperbarui', 'success');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->deleted_by = Auth::id();

        if($role->users()->count() > 0) {
            Alert::error('Error', 'Role tidak dapat dihapus karena masih digunakan oleh pengguna.');
            return redirect()->back();
        }

        if($role->subroles()->count() > 0) {
            Alert::error('Error', 'Role tidak dapat dihapus karena masih memiliki subrole terkait.');
            return redirect()->back();
        }
        $role->save();
        $role->delete();

        Alert::toast('Role berhasil dipindahkan ke sampah', 'success');
        return redirect()->back();
    }

    public function restore($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->restore();

        Alert::toast('Role berhasil dikembalikan', 'success');
        return redirect()->back();
    }
}
