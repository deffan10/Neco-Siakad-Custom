@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Rekap presensi per mahasiswa, dapat difilter berdasarkan NIM mahasiswa.</div>
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
                    <a class="nav-link active fw-bold" href="{{ route('admin.presensi-kuliah.mahasiswa') }}"><i class="fas fa-user-graduate me-1"></i> Presensi Mahasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.presensi-kuliah.setting') }}"><i class="fas fa-cog me-1"></i> Setting Presensi</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Filter by Mahasiswa -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2">
                <select name="mahasiswa_id" class="form-select">
                    <option value="">-- Filter Mahasiswa --</option>
                    @foreach($mahasiswas as $m)
                        <option value="{{ $m->id }}" {{ request('mahasiswa_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->username }} – {{ $m->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                <a href="{{ route('admin.presensi-kuliah.mahasiswa') }}" class="btn btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="table-responsive">
            <table class="table card-table table-vcenter table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Pertemuan</th>
                        <th>Status Hadir</th>
                        <th>Catatan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presensis as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $p->mahasiswa->name ?? '-' }}</strong><br><small class="text-muted">{{ $p->mahasiswa->username ?? '' }}</small></td>
                            <td>{{ $p->pertemuan->jadwalPerkuliahan->mataKuliah->name ?? '-' }}</td>
                            <td>Pertemuan {{ $p->pertemuan->pertemuan_ke ?? '-' }}</td>
                            <td>
                                @if($p->hadir)
                                    <span class="badge bg-success">Hadir</span>
                                @elseif($p->hadir === false)
                                    <span class="badge bg-danger">Tidak Hadir</span>
                                @else
                                    <span class="badge bg-secondary">Belum Diisi</span>
                                @endif
                            </td>
                            <td>{{ $p->catatan ?? '-' }}</td>
                            <td>{{ $p->created_at ? $p->created_at->format('d M Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data presensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $presensis->links() }}</div>
    </div>
</div>
@endsection
