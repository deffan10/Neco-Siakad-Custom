@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Review, setujui, atau tolak pendaftaran judul proposal/skripsi mahasiswa.</div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.tugas-akhir.index') }}" method="GET" class="row g-2">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Cari Judul, Nama, atau NIM..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">-- Semua Status --</option>
                        <option value="Diajukan" {{ request('status') === 'Diajukan' ? 'selected' : '' }}>Diajukan (Pending)</option>
                        <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table of Submissions -->
    <div class="card mt-3">
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Tipe</th>
                        <th>Judul & Abstrak</th>
                        <th>Pembimbing</th>
                        <th>Draft File</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>
                                <div><strong>{{ $item->mahasiswa->name ?? 'N/A' }}</strong></div>
                                <div class="text-muted small">NIM: {{ $item->mahasiswa->nim ?? ($item->mahasiswa->username ?? '-') }}</div>
                            </td>
                            <td>
                                @if($item->tipe === 'Proposal')
                                    <span class="badge bg-purple-lt">Proposal</span>
                                @else
                                    <span class="badge bg-blue-lt">Skripsi</span>
                                @endif
                            </td>
                            <td>
                                <div><strong>{{ $item->judul }}</strong></div>
                                <div class="text-muted small" style="max-width: 350px;">{{ Str::limit($item->abstrak, 120) }}</div>
                            </td>
                            <td>
                                {{ $item->dosenPembimbing->name ?? '-' }}
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $item->file_draft) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                            </td>
                            <td>
                                @if($item->status === 'Diajukan')
                                    <span class="badge bg-warning">Diajukan</span>
                                @elseif($item->status === 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($item->status === 'Diajukan')
                                    <div class="d-flex justify-content-end gap-1">
                                        <form action="{{ route('admin.tugas-akhir.approve', $item->id) }}" method="POST" onsubmit="return confirm('Setujui judul tugas akhir ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-success">Setujui</button>
                                        </form>

                                        <button type="button" class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $item->id }}">
                                            Tolak
                                        </button>
                                    </div>

                                    <!-- Rejection Modal -->
                                    <div class="modal modal-blur fade" id="rejectModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Pengajuan Tugas Akhir</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.tugas-akhir.reject', $item->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label required">Alasan Penolakan / Catatan Review</label>
                                                            <textarea class="form-control" name="catatan_review" rows="3" placeholder="Sebutkan kekurangan atau saran revisi..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak & Kirim Catatan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">{{ $item->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan proposal atau tugas akhir masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
