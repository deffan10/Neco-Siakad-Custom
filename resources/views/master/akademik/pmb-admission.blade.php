@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola ujian masuk tertulis online, jadwal interview, dan status tes kesehatan.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        {{-- Sesi Ujian Tulis --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Sesi Ujian Tes Tulis Online</h3>
                    <button class="btn btn-sm btn-primary">+ Sesi Baru</button>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nama Ujian</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tes Potensi Akademik 1</td>
                                <td>20 Juli 2026</td>
                                <td>90 Menit</td>
                                <td><span class="badge bg-green-lt">Menunggu</span></td>
                            </tr>
                            <tr>
                                <td>Ujian Bidang MIPA</td>
                                <td>21 Juli 2026</td>
                                <td>120 Menit</td>
                                <td><span class="badge bg-green-lt">Menunggu</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sesi Interview --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Jadwal Wawancara (Interview)</h3>
                    <button class="btn btn-sm btn-primary">+ Jadwal</button>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nama Camaba</th>
                                <th>Pewawancara</th>
                                <th>Tanggal/Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Randi Saputra</td>
                                <td>Drs. H. Mulyadi, M.Si</td>
                                <td>18 Juli 2026, 09:00</td>
                                <td><span class="badge bg-yellow">Terjadwal</span></td>
                            </tr>
                            <tr>
                                <td>Siti Aisyah</td>
                                <td>Dr. Linda Wati, M.Pd</td>
                                <td>18 Juli 2026, 10:30</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tes Kesehatan --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Pencatatan Hasil Tes Kesehatan</h3>
                    <button class="btn btn-outline-secondary btn-sm">Import Excel</button>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>No Pendaftaran</th>
                                <th>Nama Mahasiswa</th>
                                <th>Tinggi/Berat</th>
                                <th>Buta Warna</th>
                                <th>Status Hasil</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PMB20260012</td>
                                <td>Ahmad Nur Hidayat</td>
                                <td>170 cm / 65 kg</td>
                                <td><span class="badge bg-green-lt">Tidak</span></td>
                                <td><span class="badge bg-success">Lolos Kesehatan</span></td>
                                <td><button class="btn btn-sm btn-outline-secondary">Update</button></td>
                            </tr>
                            <tr>
                                <td>PMB20260085</td>
                                <td>Siti Aisyah</td>
                                <td>158 cm / 50 kg</td>
                                <td><span class="badge bg-danger-lt">Ya (Parsial)</span></td>
                                <td><span class="badge bg-success">Lolos Kesehatan</span></td>
                                <td><button class="btn btn-sm btn-outline-secondary">Update</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
