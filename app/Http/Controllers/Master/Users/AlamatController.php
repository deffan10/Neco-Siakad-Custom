<?php

namespace App\Http\Controllers\Master\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
// Use Models
use App\Models\User;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;
// Use Others
use App\DataTables\Manager\Users\AlamatDataTable;
use App\Services\Manager\Users\AlamatService;
use App\Http\Requests\Manager\Users\AlamatRequest;

class AlamatController extends Controller
{
    protected AlamatService $alamatService;

    public function __construct(AlamatService $alamatService)
    {
        $this->alamatService = $alamatService;
    }

    public function index(AlamatDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Alamat';
        $data['pages'] = "Halaman Data Alamat";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = false;

        $dataTable->setTrash(false);
        return $dataTable->render('master.users.alamat-index', $data, compact('user'));
    }

    public function trash(AlamatDataTable $dataTable)
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? '';
        $data['menus'] = 'Referensi Alamat';
        $data['pages'] = "Halaman Data Alamat yang Dihapus";
        $data['system'] = System::first();
        $data['academy'] = Kampus::first();
        $data['users'] = User::orderBy('name')->get();
        $data['is_trash'] = true;

        $dataTable->setTrash(true);
        return $dataTable->render('master.users.alamat-index', $data, compact('user'));
    }

    public function store(AlamatRequest $request)
    {
        try {
            $this->alamatService->createAlamat($request->validated());

            Alert::success('Berhasil', 'Data alamat berhasil ditambahkan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update(AlamatRequest $request, $id)
    {
        try {
            $this->alamatService->updateAlamat($id, $request->validated());

            Alert::success('Berhasil', 'Data alamat berhasil diperbarui');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $this->alamatService->deleteAlamat($id);

            Alert::success('Berhasil', 'Data alamat berhasil dihapus');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function restore($id)
    {
        try {
            $this->alamatService->restoreAlamat($id);

            Alert::success('Berhasil', 'Data alamat berhasil dikembalikan');
            return redirect()->back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
            return redirect()->back();
        }
    }
}