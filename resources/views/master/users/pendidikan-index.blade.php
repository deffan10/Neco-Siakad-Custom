@extends('themes.core-backpage')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('vendor') }}/siakad/siakad-crud.css">

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12 mb-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $pages ?? 'Daftar Pendidikan' }}</h5>
                    <div>
                        @if($is_trash)
                            <a href="{{ route($activeRole . '.users.pendidikan-index') }}" class="btn btn-sm btn-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        @else
                            <a href="{{ route($activeRole . '.users.pendidikan-trash') }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-trash me-2"></i>Trash
                            </a>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Pendidikan
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(!$is_trash)
                        <div class="collapse" id="collapseForm">
                            <div class="card card-body border">
                                <h5 class="card-title mb-3">Tambah Data Pendidikan</h5>
                                <form action="{{ route($activeRole . '.users.pendidikan-store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Jenjang Pendidikan</label>
                                            <select class="form-select" name="jenjang" required>
                                                <option value="">Pilih Jenjang</option>
                                                <option value="Paket C">Paket C</option>
                                                <option value="SMA">SMA</option>
                                                <option value="SMK">SMK</option>
                                                <option value="D3">D3</option>
                                                <option value="S1">S1</option>
                                                <option value="S2">S2</option>
                                                <option value="S3">S3</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Institusi</label>
                                            <input type="text" class="form-control" name="nama_institusi" required>
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
                                            <label class="form-label">Jurusan</label>
                                            <input type="text" class="form-control" name="jurusan">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">IPK</label>
                                            <input type="text" class="form-control" name="ipk" placeholder="contoh: 3.85">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tahun Masuk</label>
                                            <input type="number" class="form-control" name="tahun_masuk" min="1900" max="{{ date('Y') + 5 }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tahun Lulus</label>
                                            <input type="number" class="form-control" name="tahun_lulus" min="1900" max="{{ date('Y') + 10 }}">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Alamat Institusi</label>
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
                    
                    <!-- Filter Section -->
                    @if(!$is_trash)
                        <div class="filter-containers">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-filter me-1"></i> Filter Jenjang</label>
                                    <select class="form-select form-select-sm" id="filter-jenjang">
                                        <option value="">Semua Jenjang</option>
                                        <option value="Paket C">Paket C</option>
                                        <option value="SMA">SMA</option>
                                        <option value="SMK">SMK</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-university me-1"></i> Filter Institusi</label>
                                    <input type="text" class="form-control form-control-sm" id="filter-institusi" placeholder="Cari institusi...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-user me-1"></i> Filter Pengguna</label>
                                    <input type="text" class="form-control form-control-sm" id="filter-pengguna" placeholder="Cari pengguna...">
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Table -->
                    <div class="table-responsive">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Edit Modals -->
    @if(!$is_trash)
        @foreach (\App\Models\User\Pendidikan::when($is_trash, function($q) { $q->onlyTrashed(); })->get() as $item)
            <div class="modal fade" id="editData{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route($activeRole . '.users.pendidikan-update', $item->id) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Pendidikan - {{ $item->nama_institusi }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jenjang Pendidikan</label>
                                        <select class="form-select" name="jenjang" required>
                                            <option value="">Pilih Jenjang</option>
                                            <option value="Paket C" {{ $item->jenjang == 'Paket C' ? 'selected' : '' }}>Paket C</option>
                                            <option value="SMA" {{ $item->jenjang == 'SMA' ? 'selected' : '' }}>SMA</option>
                                            <option value="SMK" {{ $item->jenjang == 'SMK' ? 'selected' : '' }}>SMK</option>
                                            <option value="D3" {{ $item->jenjang == 'D3' ? 'selected' : '' }}>D3</option>
                                            <option value="S1" {{ $item->jenjang == 'S1' ? 'selected' : '' }}>S1</option>
                                            <option value="S2" {{ $item->jenjang == 'S2' ? 'selected' : '' }}>S2</option>
                                            <option value="S3" {{ $item->jenjang == 'S3' ? 'selected' : '' }}>S3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Institusi</label>
                                        <input type="text" class="form-control" name="nama_institusi" value="{{ $item->nama_institusi }}" required>
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
                                        <label class="form-label">Jurusan</label>
                                        <input type="text" class="form-control" name="jurusan" value="{{ $item->jurusan }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">IPK</label>
                                        <input type="text" class="form-control" name="ipk" value="{{ $item->ipk }}" placeholder="contoh: 3.85">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tahun Masuk</label>
                                        <input type="number" class="form-control" name="tahun_masuk" value="{{ $item->tahun_masuk }}" min="1900" max="{{ date('Y') + 5 }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tahun Lulus</label>
                                        <input type="number" class="form-control" name="tahun_lulus" value="{{ $item->tahun_lulus }}" min="1900" max="{{ date('Y') + 10 }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Alamat Institusi</label>
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
    @endif

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="fas fa-upload me-2"></i>Import Data Pendidikan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="importAlert" class="alert d-none" role="alert"></div>
                    
                    <form id="importForm" action="{{ route($activeRole . '.users.pendidikan-import') }}" method="POST" enctype="multipart/form-data">
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
                                        <a href="{{ route($activeRole . '.users.pendidikan-template') }}" class="btn btn-outline-info btn-sm">
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
    {!! $dataTable->scripts() !!}
    
    <script>
        $(document).ready(function() {
            var table = window.LaravelDataTables["pendidikan-table"];

            // Add custom export/import buttons
            table.button().add(0, {
                text: '<i class="fas fa-file-csv me-1"></i> CSV',
                className: 'btn btn-sm btn-success',
                action: function (e, dt, node, config) {
                    window.open('{{ route($activeRole . ".users.pendidikan-export-csv") }}', '_blank');
                }
            });
            
            table.button().add(1, {
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-sm btn-success',
                action: function (e, dt, node, config) {
                    window.open('{{ route($activeRole . ".users.pendidikan-export-excel") }}', '_blank');
                }
            });
            
            table.button().add(2, {
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-sm btn-danger',
                action: function (e, dt, node, config) {
                    window.open('{{ route($activeRole . ".users.pendidikan-export-pdf") }}', '_blank');
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

            // Filter by Jenjang
            $('#filter-jenjang').on('change', function() {
                table.column(1).search(this.value).draw();
            });

            // Filter by Institusi
            $('#filter-institusi').on('keyup', function() {
                table.column(2).search(this.value).draw();
            });

            // Filter by Pengguna
            $('#filter-pengguna').on('keyup', function() {
                table.column(3).search(this.value).draw();
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

            // Reinitialize tooltips after table redraw
            table.on('draw', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });
        });
    </script>
@endpush