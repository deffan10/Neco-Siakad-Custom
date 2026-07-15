@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ $pages }}</h2>
                <div class="text-muted mt-1">Daftar tenaga pengajar, fungsional akademis, dan manajemen profil dosen.</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-register">
                    <i class="fas fa-plus me-2"></i>Registrasi Dosen Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Table List -->
    <div class="card mt-3">
        <div class="table-responsive">
            <table class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th>Nama Dosen / Gelar</th>
                        <th>NIDN / NIP</th>
                        <th>Status / Jenis</th>
                        <th>Jabatan Akademik</th>
                        <th>Bidang Keahlian</th>
                        <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dosens as $d)
                        @php
                            $dd = $d->dataDosen;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $d->name }}</strong>
                                @if($dd && $dd->gelar_akademik)
                                    <span class="text-muted">, {{ $dd->gelar_akademik }}</span>
                                @endif
                                <br>
                                <small class="text-muted">{{ $d->email }}</small>
                            </td>
                            <td>
                                <div>NIDN: {{ $dd->nidn ?? '-' }}</div>
                                <div class="text-muted small">NIP: {{ $dd->nip ?? '-' }}</div>
                            </td>
                            <td>
                                <div><span class="badge bg-blue-lt">{{ $dd->status_dosen ?? 'Tetap' }}</span></div>
                                <div class="text-muted small">{{ $dd->jenis_dosen ?? 'Dosen Penuh' }}</div>
                            </td>
                            <td>{{ $dd->jabatan->name ?? '-' }}</td>
                            <td>{{ $dd->bidang_keahlian ?? '-' }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <button class="btn btn-sm btn-white" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $dd->id ?? 0 }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    @if($dd)
                                        <form action="{{ route('admin.dosen.destroy', $dd->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini? Peran dosen dari pengguna ini juga akan dihapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        @if($dd)
                            <div class="modal modal-blur fade" id="modal-edit-{{ $dd->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.dosen.update', $dd->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Profil Dosen: {{ $d->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">NIDN</label>
                                                        <input type="text" name="nidn" class="form-control" value="{{ $dd->nidn }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">NIP</label>
                                                        <input type="text" name="nip" class="form-control" value="{{ $dd->nip }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Status Dosen</label>
                                                        <select name="status_dosen" class="form-select" required>
                                                            <option value="Tetap" {{ $dd->status_dosen === 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                                            <option value="Kontrak" {{ $dd->status_dosen === 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                                            <option value="Tidak Tetap" {{ $dd->status_dosen === 'Tidak Tetap' ? 'selected' : '' }}>Tidak Tetap</option>
                                                            <option value="Emeritus" {{ $dd->status_dosen === 'Emeritus' ? 'selected' : '' }}>Emeritus</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Jenis Dosen</label>
                                                        <select name="jenis_dosen" class="form-select" required>
                                                            <option value="Dosen Penuh" {{ $dd->jenis_dosen === 'Dosen Penuh' ? 'selected' : '' }}>Dosen Penuh</option>
                                                            <option value="Dosen Luar Biasa" {{ $dd->jenis_dosen === 'Dosen Luar Biasa' ? 'selected' : '' }}>Dosen Luar Biasa</option>
                                                            <option value="Doswal" {{ $dd->jenis_dosen === 'Doswal' ? 'selected' : '' }}>Doswal</option>
                                                            <option value="Guest Lecturer" {{ $dd->jenis_dosen === 'Guest Lecturer' ? 'selected' : '' }}>Guest Lecturer</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jabatan Fungsional Akademik</label>
                                                    <select name="jabatan_id" class="form-select">
                                                        <option value="">-- Pilih Jabatan --</option>
                                                        @foreach($jabatans as $j)
                                                            <option value="{{ $j->id }}" {{ $dd->jabatan_id == $j->id ? 'selected' : '' }}>{{ $j->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Gelar Akademik</label>
                                                        <input type="text" name="gelar_akademik" class="form-control" value="{{ $dd->gelar_akademik }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Bidang Keahlian</label>
                                                        <input type="text" name="bidang_keahlian" class="form-control" value="{{ $dd->bidang_keahlian }}">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">NPWP</label>
                                                    <input type="text" name="npwp" class="form-control" value="{{ $dd->npwp }}">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nama Bank</label>
                                                        <input type="text" name="nama_bank" class="form-control" value="{{ $dd->nama_bank }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nomor Rekening</label>
                                                        <input type="text" name="no_rekening" class="form-control" value="{{ $dd->no_rekening }}">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Riwayat Pendidikan</label>
                                                    <textarea name="riwayat_pendidikan" class="form-control" rows="3">{{ $dd->riwayat_pendidikan }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data dosen terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal modal-blur fade" id="modal-register" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.dosen.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrasi Dosen Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Pilih Pengguna</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Pilih Pengguna --</option>
                            @foreach($nonDosens as $nd)
                                <option value="{{ $nd->id }}">{{ $nd->name }} ({{ $nd->email }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pengguna terpilih akan otomatis ditambahkan peran 'dosen'.</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIDN</label>
                            <input type="text" name="nidn" class="form-control" placeholder="cth: 0412345678">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control" placeholder="cth: 19800101...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Status Dosen</label>
                            <select name="status_dosen" class="form-select" required>
                                <option value="Tetap">Tetap</option>
                                <option value="Kontrak">Kontrak</option>
                                <option value="Tidak Tetap">Tidak Tetap</option>
                                <option value="Emeritus">Emeritus</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Jenis Dosen</label>
                            <select name="jenis_dosen" class="form-select" required>
                                <option value="Dosen Penuh">Dosen Penuh</option>
                                <option value="Dosen Luar Biasa">Dosen Luar Biasa</option>
                                <option value="Doswal">Doswal</option>
                                <option value="Guest Lecturer">Guest Lecturer</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan Fungsional Akademik</label>
                        <select name="jabatan_id" class="form-select">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatans as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gelar Akademik</label>
                            <input type="text" name="gelar_akademik" class="form-control" placeholder="cth: S.T., M.T.">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bidang Keahlian</label>
                            <input type="text" name="bidang_keahlian" class="form-control" placeholder="cth: Artificial Intelligence">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NPWP</label>
                        <input type="text" name="npwp" class="form-control" placeholder="cth: 12.345.678.9-012.000">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Bank</label>
                            <input type="text" name="nama_bank" class="form-control" placeholder="cth: Bank Mandiri">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Rekening</label>
                            <input type="text" name="no_rekening" class="form-control" placeholder="cth: 123000998877">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Riwayat Pendidikan</label>
                        <textarea name="riwayat_pendidikan" class="form-control" rows="3" placeholder="S1 Teknik Informatika Univ X, S2 Sistem Informasi Univ Y"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Daftarkan Dosen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
