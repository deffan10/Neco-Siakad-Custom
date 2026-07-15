@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola data asisten laboratorium dan penugasan mata kuliah praktikum.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="#daftar-asisten" data-bs-toggle="tab">
                        <i class="fas fa-users-cog me-1"></i> Asisten Laboratorium
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="#penugasan-mk" data-bs-toggle="tab">
                        <i class="fas fa-book-reader me-1"></i> Penugasan Mata Kuliah
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content mt-3">
        <!-- Tab 1: Daftar Asisten -->
        <div class="tab-pane fade show active" id="daftar-asisten">
            <div class="card mb-3">
                <div class="card-body py-2">
                    <form method="GET" class="d-flex gap-2 flex-wrap">
                        <input type="text" name="search" class="form-control" style="max-width:260px" placeholder="Cari Nama / NIM..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        <a href="{{ route('admin.asisten-lab.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </form>
                </div>
            </div>

            <div class="row row-cards">
                <!-- Tambah Form -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title"><i class="fas fa-plus me-2"></i>Tambah Asisten Lab</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.asisten-lab.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label required">Pengguna / Mahasiswa</label>
                                    <select name="user_id" class="form-select" required>
                                        <option value="">-- Pilih Mahasiswa --</option>
                                        @foreach($candidates as $c)
                                            <option value="{{ $c->id }}">{{ $c->username }} - {{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">NIM / NIP Asisten</label>
                                    <input type="text" name="nim_nip" class="form-control" placeholder="cth: 2010203001" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Catatan</label>
                                    <textarea name="catatan" class="form-control" rows="3" placeholder="Masukkan keahlian atau spesialisasi..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Simpan Asisten</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- List Table -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Asisten Lab ({{ $assistants->total() }} data)</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>NIM / NIP</th>
                                        <th>Keahlian / Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assistants as $a)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $a->user->name ?? '-' }}</strong></td>
                                            <td><code>{{ $a->nim_nip }}</code></td>
                                            <td>{{ $a->catatan ?? '-' }}</td>
                                            <td>
                                                <form action="{{ route('admin.asisten-lab.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus asisten lab ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada asisten laboratorium terdaftar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $assistants->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Penugasan MK -->
        <div class="tab-pane fade" id="penugasan-mk">
            <div class="row row-cards">
                <!-- Assign Form -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title"><i class="fas fa-link me-2"></i>Tugaskan Mata Kuliah</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.asisten-lab.assign') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label required">Asisten Lab</label>
                                    <select name="asisten_lab_id" class="form-select" required>
                                        <option value="">-- Pilih Asisten --</option>
                                        @foreach($assistants as $a)
                                            <option value="{{ $a->id }}">{{ $a->user->name ?? '-' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Mata Kuliah Praktikum</label>
                                    <select name="mata_kuliah_id" class="form-select" required>
                                        <option value="">-- Pilih MK --</option>
                                        @foreach($mataKuliahs as $mk)
                                            <option value="{{ $mk->id }}">{{ $mk->code }} - {{ $mk->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Tahun Akademik</label>
                                    <select name="tahun_akademik_id" class="form-select" required>
                                        <option value="">-- Pilih Semester --</option>
                                        @foreach($tahunAkademiks as $ta)
                                            <option value="{{ $ta->id }}">{{ $ta->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success w-100"><i class="fas fa-save me-2"></i>Simpan Penugasan</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Assignment Table -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Penugasan Praktikum</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Asisten</th>
                                        <th>Mata Kuliah</th>
                                        <th>Semester</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $count = 1; @endphp
                                    @forelse($assistants as $a)
                                        @foreach($a->assignments as $asg)
                                            <tr>
                                                <td>{{ $count++ }}</td>
                                                <td><strong>{{ $a->user->name ?? '-' }}</strong></td>
                                                <td>{{ $asg->mataKuliah->name ?? '-' }} ({{ $asg->mataKuliah->code ?? '' }})</td>
                                                <td>{{ $asg->tahunAkademik->name ?? '-' }}</td>
                                                <td>
                                                    <form action="{{ route('admin.asisten-lab.assign-destroy', $asg->id) }}" method="POST" onsubmit="return confirm('Hapus penugasan MK praktikum ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada penugasan praktikum.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
