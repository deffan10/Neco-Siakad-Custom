@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Rekap dan laporan aktivitas bimbingan PA Online seluruh mahasiswa dan dosen.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.bimbingan-pa.index') }}"><i class="fas fa-chart-bar me-1"></i> Laporan PA</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Rekap Per Dosen -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-chalkboard-teacher me-2"></i> Rekap Per Dosen PA</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Dosen</th>
                                <th>Total Bimbingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapDosen as $r)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $r->dosen->name ?? 'Unknown' }}</strong></td>
                                    <td><span class="badge bg-primary">{{ $r->total }} pesan</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada aktivitas bimbingan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Rekap Per Mahasiswa Top 20 -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title"><i class="fas fa-user-graduate me-2"></i> Top 20 Mahasiswa Aktif Bimbingan</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mahasiswa</th>
                                <th>Total Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapMahasiswa as $r)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $r->mahasiswa->name ?? 'Unknown' }}</strong><br>
                                        <small class="text-muted">{{ $r->mahasiswa->username ?? '' }}</small>
                                    </td>
                                    <td><span class="badge bg-success">{{ $r->total }} pesan</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada aktivitas bimbingan mahasiswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
