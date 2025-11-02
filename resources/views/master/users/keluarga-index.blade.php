@extends('themes.core-backpage')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('vendor') }}/siakad/siakad-crud.css">

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12 mb-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $pages ?? 'Daftar Keluarga' }}</h5>
                    <div>
                        @if($is_trash)
                            <a href="{{ route($activeRole . '.users.keluarga-index') }}" class="btn btn-sm btn-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        @else
                            <a href="{{ route($activeRole . '.users.keluarga-trash') }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-trash me-2"></i>Trash
                            </a>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Keluarga
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(!$is_trash)
                        <div class="collapse" id="collapseForm">
                            <div class="card card-body border">
                                <h5 class="card-title mb-3">Tambah Data Keluarga</h5>
                                <form action="{{ route($activeRole . '.users.keluarga-store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Hubungan Keluarga</label>
                                            <select class="form-select" name="hubungan" required>
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
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" name="nama" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Pengguna</label>
                                            <select class="form-select" name="user_id" required>
                                                <option value="">Pilih Pengguna</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Pekerjaan</label>
                                            <input type="text" class="form-control" name="pekerjaan">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Telepon</label>
                                            <input type="text" class="form-control" name="telepon">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tempat Lahir</label>
                                            <input type="text" class="form-control" name="tempat_lahir">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="date" class="form-control" name="tanggal_lahir">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Penghasilan</label>
                                            <input type="number" class="form-control" name="penghasilan" min="0">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Alamat</label>
                                            <textarea class="form-control" name="alamat" rows="2"></textarea>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-save me-2"></i>Simpan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    
                    <div class="dataTables-toolbar mt-3" id="customToolbar" style="display: none;">
                        <div class="export-buttons" id="exportButtons">
                            <!-- Export buttons will be moved here -->
                        </div>
                        <div class="entries-filter">
                            <label for="entriesSelect" class="form-label mb-0">Tampilkan:</label>
                            <select class="form-select form-select-sm" id="entriesSelect" style="width: auto;">
                                <option value="10">10 entries</option>
                                <option value="25">25 entries</option>
                                <option value="50">50 entries</option>
                                <option value="100">100 entries</option>
                                <option value="-1">Semua entries</option>
                            </select>
                        </div>
                    </div>
                    
                    @if(!$is_trash)
                        <div class="filter-container mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small">
                                        <i class="fas fa-filter me-1"></i> Filter Hubungan
                                    </label>
                                    <select class="form-select form-select-sm" id="filter-hubungan">
                                        <option value="">Semua Hubungan</option>
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
                                <div class="col-md-4">
                                    <label class="form-label small">
                                        <i class="fas fa-user me-1"></i> Filter Pengguna
                                    </label>
                                    <select class="form-select form-select-sm" id="filter-user">
                                        <option value="">Semua Pengguna</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">
                                        <i class="fas fa-search me-1"></i> Cepat Cari
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="quick-search" placeholder="Ketik untuk cari...">
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- DataTable (server side) -->
                    <div class="mt-3">
                        {{ $dataTable->table(['id' => 'keluarga-table', 'class' => 'table table-striped table-bordered dt-responsive nowrap', 'style' => 'width:100%']) }}
                    </div>
                </div>
            </div>
        </div>
        

    </div>

    <!-- Edit Modals -->
    @foreach(\App\Models\User\Keluarga::when($is_trash, function($q) { $q->onlyTrashed(); })->get() as $item)
        <div class="modal fade" id="editData{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Keluarga - {{ $item->nama }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route($activeRole . '.users.keluarga-update', $item->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hubungan Keluarga</label>
                                    <select class="form-select" name="hubungan" required>
                                        <option value="Ayah" {{ $item->hubungan == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                        <option value="Ibu" {{ $item->hubungan == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                        <option value="Suami" {{ $item->hubungan == 'Suami' ? 'selected' : '' }}>Suami</option>
                                        <option value="Istri" {{ $item->hubungan == 'Istri' ? 'selected' : '' }}>Istri</option>
                                        <option value="Anak" {{ $item->hubungan == 'Anak' ? 'selected' : '' }}>Anak</option>
                                        <option value="Kakak" {{ $item->hubungan == 'Kakak' ? 'selected' : '' }}>Kakak</option>
                                        <option value="Adik" {{ $item->hubungan == 'Adik' ? 'selected' : '' }}>Adik</option>
                                        <option value="Wali" {{ $item->hubungan == 'Wali' ? 'selected' : '' }}>Wali</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama" value="{{ $item->nama }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pengguna</label>
                                    <select class="form-select" name="user_id" required>
                                        <option value="">Pilih Pengguna</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $item->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pekerjaan</label>
                                    <input type="text" class="form-control" name="pekerjaan" value="{{ $item->pekerjaan }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" name="telepon" value="{{ $item->telepon }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" name="tempat_lahir" value="{{ $item->tempat_lahir }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" name="tanggal_lahir" value="{{ $item->tanggal_lahir }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Penghasilan</label>
                                    <input type="number" class="form-control" name="penghasilan" value="{{ $item->penghasilan }}" min="0">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="2">{{ $item->alamat }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="fas fa-upload me-2"></i>Import Data Keluarga
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="importAlert" class="alert d-none" role="alert"></div>
                    
                    <form id="importForm" action="{{ route($activeRole . '.users.keluarga-import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="excel_file" class="form-label">
                                    <strong>Pilih File Excel</strong>
                                    <small class="text-muted">(Format: .xlsx, .xls)</small>
                                </label>
                                <input type="file" class="form-control" id="excel_file" name="file" accept=".xlsx,.xls" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    File maksimal 2MB. Pastikan format sesuai dengan template.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-body">
                                        <h6 class="card-title text-info">
                                            <i class="fas fa-download me-2"></i>Download Template
                                        </h6>
                                        <p class="card-text small text-muted">
                                            Download template Excel untuk memastikan format yang benar.
                                        </p>
                                        <a href="{{ route($activeRole . '.users.keluarga-template') }}" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-file-excel me-1"></i>Download Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label"><strong>Opsi Import</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="skip_duplicates" name="skip_duplicates" checked>
                                    <label class="form-check-label" for="skip_duplicates">
                                        Skip data duplikat
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="importProgress" class="mb-3 d-none">
                            <label class="form-label">Progress Import:</label>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted">Sedang memproses data...</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" form="importForm" class="btn btn-success" id="importBtn">
                        <i class="fas fa-upload me-2"></i>Import Data
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            var table = window.LaravelDataTables["keluarga-table"];

            // Add custom export/import buttons
            table.button().add(0, {
                text: '<i class="fas fa-file-csv me-1"></i> CSV',
                className: 'btn btn-sm btn-success',
                action: function (e, dt, node, config) {
                    window.open('{{ route($activeRole . ".users.keluarga-export-csv") }}', '_blank');
                }
            });
            
            table.button().add(1, {
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-sm btn-success',
                action: function (e, dt, node, config) {
                    window.open('{{ route($activeRole . ".users.keluarga-export-excel") }}', '_blank');
                }
            });
            
            table.button().add(2, {
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-sm btn-danger',
                action: function (e, dt, node, config) {
                    window.open('{{ route($activeRole . ".users.keluarga-export-pdf") }}', '_blank');
                }
            });
            
            table.button().add(3, {
                text: '<i class="fas fa-print me-1"></i> Print',
                extend: 'print',
                className: 'btn btn-sm btn-info',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            });
            
            table.button().add(4, {
                text: '<i class="fas fa-upload me-1"></i> Import',
                className: 'btn btn-sm btn-warning',
                action: function (e, dt, node, config) {
                    $('#importModal').modal('show');
                }
            });

            // Quick Search (Global)
            $('#quick-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Filter by Hubungan
            $('#filter-hubungan').on('change', function() {
                table.column(1).search(this.value).draw();
            });

            // Filter by User/Pengguna
            $('#filter-user').on('change', function() {
                table.column(3).search(this.value).draw();
            });

            // Reinitialize tooltips after table redraw
            table.on('draw', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            // Handle Import Form
            $('#importForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const fileInput = $('#excel_file')[0];
                
                if (!fileInput.files.length) {
                    showImportAlert('danger', 'Silahkan pilih file Excel terlebih dahulu!');
                    return;
                }
                
                $('#importBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Importing...');
                $('#importProgress').removeClass('d-none');
                
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 90) progress = 90;
                    $('.progress-bar').css('width', progress + '%');
                }, 500);
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        clearInterval(progressInterval);
                        $('.progress-bar').css('width', '100%');
                        
                        if (response.success) {
                            showImportAlert('success', response.message);
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showImportAlert('danger', response.message || 'Terjadi kesalahan');
                        }
                    },
                    error: function(xhr) {
                        clearInterval(progressInterval);
                        showImportAlert('danger', 'Terjadi kesalahan saat upload file');
                    },
                    complete: function() {
                        $('#importBtn').prop('disabled', false).html('<i class="fas fa-upload me-2"></i>Import Data');
                        setTimeout(() => {
                            $('#importProgress').addClass('d-none');
                            $('.progress-bar').css('width', '0%');
                        }, 2000);
                    }
                });
            });

            // Reset modal when closed
            $('#importModal').on('hidden.bs.modal', function() {
                $('#importForm')[0].reset();
                $('#importAlert').addClass('d-none').removeClass('alert-success alert-danger').html('');
                $('#importProgress').addClass('d-none');
                $('.progress-bar').css('width', '0%');
            });

            function showImportAlert(type, message) {
                $('#importAlert')
                    .removeClass('d-none alert-success alert-danger')
                    .addClass('alert-' + type)
                    .html('<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' me-2"></i>' + message);
            }
        });

    </script>
@endpush