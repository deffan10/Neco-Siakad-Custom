@extends('themes.core-backpage')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('vendor') }}/siakad/siakad-crud.css">

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12 mb-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $pages ?? 'Daftar Alamat' }}</h5>
                    <div>
                        @if($is_trash)
                            <a href="{{ route($activeRole . '.users.alamat-index') }}" class="btn btn-sm btn-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        @else
                            <a href="{{ route($activeRole . '.users.alamat-trash') }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-trash me-2"></i>Trash
                            </a>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Alamat
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(!$is_trash)
                        <div class="collapse" id="collapseForm">
                            <div class="card card-body border">
                                <h5 class="card-title mb-3">Tambah Alamat Baru</h5>
                                <form action="{{ route($activeRole . '.users.alamat-store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tipe Alamat</label>
                                            <select class="form-select" name="tipe" required>
                                                <option value="">Pilih Tipe</option>
                                                <option value="ktp">KTP</option>
                                                <option value="domisili">Domisili</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Pemilik</label>
                                            <select class="form-select" name="user_id" required>
                                                <option value="">Pilih Pemilik</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Alamat Lengkap</label>
                                            <textarea class="form-control" name="alamat_lengkap" rows="3" required></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kelurahan</label>
                                            <input type="text" class="form-control" name="kelurahan">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kecamatan</label>
                                            <input type="text" class="form-control" name="kecamatan">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kota/Kabupaten</label>
                                            <input type="text" class="form-control" name="kota_kabupaten">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Provinsi</label>
                                            <input type="text" class="form-control" name="provinsi">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Kode Pos</label>
                                            <input type="text" class="form-control" name="kode_pos">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">RT</label>
                                            <input type="text" class="form-control" name="rt">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">RW</label>
                                            <input type="text" class="form-control" name="rw">
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
                                        <i class="fas fa-filter me-1"></i> Filter Tipe
                                    </label>
                                    <select class="form-select form-select-sm" id="filter-tipe">
                                        <option value="">Semua Tipe</option>
                                        <option value="ktp">KTP</option>
                                        <option value="domisili">Domisili</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">
                                        <i class="fas fa-user me-1"></i> Filter Pemilik
                                    </label>
                                    <select class="form-select form-select-sm" id="filter-user">
                                        <option value="">Semua Pemilik</option>
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
                    
                    <!-- DataTable -->
                    <div class="mt-3">
                        {{ $dataTable->table(['class' => 'table table-striped table-bordered dt-responsive nowrap', 'style' => 'width:100%']) }}
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Edit Modals -->
    @foreach(\App\Models\User\Alamat::when($is_trash, function($q) { $q->onlyTrashed(); })->get() as $item)
        <div class="modal fade" id="editData{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Alamat - {{ $item->user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route($activeRole . '.users.alamat-update', $item->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipe Alamat</label>
                                    <select class="form-select" name="tipe" required>
                                        <option value="ktp" {{ $item->tipe == 'ktp' ? 'selected' : '' }}>KTP</option>
                                        <option value="domisili" {{ $item->tipe == 'domisili' ? 'selected' : '' }}>Domisili</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pemilik</label>
                                    <select class="form-select" name="user_id" required>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $item->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control" name="alamat_lengkap" rows="3" required>{{ $item->alamat_lengkap }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kelurahan</label>
                                    <input type="text" class="form-control" name="kelurahan" value="{{ $item->kelurahan }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kecamatan</label>
                                    <input type="text" class="form-control" name="kecamatan" value="{{ $item->kecamatan }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kota/Kabupaten</label>
                                    <input type="text" class="form-control" name="kota_kabupaten" value="{{ $item->kota_kabupaten }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" name="provinsi" value="{{ $item->provinsi }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control" name="kode_pos" value="{{ $item->kode_pos }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">RT</label>
                                    <input type="text" class="form-control" name="rt" value="{{ $item->rt }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">RW</label>
                                    <input type="text" class="form-control" name="rw" value="{{ $item->rw }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update
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
            var table = window.LaravelDataTables["alamat-table"];
            
            // Filter by Tipe
            $('#filter-tipe').on('change', function() {
                table.column(1).search(this.value).draw();
            });
            
            // Filter by User/Pemilik
            $('#filter-user').on('change', function() {
                table.column(2).search(this.value).draw();
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

    </script>
@endpush