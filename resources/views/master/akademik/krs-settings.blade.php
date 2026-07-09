@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Konfigurasi rentang waktu KRS Online dan syarat pengambilan jumlah SKS maksimal mahasiswa berdasarkan IP Semester.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Pengaturan Waktu KRS -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Jadwal KRS Online</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.krs.waktu-store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tahun Akademik / Semester</label>
                            <select class="form-select" name="tahun_akademik_id" required>
                                @foreach($tahunAkademiks as $ta)
                                    <option value="{{ $ta->id }}" {{ $ta->is_active ? 'selected' : '' }}>
                                        {{ $ta->name }} ({{ $ta->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai KRS</label>
                            <input type="datetime-local" class="form-control" name="tanggal_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai KRS</label>
                            <input type="datetime-local" class="form-control" name="tanggal_selesai" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Jadwal KRS</button>
                    </form>
                </div>
            </div>

            <!-- Batas Syarat SKS -->
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">Batas Syarat SKS Maksimum</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.krs.syarat-store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">IP Minimum</label>
                                <input type="number" step="0.01" class="form-control" name="ip_min" min="0" max="4.00" placeholder="0.00" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">IP Maksimum</label>
                                <input type="number" step="0.01" class="form-control" name="ip_max" min="0" max="4.00" placeholder="4.00" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Beban SKS Maksimum</label>
                            <input type="number" class="form-control" name="max_sks" min="1" max="24" placeholder="24" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan Batas SKS</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Waktu & Aturan SKS -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aturan Pengambilan SKS Aktif</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Rentang IP Semester</th>
                                <th>Max SKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($syaratSks as $s)
                                <tr>
                                    <td><strong>{{ $s->ip_min }} - {{ $s->ip_max }}</strong></td>
                                    <td><span class="badge bg-green">{{ $s->max_sks }} SKS</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Belum ada aturan batas SKS.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Jadwal Pembukaan KRS</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Semester</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($waktuKrs as $w)
                                <tr>
                                    <td>{{ $w->tahunAkademik->name }}</td>
                                    <td>{{ Carbon\Carbon::parse($w->tanggal_mulai)->format('d/m/Y H:i') }}</td>
                                    <td>{{ Carbon\Carbon::parse($w->tanggal_selesai)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($w->is_active)
                                            <span class="badge bg-blue">Aktif</span>
                                        @else
                                            <span class="text-muted">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada jadwal KRS terdaftar.</td>
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
