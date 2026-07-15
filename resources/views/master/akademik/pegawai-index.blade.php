@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Daftar pegawai administratif, status kepegawaian, dan data jabatan.</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah">
                    <i class="fas fa-plus me-2"></i>Tambah Pegawai
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <input type="text" name="search" class="form-control" style="max-width:280px" placeholder="Cari Nama / NIP / NIK..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                <a href="{{ route('admin.pegawai.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Pegawai</th>
                        <th>NIP / NIK</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Tanggal Gabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $p->user->name ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $p->user->email ?? '' }}</small>
                            </td>
                            <td>
                                <strong>NIP:</strong> {{ $p->nip ?? '-' }}<br>
                                <strong>NIK:</strong> {{ $p->nik ?? '-' }}
                            </td>
                            <td>
                                <span class="badge bg-blue-lt">{{ $p->jabatan->name ?? 'Staf' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-green">{{ $p->status_kerja }}</span>
                            </td>
                            <td>{{ $p->tanggal_bergabung ? \Carbon\Carbon::parse($p->tanggal_bergabung)->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $p->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.pegawai.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pegawai ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal modal-blur fade" id="modal-edit-{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('admin.pegawai.update', $p->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Pegawai - {{ $p->user->name ?? '' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label required">Nama Lengkap</label>
                                                <input type="text" name="name" class="form-control" value="{{ $p->user->name ?? '' }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required">NIP</label>
                                                <input type="text" name="nip" class="form-control" value="{{ $p->nip }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">NIK</label>
                                                <input type="text" name="nik" class="form-control" value="{{ $p->nik }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jabatan</label>
                                                <select name="jabatan_id" class="form-select">
                                                    <option value="">-- Pilih Jabatan --</option>
                                                    @foreach($jabatans as $j)
                                                        <option value="{{ $j->id }}" {{ $p->jabatan_id == $j->id ? 'selected' : '' }}>{{ $j->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label required">Status Kerja</label>
                                                    <select name="status_kerja" class="form-select" required>
                                                        <option value="Tetap" {{ $p->status_kerja === 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                                        <option value="Kontrak" {{ $p->status_kerja === 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                                        <option value="Honorer" {{ $p->status_kerja === 'Honorer' ? 'selected' : '' }}>Honorer</option>
                                                        <option value="Outsourcing" {{ $p->status_kerja === 'Outsourcing' ? 'selected' : '' }}>Outsourcing</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">Tanggal Bergabung</label>
                                                    <input type="date" name="tanggal_bergabung" class="form-control" value="{{ $p->tanggal_bergabung }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $pegawais->links() }}
        </div>
    </div>
</div>

<!-- Tambah Modal -->
<div class="modal modal-blur fade" id="modal-tambah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.pegawai.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pegawai Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="cth: Ahmad Dahlan, S.Kom." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="cth: ahmad@kampus.ac.id" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label required">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="ahmad123" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label required">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">NIP</label>
                        <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP resmi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" class="form-control" placeholder="Masukkan NIK KTP">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <select name="jabatan_id" class="form-select">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatans as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label required">Status Kerja</label>
                            <select name="status_kerja" class="form-select" required>
                                <option value="Tetap">Tetap</option>
                                <option value="Kontrak" selected>Kontrak</option>
                                <option value="Honorer">Honorer</option>
                                <option value="Outsourcing">Outsourcing</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Tanggal Bergabung</label>
                            <input type="date" name="tanggal_bergabung" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Pegawai</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
