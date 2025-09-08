@extends('themes.core-backpage')

@section('custom-css')
<style>
    .settings-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 10px;
        color: white;
        margin-bottom: 2rem;
    }
    .nav-tabs .nav-link.active {
        background-color: #667eea;
        color: white;
        border-color: #667eea;
    }
    .form-section {
        padding: 1.5rem;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    .stats-card.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    .stats-card.warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }
    .stats-card.info {
        background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="settings-header">
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <i class="fas fa-chalkboard-teacher fa-3x"></i>
                    </div>
                    <div>
                        <h2 class="mb-1">{{ $kelasPerkuliahan->name }}</h2>
                        <p class="mb-0">{{ $kelasPerkuliahan->code }} - {{ $kelasPerkuliahan->programStudi->name ?? 'Program Studi Tidak Ditemukan' }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $kelasPerkuliahan->kelasMahasiswa->count() }}</h4>
                                <small>Total Mahasiswa</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card success">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $kelasPerkuliahan->jadwalKelas->count() }}</h4>
                                <small>Jadwal Perkuliahan</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card warning">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-book fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $kelasPerkuliahan->kapasitas ?? 0 }}</h4>
                                <small>Kapasitas Kelas</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card info">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $kelasPerkuliahan->mataKuliah->name ?? 'Tidak Ada' }}</h4>
                                <small>Mata Kuliah</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs" id="kelasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="informasi-tab" data-bs-toggle="tab" data-bs-target="#informasi" type="button" role="tab">
                        <i class="fas fa-info-circle me-2"></i>Informasi Kelas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="mahasiswa-tab" data-bs-toggle="tab" data-bs-target="#mahasiswa" type="button" role="tab">
                        <i class="fas fa-users me-2"></i>Daftar Mahasiswa
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="jadwal-tab" data-bs-toggle="tab" data-bs-target="#jadwal" type="button" role="tab">
                        <i class="fas fa-calendar-alt me-2"></i>Jadwal Perkuliahan
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="kelasTabsContent">
                <!-- Informasi Kelas Tab -->
                <div class="tab-pane fade show active" id="informasi" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Informasi Detail Kelas Perkuliahan</h5>
                        </div>
                        <div class="card-body">
                            <form id="editKelasForm" action="{{ route('akademik.kelas-perkuliahan-update', $kelasPerkuliahan->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
                                        <select class="form-select" name="tahun_akademik_id" required>
                                            <option value="">Pilih Tahun Akademik</option>
                                            @foreach($tahunAkademiks as $tahun)
                                                <option value="{{ $tahun->id }}" {{ $kelasPerkuliahan->tahun_akademik_id == $tahun->id ? 'selected' : '' }}>
                                                    {{ $tahun->name }} ({{ $tahun->semester }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                                        <select class="form-select" name="program_studi_id" required>
                                            <option value="">Pilih Program Studi</option>
                                            @foreach($programStudis as $prodi)
                                                <option value="{{ $prodi->id }}" {{ $kelasPerkuliahan->program_studi_id == $prodi->id ? 'selected' : '' }}>
                                                    {{ $prodi->name }} ({{ $prodi->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mata Kuliah</label>
                                        <select class="form-select" name="mata_kuliah_id">
                                            <option value="">Pilih Mata Kuliah</option>
                                            @foreach($mataKuliahs as $mk)
                                                <option value="{{ $mk->id }}" {{ $kelasPerkuliahan->mata_kuliah_id == $mk->id ? 'selected' : '' }}>
                                                    {{ $mk->name }} ({{ $mk->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ $kelasPerkuliahan->name }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kode Kelas <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="code" value="{{ $kelasPerkuliahan->code }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kapasitas</label>
                                        <input type="number" class="form-control" name="kapasitas" value="{{ $kelasPerkuliahan->kapasitas }}" min="1">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary me-2" onclick="submitEditForm()">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                    <a href="{{ route('akademik.kelas-perkuliahan-index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Daftar Mahasiswa Tab -->
                <div class="tab-pane fade" id="mahasiswa" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Daftar Mahasiswa Terdaftar</h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMahasiswaModal">
                                <i class="fas fa-plus me-2"></i>Tambah Mahasiswa
                            </button>
                        </div>
                        <div class="card-body">
                            @if($kelasPerkuliahan->kelasMahasiswa->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="mahasiswaTableBody">
                                            @foreach($kelasPerkuliahan->kelasMahasiswa as $index => $km)
                                                <tr id="mahasiswa-row-{{ $km->id }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $km->mahasiswa->nim ?? '-' }}</td>
                                                    <td>{{ $km->mahasiswa->name ?? 'Mahasiswa Tidak Ditemukan' }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Aktif</span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeMahasiswa({{ $km->id }}, '{{ $km->mahasiswa->name ?? 'mahasiswa ini' }}')">
                                                            <i class="fas fa-user-minus"></i> Hapus
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <h5>Belum ada mahasiswa terdaftar</h5>
                                    <p>Belum ada mahasiswa yang terdaftar dalam kelas ini</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMahasiswaModal">
                                        <i class="fas fa-plus me-2"></i>Tambah Mahasiswa Pertama
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Jadwal Perkuliahan Tab -->
                <div class="tab-pane fade" id="jadwal" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Jadwal Perkuliahan</h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                                <i class="fas fa-plus me-2"></i>Tambah Jadwal
                            </button>
                        </div>
                        <div class="card-body">
                            @if($kelasPerkuliahan->jadwalKelas->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Hari</th>
                                                <th>Jam Mulai</th>
                                                <th>Jam Selesai</th>
                                                <th>Ruangan</th>
                                                <th>Mata Kuliah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="jadwalTableBody">
                                            @foreach($kelasPerkuliahan->jadwalKelas as $index => $jadwal)
                                                <tr id="jadwal-row-{{ $jadwal->id }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $jadwal->jadwal->hari ?? '-' }}</td>
                                                    <td>{{ $jadwal->jadwal->jam_mulai ?? '-' }}</td>
                                                    <td>{{ $jadwal->jadwal->jam_selesai ?? '-' }}</td>
                                                    <td>{{ $jadwal->jadwal->ruang->name ?? '-' }}</td>
                                                    <td>{{ $jadwal->jadwal->mataKuliah->name ?? '-' }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeJadwal({{ $jadwal->jadwal_id }}, '{{ $jadwal->jadwal->hari ?? 'jadwal ini' }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                    <h5>Belum ada jadwal perkuliahan</h5>
                                    <p>Belum ada jadwal yang ditetapkan untuk kelas ini</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                                        <i class="fas fa-plus me-2"></i>Tambah Jadwal Pertama
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals will be added here -->
<div class="modal fade" id="addMahasiswaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addMahasiswaForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mahasiswa_id" class="form-label">Pilih Mahasiswa</label>
                        <select class="form-select" id="mahasiswa_id" name="mahasiswa_id" required>
                            <option value="">Pilih Mahasiswa...</option>
                            @foreach($mahasiswas as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}">{{ $mahasiswa->name }} ({{ $mahasiswa->nim }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addJadwalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addJadwalForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="jadwal_perkuliahan_id" class="form-label">Pilih Jadwal Perkuliahan</label>
                        <select class="form-select" id="jadwal_perkuliahan_id" name="jadwal_id" required>
                            <option value="">Pilih Jadwal...</option>
                            @foreach($jadwalPerkuliahans as $jadwal)
                            <option value="{{ $jadwal->id }}">
                                {{ $jadwal->mataKuliah->name ?? 'N/A' }} - 
                                {{ $jadwal->dosen->name ?? 'N/A' }} - 
                                {{ $jadwal->hari }} {{ $jadwal->jam_mulai }}-{{ $jadwal->jam_selesai }} 
                                ({{ $jadwal->ruangan->name ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('custom-js')
<script>
// Submit edit form function
function submitEditForm() {
    const form = document.getElementById('editKelasForm');
    if (!form) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Form edit tidak ditemukan'
        });
        return;
    }
    
    // Get form data
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value;
    
    if (!csrfToken) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'CSRF token tidak ditemukan'
        });
        return;
    }
    
    // Show loading
    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Send PATCH request
    fetch(form.action, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Terjadi kesalahan saat menyimpan');
            });
        }
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message || 'Data kelas berhasil diperbarui',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Terjadi kesalahan saat menyimpan data'
        });
    });
}

// Remove mahasiswa function
function removeMahasiswa(id, name) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Mahasiswa ${name} akan dihapus dari kelas ini`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send DELETE request
            fetch(`{{ route('akademik.kelas-perkuliahan-remove-mahasiswa', [$kelasPerkuliahan->id, ':id']) }}`.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Mahasiswa berhasil dihapus',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        loadMahasiswaTable();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Terjadi kesalahan saat menghapus mahasiswa'
                });
            });
        }
    });
}

// Delete jadwal function
function deleteJadwal(id, hari) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Jadwal ${hari} akan dihapus`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send DELETE request
            fetch(`{{ route('akademik.kelas-perkuliahan-remove-jadwal', [$kelasPerkuliahan->id, ':id']) }}`.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Jadwal berhasil dihapus',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        loadJadwalTable();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Terjadi kesalahan saat menghapus jadwal'
                });
            });
        }
    });
}

