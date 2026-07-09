@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('mahasiswa.kuesioner.index') }}" class="btn btn-outline-secondary mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kuesioner
                </a>
                <h2 class="page-title">{{ $kuesioner->judul }}</h2>
                <div class="text-muted mt-1">Harap isi seluruh butir pertanyaan kuesioner dengan objektif dan jujur.</div>
            </div>
        </div>
    </div>

    @if($kuesioner->deskripsi)
        <div class="card mt-3">
            <div class="card-body">
                <h4 class="card-title">Petunjuk Pengisian:</h4>
                <p class="text-muted mb-0">{!! nl2br(e($kuesioner->deskripsi)) !!}</p>
            </div>
        </div>
    @endif

    <div class="card mt-3">
        <form action="{{ route('mahasiswa.kuesioner.submit', $kuesioner->id) }}" method="POST">
            @csrf
            <div class="card-body">
                @forelse($kuesioner->pertanyaans as $index => $q)
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label font-weight-bold mb-2">
                            {{ $index + 1 }}. {{ $q->pertanyaan }}
                        </label>

                        @if($q->tipe === 'text')
                            <!-- Tipe Isian Bebas -->
                            <textarea class="form-control" name="q_{{ $q->id }}" rows="3" placeholder="Tulis jawaban Anda di sini..." required></textarea>
                        @elseif($q->tipe === 'radio')
                            <!-- Tipe Radio Pilihan Tunggal -->
                            <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column gap-2">
                                @if(is_array($q->pilihan_jawaban))
                                    @foreach($q->pilihan_jawaban as $optIndex => $opt)
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="q_{{ $q->id }}" value="{{ $opt }}" class="form-selectgroup-input" required>
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>{{ $opt }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        @elseif($q->tipe === 'checkbox')
                            <!-- Tipe Checkbox Pilihan Banyak -->
                            <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column gap-2">
                                @if(is_array($q->pilihan_jawaban))
                                    @foreach($q->pilihan_jawaban as $optIndex => $opt)
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="checkbox" name="q_{{ $q->id }}[]" value="{{ $opt }}" class="form-selectgroup-input">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>{{ $opt }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                            <small class="text-muted d-block mt-1"><i class="fas fa-info-circle"></i> Anda dapat memilih lebih dari satu opsi jawaban.</small>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Kuesioner ini belum memiliki daftar pertanyaan.</div>
                @endforelse
            </div>
            
            @if($kuesioner->pertanyaans->count() > 0)
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Apakah Anda yakin ingin mengirimkan jawaban kuesioner ini?')">
                        <i class="fas fa-paper-plane me-1"></i> Kirim Jawaban Survei
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
