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
            <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('admin.pengumuman.index') }}"><i class="fas fa-bullhorn me-1"></i> Daftar Pengumuman</a></li>
            <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('admin.pengumuman.create') }}"><i class="fas fa-plus-circle me-1"></i> Tambah Pengumuman</a></li>
            <li class="nav-item"><a class="nav-link active fw-bold" href="{{ route('admin.pengumuman.kategori') }}"><i class="fas fa-folder me-1"></i> Kategori</a></li>
        </ul>
    </div></div>

    <div class="row row-cards mt-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white"><h3 class="card-title">Tambah Kategori</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.pengumuman.kategori-store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Nama Kategori</label>
                            <input type="text" name="nama" class="form-control" placeholder="cth: Akademik, Keuangan..." required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Kategori</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Daftar Kategori</h3></div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead><tr><th>#</th><th>Nama Kategori</th><th>Jumlah Pengumuman</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @forelse($kategoris as $k)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $k->nama }}</strong></td>
                                    <td><span class="badge bg-info">{{ $k->pengumumans_count }}</span></td>
                                    <td>
                                        <form action="{{ route('admin.pengumuman.kategori-destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
