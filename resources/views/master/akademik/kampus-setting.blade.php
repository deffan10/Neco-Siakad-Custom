@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Konfigurasi badan hukum yayasan pelaksana, kode perguruan tinggi, profil institusi, dan alamat kontak resmi.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <div class="col-12">
            <form action="{{ route('admin.kampus-setting.update') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab-yayasan" class="nav-link active" data-bs-toggle="tab" role="tab" aria-selected="true">
                                    <i class="fas fa-balance-scale me-2"></i> Badan Hukum Yayasan
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-pt" class="nav-link" data-bs-toggle="tab" role="tab" aria-selected="false">
                                    <i class="fas fa-university me-2"></i> Perguruan Tinggi
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-kontak" class="nav-link" data-bs-toggle="tab" role="tab" aria-selected="false">
                                    <i class="fas fa-map-marked-alt me-2"></i> Lokasi & Kontak
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Tab 1: Yayasan -->
                            <div class="tab-pane fade show active" id="tab-yayasan" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Yayasan / Badan Hukum</label>
                                        <input type="text" name="nama_yayasan" class="form-control" value="{{ $kampus->nama_yayasan ?? '' }}" placeholder="cth: Yayasan Pendidikan Maju Bersama">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nomor SK Yayasan</label>
                                        <input type="text" name="sk_yayasan" class="form-control" value="{{ $kampus->sk_yayasan ?? '' }}" placeholder="cth: AHU-0012345.AH.01.04.Tahun 2026">
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: PT -->
                            <div class="tab-pane fade" id="tab-pt" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Nama Perguruan Tinggi</label>
                                        <input type="text" name="name" class="form-control" value="{{ $kampus->name ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kode Perguruan Tinggi (PDDIKTI)</label>
                                        <input type="text" name="kode_pt" class="form-control" value="{{ $kampus->kode_pt ?? '' }}" placeholder="cth: 041065">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Domain Website Utama</label>
                                        <input type="text" name="domain" class="form-control" value="{{ $kampus->domain ?? '' }}" placeholder="cth: nusantara.ac.id">
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 3: Lokasi & Kontak -->
                            <div class="tab-pane fade" id="tab-kontak" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea name="alamat" class="form-control" rows="3">{{ $kampus->alamat ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kota / Kabupaten</label>
                                        <input type="text" name="kota_kabupaten" class="form-control" value="{{ $kampus->kota_kabupaten ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Provinsi</label>
                                        <input type="text" name="provinsi" class="form-control" value="{{ $kampus->provinsi ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Telepon Resmi</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $kampus->phone ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Informasi</label>
                                        <input type="email" name="email_info" class="form-control" value="{{ $kampus->email_info ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Konfigurasi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
