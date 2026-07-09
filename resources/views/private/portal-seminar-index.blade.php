@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Daftar event seminar aktif. Daftarkan diri Anda untuk berpartisipasi.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        @forelse($events as $event)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="badge {{ $event->tipe_event === 'Proposal' ? 'bg-purple' : ($event->tipe_event === 'Hasil' ? 'bg-blue' : 'bg-cyan') }}-lt">
                        {{ $event->tipe_event }}
                    </span>
                    @if($event->is_wajib)
                        <span class="badge bg-red-lt">Wajib</span>
                    @endif
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ $event->nama_event }}</h4>
                    @if($event->deskripsi)
                        <p class="text-muted small mb-2">{{ Str::limit($event->deskripsi, 80) }}</p>
                    @endif
                    <ul class="list-unstyled small text-muted mb-0">
                        <li><i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($event->tanggal)->format('d F Y') }}</li>
                        @if($event->jam_mulai)
                        <li><i class="fas fa-clock me-1"></i> {{ substr($event->jam_mulai,0,5) }} - {{ substr($event->jam_selesai,0,5) }}</li>
                        @endif
                        @if($event->lokasi)
                        <li><i class="fas fa-map-marker-alt me-1"></i> {{ $event->lokasi }}</li>
                        @endif
                        @if($event->narasumber)
                        <li><i class="fas fa-user-tie me-1"></i> {{ $event->narasumber }}</li>
                        @endif
                        @if($event->kuota)
                        <li><i class="fas fa-users me-1"></i> {{ $event->peserta_mahasiswas_count }}/{{ $event->kuota }} peserta</li>
                        @endif
                    </ul>
                </div>
                <div class="card-footer">
                    @if($event->is_registered)
                        <button class="btn btn-success w-100" disabled>
                            <i class="fas fa-check me-1"></i> Sudah Terdaftar
                        </button>
                    @elseif($event->kuota && $event->peserta_mahasiswas_count >= $event->kuota)
                        <button class="btn btn-secondary w-100" disabled>Kuota Penuh</button>
                    @else
                        <form action="{{ route('mahasiswa.seminar.daftar', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-1"></i> Daftar Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-calendar-times fa-3x mb-3 opacity-50"></i>
                    <p>Saat ini tidak ada event seminar yang sedang dibuka untuk pendaftaran.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
