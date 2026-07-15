@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Salin data penerima beasiswa dari semester sebelumnya ke semester baru.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.beasiswa.data') }}"><i class="fas fa-users me-1"></i> Data Penerima</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.beasiswa.jenis') }}"><i class="fas fa-tags me-1"></i> Jenis Beasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.beasiswa.salin') }}"><i class="fas fa-copy me-1"></i> Salin Beasiswa</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title"><i class="fas fa-copy me-2"></i> Salin Data Beasiswa Antar Semester</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Info:</strong> Fitur ini akan menyalin semua data penerima beasiswa dari semester asal ke semester tujuan. Data yang sudah ada tidak akan disalin ulang.
                    </div>
                    <form action="{{ route('admin.beasiswa.salin-process') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Dari Semester</label>
                            <select name="dari_tahun_akademik_id" class="form-select" required>
                                <option value="">-- Pilih Semester Asal --</option>
                                @foreach($tahunAkademiks as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Ke Semester</label>
                            <select name="ke_tahun_akademik_id" class="form-select" required>
                                <option value="">-- Pilih Semester Tujuan --</option>
                                @foreach($tahunAkademiks as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Yakin ingin menyalin data beasiswa?')">
                            <i class="fas fa-copy me-2"></i> Proses Salin Beasiswa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
