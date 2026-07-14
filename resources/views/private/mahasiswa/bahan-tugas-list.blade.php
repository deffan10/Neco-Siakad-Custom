@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Mata Kuliah: <strong>{{ $kelas->mataKuliah->name }}</strong> (Kelas: {{ $kelas->code }})</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('mahasiswa.bahan-tugas.index') }}" class="btn btn-outline-secondary">
                    Kembali ke Kelas
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Bahan Ajar & Tugas</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>Pertemuan</th>
                        <th>Judul & Deskripsi</th>
                        <th>Tipe</th>
                        <th>Bahan File</th>
                        <th>Deadline</th>
                        <th>Nilai Anda</th>
                        <th class="text-end">Aksi Tugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $sub = $submissions[$item->id] ?? null;
                            $deadlinePassed = $item->deadline ? \Carbon\Carbon::parse($item->deadline)->isPast() : false;
                        @endphp
                        <tr>
                            <td><span class="badge bg-purple-lt">Pertemuan Ke-{{ $item->pertemuan->pertemuan_ke }}</span></td>
                            <td>
                                <div><strong>{{ $item->judul }}</strong></div>
                                <div class="text-muted small">{{ $item->deskripsi ?? 'Tidak ada instruksi khusus' }}</div>
                            </td>
                            <td>
                                @if($item->tipe === 'Materi')
                                    <span class="badge bg-green">Materi</span>
                                @else
                                    <span class="badge bg-orange">Tugas</span>
                                @endif
                            </td>
                            <td>
                                @if($item->file_path)
                                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-sm btn-pill btn-outline-info">
                                        <i class="fas fa-download me-1"></i> Unduh Bahan
                                    </a>
                                @else
                                    <span class="text-muted small">Tidak ada file</span>
                                @endif
                            </td>
                            <td>
                                @if($item->tipe === 'Tugas')
                                    <span class="{{ $deadlinePassed ? 'text-danger fw-bold' : 'text-muted' }} small">
                                        {{ \Carbon\Carbon::parse($item->deadline)->format('d M Y, H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->tipe === 'Tugas' && $sub && $sub->nilai !== null)
                                    <span class="badge bg-success fw-bold fs-6">{{ $sub->nilai }}</span>
                                @elseif($item->tipe === 'Tugas' && $sub)
                                    <span class="badge bg-warning">Menunggu Penilaian</span>
                                @elseif($item->tipe === 'Tugas')
                                    <span class="text-danger small">Belum Dinilai</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($item->tipe === 'Tugas')
                                    @if($sub)
                                        <div class="d-flex justify-content-end align-items-center gap-2">
                                            <span class="text-success small fw-bold"><i class="fas fa-check-circle"></i> Sudah Dikirim</span>
                                            @if(!$deadlinePassed)
                                                <button type="button" class="btn btn-xs btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#submitModal-{{ $item->id }}">Ubah File</button>
                                            @endif
                                        </div>
                                    @elseif($deadlinePassed)
                                        <span class="text-danger small fw-bold"><i class="fas fa-times-circle"></i> Terlambat</span>
                                    @else
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#submitModal-{{ $item->id }}">
                                            <i class="fas fa-upload me-1"></i> Kumpulkan
                                        </button>
                                    @endif

                                    <!-- Submission Upload Modal -->
                                    <div class="modal modal-blur fade" id="submitModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Kumpulkan Tugas Kuliah</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mahasiswa.bahan-tugas.submit', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="alert alert-info py-2 small">
                                                            <strong>Tugas:</strong> {{ $item->judul }} <br>
                                                            <strong>Batas Waktu:</strong> {{ \Carbon\Carbon::parse($item->deadline)->format('d F Y, H:i') }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label required">Upload File Tugas (PDF, Docx, Zip, Max 5MB)</label>
                                                            <input type="file" class="form-control" name="file" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Catatan Tambahan (Opsional)</label>
                                                            <textarea class="form-control" name="catatan" rows="3" placeholder="Tulis catatan jika ada..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Kumpulkan Tugas</button>
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
                            <td colspan="7" class="text-center text-muted py-4">Belum ada bahan ajar atau tugas kuliah di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
