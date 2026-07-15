@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola jenis-jenis beasiswa yang tersedia di kampus.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.beasiswa.data') }}"><i class="fas fa-users me-1"></i> Data Penerima</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.beasiswa.jenis') }}"><i class="fas fa-tags me-1"></i> Jenis Beasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.beasiswa.salin') }}"><i class="fas fa-copy me-1"></i> Salin Beasiswa</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Form Tambah Jenis -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Tambah Jenis Beasiswa</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.beasiswa.jenis-store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Nama Beasiswa</label>
                            <input type="text" name="nama" class="form-control" placeholder="cth: Beasiswa Bidikmisi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Kode</label>
                            <input type="text" name="kode" class="form-control" placeholder="cth: BDK-2024" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nominal (Rp)</label>
                            <input type="number" name="nominal" class="form-control" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Jenis</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- List Jenis -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Daftar Jenis Beasiswa</h3></div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Nominal</th>
                                <th>Penerima</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jenisBeasiswas as $j)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $j->nama }}</strong><br><small class="text-muted">{{ $j->deskripsi }}</small></td>
                                    <td><code>{{ $j->kode }}</code></td>
                                    <td>{{ $j->nominal ? 'Rp '.number_format($j->nominal,0,',','.') : '-' }}</td>
                                    <td><span class="badge bg-info">{{ $j->penerimas_count }} mahasiswa</span></td>
                                    <td>
                                        <form action="{{ route('admin.beasiswa.jenis-destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jenis beasiswa ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada jenis beasiswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
