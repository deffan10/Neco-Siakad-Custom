@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Rekap presensi seluruh dosen dan asisten pada semester aktif.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.presensi-kuliah.dosen') }}"><i class="fas fa-chalkboard-teacher me-1"></i> Presensi Dosen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.presensi-kuliah.mahasiswa') }}"><i class="fas fa-user-graduate me-1"></i> Presensi Mahasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.presensi-kuliah.setting') }}"><i class="fas fa-cog me-1"></i> Setting Presensi</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari mata kuliah atau dosen..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                <a href="{{ route('admin.presensi-kuliah.dosen') }}" class="btn btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="table-responsive">
            <table class="table card-table table-vcenter table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen / Asisten</th>
                        <th>Semester</th>
                        <th>Hari & Jam</th>
                        <th>Pertemuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $j)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $j->mataKuliah->name ?? '-' }}</strong></td>
                            <td>{{ $j->dosen->name ?? '-' }}</td>
                            <td>{{ $j->tahunAkademik->name ?? '-' }}</td>
                            <td>{{ $j->hari ?? '-' }} / {{ $j->jam_mulai ?? '-' }} - {{ $j->jam_selesai ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $j->jadwalPertemuan()->count() }} pertemuan</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.akademik.jadwal-perkuliahan-view', $j->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada jadwal perkuliahan aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $jadwals->links() }}</div>
    </div>
</div>
@endsection
