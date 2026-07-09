@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Cetak Surat Keterangan Lulus (SKL) resmi mandiri yang diterbitkan oleh institusi.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <div class="col-md-8 mx-auto">
            @if(!$skl)
                <!-- SKL Belum Diterbitkan -->
                <div class="card">
                    <div class="card-status-top bg-yellow"></div>
                    <div class="card-body text-center py-5">
                        <span class="avatar avatar-xl mb-4 bg-yellow-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 17h.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                        </span>
                        <h3>SKL Belum Diterbitkan</h3>
                        <p class="text-muted max-w-md mx-auto">
                            Surat Keterangan Lulus (SKL) Anda belum diterbitkan atau sedang dalam tahap validasi oleh operator akademik/BAAK. Silakan selesaikan seluruh berkas administrasi dan biaya wisuda Anda.
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('mahasiswa.wisuda.index') }}" class="btn btn-primary">
                                <i class="fas fa-graduation-cap me-1"></i> Periksa Status Wisuda
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- SKL Tersedia & Siap Cetak -->
                <div class="card">
                    <div class="card-status-top bg-success"></div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <span class="avatar avatar-lg bg-success-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" /></svg>
                            </span>
                            <div>
                                <h3 class="mb-1 text-success">SKL Anda Telah Diterbitkan</h3>
                                <p class="text-muted mb-0">Surat Keterangan Lulus resmi Anda dengan Nomor: <code class="text-purple">{{ $skl->nomor_skl }}</code></p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="w-30">Nomor SKL</th>
                                    <td><strong>{{ $skl->nomor_skl }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Yudisium</th>
                                    <td>{{ \Carbon\Carbon::parse($skl->tanggal_lulus)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>IPK Kelulusan</th>
                                    <td><strong>{{ number_format($skl->ipk, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Predikat (Yudisium)</th>
                                    <td><span class="badge bg-green-lt">{{ $skl->yudisium }}</span></td>
                                </tr>
                                <tr>
                                    <th>Judul Skripsi</th>
                                    <td style="font-style: italic;">"{{ $skl->judul_skripsi }}"</td>
                                </tr>
                                <tr>
                                    <th>Tanda Tangan Pejabat</th>
                                    <td>{{ $skl->pejabat_penandatangan }} ({{ $skl->jabatan_penandatangan }})</td>
                                </tr>
                            </table>
                        </div>

                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-info-circle me-1"></i> SKL ini diterbitkan secara resmi oleh pihak Sekolah Tinggi Farmasi Muhammadiyah Cirebon dan dapat dicetak mandiri sebagai dokumen pengganti ijazah sementara.
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('mahasiswa.skl.print', $skl->id) }}" target="_blank" class="btn btn-success btn-lg">
                            <i class="fas fa-print me-2"></i> Cetak / Unduh SKL (PDF)
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
