@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola konten depan portal penerimaan mahasiswa baru.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        {{-- Section: Header & Footer --}}
        <div class="col-md-6">
            <div class="card card-stacked">
                <div class="card-status-top bg-primary"></div>
                <div class="card-header">
                    <h3 class="card-title">Header & Footer Settings</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Teks Menu Navigasi Header</label>
                        <input type="text" class="form-control" value="Beranda, Alur Pendaftaran, Brosur, Pengumuman, Kontak" placeholder="Pisahkan dengan koma">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo Portal PMB</label>
                        <div class="input-group">
                            <input type="file" class="form-control">
                            <button class="btn btn-outline-secondary" type="button">Upload</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teks Hak Cipta Footer</label>
                        <input type="text" class="form-control" value="© 2026 Universitas Muhammadiyah Madura. All Rights Reserved.">
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="button" class="btn btn-primary">Simpan Header & Footer</button>
                </div>
            </div>
        </div>

        {{-- Section: Halaman Login --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Login Page Customization</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Gambar Latar Belakang Login</label>
                        <input type="file" class="form-control">
                        <small class="text-muted">Rekomendasi ukuran: 1920x1080 px</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pesan Pembuka (Welcome Text)</label>
                        <textarea class="form-control" rows="3">Selamat datang di Portal PMB UMMADA. Silakan masuk untuk melanjutkan proses pendaftaran.</textarea>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="button" class="btn btn-primary">Simpan Latar & Pesan</button>
                </div>
            </div>
        </div>

        {{-- Section: Sliders & Popups --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Promotional Sliders & Popups</h3>
                    <button class="btn btn-primary btn-sm">+ Tambah Slider/Popup</button>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Tipe</th>
                                <th>Judul / Deskripsi</th>
                                <th>Gambar Banner</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-blue">Slider</span></td>
                                <td>Pendaftaran Gelombang 1 Dibuka!</td>
                                <td><a href="#" class="text-underline">banner-gel1.jpg</a></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-purple">Popup</span></td>
                                <td>Pengumuman Ujian Tes Tulis Online</td>
                                <td><a href="#" class="text-underline">popup-info.jpg</a></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
