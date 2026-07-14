@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Konsultasikan kendala akademik, KRS, atau progres studi Anda dengan Dosen Wali / Pembimbing Akademik.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Dosen Wali Profile -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Dosen Pembimbing Akademik</h3>
                </div>
                <div class="card-body text-center">
                    @if($profile && $profile->dosenPa)
                        <div class="avatar avatar-xl mb-3 rounded-circle bg-blue text-white fw-bold">
                            {{ strtoupper(substr($profile->dosenPa->name, 0, 2)) }}
                        </div>
                        <h3 class="mb-1">{{ $profile->dosenPa->name }}</h3>
                        <div class="text-muted small">NIDN/NIP: {{ $profile->dosenPa->username ?? '-' }}</div>
                        <div class="text-muted small mt-2">Dosen Wali siap membimbing perjalanan studi Anda dari awal masuk sampai yudisium.</div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <strong>Perhatian!</strong><br>
                            Anda belum ditetapkan memiliki Dosen Pembimbing Akademik. Silakan hubungi bagian administrasi prodi Anda.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Chat Consultation Stream -->
        <div class="col-md-8">
            <div class="card d-flex flex-column" style="height: 480px;">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Percakapan Bimbingan</h3>
                </div>
                <div class="card-body scrollable flex-fill p-3" style="overflow-y: auto; background-color: #f8f9fa;" id="chat-box">
                    @forelse($messages as $msg)
                        <div class="d-flex flex-column mb-3 {{ $msg->pengirim === 'Mahasiswa' ? 'align-items-end' : 'align-items-start' }}">
                            <div class="small text-muted mb-1">
                                <strong>{{ $msg->pengirim === 'Mahasiswa' ? 'Saya' : ($profile->dosenPa->name ?? 'Dosen Wali') }}</strong>
                                <span class="extra-small">({{ $msg->created_at->format('d M H:i') }})</span>
                            </div>
                            <div class="p-2 px-3 rounded shadow-sm" style="max-width: 80%; {{ $msg->pengirim === 'Mahasiswa' ? 'background-color: #0b5ad0; color: white;' : 'background-color: white; color: #333;' }}">
                                {{ $msg->pesan }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5 my-auto">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <p>Belum ada percakapan. Tulis pesan di bawah untuk memulai konsultasi akademik.</p>
                        </div>
                    @endforelse
                </div>
                @if($profile && $profile->dosen_pa_id)
                    <div class="card-footer border-top p-2 bg-white">
                        <form action="{{ route('mahasiswa.pa-online.store') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="pesan" class="form-control" placeholder="Tulis pesan konsultasi Anda..." required autocomplete="off">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Scroll chat box to bottom
    const chatBox = document.getElementById('chat-box');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>
@endpush
