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

        /* Collapsible form */
        .collapse {
            transition: all 0.3s ease;
        }

        .collapse.show {
            margin-top: 1rem;
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
        #tahunAkademikTable_wrapper .row:first-child {
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
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Tahun Akademik</h6>
                            <h3 class="mb-0">{{ count($tahunAkademiks) }}</h3>
                        </div>
                        <div class="rounded p-3 bg-primary bg-opacity-25">
                            <i class="fas fa-calendar-alt text-primary fa-2x"></i>
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
                            <h6 class="mb-0">Aktif</h6>
                            <h3 class="mt-2 mb-0">{{ $tahunAkademiks->where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="rounded-circle p-3 bg-success text-white">
                            <i class="fas fa-check-circle fa-lg"></i>
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
                            <h6 class="mb-0">Ganjil</h6>
                            <h3 class="mt-2 mb-0">{{ $tahunAkademiks->where('semester', 'Ganjil')->count() }}</h3>
                        </div>
                        <div class="rounded-circle p-3 bg-warning text-white">
                            <i class="fas fa-moon fa-lg"></i>
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
                            <h6 class="mb-0">Genap</h6>
                            <h3 class="mt-2 mb-0">{{ $tahunAkademiks->where('semester', 'Genap')->count() }}</h3>
                        </div>
                        <div class="rounded-circle p-3 bg-info text-white">
                            <i class="fas fa-sun fa-lg"></i>
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
                    <h5 class="mb-0">{{ $pages ?? 'Daftar Tahun Akademik' }}</h5>
                    <div>
                        @if($is_trash)
                            <a href="{{ route($activeRole . '.akademik.tahun-akademik-index') }}" class="btn btn-sm btn-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Utama
                            </a>
                        @else
                            <a href="{{ route($activeRole . '.akademik.tahun-akademik-trash') }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-trash me-2"></i>Trash
                            </a>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Tahun Akademik
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tab Navigation for Kalender Akademik -->
                    <ul class="nav nav-tabs mb-4" id="calendarTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="periode-tab" data-bs-toggle="tab" data-bs-target="#periode" type="button" role="tab" aria-controls="periode" aria-selected="true">
                                <i class="fas fa-list me-2"></i>Periode Tahun Akademik
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="calendar-display-tab" data-bs-toggle="tab" data-bs-target="#calendar-display" type="button" role="tab" aria-controls="calendar-display" aria-selected="false">
                                <i class="fas fa-calendar-alt me-2"></i>Display Kalender Akademik
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="calendarTabsContent">
                        <!-- Tab 1: Periode Tahun Akademik -->
                        <div class="tab-pane fade show active" id="periode" role="tabpanel" aria-labelledby="periode-tab">
                            <!-- Collapsible Form -->
                            @if(!$is_trash)
                        <div class="collapse" id="collapseForm">
                            <div class="card card-body border">
                                <h5 class="card-title mb-3">Tambah Tahun Akademik Baru</h5>
                                <form action="{{ route($activeRole . '.akademik.tahun-akademik-store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Nama Tahun Akademik</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama tahun akademik" required>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="code" class="form-label">Kode Tahun Akademik</label>
                                            <input type="text" class="form-control" name="code" id="code" placeholder="Masukkan kode tahun akademik" required>
                                            @error('code')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="semester" class="form-label">Semester</label>
                                            <select class="form-select" name="semester" id="semester" required>
                                                <option value="">Pilih Semester</option>
                                                <option value="Ganjil">Ganjil</option>
                                                <option value="Genap">Genap</option>
                                                <option value="Pendek">Pendek</option>
                                            </select>
                                            @error('semester')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                            <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" required>
                                            @error('tanggal_mulai')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                            <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai" required>
                                            @error('tanggal_selesai')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Status</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                                <label class="form-check-label" for="is_active">Aktif</label>
                                            </div>
                                            @error('is_active')
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
                        <table class="table table-striped table-bordered" id="tahunAkademikTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Semester</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    @if($is_trash)
                                        <th>Dihapus Oleh</th>
                                        <th>Dihapus Pada</th>
                                    @endif
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tahunAkademiks as $key => $item)
                                    <tr>
                                        <td data-label="No">{{ ++$key }}</td>
                                        <td data-label="Nama">{{ $item->name }}</td>
                                        <td data-label="Kode">{{ $item->code }}</td>
                                        <td data-label="Semester">
                                            @if($item->semester == 'Ganjil')
                                                <span class="badge bg-warning">{{ $item->semester }}</span>
                                            @elseif($item->semester == 'Genap')
                                                <span class="badge bg-info">{{ $item->semester }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $item->semester }}</span>
                                            @endif
                                        </td>
                                        <td data-label="Tanggal Mulai">{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                                        <td data-label="Tanggal Selesai">{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                                        <td data-label="Status">
                                            @if($item->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
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
                                                <form action="{{ route($activeRole . '.akademik.tahun-akademik-restore', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Restore Tahun Akademik">
                                                        <i class="fas fa-undo me-1"></i> Restore
                                                    </button>
                                                </form>
                                            @else
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#editData{{ $item->id }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="Edit Tahun Akademik">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <form action="{{ route($activeRole . '.akademik.tahun-akademik-destroy', $item->id) }}" method="POST" class="d-inline" id="delete-form-{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" data-confirm-delete="true" data-bs-toggle="tooltip" title="Hapus Tahun Akademik" onclick="confirmDelete('{{ $item->id }}')">
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
                        </div> <!-- End of Tab 1 -->

                        <!-- Tab 2: Display Kalender Akademik -->
                        <div class="tab-pane fade" id="calendar-display" role="tabpanel" aria-labelledby="calendar-display-tab">
                            <div class="row">
                                @forelse($tahunAkademiks->where('is_active', true) as $ta)
                                    <div class="col-md-12 mb-4">
                                        <div class="card border shadow-sm">
                                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Kalender Akademik: {{ $ta->name }} (Semester {{ $ta->semester }})</h5>
                                                <span class="badge bg-success">Aktif</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="timeline timeline-simple mt-3">
                                                    <!-- Lecture Mulai -->
                                                    <div class="d-flex mb-3 align-items-center">
                                                        <div class="timeline-icon bg-info text-white p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-graduation-cap"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">Mulai Perkuliahan</h6>
                                                            <p class="text-muted mb-0 small">{{ $ta->tanggal_mulai ? \Carbon\Carbon::parse($ta->tanggal_mulai)->format('d F Y') : '-' }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- KRS Online -->
                                                    @php
                                                        $krsSemester = $waktuKrs->where('tahun_akademik_id', $ta->id)->first();
                                                    @endphp
                                                    @if($krsSemester)
                                                        <div class="d-flex mb-3 align-items-center">
                                                            <div class="timeline-icon bg-warning text-white p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-file-signature"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">Pengisian KRS Online</h6>
                                                                <p class="text-muted mb-0 small">
                                                                    {{ \Carbon\Carbon::parse($krsSemester->tanggal_mulai)->format('d F Y H:i') }} s/d 
                                                                    {{ \Carbon\Carbon::parse($krsSemester->tanggal_selesai)->format('d F Y H:i') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Lecture Selesai -->
                                                    <div class="d-flex mb-3 align-items-center">
                                                        <div class="timeline-icon bg-danger text-white p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-times-circle"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">Selesai Perkuliahan</h6>
                                                            <p class="text-muted mb-0 small">{{ $ta->tanggal_selesai ? \Carbon\Carbon::parse($ta->tanggal_selesai)->format('d F Y') : '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-4 text-muted">
                                        <i class="fas fa-calendar-times fa-3x mb-3 text-secondary"></i>
                                        <p>Tidak ada tahun akademik aktif untuk ditampilkan.</p>
                                    </div>
                                @endforelse
                                
                                <div class="col-12 mt-3">
                                    <h5 class="mb-3"><i class="fas fa-history me-2"></i>Timeline Riwayat Semester Sebelumnya</h5>
                                    <div class="row">
                                        @foreach($tahunAkademiks->where('is_active', false)->take(3) as $ta)
                                            <div class="col-md-4 mb-3">
                                                <div class="card border p-3">
                                                    <h6 class="mb-1 text-muted">{{ $ta->name }}</h6>
                                                    <small class="d-block mb-2 text-info">Semester: {{ $ta->semester }}</small>
                                                    <div class="small">
                                                        <span class="d-block"><i class="fas fa-play me-1 text-success"></i>Mulai: {{ $ta->tanggal_mulai ? \Carbon\Carbon::parse($ta->tanggal_mulai)->format('d/m/Y') : '-' }}</span>
                                                        <span class="d-block"><i class="fas fa-stop me-1 text-danger"></i>Selesai: {{ $ta->tanggal_selesai ? \Carbon\Carbon::parse($ta->tanggal_selesai)->format('d/m/Y') : '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End of tab-content -->
                </div>
            </div>
        </div>
        
        <!-- Sidebar Content -->
        <div class="col-lg-4 col-12 mb-2">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Informasi Tahun Akademik</h5>
                </div>
                <div class="card-body">
                    <p>Bagian ini menampilkan informasi umum dan petunjuk terkait pengelolaan Tahun Akademik.</p>
                    
                    <div class="alert alert-light-primary">
                        <h6 class="">Petunjuk Penggunaan:</h6>
                        <ul class="mb-0">
                            <li>Klik tombol "Tambah Tahun Akademik" untuk menambahkan tahun akademik baru</li>
                            <li>Klik ikon <i class="fas fa-edit"></i> untuk mengedit tahun akademik</li>
                            @if($is_trash)
                                <li>Klik ikon <i class="fas fa-undo"></i> untuk restore tahun akademik</li>
                            @else
                                <li>Klik ikon <i class="fas fa-trash"></i> untuk menghapus tahun akademik</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mb-2">
            <!-- Semester Distribution -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Distribusi Semester</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 justify-content-between">
                        <div class="text-center p-2 border rounded" style="flex: 1; min-width: 100px;">
                            <div class="rounded-circle bg-warning text-white mx-auto mb-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-moon"></i>
                            </div>
                            <h6 class="mb-1">Ganjil</h6>
                            <span class="badge bg-warning">{{ $tahunAkademiks->where('semester', 'Ganjil')->count() }}</span>
                        </div>
                        <div class="text-center p-2 border rounded" style="flex: 1; min-width: 100px;">
                            <div class="rounded-circle bg-info text-white mx-auto mb-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-sun"></i>
                            </div>
                            <h6 class="mb-1">Genap</h6>
                            <span class="badge bg-info">{{ $tahunAkademiks->where('semester', 'Genap')->count() }}</span>
                        </div>
                        <div class="text-center p-2 border rounded" style="flex: 1; min-width: 100px;">
                            <div class="rounded-circle bg-secondary text-white mx-auto mb-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h6 class="mb-1">Pendek</h6>
                            <span class="badge bg-secondary">{{ $tahunAkademiks->where('semester', 'Pendek')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tahun Akademik Terbaru -->
            @if(count($tahunAkademiks) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Tahun Akademik Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($tahunAkademiks->sortByDesc('created_at')->take(5) as $item)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $item->name }}</h6>
                                    <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">Kode: {{ $item->code }}</small>
                                    <span class="badge {{ $item->semester == 'Ganjil' ? 'bg-warning' : ($item->semester == 'Genap' ? 'bg-info' : 'bg-secondary') }}">{{ $item->semester }}</span>
                                </div>
                                <small class="d-block mt-1">
                                    {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') : '-' }} - 
                                    {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-' }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="col-lg-4 col-12 mb-2">
            <!-- Statistik Tahun Akademik -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Statistik Tahun Akademik</h5>
                </div>
                <div class="card-body">
                    <canvas id="tahunAkademikChart" width="100%" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @if(!$is_trash)
        @foreach ($tahunAkademiks as $item)
            <div class="modal fade" id="editData{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route($activeRole . '.akademik.tahun-akademik-update', $item->id) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Tahun Akademik - {{ $item->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_name{{ $item->id }}" class="form-label">Nama Tahun Akademik</label>
                                        <input type="text" class="form-control" name="name" id="edit_name{{ $item->id }}" value="{{ $item->name }}" placeholder="Masukkan nama tahun akademik" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_code{{ $item->id }}" class="form-label">Kode Tahun Akademik</label>
                                        <input type="text" class="form-control" name="code" id="edit_code{{ $item->id }}" value="{{ $item->code }}" placeholder="Masukkan kode tahun akademik" required>
                                        @error('code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_semester{{ $item->id }}" class="form-label">Semester</label>
                                        <select class="form-select" name="semester" id="edit_semester{{ $item->id }}" required>
                                            <option value="">Pilih Semester</option>
                                            <option value="Ganjil" {{ $item->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                            <option value="Genap" {{ $item->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                                            <option value="Pendek" {{ $item->semester == 'Pendek' ? 'selected' : '' }}>Pendek</option>
                                        </select>
                                        @error('semester')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_tanggal_mulai{{ $item->id }}" class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control" name="tanggal_mulai" id="edit_tanggal_mulai{{ $item->id }}" value="{{ $item->tanggal_mulai }}" required>
                                        @error('tanggal_mulai')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_tanggal_selesai{{ $item->id }}" class="form-label">Tanggal Selesai</label>
                                        <input type="date" class="form-control" name="tanggal_selesai" id="edit_tanggal_selesai{{ $item->id }}" value="{{ $item->tanggal_selesai }}" required>
                                        @error('tanggal_selesai')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active{{ $item->id }}" value="1" {{ $item->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_is_active{{ $item->id }}">Aktif</label>
                                        </div>
                                        @error('is_active')
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
            var table = $('#tahunAkademikTable').DataTable({
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
                            return 'Data_Tahun_Akademik_' + new Date().toISOString().slice(0,10);
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
                            return 'Data_Tahun_Akademik_' + new Date().toISOString().slice(0,10);
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
                            return 'Data_Tahun_Akademik_' + new Date().toISOString().slice(0,10);
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

        // Chart initialization
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('tahunAkademikChart');
            if (ctx) {
                ctx = ctx.getContext('2d');
                var tahunAkademikChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Aktif', 'Nonaktif', 'Ganjil', 'Genap', 'Pendek'],
                        datasets: [{
                            label: 'Statistik Tahun Akademik',
                            data: [
                                {{ $tahunAkademiks->where('is_active', true)->count() }},
                                {{ $tahunAkademiks->where('is_active', false)->count() }},
                                {{ $tahunAkademiks->where('semester', 'Ganjil')->count() }},
                                {{ $tahunAkademiks->where('semester', 'Genap')->count() }},
                                {{ $tahunAkademiks->where('semester', 'Pendek')->count() }}
                            ],
                            backgroundColor: [
                                'rgba(40, 167, 69, 0.8)',
                                'rgba(108, 117, 125, 0.8)',
                                'rgba(255, 193, 7, 0.8)',
                                'rgba(23, 162, 184, 0.8)',
                                'rgba(108, 117, 125, 0.8)'
                            ],
                            borderColor: [
                                'rgba(40, 167, 69, 1)',
                                'rgba(108, 117, 125, 1)',
                                'rgba(255, 193, 7, 1)',
                                'rgba(23, 162, 184, 1)',
                                'rgba(108, 117, 125, 1)'
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
            }
        });
    </script>
@endsection