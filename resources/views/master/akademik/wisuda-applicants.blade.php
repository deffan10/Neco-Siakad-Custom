@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('admin.wisuda.settings') }}" class="btn btn-outline-secondary mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Pengaturan Wisuda
                </a>
                <h2 class="page-title">Pendaftar Kegiatan: {{ $kegiatan->nama }}</h2>
                <div class="text-muted mt-1">Verifikasi berkas persyaratan wisuda mahasiswa, status pembayaran biaya wisuda, dan tetapkan keputusan persetujuan.</div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Daftar Pengajuan Wisuda Mahasiswa</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>NIM & Nama</th>
                        <th>Ukuran Toga</th>
                        <th>TOEFL Score</th>
                        <th>Berkas Upload</th>
                        <th>Status Bayar</th>
                        <th>Status Berkas</th>
                        <th>Verifikator</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $p)
                        <tr>
                            <td>
                                <div><strong>{{ $p->mahasiswa->user->name }}</strong></div>
                                <div class="text-muted small">NIM: {{ $p->mahasiswa->user->username }}</div>
                            </td>
                            <td><span class="badge bg-blue-lt">{{ $p->ukuran_toga }}</span></td>
                            <td>{{ $p->toefl_score ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex flex-column gap-1 small">
                                    @if($p->berkas_photo)
                                        <a href="{{ asset('storage/' . $p->berkas_photo) }}" target="_blank" class="text-info">
                                            <i class="fas fa-image me-1"></i> Foto Wisuda
                                        </a>
                                    @endif
                                    @if($p->berkas_bebas_pustaka)
                                        <a href="{{ asset('storage/' . $p->berkas_bebas_pustaka) }}" target="_blank" class="text-info">
                                            <i class="fas fa-file-pdf me-1"></i> Bebas Perpus
                                        </a>
                                    @endif
                                    @if($p->berkas_skripsi)
                                        <a href="{{ asset('storage/' . $p->berkas_skripsi) }}" target="_blank" class="text-info">
                                            <i class="fas fa-file-pdf me-1"></i> Pengesahan Skripsi
                                        </a>
                                    @endif
                                    @if($p->berkas_toefl)
                                        <a href="{{ asset('storage/' . $p->berkas_toefl) }}" target="_blank" class="text-info">
                                            <i class="fas fa-file-pdf me-1"></i> Sertifikat TOEFL
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($p->is_paid)
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($kegiatan->biaya == 0)
                                    <span class="badge bg-secondary">Gratis</span>
                                @else
                                    <span class="badge bg-danger">Belum Bayar</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status === 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($p->status === 'Ditolak')
                                    <span class="badge bg-danger" data-bs-toggle="tooltip" title="Alasan: {{ $p->catatan }}">Ditolak</span>
                                @elseif($p->status === 'Diajukan')
                                    <span class="badge bg-warning">Diajukan</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>
                                @if($p->verifiedBy)
                                    <div class="small"><strong>{{ $p->verifiedBy->name }}</strong></div>
                                    <div class="text-muted extra-small">{{ \Carbon\Carbon::parse($p->verified_at)->format('d/m H:i') }}</div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($p->status === 'Diajukan')
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#verifyModal-{{ $p->id }}">
                                        <i class="fas fa-check-double me-1"></i> Verifikasi
                                    </button>

                                    <!-- Verify Modal -->
                                    <div class="modal modal-blur fade" id="verifyModal-{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Verifikasi Pendaftaran: {{ $p->mahasiswa->user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.wisuda.verify', $p->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if(!$p->is_paid && $kegiatan->biaya > 0)
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-1"></i> 
                                                                Mahasiswa belum melunasi biaya wisuda sebesar Rp {{ number_format($kegiatan->biaya, 0, ',', '.') }}. Anda tidak bisa menyetujui pendaftaran ini sampai pembayaran dikonfirmasi Lunas.
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Keputusan Persetujuan</label>
                                                            <select class="form-select" name="status" required id="statusSelect-{{ $p->id }}">
                                                                <option value="Disetujui" {{ (!$p->is_paid && $kegiatan->biaya > 0) ? 'disabled' : '' }}>Setujui & Verifikasi Berkas</option>
                                                                <option value="Ditolak">Tolak Pendaftaran (Berkas/Persyaratan Tidak Sesuai)</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Catatan / Alasan Penolakan</label>
                                                            <textarea class="form-control" name="catatan" rows="3" placeholder="Masukkan catatan atau alasan jika pendaftaran ditolak..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">Sudah Diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada mahasiswa yang mengajukan pendaftaran wisuda untuk kegiatan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
