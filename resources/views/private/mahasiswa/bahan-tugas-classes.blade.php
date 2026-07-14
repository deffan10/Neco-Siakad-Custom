@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Pilih kelas kuliah Anda untuk melihat materi ajar atau mengumpulkan tugas perkuliahan.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        @forelse($enrollments as $enr)
            <div class="col-md-4">
                <div class="card card-stacked">
                    <div class="card-body">
                        <div class="subheader text-blue mb-1">Kode Kelas: {{ $enr->kelas->code }}</div>
                        <h3 class="card-title mb-2">{{ $enr->kelas->mataKuliah->name }}</h3>
                        <p class="text-muted small mb-3">
                            Tahun Semester: {{ $enr->kelas->tahunAkademik->name ?? 'N/A' }} <br>
                            SKS: {{ $enr->kelas->mataKuliah->sks ?? '-' }} SKS
                        </p>
                        <a href="{{ route('mahasiswa.bahan-tugas.show', $enr->kelas_id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-folder-open me-1"></i> Buka Materi & Tugas
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center py-4">
                    Belum ada mata kuliah yang terdaftar untuk semester berjalan.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
