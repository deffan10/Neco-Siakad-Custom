@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Cari peserta kegiatan berdasarkan NIM atau Nama dan update status kehadiran secara instan.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.seminar.index') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.seminar.index') }}"><i class="fas fa-calendar-check me-1"></i> Daftar Event</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.seminar.kuota') }}"><i class="fas fa-sliders-h me-1"></i> Waktu & Kuota</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.seminar.cari') }}"><i class="fas fa-search me-1"></i> Cari Peserta</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title font-weight-bold">Form Pencarian Peserta</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.seminar.cari') }}" method="GET">
                <div class="row g-2">
                    <div class="col">
                        <input type="text" name="search" class="form-control" placeholder="Masukkan NIM atau Nama Mahasiswa..." value="{{ request('search') }}" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(request()->has('search'))
            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Kegiatan</th>
                            <th>Status Pendaftaran</th>
                            <th>Ubah Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesertaList as $p)
                            <tr>
                                <td><strong>{{ $p->mahasiswa->user->nim ?? '-' }}</strong></td>
                                <td>{{ $p->mahasiswa->user->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $p->event->nama_event }}
                                    <div class="small text-muted">{{ \Carbon\Carbon::parse($p->event->tanggal)->format('d-M-Y') }}</div>
                                </td>
                                <td>
                                    @if($p->status === 'Hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($p->status === 'Tidak Hadir')
                                        <span class="badge bg-danger">Tidak Hadir</span>
                                    @else
                                        <span class="badge bg-warning">Mendaftar</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.seminar.update-peserta', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Hadir">
                                        <button type="submit" class="btn btn-sm btn-outline-success me-1" {{ $p->status === 'Hadir' ? 'disabled' : '' }}>
                                            Hadir
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.seminar.update-peserta', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Tidak Hadir">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" {{ $p->status === 'Tidak Hadir' ? 'disabled' : '' }}>
                                            Absen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Tidak ada mahasiswa yang ditemukan dengan kueri tersebut.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
