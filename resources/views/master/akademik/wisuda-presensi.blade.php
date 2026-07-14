@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kegiatan Wisuda: <strong>{{ $kegiatan->nama }}</strong></div>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.wisuda.settings') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Daftar Kehadiran Upacara Wisudawan</h3>
        </div>
        <form action="{{ route('admin.wisuda.presensi.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th width="80" class="text-center">Hadir Upacara</th>
                            <th>NIM</th>
                            <th>Nama Lengkap</th>
                            <th>Program Studi</th>
                            <th>Ukuran Toga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftarans as $p)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input" name="kehadiran[]" value="{{ $p->id }}" {{ $p->kehadiran_upacara ? 'checked' : '' }}>
                                </td>
                                <td><strong>{{ $p->mahasiswa->user->nim ?? '-' }}</strong></td>
                                <td>{{ $p->mahasiswa->user->name ?? 'N/A' }}</td>
                                <td>{{ $p->mahasiswa->programStudi->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-purple-lt">{{ $p->ukuran_toga }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada mahasiswa yang disetujui untuk mengikuti upacara wisuda ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary" {{ $pendaftarans->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-save me-1"></i> Simpan Kehadiran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
