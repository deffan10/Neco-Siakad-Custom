@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Review, setujui, atau tolak pengajuan cuti akademik dari mahasiswa.</div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header border-bottom py-3">
            <form action="" method="GET" class="d-flex align-items-center gap-2">
                <div class="w-40">
                    <input type="text" class="form-control" name="search" placeholder="Cari nama mahasiswa atau NIM..." value="{{ request('search') }}">
                </div>
                <div class="w-30">
                    <select class="form-select" name="status">
                        <option value="">Semua Status Pengajuan</option>
                        <option value="Diajukan" {{ request('status') === 'Diajukan' ? 'selected' : '' }}>Diajukan (Pending)</option>
                        <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui (Approved)</option>
                        <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>NIM & Nama</th>
                        <th>Tahun Akademik</th>
                        <th>Alasan Pengajuan</th>
                        <th>Status</th>
                        <th>Catatan Admin</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cutis as $c)
                        <tr>
                            <td>
                                <div><strong>{{ $c->mahasiswa->name ?? 'N/A' }}</strong></div>
                                <div class="text-muted small">NIM: {{ $c->mahasiswa->nim ?? 'N/A' }}</div>
                            </td>
                            <td>{{ $c->tahunAkademik->name ?? 'N/A' }}</td>
                            <td>
                                <span class="small" title="{{ $c->alasan }}">{{ Str::limit($c->alasan, 50) }}</span>
                            </td>
                            <td>
                                @if($c->status === 'Diajukan')
                                    <span class="badge bg-warning">Diajukan</span>
                                @elseif($c->status === 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">{{ $c->catatan_admin ?? '-' }}</span>
                            </td>
                            <td class="text-end">
                                @if($c->status === 'Diajukan')
                                    <div class="d-flex justify-content-end gap-1">
                                        <form action="{{ route('admin.cuti.approve', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan cuti ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $c->id }}">Tolak</button>
                                    </div>

                                    <!-- Reject Note Modal -->
                                    <div class="modal modal-blur fade" id="rejectModal-{{ $c->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Pengajuan Cuti — {{ $c->mahasiswa->name ?? 'N/A' }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.cuti.reject', $c->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label required">Alasan Penolakan</label>
                                                            <textarea class="form-control" name="catatan_admin" rows="4" placeholder="Tulis alasan penolakan agar mahasiswa mengetahui..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    <span class="text-muted small"><em>Selesai</em></span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada pengajuan cuti akademik yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            {{ $cutis->links() }}
        </div>
    </div>
</div>
@endsection
