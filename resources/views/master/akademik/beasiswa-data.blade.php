@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Cari, entri, dan kelola data penerima beasiswa mahasiswa.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.beasiswa.data') }}"><i class="fas fa-users me-1"></i> Data Penerima</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.beasiswa.jenis') }}"><i class="fas fa-tags me-1"></i> Jenis Beasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.beasiswa.salin') }}"><i class="fas fa-copy me-1"></i> Salin Beasiswa</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <input type="text" name="search" class="form-control" style="max-width:220px" placeholder="Cari NIM / Nama..." value="{{ request('search') }}">
                <select name="jenis_beasiswa_id" class="form-select" style="max-width:200px">
                    <option value="">-- Semua Jenis --</option>
                    @foreach($jenisBeasiswas as $j)
                        <option value="{{ $j->id }}" {{ request('jenis_beasiswa_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                    @endforeach
                </select>
                <select name="tahun_akademik_id" class="form-select" style="max-width:180px">
                    <option value="">-- Semua Semester --</option>
                    @foreach($tahunAkademiks as $t)
                        <option value="{{ $t->id }}" {{ request('tahun_akademik_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                <a href="{{ route('admin.beasiswa.data') }}" class="btn btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Entri Form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white"><h3 class="card-title">Entri Penerima Beasiswa</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.beasiswa.data-store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Mahasiswa</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $m)
                                    <option value="{{ $m->id }}">{{ $m->username }} – {{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Jenis Beasiswa</label>
                            <select name="jenis_beasiswa_id" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenisBeasiswas as $j)
                                    <option value="{{ $j->id }}">{{ $j->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Akademik</label>
                            <select name="tahun_akademik_id" class="form-select">
                                <option value="">-- Pilih Semester --</option>
                                @foreach($tahunAkademiks as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control">
                            </div>
                            <div class="col">
                                <label class="form-label">Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nominal (Rp)</label>
                            <input type="number" name="nominal" class="form-control" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Daftar Penerima ({{ $beasiswas->total() }} total)</h3></div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mahasiswa</th>
                                <th>Jenis</th>
                                <th>Semester</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($beasiswas as $b)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $b->mahasiswa->name ?? '-' }}</strong><br><small class="text-muted">{{ $b->mahasiswa->username ?? '' }}</small></td>
                                    <td>{{ $b->jenisBeasiswa->nama ?? '-' }}</td>
                                    <td>{{ $b->tahunAkademik->name ?? '-' }}</td>
                                    <td>{{ $b->nominal ? 'Rp '.number_format($b->nominal,0,',','.') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $b->status === 'Aktif' ? 'success' : ($b->status === 'Selesai' ? 'secondary' : 'warning') }}">
                                            {{ $b->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.beasiswa.data-destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data penerima beasiswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $beasiswas->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
