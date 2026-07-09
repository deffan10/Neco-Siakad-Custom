@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Unggah sertifikat kompetensi Anda untuk keperluan SKPI (Surat Keterangan Pendamping Ijazah).</div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Form Upload Sertifikat -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-cyan text-white">
                    <h3 class="card-title">Unggah Sertifikat Baru</h3>
                </div>
                <form action="{{ route('mahasiswa.sertifikasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label required">Nama Sertifikat</label>
                            <input type="text" name="nama_sertifikat" class="form-control" required placeholder="Contoh: TOEFL Score 550">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Lembaga Penerbit</label>
                            <input type="text" name="lembaga_penerbit" class="form-control" required placeholder="ETS, Certiport, Cisco...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Sertifikat</label>
                            <input type="text" name="nomor_sertifikat" class="form-control" placeholder="Opsional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="Bahasa">Bahasa</option>
                                <option value="Teknologi">Teknologi</option>
                                <option value="Profesi">Profesi</option>
                                <option value="Soft Skill">Soft Skill</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Tanggal Terbit</label>
                            <input type="date" name="tanggal_terbit" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Kadaluarsa <small class="text-muted">(opsional)</small></label>
                            <input type="date" name="tanggal_kadaluarsa" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File Sertifikat <small class="text-muted">(PDF/JPG/PNG, maks. 3MB)</small></label>
                            <input type="file" name="file_sertifikat" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-cyan w-100 text-white">
                            <i class="fas fa-upload me-1"></i> Unggah Sertifikat
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Sertifikat -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sertifikat Saya</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nama Sertifikat</th>
                                <th>Lembaga</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Berkas</th>
                                <th>Status Verifikasi</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sertifikasis as $s)
                            <tr>
                                <td>
                                    <strong>{{ $s->nama_sertifikat }}</strong>
                                    @if($s->nomor_sertifikat)
                                        <div class="text-muted small">No: {{ $s->nomor_sertifikat }}</div>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $s->lembaga_penerbit }}</td>
                                <td><span class="badge bg-azure-lt">{{ $s->kategori }}</span></td>
                                <td class="small">
                                    {{ \Carbon\Carbon::parse($s->tanggal_terbit)->format('d M Y') }}
                                    @if($s->tanggal_kadaluarsa)
                                        <div class="text-muted">s/d {{ \Carbon\Carbon::parse($s->tanggal_kadaluarsa)->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($s->file_sertifikat)
                                        <a href="{{ asset('storage/' . $s->file_sertifikat) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->status_verifikasi === 'Disetujui')
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i> Disetujui</span>
                                    @elseif($s->status_verifikasi === 'Ditolak')
                                        <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Ditolak</span>
                                        @if($s->catatan_verifikasi)
                                            <div class="text-muted small mt-1">{{ $s->catatan_verifikasi }}</div>
                                        @endif
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-hourglass me-1"></i> Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->status_verifikasi !== 'Disetujui')
                                    <form action="{{ route('mahasiswa.sertifikasi.destroy', $s->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus sertifikat ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Anda belum mengunggah sertifikat apapun.</td>
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
