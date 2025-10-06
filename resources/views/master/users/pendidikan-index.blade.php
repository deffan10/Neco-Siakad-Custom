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
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush