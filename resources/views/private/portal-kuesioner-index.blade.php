@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Isi kuesioner evaluasi perkuliahan dan survei pelacakan karier alumni secara mandiri.</div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-purple text-white">
            <h3 class="card-title">Kuesioner Aktif Untuk Anda</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>Nama Kuesioner</th>
                        <th>Target Responden</th>
                        <th>Batas Waktu Pengisian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kuesioners as $k)
                        <tr>
                            <td>
                                <div><strong>{{ $k->judul }}</strong></div>
                                <div class="text-muted small">{{ $k->deskripsi }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $k->target_responden === 'Alumni' ? 'bg-purple-lt' : 'bg-blue-lt' }}">
                                    {{ $k->target_responden }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    Sampai: <strong>{{ \Carbon\Carbon::parse($k->tanggal_selesai)->format('d M Y') }}</strong>
                                </div>
                            </td>
                            <td>
                                @if($k->is_answered)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Sudah Diisi</span>
                                @else
                                    <span class="badge bg-warning"><i class="fas fa-exclamation-circle me-1"></i> Belum Diisi</span>
                                @endif
                            </td>
                            <td>
                                @if($k->is_answered)
                                    <button class="btn btn-sm btn-outline-success" disabled>
                                        Selesai Berpartisipasi
                                    </button>
                                @else
                                    <a href="{{ route('mahasiswa.kuesioner.show', $k->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit me-1"></i> Isi Kuesioner
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Saat ini tidak ada kuesioner aktif yang membutuhkan pengisian dari Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
