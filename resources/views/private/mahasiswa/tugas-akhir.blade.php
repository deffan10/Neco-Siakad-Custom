@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Daftarkan judul proposal metodologi penelitian atau draf skripsi final Anda di sini.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Form Pengajuan Tugas Akhir -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Form Pendaftaran Baru</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('mahasiswa.tugas-akhir.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Tipe Pengajuan</label>
                            <select class="form-select" name="tipe" required>
                                <option value="Proposal">Proposal Penelitian</option>
                                <option value="Skripsi">Skripsi / Tugas Akhir Final</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Judul Penelitian</label>
                            <input type="text" class="form-control" name="judul" placeholder="Contoh: Analisis Implementasi Kurikulum..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Abstrak / Rangkuman Singkat</label>
                            <textarea class="form-control" name="abstrak" rows="4" placeholder="Tulis abstrak singkat rancangan penelitian Anda..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Dosen Wali / Pembimbing</label>
                            <select class="form-select" name="dosen_pembimbing_id" required>
                                <option value="" disabled selected>-- Pilih Dosen Pembimbing --</option>
                                @foreach($lecturers as $lec)
                                    <option value="{{ $lec->id }}">{{ $lec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Unggah Draft Dokumen (PDF/Docx, Max 10MB)</label>
                            <input type="file" class="form-control" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Kirim Pengajuan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Riwayat Pengajuan Tugas Akhir -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Pengajuan Penelitian</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Judul / Pembimbing</th>
                                <th>Draft File</th>
                                <th>Status</th>
                                <th>Catatan Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>
                                        @if($item->tipe === 'Proposal')
                                            <span class="badge bg-purple">Proposal</span>
                                        @else
                                            <span class="badge bg-blue">Skripsi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div><strong>{{ $item->judul }}</strong></div>
                                        <div class="text-muted small">Pembimbing: {{ $item->dosenPembimbing->name ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <a href="{{ asset('storage/' . $item->file_draft) }}" target="_blank" class="btn btn-sm btn-pill btn-outline-info">
                                            <i class="fas fa-download me-1"></i> Draft
                                        </a>
                                    </td>
                                    <td>
                                        @if($item->status === 'Diajukan')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($item->status === 'Disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $item->catatan_review ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat pengajuan tugas akhir atau proposal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
