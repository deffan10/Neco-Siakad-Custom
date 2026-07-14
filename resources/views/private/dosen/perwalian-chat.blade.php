@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Berikan bimbingan, tanggapan, atau persetujuan kaitan perwalian akademik untuk mahasiswa.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('dosen.perwalian.index') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Student Detail Info Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Profil Mahasiswa</h3>
                </div>
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mb-3 rounded-circle bg-purple text-white fw-bold">
                        {{ strtoupper(substr($student->name, 0, 2)) }}
                    </div>
                    <h3 class="mb-1">{{ $student->name }}</h3>
                    <div class="text-muted small">NIM: {{ $student->nim ?? ($student->username ?? '-') }}</div>
                    <div class="text-muted small">Email: {{ $student->email ?? '-' }}</div>
                    <div class="text-muted small mt-2">Gunakan percakapan ini untuk merekam log konsultasi KRS atau kemajuan studi mahasiswa wali Anda.</div>
                </div>
            </div>
        </div>

        <!-- Consultation Chat Box -->
        <div class="col-md-8">
            <div class="card d-flex flex-column" style="height: 480px;">
                <div class="card-header">
                    <h3 class="card-title">Percakapan Bimbingan dengan {{ $student->name }}</h3>
                </div>
                <div class="card-body scrollable flex-fill p-3" style="overflow-y: auto; background-color: #f8f9fa;" id="chat-box">
                    @forelse($messages as $msg)
                        <div class="d-flex flex-column mb-3 {{ $msg->pengirim === 'Dosen' ? 'align-items-end' : 'align-items-start' }}">
                            <div class="small text-muted mb-1">
                                <strong>{{ $msg->pengirim === 'Dosen' ? 'Saya' : $student->name }}</strong>
                                <span class="extra-small">({{ $msg->created_at->format('d M H:i') }})</span>
                            </div>
                            <div class="p-2 px-3 rounded shadow-sm" style="max-width: 80%; {{ $msg->pengirim === 'Dosen' ? 'background-color: #0b5ad0; color: white;' : 'background-color: white; color: #333;' }}">
                                {{ $msg->pesan }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5 my-auto">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <p>Belum ada percakapan bimbingan. Tulis pesan di bawah untuk memberikan masukan awal.</p>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer border-top p-2 bg-white">
                    <form action="{{ route('dosen.perwalian.store', $student->id) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="pesan" class="form-control" placeholder="Tulis masukan/jawaban bimbingan Anda..." required autocomplete="off">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chatBox = document.getElementById('chat-box');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>
@endpush
