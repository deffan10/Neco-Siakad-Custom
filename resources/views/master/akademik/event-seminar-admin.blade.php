@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola event seminar, webinar, dan workshop kemahasiswaan.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.seminar.index') }}"><i class="fas fa-calendar-check me-1"></i> Daftar Event</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.seminar.kuota') }}"><i class="fas fa-sliders-h me-1"></i> Waktu & Kuota</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.seminar.cari') }}"><i class="fas fa-search me-1"></i> Cari Peserta</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Form Tambah Event -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Buat Event Baru</h3>
                </div>
                <form action="{{ route('admin.seminar.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label required">Nama Event</label>
                            <input type="text" name="nama_event" class="form-control" required placeholder="Seminar Nasional Kewirausahaan...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Tipe Event</label>
                            <select name="tipe_event" class="form-select" required>
                                <option value="Umum">Seminar Umum</option>
                                <option value="Proposal">Seminar Proposal</option>
                                <option value="Hasil">Seminar Hasil</option>
                                <option value="Webinar">Webinar</option>
                                <option value="Workshop">Workshop</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Akademik</label>
                            <select name="tahun_akademik_id" class="form-select">
                                <option value="">-- Pilih Tahun Akademik --</option>
                                @foreach($tahunAkademiks as $ta)
                                    <option value="{{ $ta->id }}">{{ $ta->name }} ({{ $ta->semester }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Tanggal Pelaksanaan</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Aula Kampus / Zoom...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Narasumber</label>
                            <input type="text" name="narasumber" class="form-control" placeholder="Dr. Ahmad Syafii, M.Kom.">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kuota Peserta</label>
                            <input type="number" name="kuota" class="form-control" min="1" placeholder="Kosongkan jika tidak terbatas">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat event..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_wajib" value="1">
                                <span class="form-check-label">Tandai sebagai Event Wajib</span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary w-100">Simpan Event Seminar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Event -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Event Seminar</h3>
                    <div class="card-options">
                        <form action="" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama event..." value="{{ request('search') }}">
                            <button class="btn btn-sm btn-primary">Cari</button>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nama Event</th>
                                <th>Tipe</th>
                                <th>Tanggal</th>
                                <th>Peserta</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr>
                                <td>
                                    <div><strong>{{ $event->nama_event }}</strong></div>
                                    <div class="text-muted small">{{ $event->lokasi ?? 'Lokasi belum ditentukan' }}</div>
                                    @if($event->is_wajib)
                                        <span class="badge bg-red-lt">Wajib</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-blue-lt">{{ $event->tipe_event }}</span></td>
                                <td>
                                    <div class="small">{{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</div>
                                    @if($event->jam_mulai)
                                        <div class="text-muted small">{{ substr($event->jam_mulai,0,5) }} - {{ substr($event->jam_selesai,0,5) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.seminar.peserta', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-users me-1"></i> {{ $event->peserta_mahasiswas_count }} peserta
                                    </a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.seminar.toggle', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $event->is_open ? 'btn-success' : 'btn-secondary' }}">
                                            {{ $event->is_open ? 'Buka' : 'Tutup' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.seminar.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus event ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada event seminar yang dibuat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $events->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
