@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('admin.seminar.index') }}" class="btn btn-outline-secondary mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">
                    Tanggal: <strong>{{ \Carbon\Carbon::parse($event->tanggal)->format('d F Y') }}</strong>
                    @if($event->lokasi) &bull; Lokasi: <strong>{{ $event->lokasi }}</strong> @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Daftar Peserta ({{ $pesertaList->count() }} orang)</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Mahasiswa</th>
                        <th>Email</th>
                        <th>Status Kehadiran</th>
                        <th>Catatan</th>
                        <th>Ubah Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesertaList as $i => $peserta)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $peserta->mahasiswa->name ?? 'N/A' }}</strong></td>
                        <td class="text-muted small">{{ $peserta->mahasiswa->email ?? '-' }}</td>
                        <td>
                            @if($peserta->status === 'Hadir')
                                <span class="badge bg-success">Hadir</span>
                            @elseif($peserta->status === 'Tidak Hadir')
                                <span class="badge bg-danger">Tidak Hadir</span>
                            @else
                                <span class="badge bg-warning">Mendaftar</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $peserta->catatan ?? '-' }}</td>
                        <td>
                            <form action="{{ route('admin.seminar.update-peserta', $peserta->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                                @csrf
                                <select name="status" class="form-select form-select-sm" style="width:140px">
                                    <option value="Mendaftar" {{ $peserta->status === 'Mendaftar' ? 'selected' : '' }}>Mendaftar</option>
                                    <option value="Hadir" {{ $peserta->status === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="Tidak Hadir" {{ $peserta->status === 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada mahasiswa yang mendaftar event ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
