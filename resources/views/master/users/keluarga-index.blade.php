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
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            var table = window.LaravelDataTables["keluarga-table"];

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
        });

    </script>
@endpush