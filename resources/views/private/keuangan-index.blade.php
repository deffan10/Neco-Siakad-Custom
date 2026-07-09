@extends('themes.core-backpage')

@section('custom-css')
<style>
    .finance-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        padding: 2rem;
        border-radius: 10px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .bill-card {
        border-left: 5px solid #11998e;
    }
    .bill-unpaid {
        border-left-color: #e53e3e;
    }
</style>
@endsection

@section('content')
<div class="container-xl">
    <div class="row">
        <div class="col-12">
            <!-- Finance Header -->
            <div class="finance-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-1">Portal Keuangan Mahasiswa</h2>
                        <p class="mb-0">Pantau tagihan semester aktif, riwayat transaksi, dan lakukan pembayaran secara instan.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="badge bg-white text-success px-3 py-2 fs-3">Status: Terverifikasi</span>
                    </div>
                </div>
            </div>

            <!-- Daftar Tagihan -->
            <div class="row row-cards">
                <div class="col-md-8">
                    <h3 class="mb-3">Tagihan Kuliah Anda</h3>
                    @forelse($tagihans as $t)
                        <div class="card bill-card {{ $t->status !== 'Lunas' ? 'bill-unpaid' : '' }} mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-muted small">Periode: {{ $t->tahunAkademik->name }}</div>
                                        <h3 class="mb-1 text-primary">Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</h3>
                                        <div class="text-muted">Status Tagihan: 
                                            @if($t->status === 'Lunas')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($t->status === 'Kurang Bayar')
                                                <span class="badge bg-warning">Kurang Bayar</span>
                                            @else
                                                <span class="badge bg-danger">Belum Lunas</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        @if($t->status !== 'Lunas')
                                            <!-- Button trigger modal untuk bayar -->
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal-{{ $t->id }}">
                                                Bayar Tagihan (Simulasi)
                                            </button>
                                        @else
                                            <span class="text-success font-weight-bold">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                Lunas Terbayar
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Simulasi Bayar -->
                        @if($t->status !== 'Lunas')
                            <div class="modal modal-blur fade" id="paymentModal-{{ $t->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Simulasi Pembayaran Tagihan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('mahasiswa.keuangan.pay', $t->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Anda akan melakukan simulasi pembayaran otomatis untuk tagihan periode <strong>{{ $t->tahunAkademik->name }}</strong>.</p>
                                                <div class="mb-3">
                                                    <label class="form-label">Total Tagihan</label>
                                                    <input type="text" class="form-control" value="Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}" disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Pilih Channel Pembayaran</label>
                                                    <select class="form-select" name="channel">
                                                        <option value="Virtual Account BNI">Virtual Account BNI</option>
                                                        <option value="Virtual Account Mandiri">Virtual Account Mandiri</option>
                                                        <option value="Virtual Account BRI">Virtual Account BRI</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Konfirmasi Pembayaran Instan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="card">
                            <div class="card-body text-center text-muted">
                                Tidak ada tagihan kuliah aktif untuk Anda.
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Histori Pembayaran -->
                <div class="col-md-4">
                    <h3 class="mb-3">Riwayat Transaksi</h3>
                    <div class="card">
                        <div class="list-group list-group-flush">
                            @php $hasPayment = false; @endphp
                            @foreach($tagihans as $t)
                                @foreach($t->pembayarans as $p)
                                    @php $hasPayment = true; @endphp
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col text-truncate">
                                                <span class="text-body d-block font-weight-bold">Rp {{ number_format($p->nominal, 0, ',', '.') }}</span>
                                                <small class="d-block text-muted text-truncate mt-n1">{{ $p->channel_pembayaran }} - {{ $p->referensi_transaksi }}</small>
                                            </div>
                                            <div class="col-auto">
                                                @if($p->status === 'Success')
                                                    <span class="badge bg-success">Sukses</span>
                                                @elseif($p->status === 'Failed')
                                                    <span class="badge bg-danger">Gagal</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                            @if(!$hasPayment)
                                <div class="p-3 text-center text-muted">
                                    Belum ada transaksi pembayaran.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
