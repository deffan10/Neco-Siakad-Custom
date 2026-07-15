@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Pusat pertukaran berkas materi perkuliahan, panduan administratif, dan dokumen akademik.</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-upload">
                    <i class="fas fa-upload me-2"></i>Unggah Berkas Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <input type="text" name="search" class="form-control" style="max-width:300px" placeholder="Cari Nama Berkas / Judul / Deskripsi..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                <a href="{{ route('admin.shared-files.index') }}" class="btn btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    <!-- File Grid -->
    <div class="row row-cards mt-3">
        @forelse($files as $file)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card card-sm d-flex flex-column justify-content-between" style="height: 100%;">
                    <div class="card-body text-center py-4">
                        @php
                            $ext = pathinfo($file->file_name, PATHINFO_EXTENSION);
                            $icon = 'fa-file-alt';
                            $color = 'text-secondary';
                            if (in_array(strtolower($ext), ['pdf'])) {
                                $icon = 'fa-file-pdf';
                                $color = 'text-danger';
                            } elseif (in_array(strtolower($ext), ['doc', 'docx'])) {
                                $icon = 'fa-file-word';
                                $color = 'text-primary';
                            } elseif (in_array(strtolower($ext), ['xls', 'xlsx'])) {
                                $icon = 'fa-file-excel';
                                $color = 'text-success';
                            } elseif (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) {
                                $icon = 'fa-file-image';
                                $color = 'text-info';
                            } elseif (in_array(strtolower($ext), ['zip', 'rar', 'tar', 'gz'])) {
                                $icon = 'fa-file-archive';
                                $color = 'text-warning';
                            }
                        @endphp
                        <span class="avatar avatar-xl rounded-circle mb-3 bg-light {{ $color }}">
                            <i class="fas {{ $icon }} fa-2x"></i>
                        </span>
                        <h4 class="m-0 mb-1" style="word-break: break-all;"><strong>{{ $file->title }}</strong></h4>
                        <div class="text-muted small text-truncate">{{ $file->file_name }}</div>
                        <div class="text-muted small mt-1">Ukuran: {{ number_format($file->file_size / 1024, 1) }} KB</div>
                        <div class="text-muted small">Oleh: {{ $file->user->name ?? 'Anonim' }}</div>

                        <!-- Visibility Badge -->
                        @php
                            $badgeClass = 'bg-secondary';
                            if ($file->visibility === 'Public') {
                                $badgeClass = 'bg-green-lt';
                            } elseif ($file->visibility === 'Dosen') {
                                $badgeClass = 'bg-blue-lt';
                            } elseif ($file->visibility === 'Mahasiswa') {
                                $badgeClass = 'bg-purple-lt';
                            } elseif ($file->visibility === 'Private') {
                                $badgeClass = 'bg-red-lt';
                            }
                        @endphp
                        <div class="mt-2">
                            <span class="badge {{ $badgeClass }}">{{ $file->visibility }}</span>
                        </div>

                        @if($file->description)
                            <div class="mt-2 text-muted small p-2 bg-light rounded text-start" style="font-size: 10px; height: 50px; overflow-y: auto;">
                                <strong>Deskripsi:</strong> {{ $file->description }}
                            </div>
                        @endif
                    </div>
                    <div class="card-footer d-flex gap-2 justify-content-center bg-light border-top py-2">
                        <a href="{{ asset('storage/' . $file->file_path) }}" class="btn btn-sm btn-success" download="{{ $file->file_name }}">
                            <i class="fas fa-download me-1"></i> Unduh
                        </a>
                        @if(Auth::id() === $file->user_id || Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin'))
                            <form action="{{ route('admin.shared-files.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Hapus file ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="fas fa-folder-open fa-4x mb-3 text-secondary"></i>
                <p>Belum ada berkas yang dibagikan.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $files->links() }}
    </div>
</div>

<!-- Upload Modal -->
<div class="modal modal-blur fade" id="modal-upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.shared-files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Unggah Berkas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Judul Berkas</label>
                        <input type="text" name="title" class="form-control" placeholder="cth: Silabus Pemrograman Web" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Catatan</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Masukkan deskripsi file..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Pilih Berkas (Maks. 10MB)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Hak Akses / Visibilitas</label>
                        <select name="visibility" class="form-select" required>
                            <option value="Public">Publik (Semua)</option>
                            <option value="Dosen">Khusus Dosen</option>
                            <option value="Mahasiswa">Khusus Mahasiswa</option>
                            <option value="Private">Hanya Saya</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-2"></i>Mulai Unggah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
