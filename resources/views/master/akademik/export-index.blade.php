@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Unduh data dan laporan sistem dalam format Excel (.xlsx) atau PDF.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">

        {{-- ====== MAHASISWA ====== --}}
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header bg-blue text-white">
                    <h3 class="card-title">
                        <i class="fas fa-user-graduate me-2"></i> Data Mahasiswa
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.export.mahasiswa-excel') }}" method="GET" class="mb-2">
                        <div class="mb-2">
                            <label class="form-label small">Filter Prodi</label>
                            <select name="prodi_id" class="form-select form-select-sm">
                                <option value="">Semua Program Studi</option>
                                @foreach($prodis as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Filter Angkatan</label>
                            <select name="angkatan" class="form-select form-select-sm">
                                <option value="">Semua Angkatan</option>
                                @foreach($angkatans as $a)
                                    <option value="{{ $a }}">{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                    </form>
                    <form action="{{ route('admin.export.mahasiswa-pdf') }}" method="GET">
                        <div class="mb-2">
                            <select name="prodi_id" class="form-select form-select-sm">
                                <option value="">Semua Program Studi</option>
                                @foreach($prodis as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <select name="angkatan" class="form-select form-select-sm">
                                <option value="">Semua Angkatan</option>
                                @foreach($angkatans as $a)
                                    <option value="{{ $a }}">{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ====== KEUANGAN ====== --}}
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header bg-green text-white">
                    <h3 class="card-title">
                        <i class="fas fa-money-bill-wave me-2"></i> Rekap Keuangan
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.export.keuangan-excel') }}" method="GET">
                        <div class="mb-2">
                            <label class="form-label small">Filter Tahun Akademik</label>
                            <select name="tahun_akademik_id" class="form-select form-select-sm">
                                <option value="">Semua Tahun Akademik</option>
                                @foreach($tahunAkademiks as $ta)
                                    <option value="{{ $ta->id }}">{{ $ta->name }} ({{ $ta->semester }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Filter Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="Belum Lunas">Belum Lunas</option>
                                <option value="Lunas">Lunas</option>
                                <option value="Cicilan">Cicilan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-file-excel me-1"></i> Export Tagihan Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ====== JURNAL DOSEN ====== --}}
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header bg-purple text-white">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher me-2"></i> Jurnal Mengajar Dosen
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.export.jurnal-excel') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label small">Filter Tahun Akademik</label>
                            <select name="tahun_akademik_id" class="form-select form-select-sm">
                                <option value="">Semua Tahun Akademik</option>
                                @foreach($tahunAkademiks as $ta)
                                    <option value="{{ $ta->id }}">{{ $ta->name }} ({{ $ta->semester }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-file-excel me-1"></i> Export Jurnal Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ====== PESERTA WISUDA ====== --}}
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header bg-yellow text-white">
                    <h3 class="card-title">
                        <i class="fas fa-graduation-cap me-2"></i> Peserta Wisuda
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.export.wisuda-excel') }}" method="GET">
                        <div class="mb-2">
                            <label class="form-label small">Pilih Kegiatan Wisuda</label>
                            <select name="kegiatan_wisuda_id" class="form-select form-select-sm">
                                <option value="">Semua Kegiatan Wisuda</option>
                                @foreach($kegiatanWisudas as $kw)
                                    <option value="{{ $kw->id }}">{{ $kw->nama_wisuda }} — {{ \Carbon\Carbon::parse($kw->tanggal_pelaksanaan)->format('d M Y') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Filter Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="Menunggu">Menunggu</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-file-excel me-1"></i> Export Wisuda Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ====== PMB ====== --}}
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header bg-cyan text-white">
                    <h3 class="card-title">
                        <i class="fas fa-door-open me-2"></i> Data Pendaftar PMB
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.export.pmb-excel') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label small">Filter Tahun Masuk</label>
                            <select name="tahun_masuk" class="form-select form-select-sm">
                                <option value="">Semua Tahun</option>
                                @foreach($angkatans as $a)
                                    <option value="{{ $a }}">{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-file-excel me-1"></i> Export PMB Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ====== SERTIFIKASI ====== --}}
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-header bg-orange text-white">
                    <h3 class="card-title">
                        <i class="fas fa-certificate me-2"></i> Rekap Sertifikasi (SKPI)
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.export.sertifikasi-excel') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label small">Filter Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="Menunggu">Menunggu</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-file-excel me-1"></i> Export Sertifikasi Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
