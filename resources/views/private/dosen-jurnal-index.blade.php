@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Isi realisasi topik perkuliahan (jurnal mengajar) dan tautan rekaman kelas virtual untuk setiap pertemuan perkuliahan Anda.</div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Jadwal Pertemuan Kuliah Saya</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>Kelas & Matakuliah</th>
                        <th>Pertemuan</th>
                        <th>Jadwal Pelaksanaan</th>
                        <th>Topik / Materi Pembelajaran</th>
                        <th>Link Media (Zoom/Meet)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pertemuans as $p)
                        <tr>
                            <td>
                                <div><strong>{{ $p->jadwal->mataKuliah->name }}</strong></div>
                                <div class="text-muted small">Kode Kelas: {{ $p->jadwal->code }}</div>
                            </td>
                            <td><span class="badge bg-purple-lt">Ke-{{ $p->pertemuan_ke }}</span></td>
                            <td>
                                <div class="small">{{ \Carbon\Carbon::parse($p->tanggal)->format('d F Y') }}</div>
                                <div class="text-muted extra-small">{{ substr($p->jam_mulai, 0, 5) }} - {{ substr($p->jam_selesai, 0, 5) }} (Ruang: {{ $p->ruangan->name ?? 'N/A' }})</div>
                            </td>
                            <td>
                                @if($p->materi)
                                    <span class="text-muted small" title="{{ $p->materi }}">{{ Str::limit($p->materi, 50) }}</span>
                                @else
                                    <span class="text-danger small"><em>Jurnal belum diisi</em></span>
                                @endif
                            </td>
                            <td>
                                @if($p->link)
                                    <a href="{{ $p->link }}" target="_blank" class="text-info small">
                                        <i class="fas fa-external-link-alt me-1"></i> Buka Link
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($p->is_realisasi)
                                    <span class="badge bg-success">Terlaksana</span>
                                @else
                                    <span class="badge bg-warning">Terjadwal</span>
                                @endif
                            </td>
                            <td>
                                @if($p->is_locked)
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        <i class="fas fa-lock me-1"></i> Dikunci
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $p->id }}">
                                        <i class="fas fa-edit me-1"></i> Isi Jurnal
                                    </button>

                                    <!-- Modal Form Isi Jurnal -->
                                    <div class="modal modal-blur fade" id="editModal-{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Isi Jurnal Pertemuan Ke-{{ $p->pertemuan_ke }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('dosen.jurnal.update', $p->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="alert alert-info py-2">
                                                            <strong>Matakuliah:</strong> {{ $p->jadwal->mataKuliah->name }} <br>
                                                            <strong>Jadwal:</strong> {{ \Carbon\Carbon::parse($p->tanggal)->format('d F Y') }}
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label required">Topik / Rangkuman Materi Perkuliahan</label>
                                                            <textarea class="form-control" name="materi" rows="4" placeholder="Masukkan materi, sub-bab, atau kompetensi yang diajarkan pada pertemuan ini..." required>{{ $p->materi }}</textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Tautan Media Pembelajaran (Zoom/Meet/Drive, opsional)</label>
                                                            <input type="url" class="form-control" name="link" value="{{ $p->link }}" placeholder="https://example.com/zoom-meeting-record">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Jurnal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada jadwal pertemuan kuliah yang ditugaskan kepada Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
