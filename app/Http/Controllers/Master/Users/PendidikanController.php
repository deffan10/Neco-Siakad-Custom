<?php

namespace App\Http\Controllers\Master\Users;

use App\DataTables\Manager\Users\PendidikanDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Users\PendidikanRequest;
// Use Models
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\User;
// Use Others
use App\Services\Manager\Users\PendidikanService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PendidikanController extends Controller
{
    protected PendidikanService $pendidikanService;

    public function __construct(PendidikanService $pendidikanService)
    {
        $this->pendidikanService = $pendidikanService;
    }

    public function index(PendidikanDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Pendidikan';
        $data['pages'] = 'Halaman Data Pendidikan';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = false;

        $dataTable->setTrash(false);

        return $dataTable->render('master.users.pendidikan-index', $data, compact('user'));
    }

    public function trash(PendidikanDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Pendidikan';
        $data['pages'] = 'Halaman Data Pendidikan yang Dihapus';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = true;

        $dataTable->setTrash(true);

        return $dataTable->render('master.users.pendidikan-index', $data, compact('user'));
    }

    public function store(PendidikanRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->pendidikanService->createPendidikan($validated);

            Alert::success('Berhasil', 'Data pendidikan berhasil ditambahkan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function update(PendidikanRequest $request, $id)
    {
        try {
            $this->pendidikanService->updatePendidikan($id, $request->validated());

            Alert::success('Berhasil', 'Data pendidikan berhasil diperbarui');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $this->pendidikanService->deletePendidikan($id);

            Alert::success('Berhasil', 'Data pendidikan berhasil dihapus');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $this->pendidikanService->restorePendidikan($id);

            Alert::success('Berhasil', 'Data pendidikan berhasil dikembalikan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function forceDelete($id)
    {
        try {
            $this->pendidikanService->forceDeletePendidikan($id);

            Alert::success('Berhasil', 'Data pendidikan berhasil dihapus permanen');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }
}
