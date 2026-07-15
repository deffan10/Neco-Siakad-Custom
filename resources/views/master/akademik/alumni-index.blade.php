@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Cari dan kelola data alumni kelulusan serta album wisuda.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'cari' ? 'active fw-bold' : 'fw-bold' }}" href="{{ route('admin.alumni.index') }}">
                        <i class="fas fa-user-graduate me-1"></i> Cari Alumni
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'album' ? 'active fw-bold' : 'fw-bold' }}" href="{{ route('admin.alumni.album') }}">
                        <i class="fas fa-images me-1"></i> Album Wisuda
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @if($activeTab === 'cari')
        <!-- Tab 1: Cari & Data Alumni -->
        <div class="card mt-3">
            <div class="card-body py-2">
                <form method="GET" class="d-flex gap-2 flex-wrap">
                    <input type="text" name="search" class="form-control" style="max-width:230px" placeholder="NIM / Nama / No Alumni..." value="{{ request('search') }}">
                    <select name="program_studi_id" class="form-select" style="max-width:200px">
                        <option value="">-- Semua Prodi --</option>
                        @foreach($programStudis as $ps)
                            <option value="{{ $ps->id }}" {{ request('program_studi_id') == $ps->id ? 'selected' : '' }}>
                                {{ $ps->nama_singkat ?? $ps->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="tahun_lulus" class="form-control" style="max-width:130px" placeholder="Tahun Lulus" value="{{ request('tahun_lulus') }}">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    <a href="{{ route('admin.alumni.index') }}" class="btn btn-outline-secondary">Reset</a>
                </form>
            </div>
        </div>

        <div class="row row-cards mt-3">
            <!-- Entri Alumni Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Promosikan ke Alumni</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.alumni.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label required">Mahasiswa Lulus</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">-- Pilih Mahasiswa --</option>
                                    @foreach($mahasiswas as $m)
                                        <option value="{{ $m->id }}">{{ $m->username }} - {{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Program Studi</label>
                                <select name="program_studi_id" class="form-select" required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach($programStudis as $ps)
                                        <option value="{{ $ps->id }}">{{ $ps->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Nomor Alumni / SK</label>
                                <input type="text" name="nomor_alumni" class="form-control" placeholder="cth: AL-2026-001" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label required">Tanggal Lulus</label>
                                    <input type="date" name="tanggal_lulus" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label required">IPK</label>
                                    <input type="number" step="0.01" name="ipk" class="form-control" placeholder="3.50" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Judul Skripsi / Tugas Akhir</label>
                                <textarea name="judul_skripsi" class="form-control" rows="2" placeholder="Judul skripsi..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pekerjaan Sekarang</label>
                                <input type="text" name="pekerjaan_sekarang" class="form-control" placeholder="cth: Software Engineer">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Simpan Alumni</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- List Table -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Alumni ({{ $alumnis->total() }} data)</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Alumni</th>
                                    <th>Prodi</th>
                                    <th>No Alumni / Kelulusan</th>
                                    <th>IPK</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alumnis as $a)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $a->user->name ?? '-' }}</strong><br>
                                            <small class="text-muted">{{ $a->user->username ?? '' }}</small>
                                        </td>
                                        <td>{{ $a->programStudi->nama_singkat ?? $a->programStudi->name ?? '-' }}</td>
                                        <td>
                                            <code>{{ $a->nomor_alumni }}</code><br>
                                            <small class="text-muted">{{ $a->tanggal_lulus ? \Carbon\Carbon::parse($a->tanggal_lulus)->format('d M Y') : '-' }}</small>
                                        </td>
                                        <td><strong>{{ $a->ipk }}</strong></td>
                                        <td>
                                            <form action="{{ route('admin.alumni.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus alumni ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data alumni.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $alumnis->links() }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Tab 2: Album Wisuda (Card Grid) -->
        <div class="card mt-3">
            <div class="card-body py-2">
                <form method="GET" class="d-flex gap-2 flex-wrap">
                    <select name="program_studi_id" class="form-select" style="max-width:250px">
                        <option value="">-- Semua Prodi --</option>
                        @foreach($programStudis as $ps)
                            <option value="{{ $ps->id }}" {{ request('program_studi_id') == $ps->id ? 'selected' : '' }}>
                                {{ $ps->nama_singkat ?? $ps->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter Album</button>
                </form>
            </div>
        </div>

        <div class="row row-cards mt-3">
            @forelse($alumnis as $a)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body text-center py-4">
                            <span class="avatar avatar-xl rounded-circle mb-3 bg-light text-dark">
                                <i class="fas fa-user-graduate fa-2x"></i>
                            </span>
                            <h4 class="m-0 mb-1"><strong>{{ $a->user->name ?? '-' }}</strong></h4>
                            <div class="text-muted">{{ $a->user->username ?? '' }}</div>
                            <div class="text-muted small mt-1">{{ $a->programStudi->name ?? '-' }}</div>
                            <div class="mt-3">
                                <span class="badge bg-green-lt">IPK: {{ $a->ipk }}</span>
                            </div>
                            <div class="mt-2 text-muted small">
                                Lulus: {{ $a->tanggal_lulus ? \Carbon\Carbon::parse($a->tanggal_lulus)->format('Y') : '-' }}
                            </div>
                            @if($a->judul_skripsi)
                                <div class="mt-2 p-2 bg-light rounded text-start" style="font-size: 10px; height: 60px; overflow-y: auto;">
                                    <strong>Skripsi:</strong> {{ $a->judul_skripsi }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-images fa-4x mb-3 text-secondary"></i>
                    <p>Album wisuda masih kosong.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-3">
            {{ $alumnis->links() }}
        </div>
    @endif
</div>
@endsection
