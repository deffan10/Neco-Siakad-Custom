@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Buat Pengumuman</a>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3"><div class="card-body py-2">
        <ul class="nav nav-tabs border-0">
            <li class="nav-item"><a class="nav-link active fw-bold" href="{{ route('admin.pengumuman.index') }}"><i class="fas fa-bullhorn me-1"></i> Daftar Pengumuman</a></li>
            <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('admin.pengumuman.create') }}"><i class="fas fa-plus-circle me-1"></i> Tambah Pengumuman</a></li>
            <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('admin.pengumuman.kategori') }}"><i class="fas fa-folder me-1"></i> Kategori</a></li>
        </ul>
    </div></div>

    <!-- Filter -->
    <div class="card mt-3"><div class="card-body py-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control" style="max-width:220px" placeholder="Cari judul..." value="{{ request('search') }}">
            <select name="target" class="form-select" style="max-width:160px">
                <option value="">-- Semua Target --</option>
                <option value="Semua" {{ request('target') === 'Semua' ? 'selected' : '' }}>Semua</option>
                <option value="Mahasiswa" {{ request('target') === 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="Dosen" {{ request('target') === 'Dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="Operator" {{ request('target') === 'Operator' ? 'selected' : '' }}>Operator</option>
            </select>
            <select name="kategori_id" class="form-select" style="max-width:180px">
                <option value="">-- Semua Kategori --</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary">Reset</a>
        </form>
    </div></div>

    <div class="card mt-3">
        <div class="table-responsive">
            <table class="table card-table table-vcenter table-striped">
                <thead class="bg-primary text-white">
                    <tr><th>#</th><th>Judul</th><th>Target</th><th>Kategori</th><th>Status</th><th>Oleh</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($pengumumans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ Str::limit($p->judul, 60) }}</strong></td>
                            <td><span class="badge bg-info">{{ $p->target }}</span></td>
                            <td>{{ $p->kategori->nama ?? '-' }}</td>
                            <td>
                                @if($p->is_published)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $p->penulis->name ?? '-' }}</td>
                            <td>{{ $p->created_at->format('d M Y') }}</td>
                            <td class="d-flex gap-1">
                                <form action="{{ route('admin.pengumuman.toggle', $p->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm {{ $p->is_published ? 'btn-warning' : 'btn-success' }}">
                                        <i class="fas {{ $p->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pengumuman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $pengumumans->links() }}</div>
    </div>
</div>
@endsection
