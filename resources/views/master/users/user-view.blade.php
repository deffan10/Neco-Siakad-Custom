@extends('themes.core-backpage')

@section('custom-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/libs/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet" />
    <script src="{{ asset('assets') }}/libs/tom-select/dist/js/tom-select.base.min.js" defer></script>
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 10px;
        color: white;
        margin-bottom: 2rem;
    }
    .profile-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        object-fit: cover;
    }
    .nav-tabs .nav-link.active {
        background-color: #667eea;
        color: white;
        border-color: #667eea;
    }
    .form-section {
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="{{ $users->photo }}" alt="Profile Photo" class="profile-photo" id="previewPhoto">
                        </div>
                        <div class="col-md-10">
                            <h2 class="mb-0">{{ $users->name }}</h2>
                            <p class="mb-1">{{ ucfirst($activeRole) }}</p>
                            <p class="mb-0"><i class="fas fa-envelope"></i> {{ $users->email }} | <i class="fas fa-phone"></i> {{ $users->phone }}</p>
                        </div>
                    </div>
                </div>
                <!-- Form Update Profile -->
                <form action="{{ route($activeRole . '.users.user-update', $users->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#biodata" role="tab">
                                <i class="fas fa-user me-2"></i> Biodata
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kontak" role="tab">
                                <i class="fas fa-address-card me-2"></i> Kontak & Sosial Media
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#identitas" role="tab">
                                <i class="fas fa-id-card me-2"></i> Identitas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#alamat" role="tab">
                                <i class="fas fa-map-marker-alt me-2"></i> Alamat
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pendidikan" role="tab">
                                <i class="fas fa-graduation-cap me-2"></i> Pendidikan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#keluarga" role="tab">
                                <i class="fas fa-users me-2"></i> Keluarga
                            </a>
                        </li>

                        {{-- Role-specific tabs based on user roles --}}
                        @if($users->hasRole('mahasiswa'))
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#data-mahasiswa" role="tab">
                                    <i class="fas fa-book me-2"></i> Data Akademik
                                </a>
                            </li>
                        @endif

                        @if($users->hasRole('karyawan') || $users->hasRole('tendik'))
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#data-karyawan" role="tab">
                                    <i class="fas fa-briefcase me-2"></i> Data Karyawan
                                </a>
                            </li>
                        @endif

                        @if($users->hasRole('dosen'))
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#data-dosen" role="tab">
                                    <i class="fas fa-chalkboard-teacher me-2"></i> Data Dosen
                                </a>
                            </li>
                        @endif

                        @if($users->hasRole('peserta-pmb'))
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#data-pmb" role="tab">
                                    <i class="fas fa-user-check me-2"></i> Data Pendaftaran
                                </a>
                            </li>
                        @endif

                        @if($users->hasRole('alumni'))
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#data-alumni" role="tab">
                                    <i class="fas fa-graduation-cap me-2"></i> Data Alumni
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#keamanan" role="tab">
                                <i class="fas fa-lock me-2"></i> Keamanan
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content p-3">
                        <!-- Tab Biodata -->
                        <div class="tab-pane active" id="biodata" role="tabpanel">
                            <div class="form-section">
                                <h5 class="mb-3">Informasi Dasar</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ $users->name }}" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" name="username" value="{{ $users->username }}" placeholder="Masukkan username unik">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Foto Profile</label>
                                        <input type="file" class="form-control" name="photo" accept="image/*" onchange="previewImage(this)">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" name="jenis_kelamin_id">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            @foreach($jenisKelamins as $jk)
                                                <option value="{{ $jk->id }}" {{ $users->jenis_kelamin_id == $jk->id ? 'selected' : '' }}>
                                                    {{ $jk->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" class="form-control" name="tempat_lahir" value="{{ $users->tempat_lahir }}" placeholder="Contoh: Jakarta">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tanggal_lahir" value="{{ $users->tanggal_lahir }}" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="mb-3">Informasi Tambahan</h5>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Agama</label>
                                        <select class="form-select" name="agama_id">
                                            <option value="">Pilih Agama</option>
                                            @foreach($agamas as $agama)
                                                <option value="{{ $agama->id }}" {{ $users->agama_id == $agama->id ? 'selected' : '' }}>
                                                    {{ $agama->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Golongan Darah</label>
                                        <select class="form-select" name="golongan_darah_id">
                                            <option value="">Pilih Golongan Darah</option>
                                            @foreach($golonganDarahs as $gd)
                                                <option value="{{ $gd->id }}" {{ $users->golongan_darah_id == $gd->id ? 'selected' : '' }}>
                                                    {{ $gd->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Kewarganegaraan</label>
                                        <select class="form-select" name="kewarganegaraan_id">
                                            <option value="">Pilih Kewarganegaraan</option>
                                            @foreach($kewarganegaraans as $kwn)
                                                <option value="{{ $kwn->id }}" {{ $users->kewarganegaraan_id == $kwn->id ? 'selected' : '' }}>
                                                    {{ $kwn->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Tinggi Badan (cm)</label>
                                        <input type="text" class="form-control" name="tinggi_badan" value="{{ $users->tinggi_badan }}" placeholder="Contoh: 170">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Berat Badan (kg)</label>
                                        <input type="text" class="form-control" name="berat_badan" value="{{ $users->berat_badan }}" placeholder="Contoh: 65">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Role <span class="text-danger">*</span></label>
                                        <select class="form-select" name="roles[]" id="select-roles" multiple required>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ $users->hasRole($role->name) ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Pilih satu atau lebih role untuk user</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Kontak -->
                        <div class="tab-pane" id="kontak" role="tabpanel">
                            <div class="form-section">
                                <h5 class="mb-3">Informasi Kontak</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" value="{{ $users->email }}" placeholder="contoh@email.com" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" value="{{ $users->phone }}" placeholder="Contoh: 081234567890" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="mb-3">Sosial Media</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label"><i class="fab fa-instagram"></i> Instagram</label>
                                        <input type="text" class="form-control" name="link_ig" value="{{ $users->link_ig }}" placeholder="@username_instagram">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label"><i class="fab fa-facebook"></i> Facebook</label>
                                        <input type="text" class="form-control" name="link_fb" value="{{ $users->link_fb }}" placeholder="facebook.com/username">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label"><i class="fab fa-linkedin"></i> LinkedIn</label>
                                        <input type="text" class="form-control" name="link_in" value="{{ $users->link_in }}" placeholder="linkedin.com/in/username">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Identitas -->
                        <div class="tab-pane" id="identitas" role="tabpanel">
                            <div class="form-section">
                                <h5 class="mb-3">Nomor Identitas</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nomor KK</label>
                                        <input type="text" class="form-control" name="nomor_kk" value="{{ $users->nomor_kk }}" placeholder="1234567890123456" maxlength="16">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nomor KTP</label>
                                        <input type="text" class="form-control" name="nomor_ktp" value="{{ $users->nomor_ktp }}" placeholder="1234567890123456" maxlength="16">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nomor NPWP</label>
                                        <input type="text" class="form-control" name="nomor_npwp" value="{{ $users->nomor_npwp }}" placeholder="123456789012345" maxlength="15">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Alamat -->
                        <div class="tab-pane" id="alamat" role="tabpanel">
                            <div class="form-section">
                                <h5 class="mb-3">Alamat KTP</h5>
                                <div class="row">
                                    <input type="hidden" name="alamat_ktp[id]" value="{{ $users->alamat_ktp->id ?? '' }}">
                                    <input type="hidden" name="alamat_ktp[tipe]" value="ktp">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Alamat Lengkap KTP</label>
                                        <textarea class="form-control" name="alamat_ktp[alamat_lengkap]" rows="3" 
                                                  placeholder="Jl. Contoh No. 123, RT 01/RW 02">{{ $users->alamat_ktp->alamat_lengkap ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">RT</label>
                                        <input type="text" class="form-control" name="alamat_ktp[rt]" 
                                               value="{{ $users->alamat_ktp->rt ?? '' }}" placeholder="001">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">RW</label>
                                        <input type="text" class="form-control" name="alamat_ktp[rw]" 
                                               value="{{ $users->alamat_ktp->rw ?? '' }}" placeholder="002">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kelurahan/Desa</label>
                                        <input type="text" class="form-control" name="alamat_ktp[kelurahan]" 
                                               value="{{ $users->alamat_ktp->kelurahan ?? '' }}" placeholder="Nama kelurahan/desa">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kecamatan</label>
                                        <input type="text" class="form-control" name="alamat_ktp[kecamatan]" 
                                               value="{{ $users->alamat_ktp->kecamatan ?? '' }}" placeholder="Nama kecamatan">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kota/Kabupaten</label>
                                        <input type="text" class="form-control" name="alamat_ktp[kota_kabupaten]" 
                                               value="{{ $users->alamat_ktp->kota_kabupaten ?? '' }}" placeholder="Nama kota/kabupaten">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Provinsi</label>
                                        <input type="text" class="form-control" name="alamat_ktp[provinsi]" 
                                               value="{{ $users->alamat_ktp->provinsi ?? '' }}" placeholder="Nama provinsi">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kode Pos</label>
                                        <input type="text" class="form-control" name="alamat_ktp[kode_pos]" 
                                               value="{{ $users->alamat_ktp->kode_pos ?? '' }}" placeholder="12345" maxlength="10">
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Alamat Domisili</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="samaDenganKTP" onchange="copyFromKTP()">
                                        <label class="form-check-label" for="samaDenganKTP">
                                            Sama dengan alamat KTP
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" name="alamat_domisili[id]" value="{{ $users->alamat_domisili->id ?? '' }}">
                                    <input type="hidden" name="alamat_domisili[tipe]" value="domisili">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Alamat Lengkap Domisili</label>
                                        <textarea class="form-control" name="alamat_domisili[alamat_lengkap]" rows="3" 
                                                  placeholder="Jl. Contoh No. 123, RT 01/RW 02" id="domisili_alamat_lengkap">{{ $users->alamat_domisili->alamat_lengkap ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">RT</label>
                                        <input type="text" class="form-control" name="alamat_domisili[rt]" 
                                               value="{{ $users->alamat_domisili->rt ?? '' }}" placeholder="001" id="domisili_rt">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">RW</label>
                                        <input type="text" class="form-control" name="alamat_domisili[rw]" 
                                               value="{{ $users->alamat_domisili->rw ?? '' }}" placeholder="002" id="domisili_rw">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kelurahan/Desa</label>
                                        <input type="text" class="form-control" name="alamat_domisili[kelurahan]" 
                                               value="{{ $users->alamat_domisili->kelurahan ?? '' }}" placeholder="Nama kelurahan/desa" id="domisili_kelurahan">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kecamatan</label>
                                        <input type="text" class="form-control" name="alamat_domisili[kecamatan]" 
                                               value="{{ $users->alamat_domisili->kecamatan ?? '' }}" placeholder="Nama kecamatan" id="domisili_kecamatan">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kota/Kabupaten</label>
                                        <input type="text" class="form-control" name="alamat_domisili[kota_kabupaten]" 
                                               value="{{ $users->alamat_domisili->kota_kabupaten ?? '' }}" placeholder="Nama kota/kabupaten" id="domisili_kota_kabupaten">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Provinsi</label>
                                        <input type="text" class="form-control" name="alamat_domisili[provinsi]" 
                                               value="{{ $users->alamat_domisili->provinsi ?? '' }}" placeholder="Nama provinsi" id="domisili_provinsi">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kode Pos</label>
                                        <input type="text" class="form-control" name="alamat_domisili[kode_pos]" 
                                               value="{{ $users->alamat_domisili->kode_pos ?? '' }}" placeholder="12345" maxlength="10" id="domisili_kode_pos">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Pendidikan -->
                        <div class="tab-pane" id="pendidikan" role="tabpanel">
                            <div class="form-section">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Riwayat Pendidikan</h5>
                                    <button type="button" class="btn btn-sm btn-success" onclick="addPendidikan()">
                                        <i class="fas fa-plus"></i> Tambah Pendidikan
                                    </button>
                                </div>
                                
                                <div id="pendidikan-container">
                                    @forelse($users->pendidikans as $index => $pendidikan)
                                        <div class="card mb-3 pendidikan-item" data-index="{{ $index }}">
                                            <div class="card-body">
                                                <div class="row">
                                                    <input type="hidden" name="pendidikan[{{ $index }}][id]" value="{{ $pendidikan->id }}">
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Jenjang Pendidikan</label>
                                                        <select class="form-select" name="pendidikan[{{ $index }}][jenjang]" required>
                                                            <option value="">Pilih Jenjang</option>
                                                            <option value="Paket C" {{ $pendidikan->jenjang == 'Paket C' ? 'selected' : '' }}>Paket C</option>
                                                            <option value="SMA" {{ $pendidikan->jenjang == 'SMA' ? 'selected' : '' }}>SMA</option>
                                                            <option value="SMK" {{ $pendidikan->jenjang == 'SMK' ? 'selected' : '' }}>SMK</option>
                                                            <option value="D3" {{ $pendidikan->jenjang == 'D3' ? 'selected' : '' }}>Diploma 3</option>
                                                            <option value="S1" {{ $pendidikan->jenjang == 'S1' ? 'selected' : '' }}>Sarjana (S1)</option>
                                                            <option value="S2" {{ $pendidikan->jenjang == 'S2' ? 'selected' : '' }}>Magister (S2)</option>
                                                            <option value="S3" {{ $pendidikan->jenjang == 'S3' ? 'selected' : '' }}>Doktor (S3)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">Nama Institusi</label>
                                                        <input type="text" class="form-control" name="pendidikan[{{ $index }}][nama_institusi]" 
                                                               value="{{ $pendidikan->nama_institusi }}" placeholder="Nama sekolah/universitas" required>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Jurusan/Program Studi</label>
                                                        <input type="text" class="form-control" name="pendidikan[{{ $index }}][jurusan]" 
                                                               value="{{ $pendidikan->jurusan }}" placeholder="Nama jurusan/prodi">
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label class="form-label">Aksi</label>
                                                        <button type="button" data-id="{{ $pendidikan->id }}" data-name="{{ $pendidikan->nama_institusi }}" class="btn btn-danger btn-sm d-block delete-pendidikan" data-index="{{ $index }}">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label class="form-label">Tahun Masuk</label>
                                                        <input type="number" class="form-control" name="pendidikan[{{ $index }}][tahun_masuk]" 
                                                               value="{{ $pendidikan->tahun_masuk }}" placeholder="2020" min="1950" max="{{ date('Y') }}">
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label class="form-label">Tahun Lulus</label>
                                                        <input type="number" class="form-control" name="pendidikan[{{ $index }}][tahun_lulus]" 
                                                               value="{{ $pendidikan->tahun_lulus }}" placeholder="2024" min="1950" max="{{ date('Y') + 10 }}">
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label class="form-label">IPK/Nilai</label>
                                                        <input type="text" class="form-control" name="pendidikan[{{ $index }}][ipk]" 
                                                               value="{{ $pendidikan->ipk }}" placeholder="3.50">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Alamat Institusi</label>
                                                        <textarea class="form-control" name="pendidikan[{{ $index }}][alamat]" rows="2" 
                                                                  placeholder="Alamat lengkap institusi">{{ $pendidikan->alamat }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center">Belum ada data pendidikan. Klik tombol "Tambah Pendidikan" untuk menambah data.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Tab Keluarga -->
                        <div class="tab-pane" id="keluarga" role="tabpanel">
                            <div class="form-section">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Data Keluarga</h5>
                                    <button type="button" class="btn btn-sm btn-success" onclick="addKeluarga()">
                                        <i class="fas fa-plus"></i> Tambah Anggota Keluarga
                                    </button>
                                </div>
                                
                                <div id="keluarga-container">
                                    @forelse($users->keluargas as $index => $keluarga)
                                        <div class="card mb-3 keluarga-item" data-index="{{ $index }}">
                                            <div class="card-body">
                                                <div class="row">
                                                    <input type="hidden" name="keluarga[{{ $index }}][id]" value="{{ $keluarga->id }}">
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Hubungan Keluarga</label>
                                                        <select class="form-select" name="keluarga[{{ $index }}][hubungan]" required>
                                                            <option value="">Pilih Hubungan</option>
                                                            <option value="Ayah" {{ $keluarga->hubungan == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                                            <option value="Ibu" {{ $keluarga->hubungan == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                                            <option value="Suami" {{ $keluarga->hubungan == 'Suami' ? 'selected' : '' }}>Suami</option>
                                                            <option value="Istri" {{ $keluarga->hubungan == 'Istri' ? 'selected' : '' }}>Istri</option>
                                                            <option value="Anak" {{ $keluarga->hubungan == 'Anak' ? 'selected' : '' }}>Anak</option>
                                                            <option value="Kakak" {{ $keluarga->hubungan == 'Kakak' ? 'selected' : '' }}>Kakak</option>
                                                            <option value="Adik" {{ $keluarga->hubungan == 'Adik' ? 'selected' : '' }}>Adik</option>
                                                            <option value="Wali" {{ $keluarga->hubungan == 'Wali' ? 'selected' : '' }}>Wali</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">Nama Lengkap</label>
                                                        <input type="text" class="form-control" name="keluarga[{{ $index }}][nama]" 
                                                               value="{{ $keluarga->nama }}" placeholder="Nama lengkap anggota keluarga" required>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Pekerjaan</label>
                                                        <input type="text" class="form-control" name="keluarga[{{ $index }}][pekerjaan]" 
                                                               value="{{ $keluarga->pekerjaan }}" placeholder="Pekerjaan/profesi">
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label class="form-label">Aksi</label>
                                                        <button type="button" data-id="{{ $keluarga->id }}" data-name="{{ $keluarga->nama }}" class="btn btn-danger btn-sm d-block delete-keluarga" data-index="{{ $index }}">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Nomor Telepon</label>
                                                        <input type="text" class="form-control" name="keluarga[{{ $index }}][telepon]" 
                                                               value="{{ $keluarga->telepon }}" placeholder="081234567890">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Tempat Lahir</label>
                                                        <input type="text" class="form-control" name="keluarga[{{ $index }}][tempat_lahir]" 
                                                               value="{{ $keluarga->tempat_lahir }}" placeholder="Jakarta">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Tanggal Lahir</label>
                                                        <input type="date" class="form-control" name="keluarga[{{ $index }}][tanggal_lahir]" 
                                                               value="{{ $keluarga->tanggal_lahir }}">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Penghasilan (Rp)</label>
                                                        <input type="number" class="form-control" name="keluarga[{{ $index }}][penghasilan]" 
                                                               value="{{ $keluarga->penghasilan }}" placeholder="5000000">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Alamat</label>
                                                        <textarea class="form-control" name="keluarga[{{ $index }}][alamat]" rows="2" 
                                                                  placeholder="Alamat lengkap">{{ $keluarga->alamat }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center">Belum ada data keluarga. Klik tombol "Tambah Anggota Keluarga" untuk menambah data.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Tab Data Mahasiswa (Student) -->
                        @if($users->hasRole('mahasiswa'))
                        <div class="tab-pane" id="data-mahasiswa" role="tabpanel">
                            <div class="form-section">
                                <h5 class="mb-3">Informasi Akademik</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">NIM <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nim" value="{{ old('nim', $users->dataMahasiswa?->nim ?? '') }}" placeholder="Nomor Induk Mahasiswa" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Program Studi</label>
                                        <select class="form-select" name="program_studi_id">
                                            <option value="">Pilih Program Studi</option>
                                            @if(isset($programStudis))
                                                @foreach($programStudis as $ps)
                                                    <option value="{{ $ps->id }}" {{ (old('program_studi_id') ?? $users->dataMahasiswa?->program_studi_id ?? null) == $ps->id ? 'selected' : '' }}>
                                                        {{ $ps->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Angkatan</label>
                                        <input type="number" class="form-control" name="angkatan" value="{{ old('angkatan', $users->dataMahasiswa?->angkatan ?? '') }}" placeholder="2021" min="1990" max="{{ date('Y') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Tanggal Masuk</label>
                                        <input type="date" class="form-control" name="tanggal_masuk" value="{{ old('tanggal_masuk', $users->dataMahasiswa?->tanggal_masuk ?? '') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">IPK</label>
                                        <input type="number" step="0.01" class="form-control" name="ipk" value="{{ old('ipk', $users->dataMahasiswa?->ipk ?? '') }}" placeholder="3.50" min="0" max="4">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">SKS Lulus</label>
                                        <input type="number" class="form-control" name="sks_lulus" value="{{ old('sks_lulus', $users->dataMahasiswa?->sks_lulus ?? '') }}" placeholder="120">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Jenis Pembiayaan</label>
                                        <select class="form-select" name="jenis_pembiayaan">
                                            <option value="">Pilih Jenis</option>
                                            <option value="Mandiri" {{ (old('jenis_pembiayaan') ?? $users->dataMahasiswa?->jenis_pembiayaan ?? null) == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                            <option value="Beasiswa" {{ (old('jenis_pembiayaan') ?? $users->dataMahasiswa?->jenis_pembiayaan ?? null) == 'Beasiswa' ? 'selected' : '' }}>Beasiswa</option>
                                            <option value="Beasiswa Penuh" {{ (old('jenis_pembiayaan') ?? $users->dataMahasiswa?->jenis_pembiayaan ?? null) == 'Beasiswa Penuh' ? 'selected' : '' }}>Beasiswa Penuh</option>
                                            <option value="Subsidi" {{ (old('jenis_pembiayaan') ?? $users->dataMahasiswa?->jenis_pembiayaan ?? null) == 'Subsidi' ? 'selected' : '' }}>Subsidi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Asal Sekolah</label>
                                        <input type="text" class="form-control" name="asal_sekolah" value="{{ old('asal_sekolah', $users->dataMahasiswa?->asal_sekolah ?? '') }}" placeholder="Nama sekolah asal">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Tab Data Karyawan (Employee) -->
                        @if($users->hasRole('karyawan') || $users->hasRole('tendik'))
                        <div class="tab-pane" id="data-karyawan" role="tabpanel">
                            <div class="form-section">
                                <h5 class="mb-3">Informasi Karyawan</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nip" value="{{ old('nip', $users->dataKaryawan?->nip ?? '') }}" placeholder="Nomor Induk Pegawai" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">NIK</label>
                                        <input type="text" class="form-control" name="nik" value="{{ old('nik', $users->dataKaryawan?->nik ?? '') }}" placeholder="Nomor Identitas Karyawan" maxlength="16">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Status Kerja</label>
                                        <select class="form-select" name="status_kerja" required>
                                            <option value="">Pilih Status</option>
                                            <option value="Tetap" {{ (old('status_kerja') ?? $users->dataKaryawan?->status_kerja ?? null) == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                            <option value="Kontrak" {{ (old('status_kerja') ?? $users->dataKaryawan?->status_kerja ?? null) == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                            <option value="Honorer" {{ (old('status_kerja') ?? $users->dataKaryawan?->status_kerja ?? null) == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                                            <option value="Outsourcing" {{ (old('status_kerja') ?? $users->dataKaryawan?->status_kerja ?? null) == 'Outsourcing' ? 'selected' : '' }}>Outsourcing</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Tanggal Bergabung</label>
                                        <input type="date" class="form-control" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $users->dataKaryawan?->tanggal_bergabung ?? '') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Tanggal Akhir Kontrak</label>
                                        <input type="date" class="form-control" name="tanggal_berakhir_kontrak" value="{{ old('tanggal_berakhir_kontrak', $users->dataKaryawan?->tanggal_berakhir_kontrak ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="mb-3">Informasi Perbankan</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">No. Rekening</label>
                                        <input type="text" class="form-control" name="no_rekening" value="{{ old('no_rekening', $users->dataKaryawan?->no_rekening ?? '') }}" placeholder="1234567890">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nama Bank</label>
                                        <input type="text" class="form-control" name="nama_bank" value="{{ old('nama_bank', $users->dataKaryawan?->nama_bank ?? '') }}" placeholder="Nama Bank">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Atas Nama Rekening</label>
                                        <input type="text" class="form-control" name="atas_nama_rekening" value="{{ old('atas_nama_rekening', $users->dataKaryawan?->atas_nama_rekening ?? '') }}" placeholder="Nama Pemilik Rekening">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">NPWP</label>
                                        <input type="text" class="form-control" name="npwp" value="{{ old('npwp', $users->dataKaryawan?->npwp ?? '') }}" placeholder="123456789012345" maxlength="15">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Tab Data Dosen (Lecturer) -->
                        @if($users->hasRole('dosen'))
                        <div class="tab-pane" id="data-dosen" role="tabpanel">
                            <div class="form-section">
                                <h5 class="mb-3">Identifikasi Dosen</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">NIDN <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nidn" value="{{ old('nidn', $users->dataDosen?->nidn ?? '') }}" placeholder="Nomor Identitas Dosen Nasional" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">NIP</label>
                                        <input type="text" class="form-control" name="nip" value="{{ old('nip', $users->dataDosen?->nip ?? '') }}" placeholder="Nomor Induk Pegawai (jika ada)">
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="mb-3">Informasi Kepegawaian</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Status Dosen</label>
                                        <select class="form-select" name="status_dosen">
                                            <option value="">Pilih Status</option>
                                            <option value="Tetap" {{ (old('status_dosen') ?? $users->dataDosen?->status_dosen ?? null) == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                            <option value="Kontrak" {{ (old('status_dosen') ?? $users->dataDosen?->status_dosen ?? null) == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                            <option value="Tidak Tetap" {{ (old('status_dosen') ?? $users->dataDosen?->status_dosen ?? null) == 'Tidak Tetap' ? 'selected' : '' }}>Tidak Tetap</option>
                                            <option value="Emeritus" {{ (old('status_dosen') ?? $users->dataDosen?->status_dosen ?? null) == 'Emeritus' ? 'selected' : '' }}>Emeritus</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Jenis Dosen</label>
                                        <select class="form-select" name="jenis_dosen">
                                            <option value="">Pilih Jenis</option>
                                            <option value="Dosen Penuh" {{ (old('jenis_dosen') ?? $users->dataDosen?->jenis_dosen ?? null) == 'Dosen Penuh' ? 'selected' : '' }}>Dosen Penuh</option>
                                            <option value="Dosen Luar Biasa" {{ (old('jenis_dosen') ?? $users->dataDosen?->jenis_dosen ?? null) == 'Dosen Luar Biasa' ? 'selected' : '' }}>Dosen Luar Biasa</option>
                                            <option value="Doswal" {{ (old('jenis_dosen') ?? $users->dataDosen?->jenis_dosen ?? null) == 'Doswal' ? 'selected' : '' }}>Doswal</option>
                                            <option value="Guest Lecturer" {{ (old('jenis_dosen') ?? $users->dataDosen?->jenis_dosen ?? null) == 'Guest Lecturer' ? 'selected' : '' }}>Guest Lecturer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tanggal Bergabung</label>
                                        <input type="date" class="form-control" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $users->dataDosen?->tanggal_bergabung ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="mb-3">Informasi Akademik</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Bidang Keahlian</label>
                                        <input type="text" class="form-control" name="bidang_keahlian" value="{{ old('bidang_keahlian', $users->dataDosen?->bidang_keahlian ?? '') }}" placeholder="Contoh: Teknologi Informasi">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gelar Akademik</label>
                                        <input type="text" class="form-control" name="gelar_akademik" value="{{ old('gelar_akademik', $users->dataDosen?->gelar_akademik ?? '') }}" placeholder="Contoh: S.Kom, M.Cs">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Riwayat Pendidikan</label>
                                        <textarea class="form-control" name="riwayat_pendidikan" rows="3" placeholder="Masukkan riwayat pendidikan formal">{{ old('riwayat_pendidikan', $users->dataDosen?->riwayat_pendidikan ?? '') }}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Sertifikasi</label>
                                        <textarea class="form-control" name="sertifikasi" rows="3" placeholder="Masukkan sertifikasi yang dimiliki">{{ old('sertifikasi', $users->dataDosen?->sertifikasi ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="mb-3">Informasi Perbankan</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">No. Rekening</label>
                                        <input type="text" class="form-control" name="no_rekening" value="{{ old('no_rekening', $users->dataDosen?->no_rekening ?? '') }}" placeholder="1234567890">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nama Bank</label>
                                        <input type="text" class="form-control" name="nama_bank" value="{{ old('nama_bank', $users->dataDosen?->nama_bank ?? '') }}" placeholder="Nama Bank">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">NPWP</label>
                                        <input type="text" class="form-control" name="npwp" value="{{ old('npwp', $users->dataDosen?->npwp ?? '') }}" placeholder="123456789012345">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Tab Data PMB (New Student Admission) -->
                        @if($users->hasRole('peserta-pmb'))
                        <div class="tab-pane" id="data-pmb" role="tabpanel">
                            <div class="form-section">
                                <h5 class="mb-3">Informasi Pendaftaran PMB</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nomor Pendaftaran <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nomor_pendaftaran" value="{{ old('nomor_pendaftaran', $users->dataPestaPMB?->nomor_pendaftaran ?? '') }}" placeholder="Nomor Pendaftaran PMB" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tahun Masuk</label>
                                        <input type="number" class="form-control" name="tahun_masuk" value="{{ old('tahun_masuk', $users->dataPestaPMB?->tahun_masuk ?? '') }}" placeholder="{{ date('Y') }}" min="2000" max="{{ date('Y') + 5 }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jalur Masuk</label>
                                        <select class="form-select" name="jalur_masuk">
                                            <option value="">Pilih Jalur</option>
                                            <option value="SNMPTN" {{ (old('jalur_masuk') ?? $users->dataPestaPMB?->jalur_masuk ?? null) == 'SNMPTN' ? 'selected' : '' }}>SNMPTN</option>
                                            <option value="SBMPTN" {{ (old('jalur_masuk') ?? $users->dataPestaPMB?->jalur_masuk ?? null) == 'SBMPTN' ? 'selected' : '' }}>SBMPTN</option>
                                            <option value="Mandiri" {{ (old('jalur_masuk') ?? $users->dataPestaPMB?->jalur_masuk ?? null) == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                            <option value="Khusus" {{ (old('jalur_masuk') ?? $users->dataPestaPMB?->jalur_masuk ?? null) == 'Khusus' ? 'selected' : '' }}>Khusus</option>
                                            <option value="Transfer" {{ (old('jalur_masuk') ?? $users->dataPestaPMB?->jalur_masuk ?? null) == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status Pendaftaran</label>
                                        <select class="form-select" name="status_pendaftaran">
                                            <option value="">Pilih Status</option>
                                            <option value="Menunggu" {{ (old('status_pendaftaran') ?? $users->dataPestaPMB?->status_pendaftaran ?? null) == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="Lolos" {{ (old('status_pendaftaran') ?? $users->dataPestaPMB?->status_pendaftaran ?? null) == 'Lolos' ? 'selected' : '' }}>Lolos</option>
                                            <option value="Tidak Lolos" {{ (old('status_pendaftaran') ?? $users->dataPestaPMB?->status_pendaftaran ?? null) == 'Tidak Lolos' ? 'selected' : '' }}>Tidak Lolos</option>
                                            <option value="Daftar Ulang" {{ (old('status_pendaftaran') ?? $users->dataPestaPMB?->status_pendaftaran ?? null) == 'Daftar Ulang' ? 'selected' : '' }}>Daftar Ulang</option>
                                            <option value="Batal" {{ (old('status_pendaftaran') ?? $users->dataPestaPMB?->status_pendaftaran ?? null) == 'Batal' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Program Pilihan 1</label>
                                        <input type="text" class="form-control" name="program_pilihan_1" value="{{ old('program_pilihan_1', $users->dataPestaPMB?->program_pilihan_1 ?? '') }}" placeholder="Program studi pilihan utama">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Program Pilihan 2</label>
                                        <input type="text" class="form-control" name="program_pilihan_2" value="{{ old('program_pilihan_2', $users->dataPestaPMB?->program_pilihan_2 ?? '') }}" placeholder="Program studi pilihan alternatif">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nilai Tes Tulis</label>
                                        <input type="number" step="0.01" class="form-control" name="nilai_tes_tulis" value="{{ old('nilai_tes_tulis', $users->dataPestaPMB?->nilai_tes_tulis ?? '') }}" placeholder="0.00">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nilai Wawancara</label>
                                        <input type="number" step="0.01" class="form-control" name="nilai_wawancara" value="{{ old('nilai_wawancara', $users->dataPestaPMB?->nilai_wawancara ?? '') }}" placeholder="0.00">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nilai Akhir</label>
                                        <input type="number" step="0.01" class="form-control" name="nilai_akhir" value="{{ old('nilai_akhir', $users->dataPestaPMB?->nilai_akhir ?? '') }}" placeholder="0.00" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Tab Data Alumni -->
                        @if($users->hasRole('alumni'))
                        <div class="tab-pane" id="data-alumni" role="tabpanel">
                            <div class="form-section">
                                <h5 class="mb-3">Informasi Alumni</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nomor Alumni</label>
                                        <input type="text" class="form-control" name="nomor_alumni" value="{{ old('nomor_alumni', $users->dataAlumni?->nomor_alumni ?? '') }}" placeholder="Nomor Alumni" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Angkatan</label>
                                        <input type="number" class="form-control" name="angkatan" value="{{ old('angkatan', $users->dataAlumni?->angkatan ?? '') }}" placeholder="2020" min="1990" max="{{ date('Y') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tahun Lulus</label>
                                        <input type="number" class="form-control" name="tahun_lulus" value="{{ old('tahun_lulus', $users->dataAlumni?->tahun_lulus ?? '') }}" placeholder="{{ date('Y') }}" min="1990" max="{{ date('Y') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Tanggal Lulus</label>
                                        <input type="date" class="form-control" name="tanggal_lulus" value="{{ old('tanggal_lulus', $users->dataAlumni?->tanggal_lulus ?? '') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">IPK Akhir</label>
                                        <input type="number" step="0.01" class="form-control" name="ipk_akhir" value="{{ old('ipk_akhir', $users->dataAlumni?->ipk_akhir ?? '') }}" placeholder="3.50" min="0" max="4">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Predikat Lulus</label>
                                        <input type="text" class="form-control" name="predikat_lulus" value="{{ old('predikat_lulus', $users->dataAlumni?->predikat_lulus ?? '') }}" placeholder="Cum Laude, Sangat Memuaskan">
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="mb-3">Informasi Karir</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Status Pekerjaan</label>
                                        <select class="form-select" name="status_pekerjaan">
                                            <option value="">Pilih Status</option>
                                            <option value="Bekerja" {{ (old('status_pekerjaan') ?? $users->dataAlumni?->status_pekerjaan ?? null) == 'Bekerja' ? 'selected' : '' }}>Bekerja</option>
                                            <option value="Belum Bekerja" {{ (old('status_pekerjaan') ?? $users->dataAlumni?->status_pekerjaan ?? null) == 'Belum Bekerja' ? 'selected' : '' }}>Belum Bekerja</option>
                                            <option value="Melanjutkan Studi" {{ (old('status_pekerjaan') ?? $users->dataAlumni?->status_pekerjaan ?? null) == 'Melanjutkan Studi' ? 'selected' : '' }}>Melanjutkan Studi</option>
                                            <option value="Wiraswasta" {{ (old('status_pekerjaan') ?? $users->dataAlumni?->status_pekerjaan ?? null) == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Bidang Pekerjaan</label>
                                        <input type="text" class="form-control" name="bidang_pekerjaan" value="{{ old('bidang_pekerjaan', $users->dataAlumni?->bidang_pekerjaan ?? '') }}" placeholder="Contoh: IT, HRD">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tahun Mulai Bekerja</label>
                                        <input type="number" class="form-control" name="tahun_mulai_bekerja" value="{{ old('tahun_mulai_bekerja', $users->dataAlumni?->tahun_mulai_bekerja ?? '') }}" placeholder="{{ date('Y') }}" min="1990" max="{{ date('Y') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Instansi Pekerjaan</label>
                                        <input type="text" class="form-control" name="instansi_pekerjaan" value="{{ old('instansi_pekerjaan', $users->dataAlumni?->instansi_pekerjaan ?? '') }}" placeholder="Nama perusahaan / instansi">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lokasi Pekerjaan</label>
                                        <input type="text" class="form-control" name="lokasi_pekerjaan" value="{{ old('lokasi_pekerjaan', $users->dataAlumni?->lokasi_pekerjaan ?? '') }}" placeholder="Kota / Lokasi kerja">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jabatan Pekerjaan</label>
                                        <input type="text" class="form-control" name="jabatan_pekerjaan" value="{{ old('jabatan_pekerjaan', $users->dataAlumni?->jabatan_pekerjaan ?? '') }}" placeholder="Posisi / Jabatan">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Melanjutkan Ke (Universitas)</label>
                                        <input type="text" class="form-control" name="melanjutkan_ke" value="{{ old('melanjutkan_ke', $users->dataAlumni?->melanjutkan_ke ?? '') }}" placeholder="Nama universitas (jika melanjutkan studi)">
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="mb-3">Catatan Tambahan</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan tambahan tentang alumni">{{ old('catatan', $users->dataAlumni?->catatan ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="tab-pane" id="keamanan" role="tabpanel">
                            <div class="form-section">
                                <h5 class="mb-3">Ubah Password</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Password Lama</label>
                                        <input type="password" class="form-control" name="current_password" placeholder="Masukkan password lama">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" name="new_password" placeholder="Minimal 8 karakter">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" name="new_password_confirmation" placeholder="Ulangi password baru">
                                    </div>
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            </div>

                            <div class="form-section">
                                <h5 class="mb-3">Pengaturan Keamanan</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" name="fst_setup" {{ $users->fst_setup ? 'checked' : '' }}>
                                            <label class="form-check-label">First Time Setup</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" name="tfa_setup" {{ $users->tfa_setup ? 'checked' : '' }}>
                                            <label class="form-check-label">Two Factor Authentication</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeImagePreview();
    initializeDeleteButtons();
    initializeSelect2();
});

// Global variables
let pendidikanIndex = {{ count($users->pendidikans) }};
let keluargaIndex = {{ count($users->keluargas) }};
const activeRole = '{{ $activeRole }}';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// ========== SELECT2 INITIALIZATION ==========
function initializeSelect2() {
    // Initialize Select2 for roles
    if (jQuery('.select2-roles').length > 0) {
        jQuery('.select2-roles').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Role',
            allowClear: false,
            width: '100%',
            closeOnSelect: false
        });
    }
}

// ========== IMAGE PREVIEW FUNCTIONS ==========
function initializeImagePreview() {
    const photoInput = document.querySelector('input[name="photo"]');
    if (photoInput) {
        photoInput.addEventListener('change', function() {
            if (this.files.length === 0) {
                resetImagePreview();
            } else {
                previewImage(this);
            }
        });
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validasi file
        if (!validateImageFile(file)) {
            input.value = '';
            return;
        }
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewPhoto = document.getElementById('previewPhoto');
            if (previewPhoto) {
                previewPhoto.src = e.target.result;
            }
        }
        reader.onerror = function() {
            showError('Terjadi kesalahan saat membaca file');
            input.value = '';
        }
        reader.readAsDataURL(file);
    }
}

function validateImageFile(file) {
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    const maxSize = 2 * 1024 * 1024; // 2MB
    
    if (!allowedTypes.includes(file.type)) {
        showError('File harus berupa gambar (JPEG, JPG, PNG, atau GIF)');
        return false;
    }
    
    if (file.size > maxSize) {
        showError('Ukuran file maksimal 2MB');
        return false;
    }
    
    return true;
}

function resetImagePreview() {
    const previewPhoto = document.getElementById('previewPhoto');
    const originalSrc = '{{ $users->photo }}';
    if (previewPhoto && originalSrc) {
        previewPhoto.src = originalSrc;
    }
}

// ========== PENDIDIKAN FUNCTIONS ==========
function addPendidikan() {
    const container = document.getElementById('pendidikan-container');
    
    // Remove empty message if exists
    const emptyMessage = container.querySelector('.text-muted');
    if (emptyMessage) {
        emptyMessage.remove();
    }
    
    const template = createPendidikanTemplate(pendidikanIndex);
    container.insertAdjacentHTML('beforeend', template);
    pendidikanIndex++;
}

function createPendidikanTemplate(index) {
    return `
        <div class="card mb-3 pendidikan-item" data-index="${index}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Jenjang Pendidikan <span class="text-danger">*</span></label>
                        <select class="form-select" name="pendidikan[${index}][jenjang]" required>
                            <option value="">Pilih Jenjang</option>
                            <option value="Paket C">Paket C</option>
                            <option value="SMA">SMA</option>
                            <option value="SMK">SMK</option>
                            <option value="D3">Diploma 3</option>
                            <option value="S1">Sarjana (S1)</option>
                            <option value="S2">Magister (S2)</option>
                            <option value="S3">Doktor (S3)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nama Institusi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pendidikan[${index}][nama_institusi]" 
                               placeholder="Nama sekolah/universitas" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Jurusan/Program Studi</label>
                        <input type="text" class="form-control" name="pendidikan[${index}][jurusan]" 
                               placeholder="Nama jurusan/prodi">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Aksi</label>
                        <button type="button" class="btn btn-danger btn-sm d-block delete-pendidikan" data-index="${index}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Tahun Masuk</label>
                        <input type="number" class="form-control" name="pendidikan[${index}][tahun_masuk]" 
                               placeholder="2020" min="1950" max="{{ date('Y') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Tahun Lulus</label>
                        <input type="number" class="form-control" name="pendidikan[${index}][tahun_lulus]" 
                               placeholder="2024" min="1950" max="{{ date('Y') + 10 }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">IPK/Nilai</label>
                        <input type="text" class="form-control" name="pendidikan[${index}][ipk]" 
                               placeholder="3.50">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Alamat Institusi</label>
                        <textarea class="form-control" name="pendidikan[${index}][alamat]" rows="2" 
                                  placeholder="Alamat lengkap institusi"></textarea>
                    </div>
                </div>
            </div>
        </div>`;
}

function removePendidikan(pendidikanId, index, pendidikanName = '') {
    const title = pendidikanName ? `Hapus Pendidikan "${pendidikanName}"` : 'Hapus Pendidikan';
    const text = pendidikanName ? 
        `Apakah Anda yakin ingin menghapus pendidikan "${pendidikanName}"?` : 
        'Apakah Anda yakin ingin menghapus data pendidikan ini?';
    
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            if (pendidikanId) {
                deletePendidikanFromServer(pendidikanId, index);
            } else {
                removePendidikanFromDOM(index);
            }
        }
    });
}

function deletePendidikanFromServer(pendidikanId, index) {
    showLoadingAlert('Menghapus pendidikan...');
    
    fetch(`/${activeRole}/users/pendidikan/${pendidikanId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showSuccessAlert(data.message || 'Data pendidikan berhasil dihapus');
            removePendidikanFromDOM(index);
        } else {
            throw new Error(data.message || 'Gagal menghapus data pendidikan');
        }
    })
    .catch(error => {
        console.error('Error deleting pendidikan:', error);
        showError(error.message || 'Terjadi kesalahan saat menghapus data');
    });
}

function removePendidikanFromDOM(index) {
    const item = document.querySelector(`.pendidikan-item[data-index="${index}"]`);
    if (item) {
        item.remove();
        checkEmptyPendidikanContainer();
    }
}

function checkEmptyPendidikanContainer() {
    const container = document.getElementById('pendidikan-container');
    if (container.children.length === 0) {
        container.innerHTML = '<p class="text-muted text-center">Belum ada data pendidikan. Klik tombol "Tambah Pendidikan" untuk menambah data.</p>';
    }
}

// ========== KELUARGA FUNCTIONS ==========
function addKeluarga() {
    const container = document.getElementById('keluarga-container');
    
    // Remove empty message if exists
    const emptyMessage = container.querySelector('.text-muted');
    if (emptyMessage) {
        emptyMessage.remove();
    }
    
    const template = createKeluargaTemplate(keluargaIndex);
    container.insertAdjacentHTML('beforeend', template);
    keluargaIndex++;
}

function createKeluargaTemplate(index) {
    return `
        <div class="card mb-3 keluarga-item" data-index="${index}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Hubungan Keluarga <span class="text-danger">*</span></label>
                        <select class="form-select" name="keluarga[${index}][hubungan]" required>
                            <option value="">Pilih Hubungan</option>
                            <option value="Ayah">Ayah</option>
                            <option value="Ibu">Ibu</option>
                            <option value="Suami">Suami</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Kakak">Kakak</option>
                            <option value="Adik">Adik</option>
                            <option value="Wali">Wali</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="keluarga[${index}][nama]" 
                               placeholder="Nama lengkap anggota keluarga" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" name="keluarga[${index}][pekerjaan]" 
                               placeholder="Pekerjaan/profesi">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Aksi</label>
                        <button type="button" class="btn btn-danger btn-sm d-block delete-keluarga" data-index="${index}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" name="keluarga[${index}][telepon]" 
                               placeholder="081234567890">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" name="keluarga[${index}][tempat_lahir]" 
                               placeholder="Jakarta">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="keluarga[${index}][tanggal_lahir]">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Penghasilan (Rp)</label>
                        <input type="number" class="form-control" name="keluarga[${index}][penghasilan]" 
                               placeholder="5000000">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="keluarga[${index}][alamat]" rows="2" 
                                  placeholder="Alamat lengkap"></textarea>
                    </div>
                </div>
            </div>
        </div>`;
}

function removeKeluarga(keluargaId, index, keluargaName = '') {
    const title = keluargaName ? `Hapus Keluarga "${keluargaName}"` : 'Hapus Data Keluarga';
    const text = keluargaName ? 
        `Apakah Anda yakin ingin menghapus data keluarga "${keluargaName}"?` : 
        'Apakah Anda yakin ingin menghapus data keluarga ini?';
    
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            if (keluargaId) {
                deleteKeluargaFromServer(keluargaId, index);
            } else {
                removeKeluargaFromDOM(index);
            }
        }
    });
}

function deleteKeluargaFromServer(keluargaId, index) {
    showLoadingAlert('Menghapus data keluarga...');
    
    fetch(`/${activeRole}/users/keluarga/${keluargaId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showSuccessAlert(data.message || 'Data keluarga berhasil dihapus');
            removeKeluargaFromDOM(index);
        } else {
            throw new Error(data.message || 'Gagal menghapus data keluarga');
        }
    })
    .catch(error => {
        console.error('Error deleting keluarga:', error);
        showError(error.message || 'Terjadi kesalahan saat menghapus data');
    });
}

function removeKeluargaFromDOM(index) {
    const item = document.querySelector(`.keluarga-item[data-index="${index}"]`);
    if (item) {
        item.remove();
        checkEmptyKeluargaContainer();
    }
}

function checkEmptyKeluargaContainer() {
    const container = document.getElementById('keluarga-container');
    if (container.children.length === 0) {
        container.innerHTML = '<p class="text-muted text-center">Belum ada data keluarga. Klik tombol "Tambah Anggota Keluarga" untuk menambah data.</p>';
    }
}

// ========== ALAMAT FUNCTIONS ==========
function copyFromKTP() {
    const checkbox = document.getElementById('samaDenganKTP');
    const ktpFields = ['alamat_lengkap', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kota_kabupaten', 'provinsi', 'kode_pos'];
    
    if (checkbox.checked) {
        ktpFields.forEach(field => {
            const ktpInput = document.querySelector(`[name="alamat_ktp[${field}]"]`);
            const domisiliInput = document.getElementById(`domisili_${field}`);
            if (ktpInput && domisiliInput) {
                domisiliInput.value = ktpInput.value;
            }
        });
    } else {
        ktpFields.forEach(field => {
            const domisiliInput = document.getElementById(`domisili_${field}`);
            if (domisiliInput) {
                domisiliInput.value = '';
            }
        });
    }
}

// ========== EVENT HANDLERS ==========
function initializeDeleteButtons() {
    document.addEventListener('click', function(e) {
        // Handle delete pendidikan
        if (e.target.closest('.delete-pendidikan')) {
            e.preventDefault();
            const button = e.target.closest('.delete-pendidikan');
            const index = button.getAttribute('data-index');
            const pendidikanId = button.getAttribute('data-id');
            const pendidikanName = button.getAttribute('data-name');
            
            removePendidikan(pendidikanId, index, pendidikanName);
        }
        
        // Handle delete keluarga
        if (e.target.closest('.delete-keluarga')) {
            e.preventDefault();
            const button = e.target.closest('.delete-keluarga');
            const index = button.getAttribute('data-index');
            const keluargaId = button.getAttribute('data-id');
            const keluargaName = button.getAttribute('data-name');
            
            removeKeluarga(keluargaId, index, keluargaName);
        }
    });
}

      document.addEventListener("DOMContentLoaded", function () {
        var el;
        window.TomSelect &&
          new TomSelect((el = document.getElementById("select-roles")), {
            copyClassesToDropdown: false,
            dropdownParent: "body",
            controlInput: "<input>",
            render: {
              item: function (data, escape) {
                if (data.customProperties) {
                  return '<div><span class="dropdown-item-indicator">' + data.customProperties + "</span>" + escape(data.text) + "</div>";
                }
                return "<div>" + escape(data.text) + "</div>";
              },
              option: function (data, escape) {
                if (data.customProperties) {
                  return '<div><span class="dropdown-item-indicator">' + data.customProperties + "</span>" + escape(data.text) + "</div>";
                }
                return "<div>" + escape(data.text) + "</div>";
              },
            },
          });
      });

// ========== UTILITY FUNCTIONS ==========
function showLoadingAlert(message = 'Memproses...') {
    Swal.fire({
        title: message,
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

function showSuccessAlert(message, timer = 2000) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        timer: timer,
        showConfirmButton: false
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: message,
        confirmButtonText: 'OK'
    });
}
</script>
@endsection

