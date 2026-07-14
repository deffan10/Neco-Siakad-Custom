@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Konfigurasi NIM, template surat kelulusan, dan field biodata wajib.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        {{-- Section: Generate NIM --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aturan Pembuatan NIM Otomatis</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Format Pola NIM</label>
                        <input type="text" class="form-control" value="[TAHUN][KODE_PRODI][NOMOR_URUT]">
                        <small class="text-muted">Contoh output: 2026110001 (Tahun + Kode Prodi + 4 digit urut)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Auto-Generate NIM saat</label>
                        <select class="form-select">
                            <option>Mahasiswa Melakukan Daftar Ulang</option>
                            <option>Operator Menyetujui Kelulusan</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="button" class="btn btn-primary">Simpan Pola NIM</button>
                </div>
            </div>
        </div>

        {{-- Section: Kelola Field Biodata --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Field Biodata Wajib Camaba</h3>
                </div>
                <div class="card-body">
                    <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column gap-2">
                        <label class="form-selectgroup-item flex-fill">
                            <input type="checkbox" class="form-selectgroup-input" checked>
                            <span class="form-selectgroup-label d-flex align-items-center p-3">
                                <span class="me-3">✔</span>
                                <span>Nomor KTP / NIK (Wajib)</span>
                            </span>
                        </label>
                        <label class="form-selectgroup-item flex-fill">
                            <input type="checkbox" class="form-selectgroup-input" checked>
                            <span class="form-selectgroup-label d-flex align-items-center p-3">
                                <span class="me-3">✔</span>
                                <span>Nama Ibu Kandung (Wajib)</span>
                            </span>
                        </label>
                        <label class="form-selectgroup-item flex-fill">
                            <input type="checkbox" class="form-selectgroup-input">
                            <span class="form-selectgroup-label d-flex align-items-center p-3">
                                <span class="me-3">❌</span>
                                <span>Nilai Rapor Sekolah (Opsional)</span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="button" class="btn btn-primary">Simpan Pengaturan Field</button>
                </div>
            </div>
        </div>

        {{-- Section: Templates Kop & Surat Lulus --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Template Kop & Surat Pengumuman Kelulusan</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Kop Surat Resmi (HTML/Text)</label>
                        <textarea class="form-control" rows="4">PANITIA PENERIMAAN MAHASISWA BARU&#10;UNIVERSITAS MUHAMMADIYAH MADURA&#10;Jl. Raya Raya No. 12, Pamekasan, Jawa Timur</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Template Surat Lulus</label>
                        <textarea class="form-control" rows="6">Dengan ini, Rektor Universitas Muhammadiyah Madura menyatakan bahwa:&#10;Nama: [NAMA_MAHASISWA]&#10;Nomor Pendaftaran: [NOMOR_DAFTAR]&#10;Dinyatakan LULUS Seleksi PMB di Program Studi [PRODI_PIL1].&#10;Harap segera melakukan daftar ulang sebelum tanggal [BATAS_DAFTAR_ULANG].</textarea>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="button" class="btn btn-primary">Simpan Template Surat</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
