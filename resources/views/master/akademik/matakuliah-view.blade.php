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
    .prasyarat-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        /* background-color: #f8f9fa; */
    }
    .dosen-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        /* background-color: #f8f9fa; */
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
                            <img src="{{ $mataKuliah->cover ? asset('storage/' . $mataKuliah->cover) : asset('assets/static/default-mk.jpg') }}" alt="Cover Mata Kuliah" class="settings-logo">
                        </div>
                        <div class="col-md-10">
                            <h2 class="mb-0">{{ $mataKuliah->name ?? 'Nama Mata Kuliah' }}</h2>
                            <p class="mb-1">{{ $mataKuliah->code ?? 'Kode MK' }} - {{ $mataKuliah->semester->name ?? 'Semester' }}</p>
                            <p class="mb-0"><i class="fas fa-graduation-cap me-2"></i> {{ $mataKuliah->beban_sks ?? '0' }} SKS | <i class="fas fa-tag me-2"></i> {{ $mataKuliah->jenis ?? 'Jenis' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route($activeRole . '.akademik.matakuliah-update', $mataKuliah->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#data-utama" role="tab">
                                <i class="fas fa-book me-2"></i> Data Utama
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#detail-konten" role="tab">
                                <i class="fas fa-file-alt me-2"></i> Detail & Konten
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#prasyarat" role="tab">
                                <i class="fas fa-link me-2"></i> Prasyarat
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#dosen-pengampu" role="tab">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Dosen Pengampu
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
                                        <label class="form-label">Semester <span class="text-danger">*</span></label>
                                        <select class="form-select" name="semester_id" required>
                                            <option value="">Pilih Semester</option>
                                            @foreach($semesters as $semester)
                                                <option value="{{ $semester->id }}" {{ $mataKuliah->semester_id == $semester->id ? 'selected' : '' }}>
                                                    {{ $semester->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ $mataKuliah->name }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama (English)</label>
                                        <input type="text" class="form-control" name="name_en" value="{{ $mataKuliah->name_en }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kode Mata Kuliah <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="code" value="{{ $mataKuliah->code }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cover Mata Kuliah</label>
                                        <input type="file" class="form-control" name="cover" accept="image/*">
                                        @if($mataKuliah->cover)
                                            <small class="text-muted">File saat ini: {{ $mataKuliah->cover }}</small>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jenis Mata Kuliah <span class="text-danger">*</span></label>
                                        <select class="form-select" name="jenis" required>
                                            <option value="">Pilih Jenis</option>
                                            <option value="Wajib" {{ $mataKuliah->jenis == 'Wajib' ? 'selected' : '' }}>Wajib</option>
                                            <option value="Pilihan" {{ $mataKuliah->jenis == 'Pilihan' ? 'selected' : '' }}>Pilihan</option>
                                            <option value="MKWU" {{ $mataKuliah->jenis == 'MKWU' ? 'selected' : '' }}>MKWU</option>
                                            <option value="MKU" {{ $mataKuliah->jenis == 'MKU' ? 'selected' : '' }}>MKU</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h5>Beban SKS</h5>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Total SKS <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="beban_sks" value="{{ $mataKuliah->beban_sks }}" min="1" required id="total_sks">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">SKS Teori</label>
                                        <input type="number" class="form-control" name="sks_teori" value="{{ $mataKuliah->sks_teori }}" min="0" id="sks_teori">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">SKS Praktik</label>
                                        <input type="number" class="form-control" name="sks_praktik" value="{{ $mataKuliah->sks_praktik }}" min="0" id="sks_praktik">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">SKS Lapangan</label>
                                        <input type="number" class="form-control" name="sks_lapangan" value="{{ $mataKuliah->sks_lapangan }}" min="0" id="sks_lapangan">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h5>Pengaturan</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Minimal Semester</label>
                                        <input type="number" class="form-control" name="min_semester" value="{{ $mataKuliah->min_semester }}" min="1" max="14">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="is_active">
                                            <option value="1" {{ $mataKuliah->is_active ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ !$mataKuliah->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Detail & Konten -->
                        <div class="tab-pane" id="detail-konten" role="tabpanel">
                            <div class="form-section">
                                <h5>Deskripsi & Konten</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="deskripsi" rows="4" required>{{ $mataKuliah->detail->deskripsi ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Capaian Pembelajaran</label>
                                        <textarea class="form-control" name="capaian_pembelajaran" rows="4">{{ $mataKuliah->detail->capaian_pembelajaran ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Materi Pokok</label>
                                        <textarea class="form-control" name="materi_pokok" rows="4">{{ $mataKuliah->detail->materi_pokok ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h5>Metode Pembelajaran</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Metode Pembelajaran</label>
                                        <div class="row">
                                            @php
                                                $metodePembelajaran = $mataKuliah->detail->metode_pembelajaran ?? [];
                                                if(is_string($metodePembelajaran)) {
                                                    $metodePembelajaran = json_decode($metodePembelajaran, true) ?? [];
                                                }
                                            @endphp
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_pembelajaran[]" value="Ceramah" {{ in_array('Ceramah', $metodePembelajaran) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Ceramah</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_pembelajaran[]" value="Diskusi" {{ in_array('Diskusi', $metodePembelajaran) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Diskusi</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_pembelajaran[]" value="Praktikum" {{ in_array('Praktikum', $metodePembelajaran) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Praktikum</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_pembelajaran[]" value="Seminar" {{ in_array('Seminar', $metodePembelajaran) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Seminar</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_pembelajaran[]" value="Studi Kasus" {{ in_array('Studi Kasus', $metodePembelajaran) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Studi Kasus</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_pembelajaran[]" value="Project Based Learning" {{ in_array('Project Based Learning', $metodePembelajaran) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Project Based Learning</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h5>Metode Penilaian</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Metode Penilaian</label>
                                        <div class="row">
                                            @php
                                                $metodePenilaian = $mataKuliah->detail->metode_penilaian ?? [];
                                                if(is_string($metodePenilaian)) {
                                                    $metodePenilaian = json_decode($metodePenilaian, true) ?? [];
                                                }
                                            @endphp
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_penilaian[]" value="UTS" {{ in_array('UTS', $metodePenilaian) ? 'checked' : '' }}>
                                                    <label class="form-check-label">UTS</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_penilaian[]" value="UAS" {{ in_array('UAS', $metodePenilaian) ? 'checked' : '' }}>
                                                    <label class="form-check-label">UAS</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_penilaian[]" value="Tugas" {{ in_array('Tugas', $metodePenilaian) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Tugas</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_penilaian[]" value="Kuis" {{ in_array('Kuis', $metodePenilaian) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Kuis</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_penilaian[]" value="Presentasi" {{ in_array('Presentasi', $metodePenilaian) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Presentasi</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="metode_penilaian[]" value="Praktikum" {{ in_array('Praktikum', $metodePenilaian) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Praktikum</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Prasyarat -->
                        <div class="tab-pane" id="prasyarat" role="tabpanel">
                            <div class="form-section">
                                <h5>Mata Kuliah Prasyarat</h5>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addPrasyarat()">
                                        <i class="fas fa-plus me-2"></i> Tambah Prasyarat
                                    </button>
                                </div>
                                <div id="prasyarat-container">
                                    @if($mataKuliah->prasyarat && $mataKuliah->prasyarat->count() > 0)
                                        @foreach($mataKuliah->prasyarat as $index => $prasyarat)
                                            <div class="prasyarat-item" data-index="{{ $index }}">
                                                <div class="row align-items-center">
                                                    <div class="col-md-10">
                                                        <label class="form-label">Mata Kuliah Prasyarat</label>
                                                        <select class="form-select" name="prasyarat_ids[]">
                                                            <option value="">Pilih Mata Kuliah</option>
                                                            @foreach($allMataKuliah as $mk)
                                                                <option value="{{ $mk->id }}" {{ $prasyarat->id == $mk->id ? 'selected' : '' }}>
                                                                    {{ $mk->code }} - {{ $mk->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removePrasyarat(this)">
                                                            <i class="fas fa-trash me-2"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-info-circle"></i>
                                            <p class="mb-0">Belum ada mata kuliah prasyarat yang ditambahkan</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Dosen Pengampu -->
                        <div class="tab-pane" id="dosen-pengampu" role="tabpanel">
                            <div class="form-section">
                                <h5>Dosen Pengampu</h5>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addDosen()">
                                        <i class="fas fa-plus"></i> Tambah Dosen
                                    </button>
                                </div>
                                <div id="dosen-container">
                                    @if($mataKuliah->dosenPengampu && $mataKuliah->dosenPengampu->count() > 0)
                                        @foreach($mataKuliah->dosenPengampu as $index => $dosen)
                                            <div class="dosen-item" data-index="{{ $index }}">
                                                <div class="row align-items-center">
                                                    <div class="col-md-10">
                                                        <label class="form-label">Dosen Pengampu</label>
                                                        <select class="form-select" name="dosen_pengampu_ids[]">
                                                            <option value="">Pilih Dosen</option>
                                                            @foreach($allDosen as $dosenItem)
                                                                <option value="{{ $dosenItem->id }}" {{ $dosen->id == $dosenItem->id ? 'selected' : '' }}>
                                                                    {{ $dosenItem->name }} - {{ $dosenItem->email }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeDosen(this)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-info-circle"></i>
                                            <p class="mb-0">Belum ada dosen pengampu yang ditambahkan</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route($activeRole . '.akademik.matakuliah-index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script>
    // Auto-calculate total SKS
    document.addEventListener('DOMContentLoaded', function() {
        const sksTeori = document.getElementById('sks_teori');
        const sksPraktik = document.getElementById('sks_praktik');
        const sksLapangan = document.getElementById('sks_lapangan');
        const totalSks = document.getElementById('total_sks');
        
        function calculateTotal() {
            const teori = parseInt(sksTeori.value) || 0;
            const praktik = parseInt(sksPraktik.value) || 0;
            const lapangan = parseInt(sksLapangan.value) || 0;
            const total = teori + praktik + lapangan;
            
            if (total > 0) {
                totalSks.value = total;
            }
        }
        
        if (sksTeori && sksPraktik && sksLapangan && totalSks) {
            sksTeori.addEventListener('input', calculateTotal);
            sksPraktik.addEventListener('input', calculateTotal);
            sksLapangan.addEventListener('input', calculateTotal);
        }
    });
    
    // Prasyarat functions
    function addPrasyarat() {
        const container = document.getElementById('prasyarat-container');
        const index = container.children.length;
        
        const html = `
            <div class="prasyarat-item" data-index="${index}">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <label class="form-label">Mata Kuliah Prasyarat</label>
                        <select class="form-select" name="prasyarat_ids[]">
                            <option value="">Pilih Mata Kuliah</option>
                            @foreach($allMataKuliah as $mk)
                                <option value="{{ $mk->id }}">{{ $mk->code }} - {{ $mk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removePrasyarat(this)">
                            <i class="fas fa-trash me-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
    }
    
    function removePrasyarat(button) {
        button.closest('.prasyarat-item').remove();
    }
    
    // Dosen functions
    function addDosen() {
        const container = document.getElementById('dosen-container');
        const index = container.children.length;
        
        const html = `
            <div class="dosen-item" data-index="${index}">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <label class="form-label">Dosen Pengampu</label>
                        <select class="form-select" name="dosen_pengampu_ids[]">
                            <option value="">Pilih Dosen</option>
                            @foreach($allDosen as $dosenItem)
                                <option value="{{ $dosenItem->id }}">{{ $dosenItem->name }} - {{ $dosenItem->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeDosen(this)">
                            <i class="fas fa-trash me-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
    }
    
    function removeDosen(button) {
        button.closest('.dosen-item').remove();
    }
</script>
@endsection