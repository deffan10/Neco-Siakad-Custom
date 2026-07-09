@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Lengkapi data skripsi, ukuran jubah wisuda (toga), dan unggah berkas kelengkapan administrasi kelulusan.</div>
            </div>
        </div>
    </div>

    @if(!$kegiatan)
        <div class="alert alert-info mt-3">
            <div class="d-flex">
                <div>
                    <i class="fas fa-info-circle me-2"></i>
                </div>
                <div>
                    <h4 class="alert-title">Pendaftaran Wisuda Belum Dibuka</h4>
                    <div class="text-muted">Saat ini belum ada kegiatan wisuda aktif untuk periode akademik sekarang. Hubungi bagian BAAK/Operator kampus untuk informasi lebih lanjut.</div>
                </div>
            </div>
        </div>
    @else
        <div class="row row-cards mt-3">
            <!-- Informasi Kegiatan Wisuda -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-purple text-white">
                        <h3 class="card-title">Informasi Kegiatan Wisuda</h3>
                    </div>
                    <div class="card-body">
                        <h3 class="mb-3 text-purple">{{ $kegiatan->nama }}</h3>
                        <ul class="list-unstyled space-y-2">
                            <li class="mb-2">
                                <i class="fas fa-calendar-alt text-muted me-2"></i>
                                <strong>Pendaftaran:</strong> 
                                <span class="d-block ms-4 small text-muted">
                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai_daftar)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai_daftar)->format('d M Y') }}
                                </span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-graduation-cap text-muted me-2"></i>
                                <strong>Pelaksanaan:</strong> 
                                <span class="d-block ms-4 small text-muted">
                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_pelaksanaan)->format('d M Y') }}
                                </span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-users text-muted me-2"></i>
                                <strong>Kuota Peserta:</strong> 
                                <span class="d-block ms-4 small text-muted">{{ $kegiatan->kuota }} orang</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-wallet text-muted me-2"></i>
                                <strong>Biaya Wisuda:</strong> 
                                <span class="d-block ms-4 small text-muted">Rp {{ number_format($kegiatan->biaya, 0, ',', '.') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Status Tagihan Wisuda -->
                @if($pendaftaran && $pendaftaran->status !== 'Draft')
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title">Status Keuangan Wisuda</h3>
                        </div>
                        <div class="card-body text-center">
                            @if($is_paid)
                                <div class="alert alert-success py-2 mb-0">
                                    <i class="fas fa-check-circle me-1"></i> Biaya Wisuda Lunas
                                </div>
                            @elseif($kegiatan->biaya == 0)
                                <div class="alert alert-secondary py-2 mb-0">
                                    <i class="fas fa-info-circle me-1"></i> Bebas Biaya
                                </div>
                            @else
                                <div class="alert alert-danger py-2 mb-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Belum Lunas
                                </div>
                                <p class="small text-muted mb-2">Segera lakukan pembayaran sebesar Rp {{ number_format($kegiatan->biaya, 0, ',', '.') }}</p>
                                <form action="{{ route('mahasiswa.keuangan.pay', $tagihan->id ?? 0) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                        <i class="fas fa-money-bill-wave me-1"></i> Simulasi Bayar Instan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Formulir & Status Berkas Pendaftaran -->
            <div class="col-md-8">
                <!-- Banner Status Berkas -->
                @if($pendaftaran)
                    @if($pendaftaran->status === 'Disetujui')
                        <div class="alert alert-success mb-3">
                            <h4 class="alert-title">Selamat! Pendaftaran Disetujui</h4>
                            <div class="text-muted">Berkas pendaftaran wisuda Anda telah lengkap dan disetujui. Anda resmi terdaftar sebagai wisudawan kegiatan ini.</div>
                        </div>
                    @elseif($pendaftaran->status === 'Diajukan')
                        <div class="alert alert-warning mb-3">
                            <h4 class="alert-title">Pendaftaran Sedang Ditinjau</h4>
                            <div class="text-muted">Berkas pendaftaran Anda telah diajukan ke admin BAAK. Pastikan pembayaran biaya wisuda Anda telah dikonfirmasi Lunas untuk memproses persetujuan.</div>
                        </div>
                    @elseif($pendaftaran->status === 'Ditolak')
                        <div class="alert alert-danger mb-3">
                            <h4 class="alert-title">Pendaftaran Ditolak</h4>
                            <div class="text-muted"><strong>Catatan Admin:</strong> {{ $pendaftaran->catatan }} <br> Silakan perbaiki data atau unggahan berkas Anda di bawah ini dan ajukan kembali.</div>
                        </div>
                    @endif
                @endif

                @if(!$pendaftaran || $pendaftaran->status === 'Draft' || $pendaftaran->status === 'Ditolak')
                    <!-- Form Pendaftaran -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Formulir Pendaftaran Wisuda</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mahasiswa.wisuda.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="kegiatan_wisuda_id" value="{{ $kegiatan->id }}">

                                <div class="mb-3">
                                    <label class="form-label required">Judul Tugas Akhir / Skripsi</label>
                                    <textarea class="form-control" name="judul_skripsi" rows="3" placeholder="Masukkan judul skripsi lengkap Anda..." required>{{ $pendaftaran->judul_skripsi ?? '' }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Ukuran Jubah Toga</label>
                                        <select class="form-select" name="ukuran_toga" required>
                                            <option value="S" {{ (isset($pendaftaran) && $pendaftaran->ukuran_toga === 'S') ? 'selected' : '' }}>S (Small)</option>
                                            <option value="M" {{ (isset($pendaftaran) && $pendaftaran->ukuran_toga === 'M') ? 'selected' : '' }}>M (Medium)</option>
                                            <option value="L" {{ (isset($pendaftaran) && $pendaftaran->ukuran_toga === 'L') ? 'selected' : '' }}>L (Large)</option>
                                            <option value="XL" {{ (isset($pendaftaran) && $pendaftaran->ukuran_toga === 'XL') ? 'selected' : '' }}>XL (Extra Large)</option>
                                            <option value="XXL" {{ (isset($pendaftaran) && $pendaftaran->ukuran_toga === 'XXL') ? 'selected' : '' }}>XXL (Double Extra Large)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Skor TOEFL (Jika ada)</label>
                                        <input type="number" class="form-control" name="toefl_score" value="{{ $pendaftaran->toefl_score ?? '' }}" placeholder="Contoh: 450">
                                    </div>
                                </div>

                                <div class="hr-text my-4">Unggah Berkas Persyaratan (Max 2MB per file)</div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Pas Foto Wisuda (JPG/PNG)</label>
                                        <input type="file" class="form-control" name="berkas_photo" {{ !isset($pendaftaran) ? 'required' : '' }}>
                                        @if(isset($pendaftaran->berkas_photo))
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Berkas sudah terunggah</small>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Surat Bebas Perpustakaan (PDF)</label>
                                        <input type="file" class="form-control" name="berkas_bebas_pustaka" {{ !isset($pendaftaran) ? 'required' : '' }}>
                                        @if(isset($pendaftaran->berkas_bebas_pustaka))
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Berkas sudah terunggah</small>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Cover & Lembar Pengesahan Skripsi (PDF)</label>
                                        <input type="file" class="form-control" name="berkas_skripsi" {{ !isset($pendaftaran) ? 'required' : '' }}>
                                        @if(isset($pendaftaran->berkas_skripsi))
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Berkas sudah terunggah</small>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Sertifikat TOEFL (PDF, opsional)</label>
                                        <input type="file" class="form-control" name="berkas_toefl">
                                        @if(isset($pendaftaran->berkas_toefl))
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Berkas sudah terunggah</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <button type="submit" class="btn btn-outline-primary">Simpan Draft</button>
                                    @if(isset($pendaftaran))
                                        <a href="{{ route('mahasiswa.wisuda.submit', $pendaftaran->id) }}" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin mengajukan pendaftaran wisuda ini ke admin? Anda tidak akan bisa mengubah berkas lagi.')">
                                            Ajukan Pendaftaran
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Tampilan Detail Jika Sudah Diajukan / Disetujui -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail Berkas Pendaftaran Anda</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="w-30">Judul Skripsi</th>
                                            <td>{{ $pendaftaran->judul_skripsi }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ukuran Toga</th>
                                            <td>{{ $pendaftaran->ukuran_toga }}</td>
                                        </tr>
                                        <tr>
                                            <th>TOEFL Score</th>
                                            <td>{{ $pendaftaran->toefl_score ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-4 text-center">
                                    @if($pendaftaran->berkas_photo)
                                        <img src="{{ asset('storage/' . $pendaftaran->berkas_photo) }}" alt="Foto Wisuda" class="img-thumbnail" style="max-height: 180px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
