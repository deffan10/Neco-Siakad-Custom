@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Review dan nilai pengumpulan tugas mahasiswa untuk: <strong>{{ $task->judul }}</strong>.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('dosen.jurnal.bahan-tugas', $task->jadwal_pertemuan_id) }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Daftar Pengumpulan Mahasiswa</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>NIM & Nama</th>
                        <th>File Submisi</th>
                        <th>Tanggal Kirim</th>
                        <th>Catatan Mahasiswa</th>
                        <th>Nilai</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enr)
                        @php
                            $sub = $submissions[$enr->mahasiswa_id] ?? null;
                        @endphp
                        <tr>
                            <td>
                                <div><strong>{{ $enr->mahasiswa->name ?? 'N/A' }}</strong></div>
                                <div class="text-muted small">NIM: {{ $enr->mahasiswa->nim ?? 'N/A' }}</div>
                            </td>
                            <td>
                                @if($sub)
                                    <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="btn btn-sm btn-pill btn-outline-info">
                                        <i class="fas fa-download me-1"></i> File Tugas
                                    </a>
                                @else
                                    <span class="text-danger small"><em>Belum mengumpulkan</em></span>
                                @endif
                            </td>
                            <td>
                                @if($sub)
                                    {{ $sub->created_at->format('d M Y, H:i') }}
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">{{ $sub->catatan ?? '-' }}</span>
                            </td>
                            <td>
                                @if($sub && $sub->nilai !== null)
                                    <span class="badge bg-success fw-bold fs-6">{{ $sub->nilai }}</span>
                                @elseif($sub)
                                    <span class="badge bg-warning">Belum Dinilai</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($sub)
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gradeModal-{{ $sub->id }}">
                                        <i class="fas fa-check-double me-1"></i> Beri Nilai
                                    </button>

                                    <!-- Grade Modal -->
                                    <div class="modal modal-blur fade" id="gradeModal-{{ $sub->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Beri Nilai Tugas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('dosen.tugas.nilai', $sub->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label required">Nilai Angka (0-100)</label>
                                                            <input type="number" class="form-control" name="nilai" value="{{ $sub->nilai }}" min="0" max="100" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada mahasiswa terdaftar untuk kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
