@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola program referral/afiliasi mahasiswa baru, akun mitra, dan pembayaran komisi.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        {{-- Affiliate Stats --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Mitra Terdaftar</div>
                            <div class="text-muted">38 Orang / Lembaga</div>
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
                            <span class="bg-green text-white avatar"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M15 19l2 2l4 -4" /></svg></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Pendaftar Lewat Afiliasi</div>
                            <div class="text-muted">142 Calon Mahasiswa</div>
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
                            <span class="bg-yellow text-white avatar"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-coin" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4h-2a2 2 0 0 1 -1.8 -1" /><path d="M12 6v2m0 8v2" /></svg></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Total Komisi Dibayar</div>
                            <div class="text-muted">Rp 35,500,000</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Affiliate Partners Table --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Mitra & Kinerja Referral</h3>
                    <button class="btn btn-primary btn-sm">+ Registrasi Mitra Baru</button>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nama Mitra</th>
                                <th>Tipe Mitra</th>
                                <th>Kode Referral</th>
                                <th>Clicks</th>
                                <th>Pendaftar</th>
                                <th>Lolos Seleksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Budi Susanto (Alumni)</td>
                                <td>Personal</td>
                                <td><span class="badge bg-purple-lt">BUDIUMD26</span></td>
                                <td>240</td>
                                <td>15</td>
                                <td>10</td>
                                <td><button class="btn btn-sm btn-outline-secondary">Detail</button></td>
                            </tr>
                            <tr>
                                <td>MA Syarif Hidayatullah (Sekolah)</td>
                                <td>Instansi Sekolah</td>
                                <td><span class="badge bg-purple-lt">MASYARIF</span></td>
                                <td>1,250</td>
                                <td>84</td>
                                <td>56</td>
                                <td><button class="btn btn-sm btn-outline-secondary">Detail</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
