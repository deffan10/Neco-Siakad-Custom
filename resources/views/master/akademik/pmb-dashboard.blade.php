@extends('themes.core-backpage')

@push('styles')
<style>
.stat-card {
    border-left: 4px solid;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}
.stat-card.blue   { border-color: #1a56db; }
.stat-card.green  { border-color: #0ca678; }
.stat-card.cyan   { border-color: #17a2b8; }
.stat-card.red    { border-color: #d63939; }
.stat-card.yellow { border-color: #f59f00; }
.stat-card.gray   { border-color: #868e96; }
.chart-container  { position: relative; height: 260px; }
</style>
@endpush

@section('content')
<div class="container-xl">

    {{-- Page Header --}}
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Analitik dan statistik penerimaan mahasiswa baru secara real-time.</div>
            </div>
            <div class="col-auto">
                <form action="" method="GET" class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0 fw-bold text-muted">Filter Tahun:</label>
                    <select name="tahun" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ $selectedTahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- ============= STAT CARDS ============= --}}
    <div class="row row-deck row-cards mt-2">
        <div class="col-sm-6 col-lg-2">
            <div class="card stat-card blue">
                <div class="card-body">
                    <div class="subheader text-muted">Total Pendaftar</div>
                    <div class="h1 mb-0 text-primary">{{ number_format($totalPendaftar) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card stat-card yellow">
                <div class="card-body">
                    <div class="subheader text-muted">Menunggu</div>
                    <div class="h1 mb-0 text-warning">{{ number_format($totalMenunggu) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card stat-card green">
                <div class="card-body">
                    <div class="subheader text-muted">Lolos Seleksi</div>
                    <div class="h1 mb-0 text-success">{{ number_format($totalLolos) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card stat-card cyan">
                <div class="card-body">
                    <div class="subheader text-muted">Daftar Ulang</div>
                    <div class="h1 mb-0 text-cyan">{{ number_format($totalDaftarUlang) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card stat-card red">
                <div class="card-body">
                    <div class="subheader text-muted">Tidak Lolos</div>
                    <div class="h1 mb-0 text-danger">{{ number_format($totalTidakLolos) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="card stat-card gray">
                <div class="card-body">
                    <div class="subheader text-muted">Dibatalkan</div>
                    <div class="h1 mb-0 text-muted">{{ number_format($totalBatal) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============= CHARTS ROW 1 ============= --}}
    <div class="row row-cards mt-3">

        {{-- Status Pendaftaran (Doughnut) --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Pendaftar Berdasarkan Status</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jalur Masuk (Bar) --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Berdasarkan Jalur Masuk</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chartJalur"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jenis Kelamin (Doughnut) --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Berdasarkan Jenis Kelamin</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chartGender"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============= CHARTS ROW 2 ============= --}}
    <div class="row row-cards mt-3">

        {{-- Tren Pendaftaran Per Bulan --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tren Pendaftaran Per Bulan {{ $selectedTahun }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height:220px">
                        <canvas id="chartTren"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jenis Sekolah --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Berdasarkan Jenis Sekolah</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height:220px">
                        <canvas id="chartJenisSekolah"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============= TABEL REKAP ============= --}}
    <div class="row row-cards mt-3">

        {{-- Rekap per Prodi & Jalur --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rekap Pendaftar per Prodi & Jalur Masuk</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Program Studi (Pilihan 1)</th>
                                <th>Jalur Masuk</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapProdi as $prodi => $rows)
                                @foreach($rows as $i => $row)
                                <tr>
                                    @if($i === 0)
                                    <td rowspan="{{ $rows->count() }}" class="fw-bold text-primary">
                                        {{ $prodi ?: 'Belum Diisi' }}
                                    </td>
                                    @endif
                                    <td>
                                        <span class="badge bg-blue-lt">{{ $row->jalur_masuk }}</span>
                                    </td>
                                    <td><strong>{{ number_format($row->total) }}</strong></td>
                                </tr>
                                @endforeach
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada data pendaftaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Lolos Seleksi per Prodi --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top Prodi — Lolos Seleksi</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Prodi</th>
                                <th>Lolos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapLolosProdi as $row)
                            <tr>
                                <td>{{ $row->prodi ?: 'Belum Diisi' }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $row->total }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Tidak ada data.</td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Palette
    const COLORS = ['#1a56db','#0ca678','#f59f00','#d63939','#ae3ec9','#17a2b8','#868e96'];
    const COLORS_LIGHT = ['rgba(26,86,219,.18)','rgba(12,166,120,.18)','rgba(245,159,0,.18)','rgba(214,57,57,.18)'];

    function makeChart(id, type, labels, data, label = 'Jumlah') {
        const ctx = document.getElementById(id)?.getContext('2d');
        if (!ctx) return;
        new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: COLORS.slice(0, data.length),
                    borderColor: type === 'line' ? COLORS[0] : COLORS.slice(0, data.length),
                    borderWidth: type === 'doughnut' ? 2 : 1.5,
                    fill: type === 'line',
                    tension: 0.4,
                    borderRadius: type === 'bar' ? 6 : 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: type === 'doughnut' ? 'bottom' : 'top', labels: { boxWidth: 12, font: { size: 12 } } },
                },
                scales: type !== 'doughnut' ? {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                } : {}
            }
        });
    }

    // Render charts
    makeChart('chartStatus',
        'doughnut',
        @json($chartStatus['labels']),
        @json($chartStatus['data'])
    );

    makeChart('chartJalur',
        'bar',
        @json($chartJalur['labels']),
        @json($chartJalur['data']),
        'Pendaftar'
    );

    makeChart('chartGender',
        'doughnut',
        @json($chartGender['labels']),
        @json($chartGender['data'])
    );

    makeChart('chartTren',
        'line',
        @json($chartTren['labels']),
        @json($chartTren['data']),
        'Pendaftaran'
    );

    makeChart('chartJenisSekolah',
        'bar',
        @json($chartJenisSekolah['labels']),
        @json($chartJenisSekolah['data']),
        'Asal Sekolah'
    );
</script>
@endpush
