@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Daftar mahasiswa yang Pembimbing Akademik (DPA) nya adalah Anda.</div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Mahasiswa Perwalian Saya</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Program Studi</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($advisees as $adv)
                        <tr>
                            <td><strong>{{ $adv->user->nim ?? ($adv->nim ?? 'N/A') }}</strong></td>
                            <td>{{ $adv->user->name ?? 'N/A' }}</td>
                            <td>{{ $adv->programStudi->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-success-lt">Aktif</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('dosen.perwalian.show', $adv->user_id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-comments me-1"></i> Bimbingan
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada mahasiswa yang ditugaskan di bawah perwalian Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
