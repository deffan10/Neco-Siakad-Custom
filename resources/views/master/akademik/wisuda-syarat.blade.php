@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Konfigurasi berkas yang wajib diunggah oleh mahasiswa saat mendaftar wisuda.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.wisuda.settings') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-3 row-cards">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Berkas Wajib Validasi</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked disabled>
                            <span class="form-check-label">Foto Wisuda Resmi (Background Merah/Biru)</span>
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked disabled>
                            <span class="form-check-label">Surat Keterangan Bebas Pustaka (Perpustakaan)</span>
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked disabled>
                            <span class="form-check-label">Lembar Pengesahan Skripsi / Tugas Akhir</span>
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked disabled>
                            <span class="form-check-label">Sertifikat Nilai TOEFL (Lembaga Resmi)</span>
                        </label>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Batas Nilai TOEFL Minimum</label>
                        <input type="number" class="form-control" value="450" disabled>
                        <span class="text-muted small">Mahasiswa yang memiliki skor di bawah batas minimum akan ditandai oranye saat review berkas.</span>
                    </div>
                    <button class="btn btn-primary w-100" disabled>Simpan Konfigurasi Syarat</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
