@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col"><h2 class="page-title">{{ $pages }}</h2></div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3"><div class="card-body py-2">
        <ul class="nav nav-tabs border-0">
            <li class="nav-item"><a class="nav-link active fw-bold" href="{{ route('admin.dispensasi.index') }}"><i class="fas fa-file-medical me-1"></i> Data Dispensasi</a></li>
        </ul>
    </div></div>

    <!-- Filter -->
    <div class="card mt-3"><div class="card-body py-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control" style="max-width:220px" placeholder="Cari NIM / Nama..." value="{{ request('search') }}">
            <select name="status" class="form-select" style="max-width:160px">
                <option value="">-- Semua Status --</option>
                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="tahun_akademik_id" class="form-select" style="max-width:180px">
                <option value="">-- Semua Semester --</option>
                @foreach($tahunAkademiks as $t)
                    <option value="{{ $t->id }}" {{ request('tahun_akademik_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            <a href="{{ route('admin.dispensasi.index') }}" class="btn btn-outline-secondary">Reset</a>
        </form>
    </div></div>

    <div class="row row-cards mt-3">
        <!-- Form Entri Admin -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white"><h3 class="card-title">Entri Dispensasi</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.dispensasi.store') }}" method="POST">
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
                            <label class="form-label required">Jenis Dispensasi</label>
                            <select name="jenis" class="form-select" required>
                                <option value="Sakit">Sakit</option>
                                <option value="Izin">Izin</option>
                                <option value="Tugas">Tugas Kampus</option>
                                <option value="Keluarga">Kepentingan Keluarga</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Alasan</label>
                            <textarea name="alasan" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label required">Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="col">
                                <label class="form-label required">Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan Dispensasi</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Daftar Dispensasi ({{ $dispensasis->total() }} total)</h3></div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr><th>#</th><th>Mahasiswa</th><th>Jenis</th><th>Periode</th><th>Status</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse($dispensasis as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $d->mahasiswa->name ?? '-' }}</strong><br><small class="text-muted">{{ $d->mahasiswa->username ?? '' }}</small></td>
                                    <td>{{ $d->jenis }}</td>
                                    <td>{{ $d->tanggal_mulai->format('d/m/Y') }} – {{ $d->tanggal_selesai->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $d->status === 'Disetujui' ? 'success' : ($d->status === 'Ditolak' ? 'danger' : 'warning text-dark') }}">
                                            {{ $d->status }}
                                        </span>
                                    </td>
                                    <td class="d-flex gap-1">
                                        @if($d->status === 'Pending')
                                            <form action="{{ route('admin.dispensasi.approve', $d->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success" title="Setujui"><i class="fas fa-check"></i></button>
                                            </form>
                                            <form action="{{ route('admin.dispensasi.reject', $d->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-times"></i></button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.dispensasi.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data dispensasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $dispensasis->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
