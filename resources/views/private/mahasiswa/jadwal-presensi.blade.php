@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <!-- Header -->
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Lihat jadwal kelas perkuliahan Anda dan pantau persentase kehadiran Anda secara realtime.</div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="card mt-3">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#tab-summary" class="nav-link active" data-bs-toggle="tab">
                        <i class="fas fa-chart-pie me-1"></i> Ringkasan Presensi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tab-schedule" class="nav-link" data-bs-toggle="tab">
                        <i class="fas fa-calendar-alt me-1"></i> Jadwal Mingguan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#tab-history" class="nav-link" data-bs-toggle="tab">
                        <i class="fas fa-history me-1"></i> Histori Pertemuan
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                
                <!-- Tab 1: Summary Statistics -->
                <div class="tab-pane active show" id="tab-summary">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Mata Kuliah</th>
                                    <th>Kelas</th>
                                    <th class="text-center">Total Pertemuan</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center">Sakit</th>
                                    <th class="text-center">Izin</th>
                                    <th class="text-center">Alpa</th>
                                    <th class="text-center">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats as $st)
                                    <tr>
                                        <td><strong>{{ $st['course'] }}</strong></td>
                                        <td><span class="badge bg-blue-lt">{{ $st['class'] }}</span></td>
                                        <td class="text-center">{{ $st['total_held'] }}</td>
                                        <td class="text-center text-success fw-bold">{{ $st['hadir'] }}</td>
                                        <td class="text-center text-info fw-bold">{{ $st['sakit'] }}</td>
                                        <td class="text-center text-warning fw-bold">{{ $st['izin'] }}</td>
                                        <td class="text-center text-danger fw-bold">{{ $st['alpa'] }}</td>
                                        <td class="text-center">
                                            @php
                                                $barColor = 'bg-success';
                                                if($st['percentage'] < 75) $barColor = 'bg-danger';
                                                elseif($st['percentage'] < 85) $barColor = 'bg-warning';
                                            @endphp
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <div class="progress progress-xs" style="width: 60px;">
                                                    <div class="progress-bar {{ $barColor }}" style="width: {{ $st['percentage'] }}%"></div>
                                                </div>
                                                <span class="fw-bold">{{ $st['percentage'] }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">Belum ada data mata kuliah terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 2: Weekly Schedule -->
                <div class="tab-pane" id="tab-schedule">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Mata Kuliah</th>
                                    <th>Kelas</th>
                                    <th>Dosen Pengampu</th>
                                    <th>Ruangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($timetable as $t)
                                    <tr>
                                        <td><strong>{{ $t['hari'] }}</strong></td>
                                        <td><span class="badge bg-purple-lt">{{ substr($t['jam_mulai'], 0, 5) }} - {{ substr($t['jam_selesai'], 0, 5) }}</span></td>
                                        <td><strong>{{ $t['mata_kuliah'] }}</strong></td>
                                        <td>{{ $t['kelas'] }}</td>
                                        <td>{{ $t['dosen'] }}</td>
                                        <td><span class="badge bg-green-lt">{{ $t['ruangan'] }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada jadwal kuliah minggu ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 3: Detailed History -->
                <div class="tab-pane" id="tab-history">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Mata Kuliah</th>
                                    <th>Pertemuan</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Topik / Rangkuman Jurnal</th>
                                    <th>Link Rekaman</th>
                                    <th class="text-center">Status Absensi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pertemuans as $p)
                                    @php
                                        $att = $attendanceLogs[$p->id] ?? null;
                                        $statusLabel = 'Belum Ada';
                                        $statusClass = 'bg-secondary-lt';
                                        if($att) {
                                            if($att->status === 'Hadir') { $statusLabel = 'Hadir'; $statusClass = 'bg-success'; }
                                            elseif($att->status === 'Sakit') { $statusLabel = 'Sakit'; $statusClass = 'bg-info'; }
                                            elseif($att->status === 'Izin') { $statusLabel = 'Izin'; $statusClass = 'bg-warning'; }
                                            elseif($att->status === 'Alpa') { $statusLabel = 'Alpa'; $statusClass = 'bg-danger'; }
                                        }
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $p->jadwal->mataKuliah->name }}</strong></td>
                                        <td><span class="badge bg-purple-lt">Ke-{{ $p->pertemuan_ke }}</span></td>
                                        <td>
                                            <div class="small">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</div>
                                            <div class="text-muted extra-small">{{ substr($p->jam_mulai, 0, 5) }} - {{ substr($p->jam_selesai, 0, 5) }}</div>
                                        </td>
                                        <td>
                                            @if($p->materi)
                                                <span class="text-muted small" title="{{ $p->materi }}">{{ Str::limit($p->materi, 50) }}</span>
                                            @else
                                                <span class="text-muted small"><em>-</em></span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->link)
                                                <a href="{{ $p->link }}" target="_blank" class="text-info small">
                                                    <i class="fas fa-external-link-alt"></i> Buka
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $statusClass }} text-white">{{ $statusLabel }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted small">{{ $att->catatan ?? '-' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat pertemuan kuliah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
