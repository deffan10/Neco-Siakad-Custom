@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Ajukan cuti akademik untuk semester berjalan atau lihat riwayat pengajuan cuti Anda.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Form Pengajuan Cuti -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Formulir Pengajuan</h3>
                </div>
                <div class="card-body">
                    @if($activeTa)
                        <form action="{{ route('mahasiswa.cuti.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahun_akademik_id" value="{{ $activeTa->id }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Semester / Tahun Akademik Aktif</label>
                                <input type="text" class="form-control" value="{{ $activeTa->name }}" readonly disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label required">Alasan Pengajuan Cuti</label>
                                <textarea class="form-control" name="alasan" rows="5" placeholder="Tulis alasan lengkap mengajukan cuti (minimal 10 karakter)..." required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Kirim Pengajuan Cuti</button>
                        </form>
                    @else
                        <div class="alert alert-warning py-3 text-center mb-0">
                            <strong>Perhatian!</strong><br>
                            Tahun akademik aktif belum ditentukan oleh bagian administrasi. Pengajuan cuti ditutup sementara.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Histori Pengajuan Cuti -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Pengajuan Cuti Saya</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Tanggal Pengajuan</th>
                                <th>Tahun Akademik</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Catatan Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cutis as $c)
                                <tr>
                                    <td>{{ $c->created_at->format('d M Y') }}</td>
                                    <td><strong>{{ $c->tahunAkademik->name ?? 'N/A' }}</strong></td>
                                    <td>{{ Str::limit($c->alasan, 60) }}</td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat pengajuan cuti akademik.</td>
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
