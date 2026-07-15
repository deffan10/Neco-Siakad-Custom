@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Daftar status mahasiswa, riwayat kelulusan, dan status keluar untuk pelaporan PDDIKTI.</div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <input type="text" name="search" class="form-control" style="max-width:250px" placeholder="Cari Nama / NIM..." value="{{ request('search') }}">
                <select name="program_studi_id" class="form-select" style="max-width:220px">
                    <option value="">-- Semua Program Studi --</option>
                    @foreach($programStudis as $ps)
                        <option value="{{ $ps->id }}" {{ request('program_studi_id') == $ps->id ? 'selected' : '' }}>
                            {{ $ps->nama_singkat ?? $ps->name }}
                        </option>
                    @endforeach
                </select>
                <select name="status_mahasiswa_id" class="form-select" style="max-width:200px">
                    <option value="">-- Semua Status --</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s->id }}" {{ request('status_mahasiswa_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                <a href="{{ route('admin.aktivitas-mahasiswa.index') }}" class="btn btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card mt-3">
        <div class="table-responsive">
            <table class="table card-table table-vcenter table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mahasiswa</th>
                        <th>NIM</th>
                        <th>Prodi</th>
                        <th>Status</th>
                        <th>IPK / SKS Lulus</th>
                        <th>Tanggal Lulus</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $m->user->name ?? '-' }}</strong><br>
                                <small class="text-muted">Angkatan: {{ $m->angkatan }}</small>
                            </td>
                            <td><code>{{ $m->nim }}</code></td>
                            <td>{{ $m->programStudi->nama_singkat ?? $m->programStudi->name ?? '-' }}</td>
                            <td>
                                @php
                                    $statusLower = strtolower($m->statusMahasiswa->name ?? '');
                                    $badgeClass = 'bg-secondary';
                                    if (strpos($statusLower, 'aktif') !== false) {
                                        $badgeClass = 'bg-success';
                                    } elseif (strpos($statusLower, 'lulus') !== false) {
                                        $badgeClass = 'bg-blue';
                                    } elseif (strpos($statusLower, 'keluar') !== false || strpos($statusLower, 'drop') !== false) {
                                        $badgeClass = 'bg-danger';
                                    } elseif (strpos($statusLower, 'cuti') !== false) {
                                        $badgeClass = 'bg-warning';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $m->statusMahasiswa->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <strong>IPK:</strong> {{ $m->ipk ?? '0.00' }}<br>
                                <strong>SKS Lulus:</strong> {{ $m->sks_lulus ?? '0' }} SKS
                            </td>
                            <td>{{ $m->tanggal_lulus ? \Carbon\Carbon::parse($m->tanggal_lulus)->format('d/m/Y') : '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $m->id }}">
                                    <i class="fas fa-edit me-1"></i> Edit Status
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal modal-blur fade" id="modal-edit-{{ $m->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('admin.aktivitas-mahasiswa.update', $m->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Perbarui Status - {{ $m->user->name ?? '' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label required">Status Mahasiswa</label>
                                                <select name="status_mahasiswa_id" class="form-select" required>
                                                    @foreach($statuses as $s)
                                                        <option value="{{ $s->id }}" {{ $m->status_mahasiswa_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Lulus / Keluar</label>
                                                <input type="date" name="tanggal_lulus" class="form-control" value="{{ $m->tanggal_lulus }}">
                                                <small class="text-muted">Diisi jika mahasiswa berstatus Lulus, Pindah, atau Keluar.</small>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">IPK Akhir</label>
                                                    <input type="number" step="0.01" min="0" max="4.00" name="ipk" class="form-control" value="{{ $m->ipk }}" placeholder="3.50">
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">Total SKS Lulus</label>
                                                    <input type="number" min="0" name="sks_lulus" class="form-control" value="{{ $m->sks_lulus }}" placeholder="144">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Status</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data mahasiswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $mahasiswas->links() }}
        </div>
    </div>
</div>
@endsection
