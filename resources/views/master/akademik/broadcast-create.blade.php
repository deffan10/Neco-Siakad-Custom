@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('admin.broadcast.index') }}" class="btn btn-outline-secondary mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat
                </a>
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Susun pengumuman dan kirimkan ke kelompok pengguna yang ditargetkan.</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Broadcast -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-paper-plane me-2"></i> Tulis Pesan Broadcast</h3>
                </div>
                <form action="{{ route('admin.broadcast.send') }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin mengirimkan email broadcast ini ke semua penerima yang dipilih?')">
                    @csrf
                    <div class="card-body">

                        {{-- Subjek --}}
                        <div class="mb-3">
                            <label class="form-label required fw-bold">Subjek Email</label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                   value="{{ old('subject') }}"
                                   placeholder="Contoh: Pengumuman Jadwal Ujian Akhir Semester Ganjil 2025/2026"
                                   required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Konten --}}
                        <div class="mb-3">
                            <label class="form-label required fw-bold">Isi Pesan / Pengumuman</label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                      rows="10"
                                      placeholder="Tuliskan isi pengumuman di sini. Tekan Enter untuk baris baru."
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Broadcast Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Target Penerima -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users me-2"></i> Target Penerima</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Pilih kelompok pengguna yang akan menerima email broadcast ini.</p>

                    <div class="mb-3">
                        <label class="form-label required fw-bold">Tipe Target</label>
                        <select name="target_type" id="target_type" class="form-select @error('target_type') is-invalid @enderror"
                                onchange="toggleTargetOptions(this.value)" required>
                            <option value="">-- Pilih Target --</option>
                            <option value="semua_dosen" {{ old('target_type') === 'semua_dosen' ? 'selected' : '' }}>Semua Dosen</option>
                            <option value="semua_mahasiswa" {{ old('target_type') === 'semua_mahasiswa' ? 'selected' : '' }}>Semua Mahasiswa</option>
                            <option value="prodi" {{ old('target_type') === 'prodi' ? 'selected' : '' }}>Berdasarkan Program Studi</option>
                            <option value="angkatan" {{ old('target_type') === 'angkatan' ? 'selected' : '' }}>Berdasarkan Angkatan</option>
                        </select>
                        @error('target_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Prodi Filter --}}
                    <div id="prodi_group" class="mb-3" style="display:none">
                        <label class="form-label fw-bold">Program Studi</label>
                        <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('prodi_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Angkatan Filter --}}
                    <div id="angkatan_group" class="mb-3" style="display:none">
                        <label class="form-label fw-bold">Tahun Angkatan</label>
                        <select name="angkatan" class="form-select @error('angkatan') is-invalid @enderror">
                            <option value="">-- Pilih Angkatan --</option>
                            @foreach($angkatans as $ang)
                                <option value="{{ $ang }}" {{ old('angkatan') == $ang ? 'selected' : '' }}>
                                    Angkatan {{ $ang }}
                                </option>
                            @endforeach
                        </select>
                        @error('angkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-warning py-2 mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan setelah email berhasil terkirim.
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body text-muted small">
                    <h5 class="text-dark">Tips Penulisan</h5>
                    <ul class="mb-0 ps-3">
                        <li>Gunakan subjek yang jelas dan ringkas.</li>
                        <li>Tekan Enter untuk membuat baris baru di isi pesan.</li>
                        <li>Pastikan email sudah terisi sebelum dikonfirmasi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleTargetOptions(val) {
        document.getElementById('prodi_group').style.display = (val === 'prodi') ? 'block' : 'none';
        document.getElementById('angkatan_group').style.display = (val === 'angkatan') ? 'block' : 'none';
    }
    // Init on load
    toggleTargetOptions('{{ old("target_type", "") }}');
</script>
@endpush
@endsection
