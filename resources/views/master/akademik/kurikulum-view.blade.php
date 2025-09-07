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
        border-radius: 8px;
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

                <form action="{{ route($spref . 'akademik.kurikulum-update', $kurikulum->id) }}" method="POST">
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
                                <i class="fas fa-book-open me-2"></i> Matakuliah
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-3">
                        <!-- Tab Data Utama -->
                        <div class="tab-pane active" id="data-utama" role="tabpanel">
                            <div class="form-section">
                                <h5>Informasi Utama</h5>
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
                        <div class="tab-pane" id="periode-berlaku" role="tabpanel">
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
                        <div class="tab-pane" id="ketentuan-akademik" role="tabpanel">
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
                        <div class="tab-pane" id="dokumen-status" role="tabpanel">
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
                        
                        <!-- Tab Matakuliah -->
                        <div class="tab-pane" id="matakuliah" role="tabpanel">
                            <div class="form-section">
                                <h5>Daftar Matakuliah dalam Kurikulum</h5>
                                
                                @if($kurikulum->kurikulumMataKuliah->count() > 0)
                                    @php
                                        $matakuliahBySemester = $kurikulum->kurikulumMataKuliah->groupBy('semester.name')->sortKeys();
                                    @endphp
                                    
                                    <div class="accordion" id="semesterAccordion">
                                        @foreach($matakuliahBySemester as $semesterName => $matakuliahs)
                                            @php
                                                $semesterId = 'semester' . str_replace(' ', '', $semesterName);
                                                $totalSks = $matakuliahs->sum(function($item) {
                                                    return $item->sks_override ?? $item->mataKuliah->beban_sks ?? 0;
                                                });
                                                $wajibCount = $matakuliahs->where('is_wajib', true)->count();
                                                $pilihanCount = $matakuliahs->where('is_wajib', false)->count();
                                            @endphp
                                            
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading{{ $semesterId }}">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $semesterId }}" aria-expanded="false" aria-controls="collapse{{ $semesterId }}">
                                                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                            <div>
                                                                <strong>{{ $semesterName ?? 'Semester Tidak Diketahui' }}</strong>
                                                                <small class="text-muted ms-2">({{ $matakuliahs->count() }} Matakuliah)</small>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <span class="badge bg-primary">{{ $totalSks }} SKS</span>
                                                                <span class="badge bg-success">{{ $wajibCount }} Wajib</span>
                                                                @if($pilihanCount > 0)
                                                                    <span class="badge bg-warning">{{ $pilihanCount }} Pilihan</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse{{ $semesterId }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $semesterId }}" data-bs-parent="#semesterAccordion">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            @foreach($matakuliahs->sortBy('urutan') as $item)
                                                                <div class="col-md-6 col-lg-4 mb-3">
                                                                    <div class="card h-100 border-start border-4 {{ $item->is_wajib ? 'border-success' : 'border-warning' }}">
                                                                        <div class="card-body">
                                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                                <span class="badge bg-primary">{{ $item->mataKuliah->code ?? '-' }}</span>
                                                                                <span class="badge {{ $item->is_wajib ? 'bg-success' : 'bg-warning' }}">
                                                                                    {{ $item->is_wajib ? 'Wajib' : 'Pilihan' }}
                                                                                </span>
                                                                            </div>
                                                                            <h6 class="card-title">{{ $item->mataKuliah->name ?? '-' }}</h6>
                                                                            <div class="row text-sm">
                                                                                <div class="col-6">
                                                                                    <small class="text-muted">SKS:</small><br>
                                                                                    <strong>{{ $item->sks_override ?? $item->mataKuliah->beban_sks ?? 0 }}</strong>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <small class="text-muted">Urutan:</small><br>
                                                                                    <strong>{{ $item->urutan }}</strong>
                                                                                </div>
                                                                            </div>
                                                                            @if($item->mataKuliah->jenis)
                                                                                <div class="mt-2">
                                                                                    <span class="badge bg-secondary">{{ $item->mataKuliah->jenis }}</span>
                                                                                </div>
                                                                            @endif
                                                                            @if($item->mataKuliah->sks_teori || $item->mataKuliah->sks_praktik || $item->mataKuliah->sks_lapangan)
                                                                                <div class="mt-2">
                                                                                    <small class="text-muted">Detail SKS:</small><br>
                                                                                    @if($item->mataKuliah->sks_teori > 0)
                                                                                        <span class="badge bg-info me-1">T: {{ $item->mataKuliah->sks_teori }}</span>
                                                                                    @endif
                                                                                    @if($item->mataKuliah->sks_praktik > 0)
                                                                                        <span class="badge bg-info me-1">P: {{ $item->mataKuliah->sks_praktik }}</span>
                                                                                    @endif
                                                                                    @if($item->mataKuliah->sks_lapangan > 0)
                                                                                        <span class="badge bg-info me-1">L: {{ $item->mataKuliah->sks_lapangan }}</span>
                                                                                    @endif
                                                                                </div>
                                                                            @endif
                                                                            @if($item->catatan)
                                                                                <div class="mt-2">
                                                                                    <small class="text-muted">Catatan:</small><br>
                                                                                    <small>{{ $item->catatan }}</small>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-info-circle fa-3x mb-3 me-2"></i>
                                        <h5>Belum ada matakuliah yang terdaftar</h5>
                                        <p>Belum ada matakuliah yang terdaftar dalam kurikulum ini</p>
                                    </div>
                                @endif
                                

                                
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2 me-2"></i>
                                    <strong>Informasi:</strong> Untuk mengelola matakuliah dalam kurikulum, silakan gunakan menu 
                                    <a href="{{ route($spref . 'akademik.kurikulum.matakuliah-index') }}" class="alert-link">Kurikulum Matakuliah</a>.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route($spref . 'akademik.kurikulum-index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script>
    // Auto-calculate total SKS when SKS Wajib or SKS Pilihan changes
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endsection