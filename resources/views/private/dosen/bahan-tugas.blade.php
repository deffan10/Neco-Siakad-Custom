@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Unggah modul, slide perkuliahan, atau berikan tugas kepada mahasiswa untuk pertemuan ini.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('dosen.jurnal.index') }}" class="btn btn-outline-secondary">
                    Kembali ke Jurnal
                </a>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Form Unggah Bahan / Tugas -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Unggah Bahan / Tugas</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('dosen.jurnal.bahan-tugas-store', $pertemuan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Judul Materi / Tugas</label>
                            <input type="text" class="form-control" name="judul" placeholder="Contoh: Slide Pertemuan 1 - Konsep Dasar" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi / Instruksi</label>
                            <textarea class="form-control" name="deskripsi" rows="3" placeholder="Masukkan instruksi atau detail singkat..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Tipe Dokumen</label>
                            <select class="form-select" name="tipe" id="tipe-select" required>
                                <option value="Materi" selected>Materi Perkuliahan (Slide/Modul)</option>
                                <option value="Tugas">Tugas Kuliah (Assignment)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File Dokumen (Max 10MB)</label>
                            <input type="file" class="form-control" name="file">
                        </div>
                        <div class="mb-3 d-none" id="deadline-container">
                            <label class="form-label">Batas Pengumpulan (Deadline)</label>
                            <input type="datetime-local" class="form-control" name="deadline">
                        </div>
                        <button type="submit" class="btn btn-primary w-100" {{ $pertemuan->is_locked ? 'disabled' : '' }}>
                            Simpan Dokumen
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Bahan & Tugas Terunggah -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Bahan & Tugas yang Diunggah</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tipe</th>
                                <th>File</th>
                                <th>Deadline / Info</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>
                                        <div><strong>{{ $item->judul }}</strong></div>
                                        <div class="text-muted small">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</div>
                                    </td>
                                    <td>
                                        @if($item->tipe === 'Materi')
                                            <span class="badge bg-green">Materi</span>
                                        @else
                                            <span class="badge bg-orange">Tugas</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->file_path)
                                            <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-sm btn-pill btn-outline-info">
                                                <i class="fas fa-download me-1"></i> Unduh
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->tipe === 'Tugas')
                                            <div class="small">Deadline:</div>
                                            <div class="text-danger small">{{ \Carbon\Carbon::parse($item->deadline)->format('d M Y, H:i') }}</div>
                                        @else
                                            <span class="text-muted small">Materi ajar</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($item->tipe === 'Tugas')
                                            <a href="{{ route('dosen.tugas.submisi', $item->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-users me-1"></i> Submisi ({{ $item->submisi_count }})
                                            </a>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada bahan ajar atau tugas terunggah untuk pertemuan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('tipe-select')?.addEventListener('change', function() {
    const isTask = this.value === 'Tugas';
    const deadlineContainer = document.getElementById('deadline-container');
    if (isTask) {
        deadlineContainer?.classList.remove('d-none');
    } else {
        deadlineContainer?.classList.add('d-none');
    }
});
</script>
@endpush
