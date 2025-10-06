<?php

namespace App\Http\Controllers\Master\Users;

use App\DataTables\Manager\Users\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Users\RoleRequest;
// Use Models
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
// Use Others
use App\Services\Manager\Users\RoleService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(RoleDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Data Role';
        $data['pages'] = 'Halaman Data Role';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['is_trash'] = false;

        $dataTable->setTrash(false);

        return $dataTable->render('master.users.role-index', $data, compact('user'));
    }

    public function trash(RoleDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Data Role';
        $data['pages'] = 'Halaman Data Role yang Dihapus';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['is_trash'] = true;

        $dataTable->setTrash(true);

        return $dataTable->render('master.users.role-index', $data, compact('user'));
    }

    public function store(RoleRequest $request)
    {
        try {
            $this->roleService->createRole($request->validated());

            Alert::success('Berhasil', 'Data role berhasil ditambahkan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function update(RoleRequest $request, $id)
    {
        try {
            $this->roleService->updateRole($id, $request->validated());

            Alert::success('Berhasil', 'Data role berhasil diperbarui');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $this->roleService->deleteRole($id);

            Alert::success('Berhasil', 'Data role berhasil dihapus');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $this->roleService->restoreRole($id);

            Alert::success('Berhasil', 'Data role berhasil dikembalikan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function forceDelete($id)
    {
        try {
            $this->roleService->forceDeleteRole($id);

            Alert::success('Berhasil', 'Data role berhasil dihapus permanen');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }
}
