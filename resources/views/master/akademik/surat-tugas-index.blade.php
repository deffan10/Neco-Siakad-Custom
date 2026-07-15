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
            <li class="nav-item"><a class="nav-link active fw-bold" href="{{ route('admin.surat-tugas.index') }}"><i class="fas fa-file-signature me-1"></i> Cetak / Daftar Surat</a></li>
        </ul>
    </div></div>

    <!-- Filter -->
    <div class="card mt-3"><div class="card-body py-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control" style="max-width:220px" placeholder="Cari nama dosen..." value="{{ request('search') }}">
            <select name="tahun_akademik_id" class="form-select" style="max-width:180px">
                <option value="">-- Semua Semester --</option>
                @foreach($tahunAkademiks as $t)
                    <option value="{{ $t->id }}" {{ request('tahun_akademik_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            <a href="{{ route('admin.surat-tugas.index') }}" class="btn btn-outline-secondary">Reset</a>
        </form>
    </div></div>

    <div class="row row-cards mt-3">
        <!-- Buat Surat Form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white"><h3 class="card-title">Buat Surat Tugas</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.surat-tugas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Dosen</label>
                            <select name="dosen_id" class="form-select" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
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
                        <div class="mb-3">
                            <label class="form-label">Nomor Surat</label>
                            <input type="text" name="nomor_surat" class="form-control" placeholder="cth: 001/ST/2024">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Perihal</label>
                            <input type="text" name="perihal" class="form-control" placeholder="Perihal surat tugas...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Surat</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Daftar Surat Tugas ({{ $surats->total() }} total)</h3></div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr><th>#</th><th>Dosen</th><th>Semester</th><th>Nomor Surat</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse($surats as $s)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $s->dosen->name ?? '-' }}</strong></td>
                                    <td>{{ $s->tahunAkademik->name ?? '-' }}</td>
                                    <td>{{ $s->nomor_surat ?? '-' }}</td>
                                    <td>{{ $s->tanggal_surat ? $s->tanggal_surat->format('d M Y') : '-' }}</td>
                                    <td><span class="badge bg-{{ $s->status === 'Aktif' ? 'success' : 'secondary' }}">{{ $s->status }}</span></td>
                                    <td>
                                        <form action="{{ route('admin.surat-tugas.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus surat ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada surat tugas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $surats->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
