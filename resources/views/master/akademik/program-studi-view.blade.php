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
        /* background-color: #f8f9fa; */
        border: 1px solid #e9ecef;
    }
    .form-section h5 {
        border-bottom: 2px solid #667eea;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        /* color: #e8eaeb; */
    }
    .preview-image {
        max-width: 100px;
        max-height: 100px;
        border-radius: 5px;
        border: 1px solid #ddd;
        margin-top: 10px;
        display: none;
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
                            <img src="{{ optional($programStudi->profile)->logo ? asset('storage/' . optional($programStudi->profile)->logo) : asset('assets/images/default-logo.png') }}" alt="Logo Program Studi" class="settings-logo">
                        </div>
                        <div class="col-md-10">
                            <h2 class="mb-0">{{ $programStudi->name ?? 'Nama Program Studi' }}</h2>
                            <p class="mb-1">{{ optional($programStudi->profile)->deskripsi ?? 'Deskripsi singkat program studi.' }}</p>
                            <p class="mb-0"><i class="fas fa-graduation-cap me-2"></i> {{ $programStudi->jenjang ?? 'Jenjang' }} | <i class="fas fa-university me-2"></i> {{ optional($programStudi->fakultas)->name ?? 'Fakultas' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route($activeRole . '.akademik.program-studi-update', $programStudi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#data-utama" role="tab">
                                <i class="fas fa-graduation-cap me-2"></i> Data Utama
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#akademik" role="tab">
                                <i class="fas fa-graduation-cap me-2"></i> Akademik
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pejabat" role="tab">
                                <i class="fas fa-user-tie me-2"></i> Pejabat
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#konten-profil" role="tab">
                                <i class="fas fa-file-alt me-2"></i> Konten Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#branding" role="tab">
                                <i class="fas fa-palette me-2"></i> Branding
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
                                        <label class="form-label">Nama Program Studi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ $programStudi->name }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Kode <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="code" value="{{ $programStudi->code }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Nama Singkat</label>
                                        <input type="text" class="form-control" name="nama_singkat" value="{{ $programStudi->nama_singkat }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fakultas <span class="text-danger">*</span></label>
                                        <select class="form-select" name="fakultas_id" required>
                                            <option value="">Pilih Fakultas</option>
                                            @foreach($fakultas as $fak)
                                                <option value="{{ $fak->id }}" {{ $programStudi->fakultas_id == $fak->id ? 'selected' : '' }}>{{ $fak->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $programStudi->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Aktif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Akademik -->
                        <div class="tab-pane" id="akademik" role="tabpanel">
                            <div class="form-section">
                                <h5>Informasi Akademik</h5>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Jenjang <span class="text-danger">*</span></label>
                                        <select class="form-select" name="jenjang" required>
                                            <option value="">Pilih Jenjang</option>
                                            <option value="D3" {{ $programStudi->jenjang == 'D3' ? 'selected' : '' }}>D3</option>
                                            <option value="D4" {{ $programStudi->jenjang == 'D4' ? 'selected' : '' }}>D4</option>
                                            <option value="S1" {{ $programStudi->jenjang == 'S1' ? 'selected' : '' }}>S1</option>
                                            <option value="S2" {{ $programStudi->jenjang == 'S2' ? 'selected' : '' }}>S2</option>
                                            <option value="S3" {{ $programStudi->jenjang == 'S3' ? 'selected' : '' }}>S3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Gelar Depan</label>
                                        <input type="text" class="form-control" name="gelar_depan" value="{{ $programStudi->gelar_depan }}" placeholder="Contoh: Dr.">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Gelar Belakang</label>
                                        <input type="text" class="form-control" name="gelar_belakang" value="{{ $programStudi->gelar_belakang }}" placeholder="Contoh: S.Kom">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Akreditasi</label>
                                        <input type="text" class="form-control" name="akreditasi" value="{{ $programStudi->akreditasi }}" placeholder="Contoh: A">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Tanggal Akreditasi</label>
                                        <input type="date" class="form-control" name="tanggal_akreditasi" value="{{ $programStudi->tanggal_akreditasi }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">SK Pendirian</label>
                                        <input type="text" class="form-control" name="sk_pendirian" value="{{ $programStudi->sk_pendirian }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Tanggal SK Pendirian</label>
                                        <input type="date" class="form-control" name="tanggal_sk_pendirian" value="{{ $programStudi->tanggal_sk_pendirian }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">SKS Kelulusan (Target)</label>
                                        <input type="number" class="form-control" name="sks_lulus" value="{{ $programStudi->sks_lulus ?? 144 }}" min="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Pejabat -->
                        <div class="tab-pane" id="pejabat" role="tabpanel">
                            <div class="form-section">
                                <h5>Pejabat Program Studi</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ketua Program Studi (Kaprodi)</label>
                                        <select class="form-select" name="kaprodi_id">
                                            <option value="">Pilih Kaprodi</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ $programStudi->kaprodi_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Sekretaris Program Studi</label>
                                        <select class="form-select" name="sekretaris_id">
                                            <option value="">Pilih Sekretaris</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ $programStudi->sekretaris_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        
                        <!-- Tab Konten Profil -->
                        <div class="tab-pane" id="konten-profil" role="tabpanel">
                            <div class="form-section">
                                <h5>Konten Profil</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Slug <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="slug" value="{{ optional($programStudi->profile)->slug }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Deskripsi Program Studi</label>
                                        <textarea class="form-control summernote-editor" id="deskripsi" name="deskripsi" rows="5" placeholder="Masukkan deskripsi program studi">{{ optional($programStudi->profile)->deskripsi }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Objektif Program Studi</label>
                                        <textarea class="form-control summernote-editor" id="objektif" name="objektif" rows="5" placeholder="Masukkan objektif program studi">{{ optional($programStudi->profile)->objektif }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Prospek Karir</label>
                                        <textarea class="form-control summernote-editor" id="karir" name="karir" rows="5" placeholder="Masukkan prospek karir lulusan">{{ optional($programStudi->profile)->karir }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Branding -->
                        <div class="tab-pane" id="branding" role="tabpanel">
                            <div class="form-section">
                                <h5>Branding</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Logo Program Studi</label>
                                        <input type="file" class="form-control" name="logo" id="logoInput" onchange="previewImage(this, 'previewLogo')">
                                        <img src="" alt="Logo Preview" class="preview-image" id="previewLogo">
                                        @if(optional($programStudi->profile)->logo)
                                            <div class="mt-2">
                                                <p>Logo saat ini:</p>
                                                <img src="{{ asset('storage/' . optional($programStudi->profile)->logo) }}" alt="Current Logo" class="preview-image" style="display:block;">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Banner Program Studi</label>
                                        <input type="file" class="form-control" name="banner" id="bannerInput" onchange="previewImage(this, 'previewBanner')">
                                        <img src="" alt="Banner Preview" class="preview-image" id="previewBanner">
                                        @if(optional($programStudi->profile)->banner)
                                            <div class="mt-2">
                                                <p>Banner saat ini:</p>
                                                <img src="{{ asset('storage/' . optional($programStudi->profile)->banner) }}" alt="Current Banner" class="preview-image" style="display:block; max-width: 200px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route($activeRole . '.akademik.program-studi-index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize summernote immediately
        $('.summernote-editor').summernote({
            placeholder: 'Masukkan konten...',
            tabsize: 2,
            height: 150,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
        
        // Re-initialize when konten-profil tab is shown
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            if (target === "#konten-profil") {
                setTimeout(function() {
                    $('.summernote-editor').summernote({
                        placeholder: 'Masukkan konten...',
                        tabsize: 2,
                        height: 150,
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture']],
                            ['view', ['fullscreen', 'codeview']]
                        ]
                    });
                }, 500);
            }
        });
    });

    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = $('#' + previewId);
                preview.attr('src', e.target.result);
                preview.css('display', 'block');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection