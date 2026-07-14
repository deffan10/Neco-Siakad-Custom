@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Atur alokasi kuota, status buka/tutup, dan waktu pelaksanaan event.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.seminar.index') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.seminar.index') }}"><i class="fas fa-calendar-check me-1"></i> Daftar Event</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.seminar.kuota') }}"><i class="fas fa-sliders-h me-1"></i> Waktu & Kuota</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.seminar.cari') }}"><i class="fas fa-search me-1"></i> Cari Peserta</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title font-weight-bold">Waktu & Kuota Pendaftaran Kegiatan</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter table-striped">
                <thead>
                    <tr>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Waktu Kegiatan</th>
                        <th class="text-center">Kuota Peserta</th>
                        <th class="text-center">Status Pendaftaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $e)
                        <tr>
                            <td><strong>{{ $e->nama_event }}</strong> <span class="badge bg-purple-lt ms-2">{{ $e->tipe_event }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($e->tanggal)->format('d-M-Y') }}</td>
                            <td>{{ $e->jam_mulai ?? '00:00' }} - {{ $e->jam_selesai ?? 'Selesai' }}</td>
                            <td class="text-center"><strong>{{ $e->kuota ?? 'Tidak Terbatas' }}</strong></td>
                            <td class="text-center">
                                <form action="{{ route('admin.seminar.toggle', $e->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $e->is_open ? 'btn-success' : 'btn-danger' }}">
                                        {{ $e->is_open ? 'Terbuka' : 'Tertutup' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data event seminar yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
