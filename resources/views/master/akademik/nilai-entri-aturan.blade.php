@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Mengunci atau membuka hak akses bagi dosen untuk melakukan input nilai.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.nilai.proses-ipk') }}"><i class="fas fa-calculator me-1"></i> Proses IPS/IPK</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.nilai.komponen') }}"><i class="fas fa-percentage me-1"></i> Komponen Nilai</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.nilai.entri') }}"><i class="fas fa-lock me-1"></i> Aturan Entri Nilai</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row mt-3 row-cards">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Pengaturan Hak Akses Entri Nilai</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked disabled>
                            <span class="form-check-label">Buka Akses Pengisian Nilai (Dosen)</span>
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Batas Waktu Entri Nilai</label>
                        <input type="datetime-local" class="form-control" value="2026-07-31T23:59" disabled>
                    </div>
                    <button class="btn btn-primary w-100" disabled>Simpan Aturan Penguncian</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
