@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Pantau tagihan pendaftaran, log transaksi pembayaran bank, dan beasiswa mahasiswa baru.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        {{-- Stat Cards --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-blue text-white avatar">Rp</span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Total Pembayaran Masuk</div>
                            <div class="text-muted">Rp 450,250,000</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-yellow text-white avatar">Rp</span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Belum Dibayar (Tagihan)</div>
                            <div class="text-muted">Rp 98,000,000</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-green text-white avatar">✔</span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Camaba Lolos Beasiswa</div>
                            <div class="text-muted">24 Mahasiswa</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment List Table --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Log Transaksi Pembayaran PMB</h3>
                    <input type="text" class="form-control w-25" placeholder="Cari nomor pendaftaran/nama...">
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>No Pendaftaran</th>
                                <th>Nama Lengkap</th>
                                <th>Item Tagihan</th>
                                <th>Jumlah Bayar</th>
                                <th>Tanggal Bayar</th>
                                <th>Metode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PMB20260012</td>
                                <td>Ahmad Nur Hidayat</td>
                                <td>Uang Formulir Pendaftaran</td>
                                <td>Rp 250,000</td>
                                <td>12-07-2026 09:30</td>
                                <td>Transfer BSI</td>
                                <td><span class="badge bg-success">Lunas</span></td>
                            </tr>
                            <tr>
                                <td>PMB20260085</td>
                                <td>Siti Aisyah</td>
                                <td>Uang Pangkal / Daftar Ulang</td>
                                <td>Rp 3,500,000</td>
                                <td>14-07-2026 14:15</td>
                                <td>VA Mandiri</td>
                                <td><span class="badge bg-success">Lunas</span></td>
                            </tr>
                            <tr>
                                <td>PMB20260104</td>
                                <td>Randi Saputra</td>
                                <td>Uang Formulir Pendaftaran</td>
                                <td>Rp 250,000</td>
                                <td>-</td>
                                <td>-</td>
                                <td><span class="badge bg-warning">Menunggu Pembayaran</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
