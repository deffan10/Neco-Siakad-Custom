<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Pengaturan\Kampus;
use App\Models\Pengaturan\System;

class KampusSettingController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Badan Hukum PT',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
        ];
    }

    public function index()
    {
        $user = Auth::user();
        $data = $this->baseData('Badan Hukum & Perguruan Tinggi');
        $data['kampus'] = Kampus::first();

        return view('master.akademik.kampus-setting', $data, compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'nama_yayasan'  => 'nullable|string|max:255',
            'sk_yayasan'    => 'nullable|string|max:255',
            'kode_pt'       => 'nullable|string|max:10',
            'phone'         => 'nullable|string|max:20',
            'email_info'    => 'nullable|email|max:255',
            'domain'        => 'nullable|string|max:255',
            'alamat'        => 'nullable|string',
            'kota_kabupaten'=> 'nullable|string|max:100',
            'provinsi'      => 'nullable|string|max:100',
        ]);

        $kampus = Kampus::first();
        if (!$kampus) {
            $kampus = new Kampus();
        }

        $kampus->fill($request->all());
        $kampus->save();

        Alert::success('Berhasil', 'Konfigurasi Badan Hukum / PT berhasil diperbarui.');
        return redirect()->back();
    }
}
