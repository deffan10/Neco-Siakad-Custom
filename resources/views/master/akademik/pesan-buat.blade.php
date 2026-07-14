@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kirim pesan kepada operator atau pengguna lain dalam sistem.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.pesan.masuk') }}"><i class="fas fa-inbox me-1"></i> Pesan Masuk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.pesan.buat') }}"><i class="fas fa-edit me-1"></i> Buat Pesan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.pesan.terkirim') }}"><i class="fas fa-paper-plane me-1"></i> Pesan Terkirim</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Form Kirim Pesan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pesan.kirim') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label required">Kirim Kepada</label>
                    <select name="penerima_id" class="form-select" required>
                        <option value="">-- Pilih Penerima --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('penerima_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('penerima_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label required">Subjek</label>
                    <input type="text" name="subjek" class="form-control" placeholder="Subjek pesan..." value="{{ old('subjek') }}" required>
                    @error('subjek')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label required">Isi Pesan</label>
                    <textarea name="isi" class="form-control" rows="6" placeholder="Tulis pesan di sini..." required>{{ old('isi') }}</textarea>
                    @error('isi')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="text-end">
                    <a href="{{ route('admin.pesan.masuk') }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
