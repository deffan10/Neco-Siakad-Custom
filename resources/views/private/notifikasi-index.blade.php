@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Daftar semua pemberitahuan dan informasi penting untuk akun Anda.</div>
            </div>
            <div class="col-auto">
                <div class="btn-list">
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                    <form action="{{ route('notifications.clear-all') }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus semua riwayat notifikasi Anda?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-1"></i> Bersihkan Semua
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="list-group list-group-flush list-group-hoverable">
            @forelse($notifications as $n)
                <div class="list-group-item {{ !$n->is_read ? 'bg-light' : '' }}">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @if(!$n->is_read)
                                <span class="status-dot status-dot-animated bg-{{ $n->tipe }} d-block"></span>
                            @else
                                <span class="status-dot bg-secondary d-block"></span>
                            @endif
                        </div>
                        <div class="col text-truncate">
                            <a href="{{ route('notifications.read', $n->id) }}" class="text-body d-block font-weight-bold">
                                <strong>{{ $n->judul }}</strong>
                            </a>
                            <div class="text-secondary text-truncate mt-1">{{ $n->pesan }}</div>
                            <div class="text-muted small mt-1">
                                {{ $n->created_at->diffForHumans() }} &bull; {{ $n->created_at->format('d M Y H:i') }} WIB
                            </div>
                        </div>
                        <div class="col-auto">
                            @if(!$n->is_read)
                                <a href="{{ route('notifications.read', $n->id) }}" class="btn btn-sm btn-primary">
                                    Buka
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="fas fa-bell-slash fa-3x mb-3 opacity-50"></i>
                    <p>Tidak ada notifikasi baru untuk Anda.</p>
                </div>
            @endforelse
        </div>
        @if($notifications->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
