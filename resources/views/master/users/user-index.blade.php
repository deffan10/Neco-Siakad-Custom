@extends('themes.core-backpage')

@section('custom-css')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Stats cards */
        .bg-light-primary {
            background-color: rgba(67, 94, 190, 0.1);
        }
        
        .bg-light-success {
            background-color: rgba(40, 167, 69, 0.1);
        }
        
        .bg-light-warning {
            background-color: rgba(255, 193, 7, 0.1);
        }
        
        .bg-light-info {
            background-color: rgba(23, 162, 184, 0.1);
        }

        /* Card styling */
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 10px;
        }

        .card-header {
            background: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
        }

        /* Form styling */
        .form-control, .form-select {
            border-radius: 5px;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 0.5rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #435ebe;
            box-shadow: 0 0 0 0.2rem rgba(67, 94, 190, 0.25);
        }

        /* Badge styling */
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }

        /* Collapsible form */
        .collapse {
            transition: all 0.3s ease;
        }

        .collapse.show {
            margin-top: 1rem;
        }

        /* Image preview */
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }

        /* SECTION TABLE SUPER */

        table {
            /* border: 1px solid #ccc; */
            /* border-collapse: collapse; */
            margin: 0;
            padding: 0;
            /* width: 100%; */
            table-layout: fixed;
        }

        /* table caption {
            font-size: 1.5em;
            margin: 0.5em 0 0.75em;
        } */

        table tr {
            /* background-color: #f8f8f8; */
            border: 1px solid #ddd;
            /* padding: 0.35em; */
        }

        table th,
        table td {
            padding: 0.625em;
            text-align: center;
        }

        table th {
            font-size: 0.85em;
            text-align: center !important;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }


        @media screen and (max-width: 600px) {
            table {
                border: 0;
            }

            table caption {
                font-size: 1.3em;
            }

            table thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }

            table tr {
                border-bottom: 3px solid #ddd;
                display: block;
                margin-bottom: 0.625em;
            }

            table td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: 0.8em;
                text-align: right;
            }

            table td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }

            table td:last-child {
                border-bottom: 0;
            }
            
            /* Improve delete info display on mobile */
            table td[data-label="Dihapus Oleh"] .d-flex.align-items-center,
            table td[data-label="Dihapus Pada"] .d-flex.align-items-center {
                align-items: flex-end !important;
                justify-content: flex-end !important;
            }
            
            /* Make delete info more compact on mobile */
            table td[data-label="Dihapus Pada"] div {
                text-align: right;
            }
        }
        
        /* DataTables Buttons Styling */
        .dt-buttons {
            margin-bottom: 1rem;
        }
        
        .dt-buttons .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        /* Custom DataTables styling */
        #usersTable_wrapper .row:first-child {
            margin-bottom: 1rem;
        }
        
        .dataTables_filter input {
            border-radius: 5px;
            border: 1px solid #dee2e6;
            padding: 0.375rem 0.75rem;
        }
        
        .dataTables_length select {
            border-radius: 5px;
            border: 1px solid #dee2e6;
            padding: 0.375rem 0.75rem;
        }
        
        /* Custom toolbar styling */
        .dataTables-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .export-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .entries-filter {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        @media (max-width: 768px) {
            .dataTables-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .export-buttons {
                justify-content: center;
            }
            
            .entries-filter {
                justify-content: center;
            }
        }

        /* Select2 fixes for dark Tabler theme: make selected tags readable */
        .select2-container--default .select2-selection--multiple {
            background-color: transparent;
            border: 1px solid rgba(255,255,255,0.06);
            min-height: 44px;
            border-radius: 5px;
            padding: 4px 8px;
        }

        /* Select2 for light mode - visible border */
        [data-bs-theme="light"] .select2-container--default .select2-selection--multiple,
        body:not([data-bs-theme="dark"]) .select2-container--default .select2-selection--multiple {
            border: 1px solid #dee2e6;
            background-color: #ffffff;
        }

        [data-bs-theme="light"] .select2-container .select2-selection--multiple .select2-search--inline .select2-search__field,
        body:not([data-bs-theme="dark"]) .select2-container .select2-selection--multiple .select2-search--inline .select2-search__field {
            color: #212529;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: rgba(67,94,190,0.95); /* primary-ish */
            color: #ffffff;
            border: none;
            padding: 4px 8px 4px 24px; /* left padding for remove button space */
            margin-top: 4px;
            margin-right: 6px;
            box-shadow: none;
            position: relative;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255,255,255,0.85);
            position: absolute;
            left: 4px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            text-align: center;
            line-height: 14px;
            font-size: 14px;
            margin: 0;
            opacity: 0.9;
            cursor: pointer;
            border-radius: 2px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background-color: rgba(255,255,255,0.2);
        }

        /* Ensure placeholder/inputs are visible */
        .select2-container .select2-selection--multiple .select2-search--inline .select2-search__field {
            color: #ffffff;
            background: transparent;
            border: none;
            padding: 4px 0;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display:flex;
            align-items:center;
            gap:4px;
        }
    </style>
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
                            <h3 class="mb-0">{{ $users->count() }}</h3>
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
                            <h3 class="mb-0">{{ $users->where('is_active', true)->count() }}</h3>
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
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Utama
                            </a>
                        @else
                            <a href="{{ route($activeRole . '.users.user-trash') }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-trash me-2"></i>Trash
                            </a>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Pengguna
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Collapsible Form -->
                    @if(!$is_trash)
                        <div class="collapse" id="collapseForm">
                            <div class="card card-body border">
                                <h5 class="card-title mb-3">Tambah Pengguna Baru</h5>
                                <form action="{{ route($activeRole . '.users.user-store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Nama Pengguna</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama Pengguna" required>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan email" required>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">No Telepon</label>
                                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Masukkan no telepon" required>
                                            @error('phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="roles" class="form-label">Roles</label>
                                            <select class="form-control form-select" name="roles[]" id="roles" multiple required>
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
                    
                    <!-- Custom Toolbar -->
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
                    
                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="usersTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No Telepon</th>
                                    <th>Roles</th>
                                    @if($is_trash)
                                        <th>Dihapus Oleh</th>
                                        <th>Dihapus Pada</th>
                                    @endif
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $item)
                                    <tr>
                                        <td data-label="No">{{ ++$key }}</td>
                                        <td data-label="Nama">{{ $item->name }}</td>
                                        <td data-label="Email">{{ $item->email }}</td>
                                        <td data-label="No Telepon">{{ $item->phone }}</td>
                                        <td data-label="Roles">
                                            @foreach($item->roles as $role)
                                                <span class="badge bg-info">{{ $role->name }}</span>
                                            @endforeach
                                        </td>

                                        @if($is_trash)
                                            <td data-label="Dihapus Oleh">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-times text-danger me-2"></i>
                                                    <span>{{ $item->deletedBy ? $item->deletedBy->name : 'Tidak diketahui' }}</span>
                                                </div>
                                            </td>
                                            <td data-label="Dihapus Pada">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar-times text-danger me-2"></i>
                                                    <div>
                                                        <small class="d-block">{{ $item->deleted_at ? $item->deleted_at->format('d/m/Y H:i') : '-' }}</small>
                                                        <small class="text-muted">{{ $item->deleted_at ? $item->deleted_at->diffForHumans() : '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        <td data-label="Aksi">
                                            @if($is_trash)
                                                <form action="{{ route($activeRole . '.users.user-restore', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Restore Pengguna">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route($activeRole . '.users.user-force-delete', $item->id) }}" method="POST" class="d-inline" id="delete-form-{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" data-confirm-delete="true" data-bs-toggle="tooltip" title="Hapus Permanent" onclick="confirmDelete('{{ $item->id }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route($activeRole . '.users.user-view', $item->id) }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="Lihat Pengguna">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route($activeRole . '.users.user-destroy', $item->id) }}" method="POST" class="d-inline" id="delete-form-{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" data-confirm-delete="true" data-bs-toggle="tooltip" title="Hapus Pengguna" onclick="confirmDelete('{{ $item->id }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        // Initialize Select2 for roles multi-select
        $(document).ready(function() {
            if ($.fn.select2) {
                $('#roles').select2({
                    placeholder: 'Pilih role',
                    width: '100%'
                });

                // Set previously selected values if available (old input)
                var oldRoles = @json(old('roles', []));
                if (oldRoles && oldRoles.length) {
                    $('#roles').val(oldRoles).trigger('change');
                }
            }
        });

        // load select2 script dynamically if not present
        (function loadSelect2(){
            if (typeof $.fn.select2 === 'undefined') {
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
                script.onload = function(){
                    $('#roles').select2({ placeholder: 'Pilih role', width: '100%' });
                    var oldRoles = @json(old('roles', []));
                    if (oldRoles && oldRoles.length) $('#roles').val(oldRoles).trigger('change');
                };
                document.head.appendChild(script);
            }
        })();
        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#usersTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copy',
                        className: 'btn btn-secondary btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)' 
                        }
                    },
                    {
                        // extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-success btn-sm',
                        action: function (e, dt, node, config) {
                            window.open('{{ route($activeRole . ".users.user-export-csv") }}', '_blank');
                        }
                    },
                    {
                        // extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        action: function (e, dt, node, config) {
                            window.open('{{ route($activeRole . ".users.user-export-excel") }}', '_blank');
                        }
                    },
                    {
                        // extend: 'excel',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        action: function (e, dt, node, config) {
                            window.open('{{ route($activeRole . ".users.user-export-pdf") }}', '_blank');
                        }
                    },
                    {
                        text: '<i class="fas fa-upload"></i> Import Excel',
                        className: 'btn btn-info btn-sm',
                        action: function (e, dt, node, config) {
                            $('#importModal').modal('show');
                        }
                    },
                    // {
                    //     extend: 'print',
                    //     text: '<i class="fas fa-print"></i> Print',
                    //     className: 'btn btn-info btn-sm',
                    //     exportOptions: {
                    //         columns: ':not(:last-child)'
                    //     }
                    // },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Columns',
                        className: 'btn btn-dark btn-sm'
                    }
                ],
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                initComplete: function() {
                    // Move buttons to custom toolbar
                    $('#customToolbar').show();
                    $('#exportButtons').empty();
                    $('.dt-buttons').appendTo('#exportButtons');
                }
            });

            // Handle entries select change
            $('#entriesSelect').on('change', function() {
                var selectedValue = $(this).val();
                table.page.len(selectedValue).draw();
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Image preview
        function previewImage(input, previewId = 'preview') {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Chart initialization
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('mataKuliahChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Wajib', 'Pilihan', 'MKWU', 'MKU'],
                    datasets: [{
                        data: [
                        ],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.7)',
                            'rgba(23, 162, 184, 0.7)',
                            'rgba(255, 193, 7, 0.7)',
                            'rgba(0, 123, 255, 0.7)'
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(23, 162, 184, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(0, 123, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed;
                                }
                            }
                        }
                    }
                }
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
@endsection