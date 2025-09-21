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
    .settings-logo {
        width: 150px;
        height: 150px;
        border-radius: 10px;
        border: 5px solid white;
        object-fit: contain;
        background-color: white;
    }
    .nav-tabs .nav-link.active {
        background-color: #667eea;
        color: white;
        border-color: #667eea;
    }
    .form-section {
        padding: 1.5rem;
              }
    }
    
    // Submit edit form function
    function submitEditForm() {
        const form = document.getElementById('editMataKuliahForm');
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
        // Add method spoofing for Laravel
        formData.append('_method', 'PATCH');
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
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editMataKuliahModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Mata kuliah berhasil diperbarui',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat menyimpan');
            }
        })
        .catch(error => {
            console.error('Edit error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Terjadi kesalahan saat menyimpan'
            });
        });
    }rder-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }
    .form-section h5 {
        border-bottom: 2px solid #667eea;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .action-buttons {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #dee2e6;
    }
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="settings-header">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="{{ $kurikulum->programStudi->profile->logo }}" alt="Logo Program Studi" class="settings-logo">
                        </div>
                        <div class="col-md-10">
                            <h2 class="mb-0">{{ $kurikulum->name ?? 'Nama Kurikulum' }}</h2>
                            <p class="mb-1">{{ $kurikulum->programStudi->name ?? 'Program Studi' }} - {{ $kurikulum->programStudi->fakultas->name ?? 'Fakultas' }}</p>
                            <p class="mb-0"><i class="fas fa-calendar me-2"></i> {{ $kurikulum->tahun_berlaku ?? 'Tahun Berlaku' }} | <i class="fas fa-graduation-cap me-2"></i> {{ $kurikulum->total_sks_lulus ?? '144' }} SKS</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route($activeRole . '.akademik.kurikulum-update', $kurikulum->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#data-utama" role="tab">
                                <i class="fas fa-book me-2"></i> Data Utama
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#periode-berlaku" role="tab">
                                <i class="fas fa-calendar-alt me-2"></i> Periode Berlaku
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#ketentuan-akademik" role="tab">
                                <i class="fas fa-graduation-cap me-2"></i> Ketentuan Akademik
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#dokumen-status" role="tab">
                                <i class="fas fa-file-contract me-2"></i> Dokumen & Status
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#matakuliah" role="tab">
                                <i class="fas fa-book-open me-2"></i> Mata Kuliah
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Tab Data Utama -->
                        <div class="tab-pane fade show active" id="data-utama" role="tabpanel">
                            <div class="form-section">
                                <h5>Data Utama Kurikulum</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                                        <select class="form-select" name="program_studi_id" required>
                                            <option value="">Pilih Program Studi</option>
                                            @foreach($programStudis as $prodi)
                                                <option value="{{ $prodi->id }}" {{ $kurikulum->program_studi_id == $prodi->id ? 'selected' : '' }}>
                                                    {{ $prodi->name }} ({{ $prodi->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Kurikulum <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ $kurikulum->name }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kode Kurikulum <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="code" value="{{ $kurikulum->code }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="deskripsi" rows="3">{{ $kurikulum->deskripsi }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Periode Berlaku -->
                        <div class="tab-pane fade" id="periode-berlaku" role="tabpanel">
                            <div class="form-section">
                                <h5>Periode Berlaku</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tahun Berlaku <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="tahun_berlaku" value="{{ $kurikulum->tahun_berlaku }}" min="2000" max="2099" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tahun Berakhir</label>
                                        <input type="number" class="form-control" name="tahun_berakhir" value="{{ $kurikulum->tahun_berakhir }}" min="2000" max="2099">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Awal Tahun Akademik <span class="text-danger">*</span></label>
                                        <select class="form-select" name="awal_tahun_akademik_id" required>
                                            <option value="">Pilih Tahun Akademik</option>
                                            @foreach($tahunAkademiks as $tahun)
                                                <option value="{{ $tahun->id }}" {{ $kurikulum->awal_tahun_akademik_id == $tahun->id ? 'selected' : '' }}>
                                                    {{ $tahun->name }} ({{ $tahun->semester }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Akhir Tahun Akademik <span class="text-danger">*</span></label>
                                        <select class="form-select" name="akhir_tahun_akademik_id" required>
                                            <option value="">Pilih Tahun Akademik</option>
                                            @foreach($tahunAkademiks as $tahun)
                                                <option value="{{ $tahun->id }}" {{ $kurikulum->akhir_tahun_akademik_id == $tahun->id ? 'selected' : '' }}>
                                                    {{ $tahun->name }} ({{ $tahun->semester }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Ketentuan Akademik -->
                        <div class="tab-pane fade" id="ketentuan-akademik" role="tabpanel">
                            <div class="form-section">
                                <h5>Ketentuan Akademik</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Total SKS Lulus <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="total_sks_lulus" value="{{ $kurikulum->total_sks_lulus }}" min="1" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">SKS Wajib</label>
                                        <input type="number" class="form-control" name="sks_wajib" value="{{ $kurikulum->sks_wajib }}" min="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">SKS Pilihan</label>
                                        <input type="number" class="form-control" name="sks_pilihan" value="{{ $kurikulum->sks_pilihan }}" min="0">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Semester Normal</label>
                                        <input type="number" class="form-control" name="semester_normal" value="{{ $kurikulum->semester_normal }}" min="1" max="14">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">IPK Minimal</label>
                                        <input type="number" class="form-control" name="ipk_minimal" value="{{ $kurikulum->ipk_minimal }}" min="0" max="4" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Dokumen & Status -->
                        <div class="tab-pane fade" id="dokumen-status" role="tabpanel">
                            <div class="form-section">
                                <h5>Dokumen & Status</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">SK Penetapan</label>
                                        <input type="text" class="form-control" name="sk_penetapan" value="{{ $kurikulum->sk_penetapan }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal SK</label>
                                        <input type="date" class="form-control" name="tanggal_sk" value="{{ $kurikulum->tanggal_sk }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status" required>
                                            <option value="Masih Berlaku" {{ $kurikulum->status == 'Masih Berlaku' ? 'selected' : '' }}>Masih Berlaku</option>
                                            <option value="Tidak Berlaku" {{ $kurikulum->status == 'Tidak Berlaku' ? 'selected' : '' }}>Tidak Berlaku</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Mata Kuliah -->
                        <div class="tab-pane fade" id="matakuliah" role="tabpanel">
                            <div class="form-section">
                                <!-- Header with Add Button and Statistics -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">Daftar Mata Kuliah dalam Kurikulum</h5>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMataKuliahModal">
                                        <i class="fas fa-plus me-2"></i>Tambah Mata Kuliah
                                    </button>
                                </div>

                                <!-- Statistics Cards -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $kurikulum->kurikulumMataKuliah->count() }}</h4>
                                                <small>Total Mata Kuliah</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $kurikulum->kurikulumMataKuliah->where('is_wajib', true)->count() }}</h4>
                                                <small>Mata Kuliah Wajib</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $kurikulum->kurikulumMataKuliah->where('is_wajib', false)->count() }}</h4>
                                                <small>Mata Kuliah Pilihan</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $kurikulum->kurikulumMataKuliah->sum(function($kmk) { return $kmk->sks_override ?? $kmk->mataKuliah->beban_sks; }) }}</h4>
                                                <small>Total SKS</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filter and Search -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <select class="form-select" id="semesterFilter" onchange="filterMataKuliah()">
                                            <option value="">Semua Semester</option>
                                            @foreach($semesters as $semester)
                                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select" id="statusFilter" onchange="filterMataKuliah()">
                                            <option value="">Semua Status</option>
                                            <option value="1">Wajib</option>
                                            <option value="0">Pilihan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="searchMataKuliah" placeholder="Cari mata kuliah..." onkeyup="filterMataKuliah()">
                                    </div>
                                </div>

                                @if($kurikulum->kurikulumMataKuliah->count() > 0)
                                    <!-- Table View -->
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="mataKuliahTable">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="10%">Kode</th>
                                                    <th width="30%">Nama Mata Kuliah</th>
                                                    <th width="10%">Semester</th>
                                                    <th width="8%">SKS</th>
                                                    <th width="8%">Status</th>
                                                    <th width="8%">Urutan</th>
                                                    <th width="8%">Jenis</th>
                                                    <th width="13%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($kurikulum->kurikulumMataKuliah->sortBy(['semester.name', 'urutan']) as $index => $item)
                                                    <tr data-semester="{{ $item->semester_id }}" data-status="{{ $item->is_wajib ? '1' : '0' }}">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <span class="badge bg-primary">{{ $item->mataKuliah->code ?? '-' }}</span>
                                                        </td>
                                                        <td>
                                                            <strong>{{ $item->mataKuliah->name ?? '-' }}</strong>
                                                            @if($item->catatan)
                                                                <br><small class="text-muted">{{ $item->catatan }}</small>
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->semester->name ?? '-' }}</td>
                                                        <td>
                                                            <span class="badge bg-secondary">
                                                                {{ $item->sks_override ?? $item->mataKuliah->beban_sks ?? 0 }}
                                                            </span>
                                                            @if($item->sks_override)
                                                                <br><small class="text-warning">Override</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge {{ $item->is_wajib ? 'bg-success' : 'bg-warning' }}">
                                                                {{ $item->is_wajib ? 'Wajib' : 'Pilihan' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">{{ $item->urutan }}</span>
                                                        </td>
                                                        <td>
                                                            @if($item->mataKuliah->jenis)
                                                                <span class="badge bg-light text-dark">{{ $item->mataKuliah->jenis }}</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button type="button" class="btn btn-outline-primary" 
                                                                    onclick="editMataKuliah({{ $item->kurikulum_id }}, {{ $item->mata_kuliah_id }}, {{ $item->semester_id }}, {{ $item->is_wajib ? 'true' : 'false' }}, {{ $item->urutan }}, {{ $item->sks_override ? $item->sks_override : 'null' }}, '{{ $item->catatan ? addslashes($item->catatan) : '' }}')"
                                                                    data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-outline-danger" onclick="deleteMataKuliah({{ $item->kurikulum_id }}, {{ $item->mata_kuliah_id }}, '{{ $item->mataKuliah->name ?? 'mata kuliah ini' }}')" data-bs-toggle="tooltip" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-book fa-3x mb-3"></i>
                                        <h5>Belum ada mata kuliah yang terdaftar</h5>
                                        <p>Belum ada mata kuliah yang terdaftar dalam kurikulum ini</p>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMataKuliahModal">
                                            <i class="fas fa-plus me-2"></i>Tambah Mata Kuliah Pertama
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route($activeRole . '.akademik.kurikulum-index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Mata Kuliah Modal -->
<div class="modal fade" id="addMataKuliahModal" tabindex="-1" aria-labelledby="addMataKuliahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route($activeRole . '.akademik.kurikulum-matakuliah-store', $kurikulum->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addMataKuliahModalLabel">Tambah Mata Kuliah ke Kurikulum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                            <select class="form-select" name="mata_kuliah_id" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach($mataKuliahs as $mk)
                                    <option value="{{ $mk->id }}" data-sks="{{ $mk->beban_sks }}" data-jenis="{{ $mk->jenis }}">
                                        {{ $mk->code }} - {{ $mk->name }} ({{ $mk->beban_sks }} SKS)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select class="form-select" name="semester_id" required>
                                <option value="">Pilih Semester</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="is_wajib" required>
                                <option value="">Pilih Status</option>
                                <option value="1">Wajib</option>
                                <option value="0">Pilihan</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Urutan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="urutan" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">SKS Override</label>
                            <input type="number" class="form-control" name="sks_override" min="0" max="10" placeholder="Kosongkan jika sama">
                            <small class="text-muted">Kosongkan jika mengikuti SKS mata kuliah</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan khusus untuk mata kuliah ini dalam kurikulum"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Mata Kuliah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mata Kuliah Modal -->
<div class="modal fade" id="editMataKuliahModal" tabindex="-1" aria-labelledby="editMataKuliahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editMataKuliahForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="editMataKuliahModalLabel">Edit Mata Kuliah dalam Kurikulum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select class="form-select" name="semester_id" id="edit_semester_id" required>
                                <option value="">Pilih Semester</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="is_wajib" id="edit_is_wajib" required>
                                <option value="">Pilih Status</option>
                                <option value="1">Wajib</option>
                                <option value="0">Pilihan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Urutan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="urutan" id="edit_urutan" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKS Override</label>
                            <input type="number" class="form-control" name="sks_override" id="edit_sks_override" min="0" max="10" placeholder="Kosongkan jika sama">
                            <small class="text-muted">Kosongkan jika mengikuti SKS mata kuliah</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan" id="edit_catatan" rows="3" placeholder="Catatan khusus untuk mata kuliah ini dalam kurikulum"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditForm()">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



@section('custom-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        if (typeof window.bootstrap !== 'undefined' && window.bootstrap.Tooltip) {
            try {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new window.bootstrap.Tooltip(tooltipTriggerEl);
                });
            } catch (e) {
                console.warn('Tooltip initialization failed:', e);
            }
        }
    });
    
    // SKS calculation
    const sksWajib = document.querySelector('input[name="sks_wajib"]');
    const sksPilihan = document.querySelector('input[name="sks_pilihan"]');
    const totalSks = document.querySelector('input[name="total_sks_lulus"]');
    
    function calculateTotal() {
        const wajib = parseInt(sksWajib.value) || 0;
        const pilihan = parseInt(sksPilihan.value) || 0;
        const total = wajib + pilihan;
        
        if (total > 0) {
            totalSks.value = total;
        }
    }
    
    if (sksWajib && sksPilihan && totalSks) {
        sksWajib.addEventListener('input', calculateTotal);
        sksPilihan.addEventListener('input', calculateTotal);
    }
    
    // Delete mata kuliah function
    function deleteMataKuliah(kurikulumId, mataKuliahId, mataKuliahName) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus mata kuliah "${mataKuliahName}" dari kurikulum ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
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
                
                // Send DELETE request
                const baseUrl = window.location.origin;
                const deleteUrl = `${baseUrl}/akademik/kurikulum/${kurikulumId}/mata-kuliah/${mataKuliahId}`;
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Terjadi kesalahan saat menghapus');
                        });
                    }
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Mata kuliah berhasil dihapus',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan saat menghapus');
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Terjadi kesalahan saat menghapus'
                    });
                });
            }
        });
    }
    
    // Filter functions for mata kuliah table
    function filterMataKuliah() {
        const semesterFilter = document.getElementById('semesterFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const searchText = document.getElementById('searchMataKuliah').value.toLowerCase();
        const rows = document.querySelectorAll('#mataKuliahTable tbody tr');
        
        rows.forEach(row => {
            const semesterData = row.getAttribute('data-semester');
            const statusData = row.getAttribute('data-status');
            const textContent = row.textContent.toLowerCase();
            
            let showRow = true;
            
            // Filter by semester
            if (semesterFilter !== '' && semesterData !== semesterFilter) {
                showRow = false;
            }
            
            // Filter by status
            if (statusFilter !== '' && statusData !== statusFilter) {
                showRow = false;
            }
            
            // Filter by search text
            if (searchText !== '' && !textContent.includes(searchText)) {
                showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    // Edit mata kuliah function
    function editMataKuliah(kurikulumId, mataKuliahId, semesterId, isWajib, urutan, sksOverride, catatan) {
        console.log('Edit function called with:', {kurikulumId, mataKuliahId, semesterId, isWajib, urutan, sksOverride, catatan});
        
        const form = document.getElementById('editMataKuliahForm');
        if (!form) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Form edit tidak ditemukan'
            });
            return;
        }
        
        const action = `{{ route($activeRole . '.akademik.kurikulum-matakuliah-update', [$kurikulum->id, ':mataKuliahId']) }}`;
        form.action = action.replace(':mataKuliahId', mataKuliahId);
        
        const semesterSelect = document.getElementById('edit_semester_id');
        const statusSelect = document.getElementById('edit_is_wajib');
        const urutanInput = document.getElementById('edit_urutan');
        const sksInput = document.getElementById('edit_sks_override');
        const catatanInput = document.getElementById('edit_catatan');
        
        if (semesterSelect) semesterSelect.value = semesterId;
        if (statusSelect) statusSelect.value = isWajib ? '1' : '0';
        if (urutanInput) urutanInput.value = urutan;
        if (sksInput) sksInput.value = (sksOverride === 'null' || sksOverride === null || sksOverride === '') ? '' : sksOverride;
        if (catatanInput) catatanInput.value = (catatan === 'null' || catatan === null) ? '' : catatan;
        
        // Show modal
        const modalElement = document.getElementById('editMataKuliahModal');
        if (modalElement) {
            // Try Bootstrap modal first
            if (typeof window.bootstrap !== 'undefined' && window.bootstrap.Modal) {
                const modal = new window.bootstrap.Modal(modalElement);
                modal.show();
            } else {
                // Fallback: manually show modal
                console.warn('Bootstrap Modal not available, using fallback');
                modalElement.classList.add('show');
                modalElement.style.display = 'block';
                modalElement.setAttribute('aria-modal', 'true');
                modalElement.setAttribute('role', 'dialog');
                document.body.classList.add('modal-open');
                
                // Add backdrop
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'modal-backdrop-' + Date.now();
                document.body.appendChild(backdrop);
                
                // Handle close button
                const closeBtn = modalElement.querySelector('.btn-close, [data-bs-dismiss="modal"]');
                if (closeBtn) {
                    closeBtn.onclick = function() {
                        modalElement.classList.remove('show');
                        modalElement.style.display = 'none';
                        modalElement.removeAttribute('aria-modal');
                        modalElement.removeAttribute('role');
                        document.body.classList.remove('modal-open');
                        document.getElementById(backdrop.id)?.remove();
                    };
                }
                
                // Handle backdrop click
                backdrop.onclick = function() {
                    closeBtn.click();
                };
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Modal edit tidak ditemukan'
            });
        }
    }
</script>
@endsection