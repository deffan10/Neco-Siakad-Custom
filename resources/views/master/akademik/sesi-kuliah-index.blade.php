@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col"><h2 class="page-title">{{ $pages }}</h2></div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3"><div class="card-body py-2">
        <ul class="nav nav-tabs border-0">
            <li class="nav-item"><a class="nav-link {{ $activeTab === 'kuliah' ? 'active fw-bold' : 'fw-bold' }}" href="{{ route('admin.sesi-kuliah.kuliah') }}"><i class="fas fa-clock me-1"></i> Sesi Kuliah</a></li>
            <li class="nav-item"><a class="nav-link {{ $activeTab === 'ujian' ? 'active fw-bold' : 'fw-bold' }}" href="{{ route('admin.sesi-kuliah.ujian') }}"><i class="fas fa-pen-alt me-1"></i> Sesi Ujian</a></li>
            <li class="nav-item"><a class="nav-link {{ $activeTab === 'kelompok' ? 'active fw-bold' : 'fw-bold' }}" href="{{ route('admin.sesi-kuliah.kelompok') }}"><i class="fas fa-layer-group me-1"></i> Kelompok Sesi</a></li>
        </ul>
    </div></div>

    <div class="row row-cards mt-3">
        <!-- Add Form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white"><h3 class="card-title">Tambah Sesi {{ ucfirst($activeTab) }}</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.sesi-kuliah.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tipe" value="{{ $activeTab === 'ujian' ? 'Ujian' : 'Kuliah' }}">
                        <div class="mb-3">
                            <label class="form-label required">Kode Sesi</label>
                            <input type="text" name="kode" class="form-control" placeholder="cth: SK01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Nama Sesi</label>
                            <input type="text" name="nama" class="form-control" placeholder="cth: Sesi 1" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label required">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" required>
                            </div>
                            <div class="col">
                                <label class="form-label required">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kelompok</label>
                            <select name="kelompok" class="form-select">
                                <option value="">-- Pilih Kelompok --</option>
                                <option value="Pagi">Pagi</option>
                                <option value="Siang">Siang</option>
                                <option value="Sore">Sore</option>
                                <option value="Malam">Malam</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Durasi (menit)</label>
                            <input type="number" name="durasi_menit" class="form-control" placeholder="50">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Sesi</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Daftar Sesi ({{ count($sesis) }} data)</h3></div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr><th>#</th><th>Kode</th><th>Nama</th><th>Jam Mulai</th><th>Jam Selesai</th><th>Kelompok</th><th>Durasi</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse($sesis as $s)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><code>{{ $s->kode }}</code></td>
                                    <td><strong>{{ $s->nama }}</strong></td>
                                    <td>{{ $s->jam_mulai }}</td>
                                    <td>{{ $s->jam_selesai }}</td>
                                    <td>{{ $s->kelompok ?? '-' }}</td>
                                    <td>{{ $s->durasi_menit ? $s->durasi_menit.' mnt' : '-' }}</td>
                                    <td>
                                        <form action="{{ route('admin.sesi-kuliah.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus sesi ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data sesi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
