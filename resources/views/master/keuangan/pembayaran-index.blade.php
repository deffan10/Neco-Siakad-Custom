@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Konfirmasi dan verifikasi bukti transfer / pembayaran dari mahasiswa.</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card mt-3">
        <div class="card-body py-2">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.keuangan.tagihan-index') }}"><i class="fas fa-file-invoice-dollar me-1"></i> Data Tagihan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="{{ route('admin.keuangan.pembayaran-index') }}"><i class="fas fa-receipt me-1"></i> Verifikasi Pembayaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('admin.keuangan.tarif-index') }}"><i class="fas fa-cogs me-1"></i> Konfigurasi Tarif & Komponen</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Riwayat & Konfirmasi Pembayaran</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap">
                <thead>
                    <tr>
                        <th>Referensi</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Channel</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $p)
                        <tr>
                            <td><span class="badge bg-purple-lt">{{ $p->referensi_transaksi }}</span></td>
                            <td>{{ $p->tagihan->mahasiswa->username }}</td>
                            <td><strong>{{ $p->tagihan->mahasiswa->name }}</strong></td>
                            <td>{{ $p->channel_pembayaran }}</td>
                            <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                            <td>
                                @if($p->status === 'Success')
                                    <span class="badge bg-success">Berhasil</span>
                                @elseif($p->status === 'Failed')
                                    <span class="badge bg-danger">Gagal / Ditolak</span>
                                @else
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @endif
                            </td>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($p->status === 'Pending')
                                    <a href="{{ route('admin.keuangan.pembayaran-verify', [$p->id, 'approve']) }}" class="btn btn-sm btn-success">Setujui</a>
                                    <a href="{{ route('admin.keuangan.pembayaran-verify', [$p->id, 'reject']) }}" class="btn btn-sm btn-danger">Tolak</a>
                                @else
                                    <span class="text-muted font-italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada transaksi pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pembayarans->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $pembayarans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
