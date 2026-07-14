@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Daftar pesan yang telah Anda kirimkan ke pengguna lain.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.pesan.buat') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Buat Pesan
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.pesan.masuk') }}"><i class="fas fa-inbox me-1"></i> Pesan Masuk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.pesan.buat') }}"><i class="fas fa-edit me-1"></i> Buat Pesan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.pesan.terkirim') }}"><i class="fas fa-paper-plane me-1"></i> Pesan Terkirim</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="table-responsive">
            <table class="table card-table table-vcenter table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Kepada</th>
                        <th>Subjek</th>
                        <th>Status Dibaca</th>
                        <th>Waktu Kirim</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-sm">{{ substr($p->penerima->name ?? 'U', 0, 1) }}</div>
                                    {{ $p->penerima->name ?? 'Unknown' }}
                                </div>
                            </td>
                            <td>{{ $p->subjek }}<br><small class="text-muted">{{ Str::limit($p->isi, 60) }}</small></td>
                            <td>
                                @if($p->dibaca)
                                    <span class="badge bg-success">Sudah Dibaca</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Dibaca</span>
                                @endif
                            </td>
                            <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pesan terkirim.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $pesans->links() }}</div>
    </div>
</div>
@endsection
