<?php

namespace App\Http\Controllers\Master\Users;

use App\DataTables\Manager\Users\KeluargaDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Users\KeluargaRequest;
// Use Models
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;
use App\Models\User;
// Use Others
use App\Services\Manager\Users\KeluargaService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KeluargaController extends Controller
{
    protected KeluargaService $keluargaService;

    public function __construct(KeluargaService $keluargaService)
    {
        $this->keluargaService = $keluargaService;
    }

    public function index(KeluargaDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Keluarga';
        $data['pages'] = 'Halaman Data Keluarga';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = false;

        $dataTable->setTrash(false);

        return $dataTable->render('master.users.keluarga-index', $data, compact('user'));
    }

    public function trash(KeluargaDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Keluarga';
        $data['pages'] = 'Halaman Data Keluarga yang Dihapus';
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = true;

        $dataTable->setTrash(true);

        return $dataTable->render('master.users.keluarga-index', $data, compact('user'));
    }

    public function store(KeluargaRequest $request)
    {
        try {
            $this->keluargaService->createKeluarga($request->validated());

            Alert::success('Berhasil', 'Data keluarga berhasil ditambahkan');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function update(KeluargaRequest $request, $id)
    {
        try {
            $this->keluargaService->updateKeluarga($id, $request->validated());

            Alert::success('Berhasil', 'Data keluarga berhasil diperbarui');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $this->keluargaService->deleteKeluarga($id);

            Alert::success('Berhasil', 'Data keluarga berhasil dihapus');

            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: '.$th->getMessage());

            return redirect()->back();
        }
    }

    public function restore(Keluarga $keluarga): RedirectResponse
    {
        $this->keluargaService->restoreKeluarga($keluarga->id);

        return redirect()->back()->with('success', 'Keluarga berhasil dipulihkan.');
    }

    public function forceDelete(Keluarga $keluarga): RedirectResponse
    {
        $this->keluargaService->forceDeleteKeluarga($keluarga->id);

        return redirect()->back()->with('success', 'Keluarga berhasil dihapus permanen.');
    }
}
