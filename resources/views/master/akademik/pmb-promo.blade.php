@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Gunakan Email & WhatsApp Blast untuk menjangkau calon pendaftar secara otomatis.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        {{-- Section Send Blast --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kirim Pesan Promosi Massal</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Saluran Pengiriman (Channel)</label>
                        <select class="form-select">
                            <option>WhatsApp (WA Blast)</option>
                            <option>Email Blast (SMTP)</option>
                            <option>Keduanya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target Penerima</label>
                        <select class="form-select">
                            <option>Semua Pendaftar Menunggu</option>
                            <option>Pendaftar Lolos (Belum Daftar Ulang)</option>
                            <option>Kontak Leads Eksternal (Upload CSV)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Template Pesan</label>
                        <select class="form-select">
                            <option>Pengingat Daftar Ulang Gel 1</option>
                            <option>Informasi Tes Seleksi Gelombang 2</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                        Mulai Pengiriman Blast
                    </button>
                </div>
            </div>
        </div>

        {{-- Section WA Templates --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Template Pesan WA & Email</h3>
                    <button class="btn btn-sm btn-primary">+ Template Baru</button>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nama Template</th>
                                <th>Tipe</th>
                                <th>Teks Singkat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Pengingat Daftar Ulang</td>
                                <td><span class="badge bg-green-lt">WhatsApp</span></td>
                                <td>Halo [NAMA], harap segera menyelesaikan pembayaran...</td>
                                <td><button class="btn btn-sm btn-outline-secondary">Edit</button></td>
                            </tr>
                            <tr>
                                <td>Info Kelulusan Seleksi</td>
                                <td><span class="badge bg-blue-lt">Email</span></td>
                                <td>Subjek: Pengumuman Kelulusan UMMADA 2026...</td>
                                <td><button class="btn btn-sm btn-outline-secondary">Edit</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