// Edit jadwal function
function editJadwal(id) {
    // Implement edit jadwal logic here
    Swal.fire('Fitur akan diimplementasikan', 'Fitur edit jadwal sedang dalam pengembangan', 'info');
}

// Add mahasiswa form handler
document.getElementById('addMahasiswaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const mahasiswaId = formData.get('mahasiswa_id');
    
    if (!mahasiswaId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Silakan pilih mahasiswa'
        });
        return;
    }
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value;
    
    // Show loading
    Swal.fire({
        title: 'Menambahkan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Send POST request
    fetch(`{{ route('akademik.kelas-perkuliahan-store-mahasiswa', $kelasPerkuliahan->id) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            mahasiswa_id: mahasiswaId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message || 'Mahasiswa berhasil ditambahkan',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Close modal and reload table
                bootstrap.Modal.getInstance(document.getElementById('addMahasiswaModal')).hide();
                loadMahasiswaTable();
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Terjadi kesalahan saat menambahkan mahasiswa'
        });
    });
});

// Add jadwal form handler
document.getElementById('addJadwalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const jadwalId = formData.get('jadwal_perkuliahan_id');
    
    if (!jadwalId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Silakan pilih jadwal'
        });
        return;
    }
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value;
    
    // Show loading
    Swal.fire({
        title: 'Menambahkan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Send POST request
    fetch(`{{ route('akademik.kelas-perkuliahan-store-jadwal', $kelasPerkuliahan->id) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            jadwal_perkuliahan_id: jadwalId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message || 'Jadwal berhasil ditambahkan',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Close modal and reload table
                bootstrap.Modal.getInstance(document.getElementById('addJadwalModal')).hide();
                loadJadwalTable();
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Terjadi kesalahan saat menambahkan jadwal'
        });
    });
});

// Load mahasiswa table function
function loadMahasiswaTable() {
    const tableBody = document.getElementById('mahasiswaTableBody');
    if (!tableBody) return;
    
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse the HTML and extract the table body content
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTableBody = doc.getElementById('mahasiswaTableBody');
        if (newTableBody) {
            tableBody.innerHTML = newTableBody.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error loading mahasiswa table:', error);
    });
}

// Load jadwal table function
function loadJadwalTable() {
    const tableBody = document.getElementById('jadwalTableBody');
    if (!tableBody) return;
    
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse the HTML and extract the table body content
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTableBody = doc.getElementById('jadwalTableBody');
        if (newTableBody) {
            tableBody.innerHTML = newTableBody.innerHTML;
        }
    })
    .catch(error => {
        console.error('Error loading jadwal table:', error);
    });
}

// Reset form when modal is shown
document.getElementById('addMahasiswaModal').addEventListener('show.bs.modal', function() {
    document.getElementById('addMahasiswaForm').reset();
});

document.getElementById('addJadwalModal').addEventListener('show.bs.modal', function() {
    document.getElementById('addJadwalForm').reset();
});
</script>
@endsection
