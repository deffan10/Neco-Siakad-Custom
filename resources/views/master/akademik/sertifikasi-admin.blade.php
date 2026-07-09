@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Verifikasi sertifikat kompetensi yang diunggah mahasiswa untuk keperluan SKPI.</div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mt-3">
        <div class="card-body py-2 border-bottom">
            <form action="" method="GET" class="d-flex gap-2 align-items-center flex-wrap">
                <input type="text" name="search" class="form-control" style="width:220px"
                       placeholder="Cari nama mhs / sertifikat..." value="{{ request('search') }}">
                <select name="status" class="form-select" style="width:180px">
                    <option value="">Semua Status</option>
                    <option value="Menunggu"  {{ request('status') === 'Menunggu'  ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak"   {{ request('status') === 'Ditolak'   ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button class="btn btn-primary">Filter</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Nama Sertifikat</th>
                        <th>Lembaga Penerbit</th>
                        <th>Kategori</th>
                        <th>Tanggal Terbit</th>
                        <th>Berkas</th>
                        <th>Status</th>
                        <th>Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sertifikasis as $s)
                    <tr>
                        <td><strong>{{ $s->mahasiswa->name ?? 'N/A' }}</strong></td>
                        <td>{{ $s->nama_sertifikat }}</td>
                        <td class="text-muted small">{{ $s->lembaga_penerbit }}</td>
                        <td><span class="badge bg-azure-lt">{{ $s->kategori }}</span></td>
                        <td class="small">{{ \Carbon\Carbon::parse($s->tanggal_terbit)->format('d M Y') }}</td>
                        <td>
                            @if($s->file_sertifikat)
                                <a href="{{ asset('storage/' . $s->file_sertifikat) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-file-alt me-1"></i> Lihat
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @if($s->status_verifikasi === 'Disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($s->status_verifikasi === 'Ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-warning">Menunggu</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.sertifikasi.verifikasi', $s->id) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <select name="status_verifikasi" class="form-select form-select-sm" style="width:120px">
                                    <option value="Menunggu"  {{ $s->status_verifikasi === 'Menunggu'  ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Disetujui" {{ $s->status_verifikasi === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="Ditolak"   {{ $s->status_verifikasi === 'Ditolak'   ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data sertifikasi mahasiswa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $sertifikasis->links() }}</div>
    </div>
</div>
@endsection
