@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Laporan rekapitulasi data mahasiswa, lulusan, dan rasio bimbingan akademik untuk Borang Akreditasi Institusi (BAN-PT).</div>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row row-cards mt-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-blue text-white avatar"><i class="fas fa-user-graduate"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Dosen Tetap & Kontrak</div>
                            <div class="text-muted">{{ $totalDosen }} Dosen</div>
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
                            <span class="bg-green text-white avatar"><i class="fas fa-users"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Staff Administrasi</div>
                            <div class="text-muted">{{ $totalStaff }} Pegawai</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- 1. Rekap Mahasiswa & Kelulusan per Prodi -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-university me-2"></i>Rekapitulasi Mahasiswa & Kelulusan per Prodi</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th>Program Studi</th>
                                <th class="text-center">Aktif</th>
                                <th class="text-center">Cuti</th>
                                <th class="text-center">Lulus (Alumni)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswaPerProdi as $p)
                                <tr>
                                    <td><strong>{{ $p->name }}</strong> ({{ $p->nama_singkat ?? '' }})</td>
                                    <td class="text-center"><span class="badge bg-green-lt">{{ $p->total_aktif }}</span></td>
                                    <td class="text-center"><span class="badge bg-warning-lt">{{ $p->total_cuti }}</span></td>
                                    <td class="text-center"><span class="badge bg-blue-lt">{{ $p->total_lulus }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada data program studi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Rekap Mahasiswa per Angkatan -->
            <div class="card mt-3">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title"><i class="fas fa-calendar-alt me-2"></i>Rekap Mahasiswa per Angkatan</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th>Angkatan</th>
                                <th>Total Mahasiswa Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswaPerAngkatan as $a)
                                <tr>
                                    <td><strong>Angkatan {{ $a->angkatan }}</strong></td>
                                    <td><span class="badge bg-blue">{{ $a->total_mhs }} Mahasiswa</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Belum ada data angkatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3. Rekap Dosen Pembimbing Akademik -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title"><i class="fas fa-chalkboard-teacher me-2"></i>Rasio Pembimbing Akademik (PA)</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th>Dosen PA</th>
                                <th class="text-center">Jumlah Bimbingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapDosenPa as $d)
                                <tr>
                                    <td>
                                        <strong>{{ $d->dosenPa->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $d->dosenPa->username ?? '' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-green">{{ $d->total_bimbingan }} Mahasiswa</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Belum ada pembagian dosen pembimbing akademik.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
