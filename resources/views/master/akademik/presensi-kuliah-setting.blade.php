@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola status kunci/buka tiap pertemuan kuliah untuk mengatur akses input presensi.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.presensi-kuliah.dosen') }}"><i class="fas fa-chalkboard-teacher me-1"></i> Presensi Dosen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.presensi-kuliah.mahasiswa') }}"><i class="fas fa-user-graduate me-1"></i> Presensi Mahasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.presensi-kuliah.setting') }}"><i class="fas fa-cog me-1"></i> Setting Presensi</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Daftar Pertemuan Kuliah</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen</th>
                        <th>Pertemuan Ke</th>
                        <th>Tanggal</th>
                        <th>Status Lock</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pertemuans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $p->jadwalPerkuliahan->mataKuliah->name ?? '-' }}</strong></td>
                            <td>{{ $p->jadwalPerkuliahan->dosen->name ?? '-' }}</td>
                            <td>Pertemuan {{ $p->pertemuan_ke }}</td>
                            <td>{{ $p->tanggal ? \Carbon\Carbon::parse($p->tanggal)->format('d M Y') : '-' }}</td>
                            <td>
                                @if($p->is_locked)
                                    <span class="badge bg-danger"><i class="fas fa-lock me-1"></i> Terkunci</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-lock-open me-1"></i> Terbuka</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.presensi-kuliah.toggle-lock', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $p->is_locked ? 'btn-success' : 'btn-warning' }}">
                                        <i class="fas {{ $p->is_locked ? 'fa-lock-open' : 'fa-lock' }} me-1"></i>
                                        {{ $p->is_locked ? 'Buka' : 'Kunci' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pertemuan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $pertemuans->links() }}</div>
    </div>
</div>
@endsection
