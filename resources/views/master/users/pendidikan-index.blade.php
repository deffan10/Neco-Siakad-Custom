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
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="pendidikanTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenjang</th>
                                    <th>Institusi</th>
                                    <th>Pengguna</th>
                                    <th>Periode</th>
                                    @if($is_trash)
                                        <th>Dihapus Oleh</th>
                                        <th>Dihapus Pada</th>
                                    @endif
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($pendidikans as $key => $item)
                                    <tr>
                                        <td data-label="No">{{ ++$key }}</td>
                                        <td data-label="Jenjang"><span class="badge {{ $item->jenjang_badge_class }}">{{ $item->jenjang_display }}</span></td>
                                        <td data-label="Institusi">
                                            <div>
                                                <strong>{{ $item->nama_institusi }}</strong>
                                                @if($item->jurusan)
                                                    <small class="d-block text-muted">{{ $item->jurusan }}</small>
                                                @endif
                                                @if($item->ipk)
                                                    <small class="d-block text-muted"><i class="fas fa-star text-warning"></i> IPK: {{ $item->ipk }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td data-label="Pengguna">
                                            <div>
                                                <strong>{{ $item->user->name }}</strong>
                                                <small class="d-block text-muted">{{ $item->user->role }}</small>
                                            </div>
                                        </td>
                                        <td data-label="Periode">{{ $item->periode }}</td>
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
                                                <form action="{{ route($activeRole . '.users.pendidikan-restore', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Restore Pendidikan">
                                                        <i class="fas fa-undo me-1"></i> Restore
                                                    </button>
                                                </form>
                                            @else
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#editData{{ $item->id }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="Edit Pendidikan">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <form action="{{ route($activeRole . '.users.pendidikan-destroy', $item->id) }}" method="POST" class="d-inline" id="delete-form-{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" data-confirm-delete="true" data-bs-toggle="tooltip" title="Hapus Pendidikan" onclick="confirmDelete('{{ $item->id }}')">
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
        @foreach ($pendidikans as $item)
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
@endsection

@section('custom-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            var table = $('#pendidikanTable').DataTable({
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
                            return 'Data_Pendidikan_' + new Date().toISOString().slice(0,10);
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
                            return 'Data_Pendidikan_' + new Date().toISOString().slice(0,10);
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
                            return 'Data_Pendidikan_' + new Date().toISOString().slice(0,10);
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
            
            // Handle entries filter change
            $('#entriesSelect').on('change', function() {
                var value = $(this).val();
                table.page.len(value).draw();
            });
            
            // Set initial value for entries select
            $('#entriesSelect').val(table.page.len());
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

        // Konfirmasi delete dengan SweetAlert
        function confirmDelete(code) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pendidikan yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + code).submit();
                }
            });
        }
    </script>
@endsection