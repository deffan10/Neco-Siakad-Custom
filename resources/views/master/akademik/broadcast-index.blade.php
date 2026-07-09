@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Log semua pengiriman email broadcast yang pernah dilakukan.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.broadcast.create') }}" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i> Broadcast Email Baru
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>Subjek Email</th>
                        <th>Target Penerima</th>
                        <th>Total Penerima</th>
                        <th>Status</th>
                        <th>Dikirim Oleh</th>
                        <th>Waktu Kirim</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($broadcasts as $b)
                        <tr>
                            <td>
                                <div><strong>{{ $b->subject }}</strong></div>
                                <div class="text-muted small" title="{{ $b->content }}">{{ Str::limit($b->content, 60) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-azure-lt">{{ $b->target_audience }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ number_format($b->total_recipients) }}</span>
                                <span class="text-muted small"> penerima</span>
                            </td>
                            <td>
                                @if($b->status === 'Terkirim')
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Terkirim</span>
                                @elseif($b->status === 'Gagal')
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Gagal</span>
                                @else
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i> Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="small">{{ $b->creator->name ?? 'System' }}</div>
                            </td>
                            <td>
                                <div class="small">
                                    {{ $b->sent_at ? \Carbon\Carbon::parse($b->sent_at)->format('d M Y, H:i') : '-' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-envelope-open-text fa-2x mb-2 d-block opacity-50"></i>
                                Belum ada riwayat pengiriman broadcast email.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            {{ $broadcasts->links() }}
        </div>
    </div>
</div>
@endsection
