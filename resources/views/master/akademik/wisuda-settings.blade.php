@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Buat kegiatan wisuda aktif, tentukan jadwal pendaftaran, biaya pendaftaran, kuota peserta, dan lihat daftar mahasiswa yang mendaftar.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.wisuda.settings') }}"><i class="fas fa-calendar-alt me-1"></i> Kegiatan Wisuda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.wisuda.syarat') }}"><i class="fas fa-file-signature me-1"></i> Syarat Wisuda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.wisuda.pengaturan') }}"><i class="fas fa-cog me-1"></i> Pengaturan Toga & Kartu</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Buat Kegiatan Wisuda Baru -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Buat Kegiatan Wisuda</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.wisuda.settings.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tahun Akademik</label>
                            <select class="form-select" name="tahun_akademik_id" required>
                                @foreach($tahunAkademiks as $ta)
                                    <option value="{{ $ta->id }}" {{ $ta->is_active ? 'selected' : '' }}>
                                        {{ $ta->name }} ({{ $ta->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan Wisuda</label>
                            <input type="text" class="form-control" name="nama" placeholder="Contoh: Wisuda Ke-XV Genap 2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai Pendaftaran</label>
                            <input type="date" class="form-control" name="tanggal_mulai_daftar" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai Pendaftaran</label>
                            <input type="date" class="form-control" name="tanggal_selesai_daftar" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pelaksanaan Wisuda</label>
                            <input type="date" class="form-control" name="tanggal_pelaksanaan" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Kuota (Orang)</label>
                                <input type="number" class="form-control" name="kuota" min="1" placeholder="200" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Biaya (Rp)</label>
                                <input type="number" class="form-control" name="biaya" min="0" placeholder="1500000" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Buat Kegiatan Wisuda</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Kegiatan Wisuda -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Kegiatan Wisuda</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Tahun Akademik</th>
                                <th>Tanggal Penting</th>
                                <th>Kuota & Biaya</th>
                                <th>Pendaftar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kegiatans as $k)
                                <tr>
                                    <td><strong>{{ $k->nama }}</strong></td>
                                    <td>{{ $k->tahunAkademik->name }}</td>
                                    <td>
                                        <div class="small">
                                            <span class="text-muted">Daftar:</span> {{ \Carbon\Carbon::parse($k->tanggal_mulai_daftar)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($k->tanggal_selesai_daftar)->format('d/m/Y') }}
                                        </div>
                                        <div class="small mt-1">
                                            <span class="text-muted">Pelaksanaan:</span> <strong>{{ \Carbon\Carbon::parse($k->tanggal_pelaksanaan)->format('d/m/Y') }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small"><span class="text-muted">Kuota:</span> {{ $k->kuota }} orang</div>
                                        <div class="small mt-1"><span class="text-muted">Biaya:</span> Rp {{ number_format($k->biaya, 0, ',', '.') }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-purple">{{ $k->pendaftarans_count }} Orang</span>
                                    </td>
                                    <td>
                                        @if($k->status)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.wisuda.applicants', $k->id) }}" class="btn btn-sm btn-info me-1">
                                            <i class="fas fa-users me-1"></i> Pendaftar
                                        </a>
                                        <a href="{{ route('admin.wisuda.presensi', $k->id) }}" class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-check-double me-1"></i> Presensi
                                        </a>
                                        <form action="{{ route('admin.wisuda.toggle', $k->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $k->status ? 'btn-warning' : 'btn-success' }}">
                                                {{ $k->status ? 'Non-Aktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada kegiatan wisuda yang dibuat.</td>
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
