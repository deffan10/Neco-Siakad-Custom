@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Pilih kelas perkuliahan aktif untuk melakukan input nilai UTS, UAS, dan Tugas mahasiswa.</div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Daftar Kelas Perkuliahan Anda</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>Kode Kelas</th>
                        <th>Mata Kuliah</th>
                        <th>Program Studi</th>
                        <th>Kelas</th>
                        <th>Kapasitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelases as $k)
                        <tr>
                            <td><span class="badge bg-purple-lt">{{ $k->code }}</span></td>
                            <td><strong>{{ $k->mataKuliah->name ?? '-' }}</strong> ({{ $k->mataKuliah->beban_sks ?? 0 }} SKS)</td>
                            <td>{{ $k->programStudi->name ?? '-' }}</td>
                            <td>{{ $k->name }}</td>
                            <td>{{ $k->kapasitas ?? 'Unlimited' }}</td>
                            <td>
                                <a href="{{ route('admin.nilai.kelas-form', $k->id) }}" class="btn btn-sm btn-primary">
                                    Input Nilai
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada kelas perkuliahan yang ditugaskan kepada Anda pada semester ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
