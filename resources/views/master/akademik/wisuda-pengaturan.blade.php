@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Atur konfigurasi global toga, kop cetak undangan, dan QR code sertifikat.</div>
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
                    <h3 class="card-title">Konfigurasi Ukuran Toga & Stok</h3>
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Tipe Ukuran Toga</label>
                            <input type="text" class="form-control" value="S, M, L, XL, XXL" disabled>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Toleransi Ukuran (cm)</label>
                            <input type="text" class="form-control" value="5 cm" disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked disabled>
                            <span class="form-check-label">Aktifkan Pembatasan Stok Toga</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Aturan Kartu & Undangan</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Format Nomor Seri Ijazah/Kelulusan</label>
                        <input type="text" class="form-control" value="WS-[TA]/[PRODI]-[NIM]" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked disabled>
                            <span class="form-check-label">Cetak QR Code di Kartu Undangan Orang Tua</span>
                        </label>
                    </div>
                    <button class="btn btn-primary w-100" disabled>Simpan Konfigurasi</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
