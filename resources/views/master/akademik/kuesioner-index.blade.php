@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola pembuatan survei umpan balik akademik dan pelacakan karier alumni (Tracer Study).</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.kuesioner.index') }}"><i class="fas fa-poll-h me-1"></i> Daftar Kuesioner</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.kuesioner.index') }}?target=Alumni"><i class="fas fa-graduation-cap me-1"></i> Tracer Study</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Buat Kuesioner Baru -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Buat Kuesioner Baru</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.kuesioner.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Judul Kuesioner</label>
                            <input type="text" class="form-control" name="judul" placeholder="Contoh: Kuesioner Tracer Study Alumni 2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi / Petunjuk Pengisian</label>
                            <textarea class="form-control" name="deskripsi" rows="3" placeholder="Masukkan petunjuk pengisian..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Target Responden</label>
                            <select class="form-select" name="target_responden" required>
                                <option value="Mahasiswa">Mahasiswa Aktif</option>
                                <option value="Alumni">Alumni (Tracer Study)</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Umum">Umum / Semua Civitas</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label required">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tanggal_mulai" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label required">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="tanggal_selesai" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Kuesioner</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Kuesioner -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Instrumen Kuesioner</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Kuesioner</th>
                                <th>Target</th>
                                <th>Masa Aktif</th>
                                <th>Respon</th>
                                <th>Publish</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kuesioners as $k)
                                <tr>
                                    <td>
                                        <div><strong>{{ $k->judul }}</strong></div>
                                        <div class="text-muted small">{{ Str::limit($k->deskripsi, 60) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $k->target_responden === 'Alumni' ? 'bg-purple-lt' : 'bg-blue-lt' }}">
                                            {{ $k->target_responden }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            {{ \Carbon\Carbon::parse($k->tanggal_mulai)->format('d/m/y') }} s/d {{ \Carbon\Carbon::parse($k->tanggal_selesai)->format('d/m/y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-green">{{ $k->responses_count }} Respon</span>
                                    </td>
                                    <td>
                                        @if($k->is_published)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-danger">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <a href="{{ route('admin.kuesioner.questions', $k->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Kelola Pertanyaan">
                                                <i class="fas fa-question-circle me-1"></i> Pertanyaan
                                            </a>
                                            <a href="{{ route('admin.kuesioner.results', $k->id) }}" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Lihat Hasil">
                                                <i class="fas fa-chart-bar me-1"></i> Analisis
                                            </a>
                                            <form action="{{ route('admin.kuesioner.toggle', $k->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $k->is_published ? 'btn-warning' : 'btn-success' }}">
                                                    {{ $k->is_published ? 'Sembunyikan' : 'Terbitkan' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.kuesioner.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuesioner ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada kuesioner yang dibuat.</td>
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
