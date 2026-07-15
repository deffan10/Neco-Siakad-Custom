@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Cetak KRS, Kartu Ujian Mahasiswa, serta Berkas Absensi Kuliah & Ujian Dosen.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- 1. Cetak KRS -->
        <div class="col-md-6">
            <div class="card card-stacked">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-file-invoice me-2"></i>Cetak KRS Mahasiswa</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cetak-kartu.krs') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label class="form-label required">Mahasiswa</label>
                            <select name="mahasiswa_id" class="form-select" required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $m)
                                    <option value="{{ $m->id }}">{{ $m->username }} - {{ $m->name }}</option>
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
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-print me-2"></i>Cetak KRS
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 2. Cetak Kartu Ujian -->
        <div class="col-md-6">
            <div class="card card-stacked">
                <div class="card-header bg-warning text-white">
                    <h3 class="card-title"><i class="fas fa-id-card me-2"></i>Cetak Kartu Ujian (UTS / UAS)</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cetak-kartu.ujian') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label class="form-label required">Mahasiswa</label>
                            <select name="mahasiswa_id" class="form-select" required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $m)
                                    <option value="{{ $m->id }}">{{ $m->username }} - {{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label required">Tahun Akademik</label>
                                <select name="tahun_akademik_id" class="form-select" required>
                                    <option value="">-- Semester --</option>
                                    @foreach($tahunAkademiks as $ta)
                                        <option value="{{ $ta->id }}">{{ $ta->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label required">Jenis Ujian</label>
                                <select name="jenis_ujian" class="form-select" required>
                                    <option value="UTS">UTS</option>
                                    <option value="UAS">UAS</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 text-white">
                            <i class="fas fa-print me-2"></i>Cetak Kartu Ujian
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 3. Cetak Absensi Kuliah -->
        <div class="col-md-6">
            <div class="card card-stacked">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title"><i class="fas fa-clipboard-check me-2"></i>Cetak Lembar Absensi Kuliah</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cetak-kartu.absensi-kuliah') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label class="form-label required">Kelas Perkuliahan</label>
                            <select name="kelas_id" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasPerkuliahans as $kp)
                                    <option value="{{ $kp->id }}">
                                        [{{ $kp->programStudi->nama_singkat ?? $kp->programStudi->name ?? '-' }}] {{ $kp->mataKuliah->name ?? '-' }} ({{ $kp->nama_kelas }}) - {{ $kp->tahunAkademik->name ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-print me-2"></i>Cetak Absensi Kuliah
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 4. Cetak Absensi Ujian -->
        <div class="col-md-6">
            <div class="card card-stacked">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title"><i class="fas fa-file-signature me-2"></i>Cetak Absensi Ujian (UTS / UAS)</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cetak-kartu.absensi-ujian') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label class="form-label required">Kelas Perkuliahan</label>
                            <select name="kelas_id" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasPerkuliahans as $kp)
                                    <option value="{{ $kp->id }}">
                                        [{{ $kp->programStudi->nama_singkat ?? $kp->programStudi->name ?? '-' }}] {{ $kp->mataKuliah->name ?? '-' }} ({{ $kp->nama_kelas }}) - {{ $kp->tahunAkademik->name ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Jenis Ujian</label>
                            <select name="jenis_ujian" class="form-select" required>
                                <option value="UTS">UTS</option>
                                <option value="UAS">UAS</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-print me-2"></i>Cetak Absensi Ujian
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
