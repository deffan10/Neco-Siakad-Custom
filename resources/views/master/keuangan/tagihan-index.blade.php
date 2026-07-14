@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola tagihan mahasiswa per semester atau lakukan generate tagihan secara massal.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.keuangan.tagihan-index') }}"><i class="fas fa-file-invoice-dollar me-1"></i> Data Tagihan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.keuangan.pembayaran-index') }}"><i class="fas fa-receipt me-1"></i> Verifikasi Pembayaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.keuangan.tarif-index') }}"><i class="fas fa-cogs me-1"></i> Konfigurasi Tarif & Komponen</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Panel Kontrol (Generate Massal & Manual) -->
    <div class="row row-cards mt-3">
        <!-- Generate Tagihan Massal -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Generate Tagihan Massal (Rutin)</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.keuangan.tagihan-generate-massal') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Semester Aktif</label>
                                <select class="form-select" name="tahun_akademik_id" required>
                                    @foreach($tahunAkademiks as $ta)
                                        <option value="{{ $ta->id }}" {{ $ta->is_active ? 'selected' : '' }}>
                                            {{ $ta->name }} ({{ $ta->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Program Studi</label>
                                <select class="form-select" name="program_studi_id" required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach(\App\Models\Akademik\ProgramStudi::orderBy('name')->get() as $prodi)
                                        <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Angkatan Mahasiswa</label>
                            <input type="number" class="form-control" name="angkatan" value="{{ date('Y') }}" min="2000" max="2100" required>
                            <small class="text-muted">Tagihan otomatis dihitung berdasarkan total Tarif Biaya yang dikonfigurasi untuk prodi & angkatan terpilih.</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Generate Tagihan Massal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Buat Tagihan Manual -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">Buat Tagihan Manual (Per Mahasiswa)</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.keuangan.tagihan-store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mahasiswa</label>
                                <select class="form-select" name="user_id" required>
                                    <option value="">-- Pilih Mahasiswa --</option>
                                    @foreach($mahasiswas as $mhs)
                                        <option value="{{ $mhs->id }}">{{ $mhs->name }} ({{ $mhs->username }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Semester</label>
                                <select class="form-select" name="tahun_akademik_id" required>
                                    @foreach($tahunAkademiks as $ta)
                                        <option value="{{ $ta->id }}" {{ $ta->is_active ? 'selected' : '' }}>
                                            {{ $ta->name }} ({{ $ta->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Tagihan (Rp)</label>
                            <input type="number" class="form-control" name="total_tagihan" placeholder="Contoh: 1500000" min="0" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan Tagihan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Tabel Tagihan -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Daftar Tagihan Mahasiswa</h3>
            <div class="card-actions">
                <form action="" method="GET" class="d-flex gap-2">
                    <select class="form-select w-auto" name="status" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Belum Lunas" {{ request('status') === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="Lunas" {{ request('status') === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Kurang Bayar" {{ request('status') === 'Kurang Bayar' ? 'selected' : '' }}>Kurang Bayar</option>
                    </select>
                    <input type="text" class="form-control" name="search" placeholder="Cari Mahasiswa..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Semester</th>
                        <th>Total Tagihan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihans as $t)
                        <tr>
                            <td>{{ $t->mahasiswa->username }}</td>
                            <td><strong>{{ $t->mahasiswa->name }}</strong></td>
                            <td>{{ $t->tahunAkademik->name }}</td>
                            <td>Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</td>
                            <td>
                                @if($t->status === 'Lunas')
                                    <span class="badge bg-success-lt">Lunas</span>
                                @elseif($t->status === 'Kurang Bayar')
                                    <span class="badge bg-warning-lt">Kurang Bayar</span>
                                @else
                                    <span class="badge bg-danger-lt">Belum Lunas</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.user-view', $t->mahasiswa->id) }}" class="btn btn-sm btn-outline-primary">Profil</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada tagihan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tagihans->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $tagihans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
