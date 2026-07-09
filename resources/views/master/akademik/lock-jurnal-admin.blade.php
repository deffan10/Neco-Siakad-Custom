@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kunci jurnal mengajar dosen dan presensi kelas agar tidak dapat dimodifikasi setelah tanggal tertentu.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Penguncian Massal (Bulk Lock) -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title">Penguncian Massal (Bulk Lock)</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.lock-jurnal.bulk') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menerapkan penguncian/pembukaan massal pada rentang tanggal tersebut?')">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Batas Tanggal Pertemuan</label>
                            <input type="date" class="form-control" name="tanggal_batas" required>
                            <small class="text-muted">Aksi akan diterapkan pada semua pertemuan yang berlangsung pada atau sebelum tanggal ini.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Aksi Penguncian</label>
                            <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column gap-2">
                                <label class="form-selectgroup-item flex-fill">
                                    <input type="radio" name="action" value="lock" class="form-selectgroup-input" checked>
                                    <div class="form-selectgroup-label d-flex align-items-center p-2">
                                        <div class="me-3">
                                            <span class="form-selectgroup-check"></span>
                                        </div>
                                        <div><i class="fas fa-lock text-danger me-1"></i> Kunci Jurnal Mengajar</div>
                                    </div>
                                </label>
                                <label class="form-selectgroup-item flex-fill">
                                    <input type="radio" name="action" value="unlock" class="form-selectgroup-input">
                                    <div class="form-selectgroup-label d-flex align-items-center p-2">
                                        <div class="me-3">
                                            <span class="form-selectgroup-check"></span>
                                        </div>
                                        <div><i class="fas fa-lock-open text-success me-1"></i> Buka Kunci Jurnal</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Jalankan Aksi Massal</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar & Filter Pertemuan Kuliah -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Daftar Pertemuan & Jurnal Mengajar</h3>
                </div>
                <div class="card-body border-bottom py-3">
                    <form action="" method="GET" class="d-flex align-items-center gap-2">
                        <div class="w-40">
                            <input type="text" class="form-control" name="search" placeholder="Cari nama dosen atau matakuliah..." value="{{ request('search') }}">
                        </div>
                        <div class="w-30">
                            <select class="form-select" name="status">
                                <option value="">Semua Status Kunci</option>
                                <option value="kunci" {{ request('status') === 'kunci' ? 'selected' : '' }}>Dikunci (Locked)</option>
                                <option value="buka" {{ request('status') === 'buka' ? 'selected' : '' }}>Terbuka (Unlocked)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Kelas & Matakuliah</th>
                                <th>Dosen</th>
                                <th>Pertemuan & Tanggal</th>
                                <th>Jurnal / Materi</th>
                                <th>Status Kunci</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pertemuans as $p)
                                <tr>
                                    <td>
                                        <div><strong>{{ $p->jadwal->mataKuliah->name }}</strong></div>
                                        <div class="text-muted small">Kelas: {{ $p->jadwal->code }}</div>
                                    </td>
                                    <td>
                                        <div class="small"><strong>{{ $p->dosen->name ?? ($p->jadwal->dosen->name ?? 'N/A') }}</strong></div>
                                    </td>
                                    <td>
                                        <div class="small">Pertemuan Ke-{{ $p->pertemuan_ke }}</div>
                                        <div class="text-muted extra-small">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }} ({{ substr($p->jam_mulai, 0, 5) }} - {{ substr($p->jam_selesai, 0, 5) }})</div>
                                    </td>
                                    <td>
                                        @if($p->materi)
                                            <span class="text-muted small" title="{{ $p->materi }}">{{ Str::limit($p->materi, 40) }}</span>
                                        @else
                                            <span class="text-danger small"><em>Belum diisi</em></span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.lock-jurnal.toggle', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $p->is_locked ? 'btn-danger' : 'btn-outline-success' }} w-100">
                                                @if($p->is_locked)
                                                    <i class="fas fa-lock me-1"></i> Locked
                                                @else
                                                    <i class="fas fa-lock-open me-1"></i> Unlocked
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data pertemuan kuliah yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center">
                    {{ $pertemuans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
