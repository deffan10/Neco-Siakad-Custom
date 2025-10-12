@extends('themes.core-backpage')

@section('custom-css')
    <link href="{{ asset('assets') }}/libs/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet" />
    <script src="{{ asset('assets') }}/libs/tom-select/dist/js/tom-select.base.min.js" defer></script>
    <link rel="stylesheet" href="{{ asset('vendor') }}/siakad/siakad-crud.css">
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-light-primary">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total User</h6>
                            <h3 class="mb-0">{{ \App\Models\User::count() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-25 p-3 rounded">
                            <i class="fas fa-users text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-light-success">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">User Aktif</h6>
                            <h3 class="mb-0">{{ \App\Models\User::where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-25 p-3 rounded">
                            <i class="fas fa-user-check text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-light-warning">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">User Dihapus</h6>
                            <h3 class="mb-0">{{ \App\Models\User::onlyTrashed()->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-25 p-3 rounded">
                            <i class="fas fa-user-slash text-warning fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-light-info">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Admin</h6>
                            <h3 class="mb-0">{{ \Spatie\Permission\Models\Role::where('name', 'Admin')->first()->users()->count() }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-25 p-3 rounded">
                            <i class="fas fa-user-shield text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-12 col-12 mb-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $pages ?? 'Daftar Pengguna' }}</h5>
                    <div>
                        @if($is_trash)
                            <a href="{{ route($activeRole . '.users.user-index') }}" class="btn btn-sm btn-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        @else
                            <a href="{{ route($activeRole . '.users.user-trash') }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-trash me-2"></i>Trash
                            </a>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Pengguna
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(!$is_trash)
                        <div class="collapse" id="collapseForm">
                            <div class="card card-body border">
                                <h5 class="card-title mb-3">Tambah Pengguna Baru</h5>
                                <form action="{{ route($activeRole . '.users.user-store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Pengguna</label>
                                            <input type="text" class="form-control" name="name" placeholder="Masukkan nama pengguna" required>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" placeholder="Masukkan email" required>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">No Telepon</label>
                                            <input type="text" class="form-control" name="phone" placeholder="Masukkan no telepon" required>
                                            @error('phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Roles</label>
                                            <select class="form-select" name="roles[]" id="select-roles" multiple required>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('roles')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
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
                    
                    <!-- Advanced Filters -->
                    @if(!$is_trash)
                        <div class="filter-container">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small">
                                        <i class="fas fa-filter me-1"></i> Filter Role
                                    </label>
                                    <select class="form-select form-select-sm" id="filter-role">
                                        <option value="">Semua Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">
                                        <i class="fas fa-user me-1"></i> Filter Nama
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="filter-name" placeholder="Cari nama...">
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
                    
                    <!-- DataTable -->
                    <div class="mt-3">
                        {{ $dataTable->table(['class' => 'table table-striped table-bordered dt-responsive', 'style' => 'width:100%']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="fas fa-upload me-2"></i>Import Data Users
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Alert untuk hasil import -->
                    <div id="importAlert" class="alert d-none" role="alert"></div>
                    
                    <!-- Form Import -->
                    <form id="importForm" action="{{ route($activeRole . '.users.user-import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- File Upload Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="excel_file" class="form-label">
                                    <strong>Pilih File Excel</strong>
                                    <small class="text-muted">(Format: .xlsx, .xls)</small>
                                </label>
                                <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    File maksimal 10MB. Pastikan format sesuai dengan template yang disediakan.
                                </div>
                            </div>
                        </div>

                        <!-- Template Download Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-body">
                                        <h6 class="card-title text-info">
                                            <i class="fas fa-download me-2"></i>Download Template
                                        </h6>
                                        <p class="card-text small text-muted">
                                            Download template Excel untuk memastikan format yang benar sebelum import.
                                        </p>
                                        <a href="{{ route($activeRole . '.users.user-template') }}" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-file-excel me-1"></i>Download Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Import Options -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label"><strong>Opsi Import</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="skip_duplicates" name="skip_duplicates" checked>
                                    <label class="form-check-label" for="skip_duplicates">
                                        Skip data duplikat (berdasarkan email dan phone)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="send_welcome" name="send_welcome">
                                    <label class="form-check-label" for="send_welcome">
                                        Kirim email welcome ke user yang berhasil diimport
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar (Hidden by default) -->
                        <div id="importProgress" class="mb-3 d-none">
                            <label class="form-label">Progress Import:</label>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted">Sedang memproses data...</small>
                        </div>

                        <!-- Important Notes -->
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>Penting!
                            </h6>
                            <ul class="mb-0 small">
                                <li><strong>Kolom Wajib:</strong> Nama, Email, Phone harus diisi</li>
                                <li><strong>Format Tanggal:</strong> YYYY-MM-DD (contoh: 2025-01-15)</li>
                                <li><strong>Status Boolean:</strong> Ya/Tidak, Aktif/Non-Aktif, True/False</li>
                                <li><strong>Password Default:</strong> Semua user akan mendapat password "password123"</li>
                                <li><strong>Role Default:</strong> User yang tidak memiliki role akan mendapat role "User"</li>
                            </ul>
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
@section('custom-js')
<script>
                // Select roles
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
</script>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    
    
    <script>
        $(document).ready(function() {
            var table = window.LaravelDataTables["user-table"];
            
            // Add custom export/import buttons to DataTable
            table.button().add(0, {
                text: '<i class="fas fa-file-csv me-1"></i> CSV',
                className: 'btn btn-sm btn-success',
                action: function (e, dt, node, config) {
                    window.open('{{ route($activeRole . ".users.user-export-csv") }}', '_blank');
                }
            });
            
            table.button().add(1, {
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-sm btn-success',
                action: function (e, dt, node, config) {
                    window.open('{{ route($activeRole . ".users.user-export-excel") }}', '_blank');
                }
            });
            
            table.button().add(2, {
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-sm btn-danger',
                action: function (e, dt, node, config) {
                    window.open('{{ route($activeRole . ".users.user-export-pdf") }}', '_blank');
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
                text: '<i class="fas fa-upload me-1"></i> Import Excel',
                className: 'btn btn-sm btn-warning',
                action: function (e, dt, node, config) {
                    $('#importModal').modal('show');
                }
            });
            


            // Filter by Role
            $('#filter-role').on('change', function() {
                table.column(4).search(this.value).draw();
            });
            
            // Filter by Name
            $('#filter-name').on('keyup', function() {
                table.column(1).search(this.value).draw();
            });
            
            // Quick Search (Global)
            $('#quick-search').on('keyup', function() {
                table.search(this.value).draw();
            });
            
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Reinitialize tooltips after table redraw
            table.on('draw', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });
        });


        // Handle Import Form
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const fileInput = $('#excel_file')[0];
            
            // Validation
            if (!fileInput.files.length) {
                showImportAlert('danger', 'Silahkan pilih file Excel terlebih dahulu!');
                return;
            }
            
            const file = fileInput.files[0];
            const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
            
            if (!allowedTypes.includes(file.type)) {
                showImportAlert('danger', 'Format file tidak didukung! Gunakan file .xlsx atau .xls');
                return;
            }
            
            if (file.size > 10 * 1024 * 1024) { // 10MB
                showImportAlert('danger', 'Ukuran file terlalu besar! Maksimal 10MB');
                return;
            }
            
            // Show loading
            $('#importBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Importing...');
            $('#importProgress').removeClass('d-none');
            
            // Simulate progress (since we can't get real progress from server)
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;
                $('.progress-bar').css('width', progress + '%');
            }, 500);
            
            // Submit form
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
                        
                        // Show detailed results if available
                        if (response.success_count || response.skip_count) {
                            let details = `<br><small>`;
                            details += `✅ Berhasil: ${response.success_count || 0} data<br>`;
                            details += `⚠️ Dilewati: ${response.skip_count || 0} data`;
                            if (response.errors && response.errors.length > 0) {
                                details += `<br>❌ Error: ${response.errors.length} data`;
                            }
                            details += `</small>`;
                            $('#importAlert').append(details);
                        }
                        
                        // Reload page after 3 seconds
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                        
                    } else {
                        showImportAlert('danger', response.message || 'Terjadi kesalahan saat import');
                        
                        // Show errors if available
                        if (response.errors && response.errors.length > 0) {
                            let errorList = '<br><small><strong>Detail Error:</strong><ul class="mt-2 mb-0">';
                            response.errors.slice(0, 5).forEach(error => { // Show max 5 errors
                                errorList += `<li>${error}</li>`;
                            });
                            if (response.errors.length > 5) {
                                errorList += `<li>... dan ${response.errors.length - 5} error lainnya</li>`;
                            }
                            errorList += '</ul></small>';
                            $('#importAlert').append(errorList);
                        }
                    }
                },
                error: function(xhr) {
                    clearInterval(progressInterval);
                    let errorMessage = 'Terjadi kesalahan saat upload file';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 422) {
                        errorMessage = 'Data yang diupload tidak valid';
                    } else if (xhr.status === 413) {
                        errorMessage = 'Ukuran file terlalu besar';
                    }
                    
                    showImportAlert('danger', errorMessage);
                },
                complete: function() {
                    // Reset button and progress
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
        
        // Show import alert
        function showImportAlert(type, message) {
            $('#importAlert')
                .removeClass('d-none alert-success alert-danger alert-warning')
                .addClass('alert-' + type)
                .html('<i class="fas fa-' + (type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle') + ' me-2"></i>' + message);
        }
    </script>
@endpush
