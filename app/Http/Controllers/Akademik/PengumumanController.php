<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Akademik\Pengumuman;
use App\Models\Akademik\KategoriPengumuman;
use App\Models\Pengaturan\System;
use App\Models\Pengaturan\Kampus;

class PengumumanController extends Controller
{
    protected function baseData($pages)
    {
        return [
            'activeRole' => session('active_role') ?? 'admin',
            'menus'      => 'Pengumuman & Info',
            'pages'      => $pages,
            'system'     => System::first(),
            'academy'    => Kampus::first(),
            'kategoris'  => KategoriPengumuman::all(),
        ];
    }

    // =====================================================
    // Tab 1: DAFTAR PENGUMUMAN - all announcements
    // =====================================================
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $this->baseData('Daftar Pengumuman');

        $query = Pengumuman::with(['penulis', 'kategori']);

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('target')) {
            $query->where('target', $request->target);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $data['pengumumans'] = $query->latest()->paginate(15);

        return view('master.akademik.pengumuman-index', $data, compact('user'));
    }

    // =====================================================
    // Tab 2: BUAT PENGUMUMAN - create form
    // =====================================================
    public function create()
    {
        $user = Auth::user();
        $data = $this->baseData('Buat Pengumuman Baru');

        return view('master.akademik.pengumuman-create', $data, compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:500',
            'isi'   => 'required|string',
            'target' => 'required|in:Semua,Mahasiswa,Dosen,Operator',
        ]);

        Pengumuman::create([
            'user_id'      => Auth::id(),
            'kategori_id'  => $request->kategori_id ?: null,
            'judul'        => $request->judul,
            'isi'          => $request->isi,
            'target'       => $request->target,
            'is_published' => $request->has('is_published'),
            'published_at' => $request->has('is_published') ? now() : null,
        ]);

        Alert::success('Berhasil', 'Pengumuman berhasil diterbitkan.');
        return redirect()->route('admin.pengumuman.index');
    }

    public function destroy($id)
    {
        Pengumuman::findOrFail($id)->delete();
        Alert::success('Dihapus', 'Pengumuman berhasil dihapus.');
        return redirect()->back();
    }

    public function togglePublish($id)
    {
        $p = Pengumuman::findOrFail($id);
        $p->update([
            'is_published' => !$p->is_published,
            'published_at' => !$p->is_published ? now() : null,
        ]);

        Alert::success('Berhasil', 'Status pengumuman diperbarui.');
        return redirect()->back();
    }

    // =====================================================
    // Tab 3: KATEGORI - manage categories
    // =====================================================
    public function kategori()
    {
        $user = Auth::user();
        $data = $this->baseData('Kategori Pengumuman');
        $data['kategoris'] = KategoriPengumuman::withCount('pengumumans')->get();

        return view('master.akademik.pengumuman-kategori', $data, compact('user'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100']);
        KategoriPengumuman::create(['nama' => $request->nama]);
        Alert::success('Berhasil', 'Kategori berhasil ditambahkan.');
        return redirect()->back();
    }

    public function destroyKategori($id)
    {
        KategoriPengumuman::findOrFail($id)->delete();
        Alert::success('Dihapus', 'Kategori berhasil dihapus.');
        return redirect()->back();
    }
}
