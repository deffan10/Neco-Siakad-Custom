@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Kalkulasi Indeks Prestasi Semester (IPS) dan Indeks Prestasi Kumulatif (IPK) secara masal untuk seluruh mahasiswa.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Proses IPS/IPK Mahasiswa</h3>
                </div>
                <div class="card-body text-center py-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/3201/3201558.png" height="120" class="mb-3" alt="Calculator Icon">
                    <p>Proses ini akan mengumpulkan seluruh nilai mahasiswa di semester terpilih, menghitung rata-rata tertimbang berdasarkan SKS (IPS), serta melakukan akumulasi nilai dari semester-semester sebelumnya untuk menghasilkan IPK.</p>
                    
                    <form action="{{ route('admin.nilai.hitung-ipk') }}" method="POST" id="prosesForm" class="mt-4 text-start">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Pilih Semester yang Ingin Diproses</label>
                            <select class="form-select" name="tahun_akademik_id" required>
                                @foreach($tahunAkademiks as $ta)
                                    <option value="{{ $ta->id }}" {{ $ta->is_active ? 'selected' : '' }}>
                                        {{ $ta->name }} ({{ $ta->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="btnSubmit">
                            Mulai Pemrosesan IPS/IPK
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Catatan & Ketentuan</h3>
                </div>
                <div class="card-body">
                    <h4>Bagaimana IPS & IPK Dihitung?</h4>
                    <p>Sistem ini menggunakan rumus standar pendidikan tinggi nasional:</p>
                    <ul>
                        <li><strong>IPS (IP Semester)</strong>: Total (Bobot Indeks Matakuliah &times; SKS) dibagi Total SKS yang diambil pada semester tersebut.</li>
                        <li><strong>IPK (IP Kumulatif)</strong>: Total (Bobot Indeks Matakuliah &times; SKS) dari seluruh semester yang telah dilalui dibagi Total SKS Kumulatif.</li>
                    </ul>
                    <div class="alert alert-info">
                        <strong>Info:</strong> Hasil kalkulasi IP ini akan langsung digunakan oleh modul KRS untuk menentukan batas beban SKS maksimum yang boleh diambil mahasiswa di semester berikutnya.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
