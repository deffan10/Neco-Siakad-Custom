@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Cari ruang kelas yang kosong pada hari dan jam tertentu untuk keperluan perkuliahan atau ujian.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.akademik.jadwal-perkuliahan-index') }}" class="btn btn-outline-secondary">
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
                    <a class="nav-link fw-bold" href="{{ route('admin.akademik.jadwal-perkuliahan-index') }}"><i class="fas fa-calendar-alt me-1"></i> Jadwal Perkuliahan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.akademik.jadwal-perkuliahan-ruang-kosong') }}"><i class="fas fa-door-open me-1"></i> Cari Ruang Kosong</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title font-weight-bold">Filter Pencarian Ruangan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.akademik.jadwal-perkuliahan-ruang-kosong') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label required">Hari</label>
                        <select name="hari" class="form-select" required>
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin" {{ request('hari') === 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ request('hari') === 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ request('hari') === 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ request('hari') === 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ request('hari') === 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ request('hari') === 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            <option value="Minggu" {{ request('hari') === 'Minggu' ? 'selected' : '' }}>Minggu</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" value="{{ request('jam_mulai') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" value="{{ request('jam_selesai') }}" required>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Periksa Ketersediaan
                    </button>
                </div>
            </form>
        </div>

        @if(request()->has('hari'))
            <div class="table-responsive">
                <table class="table card-table table-vcenter table-striped">
                    <thead>
                        <tr>
                            <th>Nama Ruangan</th>
                            <th>Keterangan Gedung</th>
                            <th>Kapasitas Belajar</th>
                            <th>Status Ruang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($freeRooms as $r)
                            <tr>
                                <td><strong>{{ $r->name }}</strong></td>
                                <td>{{ $r->gedung->name ?? 'Gedung Utama' }}</td>
                                <td>{{ $r->kapasitas ?? 'N/A' }} mahasiswa</td>
                                <td><span class="badge bg-success">Tersedia / Kosong</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Semua ruangan kelas sedang terpakai pada hari dan jam tersebut.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
