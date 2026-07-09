@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola pencetakan dan penerbitan Surat Keterangan Lulus (SKL) resmi untuk mahasiswa tingkat akhir.</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.skl.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Terbitkan SKL Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="card mt-3">
        <div class="card-body border-bottom py-3">
            <form action="" method="GET" class="d-flex align-items-center justify-content-between gap-2">
                <div class="w-30">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Cari nama atau NIM..." value="{{ request('search') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>NIM & Nama</th>
                        <th>Program Studi</th>
                        <th>Nomor SKL</th>
                        <th>Tanggal Yudisium</th>
                        <th>IPK Akhir</th>
                        <th>Predikat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skls as $s)
                        <tr>
                            <td>
                                <div><strong>{{ $s->mahasiswa->user->name }}</strong></div>
                                <div class="text-muted small">NIM: {{ $s->mahasiswa->user->username }}</div>
                            </td>
                            <td>{{ $s->mahasiswa->programStudi->name }}</td>
                            <td><code class="text-purple">{{ $s->nomor_skl }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_lulus)->format('d M Y') }}</td>
                            <td><strong>{{ number_format($s->ipk, 2) }}</strong></td>
                            <td><span class="badge bg-green-lt">{{ $s->yudisium }}</span></td>
                            <td>
                                <a href="{{ route('admin.skl.print', $s->id) }}" target="_blank" class="btn btn-sm btn-outline-info me-1">
                                    <i class="fas fa-print me-1"></i> Cetak SKL
                                </a>
                                <form action="{{ route('admin.skl.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SKL ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada SKL yang diterbitkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            {{ $skls->links() }}
        </div>
    </div>
</div>
@endsection
