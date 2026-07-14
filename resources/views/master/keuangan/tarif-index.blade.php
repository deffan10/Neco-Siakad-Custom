@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Atur komponen dan tarif biaya kuliah berdasarkan program studi dan angkatan.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.keuangan.tagihan-index') }}"><i class="fas fa-file-invoice-dollar me-1"></i> Data Tagihan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.keuangan.pembayaran-index') }}"><i class="fas fa-receipt me-1"></i> Verifikasi Pembayaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.keuangan.tarif-index') }}"><i class="fas fa-cogs me-1"></i> Konfigurasi Tarif & Komponen</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Tambah Komponen Biaya -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Komponen Biaya</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.keuangan.komponen-store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Komponen</label>
                            <input type="text" class="form-control" name="name" placeholder="Contoh: SPP Variabel" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kode Komponen</label>
                            <input type="text" class="form-control" name="code" placeholder="Contoh: SPP_VAR" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nominal Default (Rp)</label>
                            <input type="number" class="form-control" name="default_amount" value="0" min="0" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Komponen</button>
                    </form>
                </div>
            </div>

            <!-- Tambah Tarif Biaya -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Atur Tarif Per Prodi & Angkatan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.keuangan.tarif-store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Komponen Biaya</label>
                            <select class="form-select" name="komponen_id" required>
                                <option value="">-- Pilih Komponen --</option>
                                @foreach($komponens as $k)
                                    <option value="{{ $k->id }}">{{ $k->name }} ({{ $k->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program Studi</label>
                            <select class="form-select" name="program_studi_id" required>
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach($programStudis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->name }} ({{ $prodi->jenjang }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Angkatan</label>
                            <input type="number" class="form-control" name="angkatan" value="{{ date('Y') }}" min="2000" max="2100" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nominal Tarif (Rp)</label>
                            <input type="number" class="form-control" name="nominal" min="0" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan Tarif</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Komponen & Tarif Biaya -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Komponen & Tarif Biaya Kuliah</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Komponen</th>
                                <th>Kode</th>
                                <th>Default</th>
                                <th>Tarif Spesifik (Prodi & Angkatan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($komponens as $k)
                                <tr>
                                    <td><strong>{{ $k->name }}</strong></td>
                                    <td><span class="badge bg-secondary">{{ $k->code }}</span></td>
                                    <td>Rp {{ number_format($k->default_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($k->tarifs->isEmpty())
                                            <span class="text-muted italic">Belum ada tarif spesifik. Menggunakan nominal default.</span>
                                        @else
                                            <ul class="list-unstyled mb-0">
                                                @foreach($k->tarifs as $t)
                                                    <li class="mb-1">
                                                        <span class="text-primary font-weight-bold">{{ $t->programStudi->name }} ({{ $t->angkatan }})</span>: 
                                                        <strong>Rp {{ number_format($t->nominal, 0, ',', '.') }}</strong>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada komponen biaya.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
