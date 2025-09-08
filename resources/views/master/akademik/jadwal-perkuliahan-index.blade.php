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
        #fakultasTable_wrapper .row:first-child {
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
                            <h6 class="text-muted mb-1">Total Jadwal</h6>
                            <h4 class="mb-0">{{ $jadwalPerkuliahans->count() }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-25 p-3 rounded">
                            <i class="fas fa-calendar-alt text-primary fa-2x"></i>
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
                            <h6 class="text-muted mb-1">Terjadwal</h6>
                            <h4 class="mb-0">{{ $jadwalPerkuliahans->where('status', 'Terjadwal')->count() }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-25 p-3 rounded">
                            <i class="fas fa-clock text-success fa-2x"></i>
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
                            <h6 class="text-muted mb-1">Terlaksana</h6>
                            <h4 class="mb-0">{{ $jadwalPerkuliahans->where('status', 'Terlaksana')->count() }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-25 p-3 rounded">
                            <i class="fas fa-check-circle text-warning fa-2x"></i>
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
                            <h6 class="text-muted mb-1">Ditunda/Dibatalkan</h6>
                            <h4 class="mb-0">{{ $jadwalPerkuliahans->whereIn('status', ['Ditunda', 'Dibatalkan'])->count() }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-25 p-3 rounded">
                            <i class="fas fa-exclamation-triangle text-info fa-2x"></i>
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
                    <h5 class="mb-0">{{ $pages ?? 'Daftar Jadwal Perkuliahan' }}</h5>
                    <div>
                        @if($is_trash)
                            <a href="{{ route('akademik.jadwal-perkuliahan-index') }}" class="btn btn-sm btn-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Utama
                            </a>
                        @else
                            <a href="{{ route('akademik.jadwal-perkuliahan-trash') }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-trash me-2"></i>Trash
                            </a>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Jadwal
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Collapsible Form -->
                    @if(!$is_trash)
                        <div class="collapse" id="collapseForm">
                            <div class="card card-body border">
                                <h5 class="card-title mb-3">Tambah Jadwal Perkuliahan Baru</h5>
                                <form action="{{ route('akademik.jadwal-perkuliahan-store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tahun_akademik_id" class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
                                            <select class="form-select" name="tahun_akademik_id" id="tahun_akademik_id" required>
                                                <option value="">Pilih Tahun Akademik</option>
                                                @foreach($tahunAkademiks as $tahun)
                                                    <option value="{{ $tahun->id }}">{{ $tahun->name }} - {{ $tahun->semester }}</option>
                                                @endforeach
                                            </select>
                                            @error('tahun_akademik_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mata_kuliah_id" class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                                            <select class="form-select" name="mata_kuliah_id" id="mata_kuliah_id" required>
                                                <option value="">Pilih Mata Kuliah</option>
                                                @foreach($mataKuliahs as $mk)
                                                    <option value="{{ $mk->id }}">{{ $mk->code }} - {{ $mk->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('mata_kuliah_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="dosen_id" class="form-label">Dosen Pengampu <span class="text-danger">*</span></label>
                                            <select class="form-select" name="dosen_id" id="dosen_id" required>
                                                <option value="">Pilih Dosen</option>
                                                @foreach($dosens as $dosen)
                                                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('dosen_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ruang_id" class="form-label">Ruangan</label>
                                            <select class="form-select" name="ruang_id" id="ruang_id">
                                                <option value="">Pilih Ruangan</option>
                                                @foreach($ruangans as $ruang)
                                                    <option value="{{ $ruang->id }}">{{ $ruang->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('ruang_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                                            <select class="form-select" name="hari" id="hari" required>
                                                <option value="">Pilih Hari</option>
                                                <option value="Senin">Senin</option>
                                                <option value="Selasa">Selasa</option>
                                                <option value="Rabu">Rabu</option>
                                                <option value="Kamis">Kamis</option>
                                                <option value="Jumat">Jumat</option>
                                                <option value="Sabtu">Sabtu</option>
                                                <option value="Minggu">Minggu</option>
                                            </select>
                                            @error('hari')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="code" class="form-label">Kode Jadwal <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="code" id="code" placeholder="Masukkan kode jadwal" required>
                                            @error('code')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control" name="jam_mulai" id="jam_mulai" required>
                                            @error('jam_mulai')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control" name="jam_selesai" id="jam_selesai" required>
                                            @error('jam_selesai')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" required>
                                            @error('tanggal_mulai')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai <small class="text-muted">(Opsional)</small></label>
                                            <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai">
                                            <small class="text-muted">Jika kosong, akan menggunakan 16 minggu ke depan. Sistem akan otomatis generate jadwal pertemuan mingguan.</small>
                                            @error('tanggal_selesai')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="metode" class="form-label">Metode <span class="text-danger">*</span></label>
                                            <select class="form-select" name="metode" id="metode" required>
                                                <option value="">Pilih Metode</option>
                                                <option value="Tatap Muka">Tatap Muka</option>
                                                <option value="Teleconference">Teleconference</option>
                                                <option value="Hybrid">Hybrid</option>
                                            </select>
                                            @error('metode')
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
                        <table class="table table-striped table-bordered" id="jadwalPerkuliahanTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Jadwal</th>
                                    <th>Tahun Akademik</th>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Ruangan</th>
                                    <th>Metode</th>
                                    @if($is_trash)
                                        <th>Dihapus Oleh</th>
                                        <th>Dihapus Pada</th>
                                    @endif
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwalPerkuliahans as $key => $item)
                                    <tr>
                                        <td data-label="No">{{ ++$key }}</td>
                                        <td data-label="Kode Jadwal">{{ $item->code }}</td>
                                        <td data-label="Tahun Akademik">{{ $item->tahunAkademik ? $item->tahunAkademik->name . ' - ' . $item->tahunAkademik->semester : '-' }}</td>
                                        <td data-label="Mata Kuliah">{{ $item->mataKuliah ? $item->mataKuliah->code . ' - ' . $item->mataKuliah->name : '-' }}</td>
                                        <td data-label="Dosen">{{ $item->dosen ? $item->dosen->name : '-' }}</td>
                                        <td data-label="Hari">{{ $item->hari }}</td>
                                        <td data-label="Jam">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</td>
                                        <td data-label="Ruangan">{{ $item->ruangan ? $item->ruangan->name : '-' }}</td>
                                        <td data-label="Metode">{{ $item->metode }}</td>

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
                                                <form action="{{ route('akademik.jadwal-perkuliahan-restore', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Restore Jadwal">
                                                        <i class="fas fa-undo me-1"></i> Restore
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('akademik.jadwal-perkuliahan-view', $item->id) }}" data-bs-toggle="modal" data-bs-target="#editData{{ $item->id }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="Edit Jadwal">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('akademik.jadwal-perkuliahan-destroy', $item->id) }}" method="POST" class="d-inline" id="delete-form-{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" data-confirm-delete="true" data-bs-toggle="tooltip" title="Hapus Jadwal" onclick="confirmDelete('{{ $item->id }}')">
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


@endsection

@section('custom-js')
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#jadwalPerkuliahanTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copy',
                        className: 'btn btn-secondary btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude action column
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
                            return 'Data_Jadwal_Perkuliahan' + new Date().toISOString().slice(0,10);
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
                            return 'Data_Jadwal_Perkuliahan' + new Date().toISOString().slice(0,10);
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
                            return 'Data_Jadwal_Perkuliahan' + new Date().toISOString().slice(0,10);
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

        // Confirm delete function
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
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