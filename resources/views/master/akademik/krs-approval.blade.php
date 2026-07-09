@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Daftar pengajuan KRS mahasiswa bimbingan akademik yang perlu ditinjau dan disetujui.</div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Daftar Pengajuan KRS</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Semester</th>
                        <th>Kelas Kuliah yang Diambil</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($krsLists as $k)
                        <tr>
                            <td>{{ $k->mahasiswa->username }}</td>
                            <td><strong>{{ $k->mahasiswa->name }}</strong></td>
                            <td>{{ $k->tahunAkademik->name }}</td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @php $totalSks = 0; @endphp
                                    @foreach($k->details as $d)
                                        @php $totalSks += $d->kelasPerkuliahan->mataKuliah->beban_sks ?? 0; @endphp
                                        <li class="mb-1">
                                            <span class="badge bg-secondary-lt">{{ $d->kelasPerkuliahan->code }}</span> 
                                            {{ $d->kelasPerkuliahan->mataKuliah->name ?? '-' }} ({{ $d->kelasPerkuliahan->mataKuliah->beban_sks ?? 0 }} SKS)
                                        </li>
                                    @endforeach
                                    <li class="mt-2 text-primary font-weight-bold">Total SKS: {{ $totalSks }} SKS</li>
                                </ul>
                            </td>
                            <td>
                                @if($k->status === 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-warning">Menunggu Persetujuan</span>
                                @endif
                            </td>
                            <td>
                                @if($k->status === 'Diajukan')
                                    <!-- Button trigger modal untuk detail/approval -->
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#approveModal-{{ $k->id }}">
                                        Tinjau KRS
                                    </button>
                                @else
                                    <span class="text-muted font-italic">-</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal Tinjau KRS -->
                        <div class="modal modal-blur fade" id="approveModal-{{ $k->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tinjau Pengajuan KRS: {{ $k->mahasiswa->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.krs.approve', $k->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Apakah Anda menyetujui pengisian KRS untuk periode <strong>{{ $k->tahunAkademik->name }}</strong> sejumlah <strong>{{ $totalSks }} SKS</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Catatan Dosen Wali (Opsional)</label>
                                                <textarea class="form-control" name="catatan_dosen" rows="3" placeholder="Tulis catatan revisi jika menolak, atau catatan bimbingan jika menyetujui..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="action" value="reject" class="btn btn-danger">Tolak & Minta Revisi</button>
                                            <button type="submit" name="action" value="approve" class="btn btn-success">Setujui KRS</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada pengajuan KRS masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($krsLists->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $krsLists->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
