@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('admin.kuesioner.index') }}" class="btn btn-outline-secondary mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Manajemen Kuesioner
                </a>
                <h2 class="page-title">Hasil Survei: {{ $kuesioner->judul }}</h2>
                <div class="text-muted mt-1">Hasil analisis rekapitulasi data umpan balik responden dari total {{ $kuesioner->responses_count }} responden.</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="row row-cards mt-3">
        <div class="col-md-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">{{ $kuesioner->responses_count }} Orang</div>
                            <div class="text-muted">Total Partisipan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-purple text-white avatar">
                                <i class="fas fa-user-tag"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">{{ $kuesioner->target_responden }}</div>
                            <div class="text-muted">Target Responden</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-green text-white avatar">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">{{ \Carbon\Carbon::parse($kuesioner->tanggal_selesai)->format('d F Y') }}</div>
                            <div class="text-muted">Batas Akhir Pengisian</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-success text-white avatar">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">{{ $kuesioner->is_published ? 'Aktif / Published' : 'Draft / Non-Aktif' }}</div>
                            <div class="text-muted">Status Publikasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analisis Butir Pertanyaan -->
    <div class="row row-cards mt-3">
        @forelse($kuesioner->pertanyaans as $index => $q)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title">Pertanyaan {{ $index + 1 }}: {{ $q->pertanyaan }}</h3>
                        <span class="badge ms-auto {{ $q->tipe === 'text' ? 'bg-secondary' : ($q->tipe === 'radio' ? 'bg-blue' : 'bg-purple') }}">
                            {{ strtoupper($q->tipe) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if($q->tipe === 'text')
                            <!-- List Tanggapan Isian Bebas -->
                            <div class="text-muted mb-2 small">Daftar jawaban deskriptif responden:</div>
                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #f1f5f9; border-radius: 4px; padding: 15px; background: #fafafa;">
                                <ul class="list-unstyled space-y-2 mb-0">
                                    @php $textAnswers = $analysis[$q->id] ?? []; @endphp
                                    @forelse($textAnswers as $idx => $ans)
                                        <li class="pb-2 border-bottom border-dashed border-200">
                                            <span class="badge bg-secondary-lt me-1">{{ $idx + 1 }}</span>
                                            {{ $ans }}
                                        </li>
                                    @empty
                                        <li class="text-center text-muted py-3">Belum ada tanggapan deskriptif.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @else
                            <!-- Tabel / Progres Bar Opsi Pilihan -->
                            @php
                                $counts = $analysis[$q->id] ?? [];
                                $totalVotes = array_sum($counts);
                            @endphp
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Pilihan Jawaban</th>
                                            <th>Jumlah Pemilih</th>
                                            <th class="w-50">Visualisasi Persentase</th>
                                            <th class="w-1">Persen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($counts as $option => $voteCount)
                                            @php
                                                $percent = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100, 1) : 0;
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $option }}</strong></td>
                                                <td>{{ $voteCount }} Respon</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-primary-lt">{{ $percent }}%</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end text-muted small mt-2">
                                Total pilihan terkumpul: <strong>{{ $totalVotes }} pilihan</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="alert alert-warning text-center">
                    Kuesioner ini belum memiliki butir pertanyaan.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
