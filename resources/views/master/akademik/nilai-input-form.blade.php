@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Input Nilai Kelas: {{ $kelas->mataKuliah->name ?? 'Tidak Ditentukan' }} ({{ $kelas->name }})</h2>
                <div class="text-muted mt-1">Isi nilai Tugas (30%), UTS (30%), dan UAS (40%) untuk masing-masing mahasiswa. Nilai Akhir Angka, Nilai Huruf, dan Indeks akan terhitung otomatis saat disimpan.</div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.nilai.kelas-store', $kelas->id) }}" method="POST">
        @csrf
        <div class="card mt-3">
            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th class="w-10">Nilai Tugas</th>
                            <th class="w-10">Nilai UTS</th>
                            <th class="w-10">Nilai UAS</th>
                            <th>Nilai Akhir (Angka / Huruf)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $st)
                            <tr>
                                <td>{{ $st->mahasiswa->username }}</td>
                                <td><strong>{{ $st->mahasiswa->name }}</strong></td>
                                <td>
                                    <input type="number" step="0.1" class="form-control" name="nilai[{{ $st->id }}][tugas]" 
                                        value="{{ $st->nilai->nilai_tugas ?? 0 }}" min="0" max="100" required>
                                </td>
                                <td>
                                    <input type="number" step="0.1" class="form-control" name="nilai[{{ $st->id }}][uts]" 
                                        value="{{ $st->nilai->nilai_uts ?? 0 }}" min="0" max="100" required>
                                </td>
                                <td>
                                    <input type="number" step="0.1" class="form-control" name="nilai[{{ $st->id }}][uas]" 
                                        value="{{ $st->nilai->nilai_uas ?? 0 }}" min="0" max="100" required>
                                </td>
                                <td>
                                    @if(isset($st->nilai))
                                        <strong>{{ $st->nilai->nilai_akhir_angka }}</strong> / <span class="badge bg-purple">{{ $st->nilai->nilai_huruf }}</span>
                                    @else
                                        <span class="text-muted font-italic">Belum dinilai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada mahasiswa terdaftar di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(!$students->isEmpty())
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan & Hitung Nilai</button>
                </div>
            @endif
        </div>
    </form>
</div>
@endsection
