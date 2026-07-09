@extends('themes.core-backpage')

@section('custom-css')
<style>
    .transkrip-header {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        padding: 2rem;
        border-radius: 10px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('content')
<div class="container-xl">
    <!-- Transkrip Header -->
    <div class="transkrip-header">
        <h2 class="mb-1">Transkrip Nilai Akademik</h2>
        <p class="mb-0">Daftar akumulasi seluruh mata kuliah yang telah Anda tempuh beserta nilai akhir.</p>
    </div>

    <!-- Ringkasan Transkrip -->
    <div class="row row-cards mb-4">
        <div class="col-sm-6 col-md-4">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-blue text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" /></svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">IPK Saat Ini</div>
                            <div class="text-muted">{{ number_format($user->dataMahasiswa->ipk ?? 0.00, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-green text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-books" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4m0 1a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-12a1 1 0 0 1 -1 -1z" /><path d="M9 4v16" /><path d="M14 4v16" /></svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Total SKS Lulus</div>
                            <div class="text-muted">{{ $user->dataMahasiswa->sks_lulus ?? 0 }} SKS</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Nilai Kumulatif -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Nilai Kumulatif</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>Semester Terambil</th>
                        <th>SKS</th>
                        <th>Nilai Angka</th>
                        <th>Nilai Huruf</th>
                        <th>Indeks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades as $g)
                        <tr>
                            <td>{{ $g->kelas->mataKuliah->code ?? '-' }}</td>
                            <td><strong>{{ $g->kelas->mataKuliah->name ?? '-' }}</strong></td>
                            <td>{{ $g->kelas->tahunAkademik->name }}</td>
                            <td>{{ $g->kelas->mataKuliah->beban_sks ?? 0 }} SKS</td>
                            <td>{{ $g->nilai->nilai_akhir_angka ?? 'N/A' }}</td>
                            <td><span class="badge bg-blue-lt">{{ $g->nilai->nilai_huruf }}</span></td>
                            <td>{{ number_format($g->nilai->bobot_indeks ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada riwayat perkuliahan atau transkrip nilai terbit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
