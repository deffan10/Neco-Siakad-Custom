@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('admin.skl.index') }}" class="btn btn-outline-secondary mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar SKL
                </a>
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Terbitkan SKL resmi sementara dengan data judul skripsi dan IPK kumulatif terintegrasi.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Formulir Penerbitan SKL</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.skl.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label required">Pilih Mahasiswa Lulusan</label>
                            <select class="form-select" name="mahasiswa_id" id="mahasiswa_id" required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $m)
                                    <option value="{{ $m->id }}">
                                        {{ $m->user->name }} (NIM: {{ $m->user->username }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hanya menampilkan mahasiswa aktif tingkat akhir yang belum diterbitkan SKL-nya.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Nomor Surat SKL</label>
                                <input type="text" class="form-control" name="nomor_skl" placeholder="Contoh: 045/SKL/UMMADA/III/2026" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Tanggal Yudisium / Kelulusan</label>
                                <input type="date" class="form-control" name="tanggal_lulus" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">IPK Akhir</label>
                                <input type="number" step="0.01" min="0" max="4.00" class="form-control" name="ipk" id="ipk" placeholder="0.00" required>
                                <small class="text-info text-italic">*Terisi otomatis dari IPK kumulatif terakhir.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Predikat Kelulusan (Yudisium)</label>
                                <select class="form-select" name="yudisium" required>
                                    <option value="Dengan Pujian (Cum Laude)">Dengan Pujian (Cum Laude)</option>
                                    <option value="Sangat Memuaskan">Sangat Memuaskan</option>
                                    <option value="Memuaskan">Memuaskan</option>
                                    <option value="Cukup">Cukup</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Judul Karya Ilmiah / Skripsi</label>
                            <textarea class="form-control" name="judul_skripsi" id="judul_skripsi" rows="3" placeholder="Judul skripsi mahasiswa..." required></textarea>
                            <small class="text-info text-italic">*Terisi otomatis dari judul berkas wisuda disetujui.</small>
                        </div>

                        <div class="hr-text my-4">Pejabat Kampus Penandatangan</div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Nama Pejabat</label>
                                <input type="text" class="form-control" name="pejabat_penandatangan" value="Dr. H. Ahmad, M.Pd." required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Jabatan Resmi</label>
                                <input type="text" class="form-control" name="jabatan_penandatangan" value="Dekan Fakultas Keguruan dan Ilmu Pendidikan" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Terbitkan SKL Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var mhsSelect = document.getElementById('mahasiswa_id');
        if (mhsSelect) {
            mhsSelect.addEventListener('change', function() {
                var mhsId = this.value;
                var ipkInput = document.getElementById('ipk');
                var judulTA = document.getElementById('judul_skripsi');

                if (mhsId) {
                    // Fetch data using fetch API
                    fetch("{{ url('admin/skl/get-student-data') }}/" + mhsId)
                        .then(response => response.json())
                        .then(data => {
                            ipkInput.value = data.ipk;
                            judulTA.value = data.judul_skripsi;
                        })
                        .catch(err => {
                            console.error('Error fetching student data:', err);
                        });
                } else {
                    ipkInput.value = '';
                    judulTA.value = '';
                }
            });
        }
    });
</script>
@endsection
