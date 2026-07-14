@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Mengatur persentase bobot penilaian default untuk Tugas, UTS, dan UAS.</div>
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
                    <a class="nav-link active fw-bold" href="{{ route('admin.nilai.komponen') }}"><i class="fas fa-percentage me-1"></i> Komponen Nilai</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.nilai.entri') }}"><i class="fas fa-lock me-1"></i> Aturan Entri Nilai</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row mt-3 row-cards">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Bobot Penilaian Default Nasional</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Bobot Tugas (%)</label>
                        <input type="number" class="form-control" value="30" required disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Bobot UTS (%)</label>
                        <input type="number" class="form-control" value="30" required disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Bobot UAS (%)</label>
                        <input type="number" class="form-control" value="40" required disabled>
                    </div>
                    <button class="btn btn-primary w-100" disabled>Simpan Bobot Default</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
