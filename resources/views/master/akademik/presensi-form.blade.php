@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kelola kehadiran mahasiswa untuk mata kuliah {{ $pertemuan->jadwal->mataKuliah->name }}.</div>
            </div>
            <div class="col-auto">
                <a href="{{ $activeRole === 'admin' ? route('admin.lock-jurnal.index') : route('dosen.jurnal.index') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title">Pertemuan Ke-{{ $pertemuan->pertemuan_ke }} — {{ \Carbon\Carbon::parse($pertemuan->tanggal)->format('d F Y') }}</h3>
                <div class="small opacity-75">Dosen: {{ $pertemuan->dosen->name ?? ($pertemuan->jadwal->dosen->name ?? 'N/A') }}</div>
            </div>
            <button type="button" class="btn btn-sm btn-pill btn-success" id="btn-hadir-semua">Set Semua Hadir</button>
        </div>
        <form action="{{ route($activeRole . '.' . ($activeRole === 'admin' ? 'lock-jurnal' : 'jurnal') . '.presensi-store', $pertemuan->id) }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th style="width: 15%;">NIM</th>
                            <th style="width: 35%;">Nama Mahasiswa</th>
                            <th style="width: 30%;" class="text-center">Status Kehadiran</th>
                            <th style="width: 20%;">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php
                                $status = $attendance[$student->id] ?? 'Hadir';
                                $note = $catatan[$student->id] ?? '';
                            @endphp
                            <tr>
                                <td><strong>{{ $student->nim }}</strong></td>
                                <td>{{ $student->name }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input radio-status" type="radio" name="status[{{ $student->id }}]" value="Hadir" {{ $status === 'Hadir' ? 'checked' : '' }}>
                                            <span class="form-check-label text-success fw-bold">H</span>
                                        </label>
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input radio-status" type="radio" name="status[{{ $student->id }}]" value="Sakit" {{ $status === 'Sakit' ? 'checked' : '' }}>
                                            <span class="form-check-label text-info fw-bold">S</span>
                                        </label>
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input radio-status" type="radio" name="status[{{ $student->id }}]" value="Izin" {{ $status === 'Izin' ? 'checked' : '' }}>
                                            <span class="form-check-label text-warning fw-bold">I</span>
                                        </label>
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input radio-status" type="radio" name="status[{{ $student->id }}]" value="Alpa" {{ $status === 'Alpa' ? 'checked' : '' }}>
                                            <span class="form-check-label text-danger fw-bold">A</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="catatan[{{ $student->id }}]" value="{{ $note }}" placeholder="keterangan tambahan...">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada mahasiswa yang terdaftar di kelas perkuliahan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary" {{ $students->isEmpty() ? 'disabled' : '' }}>Simpan Presensi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('btn-hadir-semua')?.addEventListener('click', function() {
    document.querySelectorAll('.radio-status[value="Hadir"]').forEach(function(radio) {
        radio.checked = true;
    });
});
</script>
@endpush
