@extends('themes.core-backpage')

@section('custom-css')
<style>
    .khs-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
    <!-- KHS Header -->
    <div class="khs-header">
        <h2 class="mb-1">Kartu Hasil Studi (KHS)</h2>
        <p class="mb-0">Lihat transkrip nilai semester perkuliahan dan statistik pencapaian indeks prestasi Anda.</p>
    </div>

    <!-- Filter Semester -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="" method="GET" class="row align-items-center">
                <div class="col-md-8 mb-2 mb-md-0">
                    <label class="form-label">Pilih Semester</label>
                    <select class="form-select" name="tahun_akademik_id" onchange="this.form.submit()">
                        @foreach($tahunAkademiks as $ta)
                            <option value="{{ $ta->id }}" {{ $selectedTaId == $ta->id ? 'selected' : '' }}>
                                {{ $ta->name }} ({{ $ta->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-md-end pt-md-4">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan KHS</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel KHS -->
    <div class="row row-cards">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rincian Nilai Mata Kuliah</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Nilai Akhir</th>
                                <th>Nilai Huruf</th>
                                <th>Indeks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($semesterGrades as $g)
                                <tr>
                                    <td>{{ $g->kelas->mataKuliah->code ?? '-' }}</td>
                                    <td><strong>{{ $g->kelas->mataKuliah->name ?? '-' }}</strong></td>
                                    <td>{{ $g->kelas->mataKuliah->beban_sks ?? 0 }} SKS</td>
                                    <td>{{ $g->nilai->nilai_akhir_angka ?? 'N/A' }}</td>
                                    <td>
                                        @if(isset($g->nilai))
                                            <span class="badge bg-purple-lt">{{ $g->nilai->nilai_huruf }}</span>
                                        @else
                                            <span class="text-muted italic">Belum dinilai</span>
                                        @endif
                                    </td>
                                    <td>{{ isset($g->nilai) ? number_format($g->nilai->bobot_indeks, 2) : '0.00' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada kelas perkuliahan atau nilai yang diterbitkan untuk semester ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary IPS / IPK -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil Studi Semester</h3>
                </div>
                <div class="card-body text-center">
                    @if($summary)
                        <div class="subheader">IP Semester (IPS)</div>
                        <div class="h1 my-2 text-warning">{{ number_format($summary->ips, 2) }}</div>
                        <hr class="my-3">
                        <div class="subheader">IP Kumulatif (IPK)</div>
                        <div class="h1 my-2 text-success">{{ number_format($summary->ipk, 2) }}</div>
                        <hr class="my-3">
                        <div class="subheader">SKS Diambil</div>
                        <div class="h3 my-1">{{ $summary->total_sks }} SKS</div>
                    @else
                        <div class="p-3 text-center text-muted italic">
                            IPS & IPK untuk semester ini belum diproses oleh administrator.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
