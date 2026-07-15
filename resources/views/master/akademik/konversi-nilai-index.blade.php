@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Konversi nilai mahasiswa pindahan/transfer dari perguruan tinggi asal ke program studi saat ini.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="#list-konversi" data-bs-toggle="tab">
                        <i class="fas fa-exchange-alt me-1"></i> Konversi Nilai
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="#impor-data" data-bs-toggle="tab">
                        <i class="fas fa-file-import me-1"></i> Impor Data
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content mt-3">
        <!-- Tab 1: List & Input Konversi -->
        <div class="tab-pane fade show active" id="list-konversi">
            <!-- Filter & Search -->
            <div class="card mb-3">
                <div class="card-body py-2">
                    <form method="GET" class="d-flex gap-2 flex-wrap">
                        <input type="text" name="search" class="form-control" style="max-width:250px" placeholder="Cari MK Asal / NIM / Nama..." value="{{ request('search') }}">
                        <select name="mahasiswa_id" class="form-select" style="max-width:250px">
                            <option value="">-- Semua Mahasiswa --</option>
                            @foreach($mahasiswas as $m)
                                <option value="{{ $m->id }}" {{ request('mahasiswa_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->username }} - {{ $m->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        <a href="{{ route('admin.konversi-nilai.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </form>
                </div>
            </div>

            <div class="row row-cards">
                <!-- Form Entri -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title"><i class="fas fa-plus me-2"></i>Entri Konversi Nilai</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.konversi-nilai.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label required">Mahasiswa Transfer</label>
                                    <select name="mahasiswa_id" class="form-select" required>
                                        <option value="">-- Pilih Mahasiswa --</option>
                                        @foreach($mahasiswas as $m)
                                            <option value="{{ $m->id }}">{{ $m->username }} - {{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-section border p-3 rounded mb-3 bg-light-muted">
                                    <h6 class="text-primary"><i class="fas fa-university me-1"></i>Asal (PT Asal)</h6>
                                    <div class="mb-2">
                                        <label class="form-label required">Mata Kuliah Asal</label>
                                        <input type="text" name="mata_kuliah_asal" class="form-control" placeholder="cth: Kalkulus Dasar" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <label class="form-label required">SKS Asal</label>
                                            <input type="number" name="sks_asal" class="form-control" min="1" max="10" required>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label required">Nilai Asal</label>
                                            <input type="text" name="nilai_asal" class="form-control" placeholder="cth: A-" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section border p-3 rounded mb-3 bg-light-muted">
                                    <h6 class="text-success"><i class="fas fa-check-circle me-1"></i>Tujuan (Ekuivalensi)</h6>
                                    <div class="mb-2">
                                        <label class="form-label required">Mata Kuliah Tujuan</label>
                                        <select name="mata_kuliah_id" class="form-select" required>
                                            <option value="">-- Pilih Mata Kuliah --</option>
                                            @foreach($mataKuliahs as $mk)
                                                <option value="{{ $mk->id }}">{{ $mk->code }} - {{ $mk->name }} ({{ $mk->sks }} SKS)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <label class="form-label required">SKS Konversi</label>
                                            <input type="number" name="sks_konversi" class="form-control" min="1" max="10" required>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label required">Nilai Konversi</label>
                                            <select name="nilai_konversi" class="form-select" required>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                                <option value="E">E</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan transfer nilai..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i>Simpan Konversi</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Table View -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Konversi Nilai ({{ $konversiNilai->total() }} data)</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mahasiswa</th>
                                        <th>MK PT Asal</th>
                                        <th>MK Ekuivalensi</th>
                                        <th>Nilai Konversi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($konversiNilai as $k)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $k->mahasiswa->name ?? '-' }}</strong><br>
                                                <small class="text-muted">{{ $k->mahasiswa->username ?? '' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $k->mata_kuliah_asal }}</span><br>
                                                <small class="text-muted">{{ $k->sks_asal }} SKS | Nilai: {{ $k->nilai_asal }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $k->mataKuliah->name ?? '-' }}</strong><br>
                                                <small class="text-muted">{{ $k->mataKuliah->code ?? '' }} | {{ $k->sks_konversi }} SKS</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $k->nilai_konversi }}</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.konversi-nilai.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus data konversi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Belum ada data konversi nilai.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $konversiNilai->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Impor Data -->
        <div class="tab-pane fade" id="impor-data">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title text-white"><i class="fas fa-file-import me-2"></i>Impor Konversi Nilai (Excel)</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>Info:</strong> Fitur impor massal konversi nilai menggunakan template Excel standard.
                            </div>
                            <form action="#" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label required">File Excel (.xlsx, .xls)</label>
                                    <input type="file" name="file" class="form-control" required disabled>
                                </div>
                                <button type="button" class="btn btn-warning w-100" disabled>
                                    <i class="fas fa-upload me-2"></i>Unggah & Impor (Segera Hadir)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
