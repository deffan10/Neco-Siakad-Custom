@extends('themes.core-backpage')

@section('custom-css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        /* Fix badge colors for Tabler theme */
        .badge.bg-success {
            background-color: #28a745 !important;
        }
        
        .badge.bg-danger {
            background-color: #dc3545 !important;
        }
        
        .badge.bg-warning {
            background-color: #ffc107 !important;
        }
        
        .badge.bg-info {
            background-color: #17a2b8 !important;
        }
        
        .badge.bg-primary {
            background-color: #007bff !important;
        }

        /* Collapsible form */
        .collapse {
            transition: all 0.3s ease;
        }

        .collapse.show {
            margin-top: 1rem;
        }

        /* Table responsive */
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }

        .table td {
            vertical-align: middle;
        }

        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.375rem 0.75rem;
            margin: 0 0.125rem;
            border-radius: 0.375rem;
        }

        /* Media queries for responsive design */
        @media (max-width: 768px) {
            .card-header {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
        }
        
        /* SECTION TABLE SUPER */

        table {
            padding: 0;
            table-layout: fixed;
        }


        table tr {
            border: 1px solid #ddd;
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
        #kurikulumMataKuliahTable_wrapper .row:first-child {
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
    </style>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-light-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Kurikulum Mata Kuliah</h6>
                            <h4 class="mb-0">{{ $kurikulumMataKuliahs->count() }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-25 p-3 rounded">
                            <i class="fas fa-book-open text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-light-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Mata Kuliah Wajib</h6>
                            <h4 class="mb-0">{{ $kurikulumMataKuliahs->where('is_wajib', true)->count() }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-25 p-3 rounded">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-light-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Mata Kuliah Pilihan</h6>
                            <h4 class="mb-0">{{ $kurikulumMataKuliahs->where('is_wajib', false)->count() }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-25 p-3 rounded">
                            <i class="fas fa-list-alt text-warning fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-light-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Kurikulum</h6>
                            <h4 class="mb-0">{{ $kurikulums->count() }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-25 p-3 rounded">
                            <i class="fas fa-graduation-cap text-info fa-2x"></i>
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
                    <h5 class="mb-0">{{ $pages ?? 'Daftar Kurikulum Mata Kuliah' }}</h5>
                    <div>
                        @if($is_trash)
                            <a href="{{ route('akademik.kurikulum.matakuliah-index') }}" class="btn btn-sm btn-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Utama
                            </a>
                        @else
                            <a href="{{ route('akademik.kurikulum.matakuliah-trash') }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-trash me-2"></i>Trash
                            </a>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Kurikulum Mata Kuliah
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Collapsible Form -->
                    @if(!$is_trash)
                        <div class="collapse" id="collapseForm">
                            <div class="card card-body border">
                                <h5 class="card-title mb-3">Tambah Kurikulum Mata Kuliah Baru</h5>
                                <form action="{{ route('akademik.kurikulum.matakuliah-store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="kurikulum_id" class="form-label">Kurikulum <span class="text-danger">*</span></label>
                                            <select class="form-select" name="kurikulum_id" id="kurikulum_id" required>
                                                <option value="">Pilih Kurikulum</option>
                                                @foreach($kurikulums as $kurikulum)
                                                    <option value="{{ $kurikulum->id }}">{{ $kurikulum->name }} ({{ $kurikulum->code }})</option>
                                                @endforeach
                                            </select>
                                            @error('kurikulum_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mata_kuliah_id" class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                                            <select class="form-select" name="mata_kuliah_id" id="mata_kuliah_id" required>
                                                <option value="">Pilih Mata Kuliah</option>
                                                @foreach($mataKuliahs as $mataKuliah)
                                                    <option value="{{ $mataKuliah->id }}">{{ $mataKuliah->name }} ({{ $mataKuliah->code }})</option>
                                                @endforeach
                                            </select>
                                            @error('mata_kuliah_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="semester_id" class="form-label">Semester <span class="text-danger">*</span></label>
                                            <select class="form-select" name="semester_id" id="semester_id" required>
                                                <option value="">Pilih Semester</option>
                                                @foreach($semesters as $semester)
                                                    <option value="{{ $semester->id }}">{{ $semester->name }} ({{ $semester->code }})</option>
                                                @endforeach
                                            </select>
                                            @error('semester_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="is_wajib" class="form-label">Status Wajib <span class="text-danger">*</span></label>
                                            <select class="form-select" name="is_wajib" id="is_wajib" required>
                                                <option value="1">Wajib</option>
                                                <option value="0">Pilihan</option>
                                            </select>
                                            @error('is_wajib')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="urutan" class="form-label">Urutan <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="urutan" id="urutan" placeholder="Masukkan urutan" min="0" required>
                                            @error('urutan')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sks_override" class="form-label">SKS Override</label>
                                            <input type="number" class="form-control" name="sks_override" id="sks_override" placeholder="Masukkan SKS override" min="0" max="10">
                                            @error('sks_override')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="catatan" class="form-label">Catatan</label>
                                            <textarea class="form-control" name="catatan" id="catatan" rows="3" placeholder="Masukkan catatan"></textarea>
                                            @error('catatan')
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
                        <table class="table table-striped table-bordered" id="kurikulumMataKuliahTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kurikulum</th>
                                    <th>Mata Kuliah</th>
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th>Urutan</th>
                                    <th>SKS Override</th>
                                    @if($is_trash)
                                        <th>Dihapus Oleh</th>
                                        <th>Dihapus Pada</th>
                                    @endif
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kurikulumMataKuliahs as $key => $item)
                                    <tr>
                                        <td data-label="No">{{ ++$key }}</td>
                                        <td data-label="Kurikulum">{{ $item->kurikulum ? $item->kurikulum->name : '-' }}</td>
                                        <td data-label="Mata Kuliah">{{ $item->mataKuliah ? $item->mataKuliah->name : '-' }}</td>
                                        <td data-label="Semester">{{ $item->semester ? $item->semester->name : '-' }}</td>
                                        <td data-label="Status">
                                            @if($item->is_wajib == true)
                                                <span class="badge bg-success">Wajib</span>
                                            @else
                                                <span class="badge bg-warning">Pilihan</span>
                                            @endif
                                        </td>
                                        <td data-label="Urutan">{{ $item->urutan }}</td>
                                        <td data-label="SKS Override">{{ $item->sks_override ?? '-' }}</td>
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
                                                        <small class="d-block">{{ $item->deleted_at ? \Carbon\Carbon::parse($item->deleted_at)->format('d/m/Y H:i') : '-' }}</small>
                                                        <small class="text-muted">{{ $item->deleted_at ? \Carbon\Carbon::parse($item->deleted_at)->diffForHumans() : '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        <td data-label="Aksi">
                                            @if($is_trash)
                                                <form action="{{ route('akademik.kurikulum.matakuliah-restore', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Restore Kurikulum Mata Kuliah">
                                                        <i class="fas fa-undo me-1"></i> Restore
                                                    </button>
                                                </form>
                                            @else
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#editData{{ $item->id }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="Edit Kurikulum Mata Kuliah">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('akademik.kurikulum.matakuliah-destroy', $item->id) }}" method="POST" class="d-inline" id="delete-form-{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" data-confirm-delete="true" data-bs-toggle="tooltip" title="Hapus Kurikulum Mata Kuliah" onclick="confirmDelete('{{ $item->id }}')">
                                                        <i class="fas fa-trash me-1"></i> Delete
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

    <!-- Edit Modals -->
    @if(!$is_trash)
        @foreach ($kurikulumMataKuliahs as $item)
            <div class="modal fade" id="editData{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('akademik.kurikulum.matakuliah-update', $item->id) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Kurikulum Mata Kuliah - {{ $item->kurikulum ? $item->kurikulum->name : 'Kurikulum' }} - {{ $item->mataKuliah ? $item->mataKuliah->name : 'Mata Kuliah' }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_kurikulum_id{{ $item->id }}" class="form-label">Kurikulum <span class="text-danger">*</span></label>
                                        <select class="form-select" name="kurikulum_id" id="edit_kurikulum_id{{ $item->id }}" required>
                                            <option value="">Pilih Kurikulum</option>
                                            @foreach($kurikulums as $kurikulum)
                                                <option value="{{ $kurikulum->id }}" {{ $item->kurikulum_id == $kurikulum->id ? 'selected' : '' }}>{{ $kurikulum->name }} ({{ $kurikulum->code }})</option>
                                            @endforeach
                                        </select>
                                        @error('kurikulum_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_mata_kuliah_id{{ $item->id }}" class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                                        <select class="form-select" name="mata_kuliah_id" id="edit_mata_kuliah_id{{ $item->id }}" required>
                                            <option value="">Pilih Mata Kuliah</option>
                                            @foreach($mataKuliahs as $mataKuliah)
                                                <option value="{{ $mataKuliah->id }}" {{ $item->mata_kuliah_id == $mataKuliah->id ? 'selected' : '' }}>{{ $mataKuliah->name }} ({{ $mataKuliah->code }})</option>
                                            @endforeach
                                        </select>
                                        @error('mata_kuliah_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_semester_id{{ $item->id }}" class="form-label">Semester <span class="text-danger">*</span></label>
                                        <select class="form-select" name="semester_id" id="edit_semester_id{{ $item->id }}" required>
                                            <option value="">Pilih Semester</option>
                                            @foreach($semesters as $semester)
                                                <option value="{{ $semester->id }}" {{ $item->semester_id == $semester->id ? 'selected' : '' }}>{{ $semester->name }} ({{ $semester->code }})</option>
                                            @endforeach
                                        </select>
                                        @error('semester_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_is_wajib{{ $item->id }}" class="form-label">Status Wajib <span class="text-danger">*</span></label>
                                        <select class="form-select" name="is_wajib" id="edit_is_wajib{{ $item->id }}" required>
                                            <option value="1" {{ $item->is_wajib ? 'selected' : '' }}>Wajib</option>
                                            <option value="0" {{ !$item->is_wajib ? 'selected' : '' }}>Pilihan</option>
                                        </select>
                                        @error('is_wajib')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_urutan{{ $item->id }}" class="form-label">Urutan <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="urutan" id="edit_urutan{{ $item->id }}" value="{{ $item->urutan }}" min="0" required>
                                        @error('urutan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_sks_override{{ $item->id }}" class="form-label">SKS Override</label>
                                        <input type="number" class="form-control" name="sks_override" id="edit_sks_override{{ $item->id }}" value="{{ $item->sks_override }}" min="0" max="10">
                                        @error('sks_override')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="edit_catatan{{ $item->id }}" class="form-label">Catatan</label>
                                        <textarea class="form-control" name="catatan" id="edit_catatan{{ $item->id }}" rows="3">{{ $item->catatan }}</textarea>
                                        @error('catatan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
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
    @endif
@endsection

@section('custom-js')

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#kurikulumMataKuliahTable').DataTable({
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
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        },
                        filename: function() {
                            return 'Data_Kurikulum_MK_' + new Date().toISOString().slice(0,10);
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        },
                        filename: function() {
                            return 'Data_Kurikulum_MK_' + new Date().toISOString().slice(0,10);
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        },
                        filename: function() {
                            return 'Data_Program_Studi' + new Date().toISOString().slice(0,10);
                        },
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
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

        // Konfirmasi delete dengan SweetAlert
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data kurikulum mata kuliah yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection