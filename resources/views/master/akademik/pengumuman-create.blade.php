@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col"><h2 class="page-title">{{ $pages }}</h2></div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3"><div class="card-body py-2">
        <ul class="nav nav-tabs border-0">
            <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('admin.pengumuman.index') }}"><i class="fas fa-bullhorn me-1"></i> Daftar Pengumuman</a></li>
            <li class="nav-item"><a class="nav-link active fw-bold" href="{{ route('admin.pengumuman.create') }}"><i class="fas fa-plus-circle me-1"></i> Tambah Pengumuman</a></li>
            <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('admin.pengumuman.kategori') }}"><i class="fas fa-folder me-1"></i> Kategori</a></li>
        </ul>
    </div></div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white"><h3 class="card-title">Form Pengumuman Baru</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.pengumuman.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Judul</label>
                            <input type="text" name="judul" class="form-control" placeholder="Judul pengumuman..." required value="{{ old('judul') }}">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Target Penerima</label>
                                <select name="target" class="form-select" required>
                                    <option value="Semua">Semua Pengguna</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                    <option value="Dosen">Dosen</option>
                                    <option value="Operator">Operator</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_id" class="form-select">
                                    <option value="">-- Tanpa Kategori --</option>
                                    @foreach($kategoris as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Isi Pengumuman</label>
                            <textarea name="isi" class="form-control" rows="8" required>{{ old('isi') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-check"><input type="checkbox" name="is_published" class="form-check-input" checked> Langsung Diterbitkan</label>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Terbitkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
