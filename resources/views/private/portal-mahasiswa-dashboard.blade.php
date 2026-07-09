@extends('themes.core-backpage')

@push('styles')
<style>
.progress-card {
    border-radius: 12px;
}
.dashboard-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.timeline-item {
    padding-left: 20px;
    border-left: 2px solid #e5e7eb;
    position: relative;
    padding-bottom: 16px;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 4px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #1a56db;
}
</style>
@endpush

@section('content')
<div class="container-xl">
    {{-- Welcome Card --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <span class="avatar avatar-lg rounded-circle me-3" style="background-image: url({{ $user->photo }})"></span>
                <div>
                    <h3 class="mb-1">Halo, {{ $user->name }}!</h3>
                    <div class="text-muted">NIM: {{ $user->dataMahasiswa?->nim ?? '-' }} &bull; {{ $user->dataMahasiswa?->programStudi?->name ?? '-' }}</div>
                </div>
                <div class="ms-auto d-none d-md-block">
                    <span class="badge bg-blue-lt py-2 px-3">{{ date('l, d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row row-cards row-deck mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <div class="subheader text-muted">Indeks Prestasi Kumulatif (IPK)</div>
                        <div class="h1 mb-0">{{ number_format($ipk, 2) }}</div>
                    </div>
                    <div class="ms-auto dashboard-stat-icon bg-blue-lt">
                        <i class="fas fa-graduation-cap fa-lg text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <div class="subheader text-muted">SKS Lulus</div>
                        <div class="h1 mb-0">{{ $sksLulus }} <span class="text-muted h4">/ {{ $sksTotal }}</span></div>
                    </div>
                    <div class="ms-auto dashboard-stat-icon bg-green-lt">
                        <i class="fas fa-check-circle fa-lg text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <div class="subheader text-muted">Tagihan Aktif</div>
                        <div class="h1 mb-0 text-danger">Rp {{ number_format($tagihanAktif, 0, ',', '.') }}</div>
                    </div>
                    <div class="ms-auto dashboard-stat-icon bg-red-lt">
                        <i class="fas fa-wallet fa-lg text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <div class="subheader text-muted">Status Wisuda</div>
                        <div class="h1 mb-0" style="font-size: 15px;">
                            @if($wisudaAktif)
                                <span class="badge bg-{{ $wisudaAktif->status === 'Disetujui' ? 'success' : ($wisudaAktif->status === 'Ditolak' ? 'danger' : 'warning') }}">
                                    {{ $wisudaAktif->status }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Belum Daftar</span>
                            @endif
                        </div>
                    </div>
                    <div class="ms-auto dashboard-stat-icon bg-yellow-lt">
                        <i class="fas fa-award fa-lg text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards">
        {{-- Progress SKS & IPK Trend --}}
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Progress Kelulusan (Target {{ $sksTotal }} SKS)</h3>
                </div>
                <div class="card-body">
                    @php
                        $percentage = ($sksTotal > 0) ? min(100, round(($sksLulus / $sksTotal) * 100)) : 0;
                    @endphp
                    <div class="progress progress-lg mb-2">
                        <div class="progress-bar bg-success" style="width: {{ $percentage }}%" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $percentage }}%
                        </div>
                    </div>
                    <p class="text-muted small">Anda telah menyelesaikan <strong>{{ $sksLulus }} SKS</strong> dari total kewajiban kelulusan minimum <strong>{{ $sksTotal }} SKS</strong>.</p>
                </div>
            </div>

            {{-- Jadwal Kelas Hari Ini --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jadwal Perkuliahan Hari Ini</h3>
                </div>
                <div class="card-body">
                    @forelse($jadwalHariIni as $j)
                        <div class="timeline-item">
                            <div class="fw-bold">{{ $j->jadwalPerkuliahan?->mataKuliah?->name ?? 'Mata Kuliah' }}</div>
                            <div class="text-muted small">
                                <i class="fas fa-clock me-1"></i> {{ substr($j->jam_mulai,0,5) }} - {{ substr($j->jam_selesai,0,5) }} &bull;
                                <i class="fas fa-map-marker-alt me-1"></i> Ruang: {{ $j->ruangan ?? 'N/A' }}
                            </div>
                            <div class="text-muted small">
                                <i class="fas fa-user-tie me-1"></i> Dosen: {{ $j->jadwalPerkuliahan?->dosen?->name ?? '-' }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-check fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">Tidak ada jadwal kelas untuk hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Side Cards: Logs and Alerts --}}
        <div class="col-lg-4">
            {{-- Notifikasi Terbaru --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Pemberitahuan Terbaru</h3>
                    <a href="{{ route('notifications.index') }}" class="small">Semua</a>
                </div>
                <div class="list-group list-group-flush list-group-hoverable">
                    @forelse($logNotif as $n)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="status-dot bg-{{ $n->tipe }} d-block"></span>
                                </div>
                                <div class="col text-truncate">
                                    <a href="{{ route('notifications.read', $n->id) }}" class="text-body d-block font-weight-bold">
                                        {{ $n->judul }}
                                    </a>
                                    <div class="text-secondary text-truncate mt-1 small">{{ $n->pesan }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4 small">Tidak ada pemberitahuan baru.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
