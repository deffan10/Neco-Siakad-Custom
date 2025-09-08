@extends('themes.core-backpage')

@section('custom-css')
<style>
    .settings-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 10px;
        color: white;
        margin-bottom: 2rem;
    }
    .nav-tabs .nav-link.active {
        background-color: #667eea;
        color: white;
        border-color: #667eea;
    }
    .form-section {
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }
    .form-section h5 {
        border-bottom: 2px solid #667eea;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .action-buttons {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #dee2e6;
    }
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    .pertemuan-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        /* background-color: #f8f9fa; */
    }
    .kelas-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        /* background-color: #f8f9fa; */
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="settings-header">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="{{ asset('storage/mata-kuliah-covers/' . ($jadwal->mataKuliah->cover ?? 'default-mk.jpg')) }}" 
                                 alt="Cover Mata Kuliah" 
                                 class="img-fluid rounded" 
                                 style="max-height: 80px; width: auto;"
                                 onerror="this.src='{{ asset('assets/static/logo.svg') }}'">
                        </div>
                        <div class="col-md-10">
                            <h2 class="mb-0">{{ $jadwal->code ?? 'Kode Jadwal' }} - {{ $jadwal->mataKuliah->name ?? 'Nama Mata Kuliah' }}</h2>
                            <p class="mb-1">{{ $jadwal->mataKuliah->code ?? 'Kode MK' }} - {{ $jadwal->tahunAkademik->name ?? 'Tahun Akademik' }}</p>
                            <p class="mb-0"><i class="fas fa-calendar me-2"></i> {{ $jadwal->hari ?? 'Hari' }} | <i class="fas fa-clock me-2"></i> {{ $jadwal->jam_mulai ?? '00:00' }} - {{ $jadwal->jam_selesai ?? '00:00' }} | <i class="fas fa-user me-2"></i> {{ $jadwal->dosen->name ?? 'Dosen' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route($spref . 'akademik.jadwal-perkuliahan-update', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#data-utama" role="tab">
                                <i class="fas fa-calendar me-2"></i> Data Jadwal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kelas-jadwal" role="tab">
                                <i class="fas fa-users me-2"></i> Kelas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pertemuan" role="tab">
                                <i class="fas fa-list me-2"></i> Pertemuan
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-3">
                        <!-- Tab Data Jadwal -->
                        <div class="tab-pane active" id="data-utama" role="tabpanel">
                            <div class="form-section">
                                <h5>Informasi Jadwal</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
                                        <select class="form-select" name="tahun_akademik_id" required>
                                            <option value="">Pilih Tahun Akademik</option>
                                            @foreach($tahunAkademiks as $ta)
                                                <option value="{{ $ta->id }}" {{ $jadwal->tahun_akademik_id == $ta->id ? 'selected' : '' }}>
                                                    {{ $ta->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                                        <select class="form-select" name="mata_kuliah_id" required>
                                            <option value="">Pilih Mata Kuliah</option>
                                            @foreach($mataKuliahs as $mk)
                                                <option value="{{ $mk->id }}" {{ $jadwal->mata_kuliah_id == $mk->id ? 'selected' : '' }}>
                                                    {{ $mk->code }} - {{ $mk->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Dosen Pengampu <span class="text-danger">*</span></label>
                                        <select class="form-select" name="dosen_id" required>
                                            <option value="">Pilih Dosen</option>
                                            @foreach($dosens as $d)
                                                <option value="{{ $d->id }}" {{ $jadwal->dosen_id == $d->id ? 'selected' : '' }}>
                                                    {{ $d->name }} - {{ $d->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ruangan</label>
                                        <select class="form-select" name="ruang_id">
                                            <option value="">Pilih Ruangan</option>
                                            @foreach($ruangans as $r)
                                                <option value="{{ $r->id }}" {{ $jadwal->ruang_id == $r->id ? 'selected' : '' }}>
                                                    {{ $r->name }} - {{ $r->kapasitas }} orang
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kode Jadwal <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="code" value="{{ $jadwal->code }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Metode <span class="text-danger">*</span></label>
                                        <select class="form-select" name="metode" required>
                                            <option value="">Pilih Metode</option>
                                            <option value="Tatap Muka" {{ $jadwal->metode == 'Tatap Muka' ? 'selected' : '' }}>Tatap Muka</option>
                                            <option value="Teleconference" {{ $jadwal->metode == 'Teleconference' ? 'selected' : '' }}>Teleconference</option>
                                            <option value="Hybrid" {{ $jadwal->metode == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h5>Waktu Perkuliahan</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_mulai" value="{{ $jadwal->tanggal_mulai }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input type="date" class="form-control" name="tanggal_selesai" value="{{ $jadwal->tanggal_selesai }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Hari <span class="text-danger">*</span></label>
                                        <select class="form-select" name="hari" required>
                                            <option value="">Pilih Hari</option>
                                            <option value="Senin" {{ $jadwal->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                            <option value="Selasa" {{ $jadwal->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                            <option value="Rabu" {{ $jadwal->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                            <option value="Kamis" {{ $jadwal->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                            <option value="Jumat" {{ $jadwal->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                            <option value="Sabtu" {{ $jadwal->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                            <option value="Minggu" {{ $jadwal->hari == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" name="jam_mulai" value="{{ $jadwal->jam_mulai }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" name="jam_selesai" value="{{ $jadwal->jam_selesai }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Kelas -->
                        <div class="tab-pane" id="kelas-jadwal" role="tabpanel">
                            <div class="form-section">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Assign Kelas Perkuliahan</h5>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addKelasAssign()">
                                        <i class="fas fa-plus"></i> Assign Kelas
                                    </button>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="40%">Kelas Perkuliahan</th>
                                                <th width="20%">Kode Kelas</th>
                                                <th width="15%">Kapasitas</th>
                                                <th width="15%">Program Studi</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="kelas-assign-container">
                                            @if(isset($jadwal->jadwalKelas) && $jadwal->jadwalKelas->count() > 0)
                                                @foreach($jadwal->jadwalKelas as $index => $assignedKelas)
                                                    @if($assignedKelas->kelas)
                                                        <tr class="kelas-assign-item" data-index="{{ $index }}">
                                                            <td>
                                                                <select class="form-select" name="kelas_assign[{{ $index }}][kelas_perkuliahan_id]" required>
                                                                    <option value="">Pilih Kelas Perkuliahan</option>
                                                                    @foreach($kelasPerkuliahans as $kp)
                                                                        <option value="{{ $kp->id }}" {{ $assignedKelas->kelas_id == $kp->id ? 'selected' : '' }}>
                                                                            {{ $kp->name }} - {{ $kp->mataKuliah->name ?? 'N/A' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <span class="kelas-code">{{ $assignedKelas->kelas->code ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="kelas-kapasitas">{{ $assignedKelas->kelas->kapasitas ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="kelas-prodi">{{ $assignedKelas->kelas->programStudi->name ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeKelasAssign({{ $index }})">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td colspan="5" class="text-danger">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                Error: Data kelas tidak ditemukan untuk ID {{ $assignedKelas->kelas_id }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr class="kelas-assign-item" data-index="0">
                                                    <td>
                                                        <select class="form-select" name="kelas_assign[0][kelas_perkuliahan_id]" required>
                                                            <option value="">Pilih Kelas Perkuliahan</option>
                                                            @foreach($kelasPerkuliahans as $kp)
                                                                <option value="{{ $kp->id }}">
                                                                    {{ $kp->name }} - {{ $kp->mataKuliah->name ?? 'N/A' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <span class="kelas-code">-</span>
                                                    </td>
                                                    <td>
                                                        <span class="kelas-kapasitas">-</span>
                                                    </td>
                                                    <td>
                                                        <span class="kelas-prodi">-</span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeKelasAssign(0)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Pertemuan -->
                        <div class="tab-pane" id="pertemuan" role="tabpanel">
                            <div class="form-section">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Jadwal Pertemuan</h5>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addPertemuan()">
                                        <i class="fas fa-plus"></i> Tambah Pertemuan
                                    </button>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="6%">Pertemuan</th>
                                                <th width="10%">Tanggal</th>
                                                <th width="6%">Jam Mulai</th>
                                                <th width="6%">Jam Selesai</th>
                                                <th width="10%">Ruangan</th>
                                                <th width="10%">Dosen</th>
                                                <th width="6%">Metode</th>
                                                <th width="12%">Materi</th>
                                                <th width="6%">Status</th>
                                                <th width="10%">Link</th>
                                                <th width="4%">Realisasi</th>
                                                <th width="4%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pertemuan-container">
                                            @if(isset($jadwal->jadwalPertemuan) && $jadwal->jadwalPertemuan->count() > 0)
                                                @foreach($jadwal->jadwalPertemuan as $index => $pertemuan)
                                                    <tr class="pertemuan-item" data-index="{{ 1000 + $index }}">
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm" name="pertemuan[{{ 1000 + $index }}][pertemuan_ke]" value="{{ $pertemuan->pertemuan_ke }}" min="1" required>
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control form-control-sm" name="pertemuan[{{ 1000 + $index }}][tanggal]" value="{{ $pertemuan->tanggal }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control form-control-sm" name="pertemuan[{{ 1000 + $index }}][jam_mulai]" value="{{ $pertemuan->jam_mulai ? \Carbon\Carbon::parse($pertemuan->jam_mulai)->format('H:i') : '' }}">
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control form-control-sm" name="pertemuan[{{ 1000 + $index }}][jam_selesai]" value="{{ $pertemuan->jam_selesai ? \Carbon\Carbon::parse($pertemuan->jam_selesai)->format('H:i') : '' }}">
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="pertemuan[{{ 1000 + $index }}][ruang_id]">
                                                                <option value="">Pilih Ruangan</option>
                                                                @foreach($ruangans as $ruang)
                                                                    <option value="{{ $ruang->id }}" {{ $pertemuan->ruang_id == $ruang->id ? 'selected' : '' }}>
                                                                        {{ $ruang->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="pertemuan[{{ 1000 + $index }}][dosen_id]">
                                                                <option value="">Pilih Dosen</option>
                                                                @foreach($dosens as $dosen)
                                                                    <option value="{{ $dosen->id }}" {{ $pertemuan->dosen_id == $dosen->id ? 'selected' : '' }}>
                                                                        {{ $dosen->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="pertemuan[{{ 1000 + $index }}][metode]">
                                                                <option value="">Pilih Metode</option>
                                                                <option value="Tatap Muka" {{ $pertemuan->metode == 'Tatap Muka' ? 'selected' : '' }}>Tatap Muka</option>
                                                                <option value="Teleconference" {{ $pertemuan->metode == 'Teleconference' ? 'selected' : '' }}>Teleconference</option>
                                                                <option value="Hybrid" {{ $pertemuan->metode == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control form-control-sm" name="pertemuan[{{ 1000 + $index }}][materi]" rows="2">{{ $pertemuan->materi }}</textarea>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="pertemuan[{{ 1000 + $index }}][status]">
                                                                <option value="Terjadwal" {{ $pertemuan->status == 'Terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                                                                <option value="Terlaksana" {{ $pertemuan->status == 'Terlaksana' ? 'selected' : '' }}>Terlaksana</option>
                                                                <option value="Ditunda" {{ $pertemuan->status == 'Ditunda' ? 'selected' : '' }}>Ditunda</option>
                                                                <option value="Dibatalkan" {{ $pertemuan->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="url" class="form-control form-control-sm" name="pertemuan[{{ 1000 + $index }}][link]" value="{{ $pertemuan->link }}" placeholder="https://...">
                                                        </td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="pertemuan[{{ 1000 + $index }}][is_realisasi]" value="1" {{ $pertemuan->is_realisasi ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="removePertemuan({{ 1000 + $index }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @for($i = 1; $i <= 16; $i++)
                                                    <tr class="pertemuan-item" data-index="{{ $i-1 }}">
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][pertemuan_ke]" value="{{ $i }}" min="1" required>
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][tanggal]" value="{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai ?? now())->addWeeks($i-1)->format('Y-m-d') }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][jam_mulai]" value="{{ $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '08:00' }}">
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][jam_selesai]" value="{{ $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '10:00' }}">
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="pertemuan[{{ $i-1 }}][ruang_id]">
                                                                <option value="">Pilih Ruangan</option>
                                                                @foreach($ruangans as $ruang)
                                                                    <option value="{{ $ruang->id }}">
                                                                        {{ $ruang->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="pertemuan[{{ $i-1 }}][dosen_id]">
                                                                <option value="">Pilih Dosen</option>
                                                                @foreach($dosens as $dosen)
                                                                    <option value="{{ $dosen->id }}">
                                                                        {{ $dosen->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="pertemuan[{{ $i-1 }}][metode]">
                                                                <option value="">Pilih Metode</option>
                                                                <option value="Tatap Muka">Tatap Muka</option>
                                                                <option value="Teleconference">Teleconference</option>
                                                                <option value="Hybrid">Hybrid</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][materi]" rows="2" placeholder="Materi pertemuan ke-{{ $i }}"></textarea>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="pertemuan[{{ $i-1 }}][status]">
                                                                <option value="Terjadwal" selected>Terjadwal</option>
                                                                <option value="Terlaksana">Terlaksana</option>
                                                                <option value="Ditunda">Ditunda</option>
                                                                <option value="Dibatalkan">Dibatalkan</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="url" class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][link]" placeholder="https://...">
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="pertemuan[{{ $i-1 }}][is_realisasi]" value="1">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="removePertemuan({{ $i-1 }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endfor
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route($spref . 'akademik.matakuliah-index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
@php
    $kelasOptions = $kelasPerkuliahans->map(function($kp) {
        return [
            'id'          => $kp->id,
            'name'        => $kp->name,
            'mata_kuliah' => $kp->mataKuliah?->name ?? 'N/A',
            'code'        => $kp->code ?? 'N/A',
            'kapasitas'   => $kp->kapasitas ?? 'N/A',
            'prodi'       => $kp->programStudi?->name ?? 'N/A',
        ];
    })->values()->toArray();
@endphp

<script>
    let kelasIndex = {{ isset($jadwal->jadwalKelas) ? $jadwal->jadwalKelas->count() : 1 }};
    let pertemuanIndex = {{ isset($jadwal->jadwalPertemuan) ? $jadwal->jadwalPertemuan->count() : 16 }};

    // Inject data kelas ke JS
    const kelasOptions = @json($kelasOptions);

    // Fungsi untuk menambah assign kelas
    function addKelasAssign() {
        const container = document.getElementById('kelas-assign-container');

        // Check for maximum kelas assign (optional limit)
        const existingRows = container.querySelectorAll('.kelas-assign-item');
        if (existingRows.length >= 10) {
            alert('Maksimal 10 kelas dapat di-assign untuk satu jadwal.');
            return;
        }

        let optionsHtml = '<option value="">Pilih Kelas Perkuliahan</option>';
        kelasOptions.forEach(kp => {
            // Check if this kelas is already assigned
            const isAlreadyAssigned = Array.from(existingRows).some(row => {
                const select = row.querySelector('select[name*="[kelas_perkuliahan_id]"]');
                return select && select.value == kp.id;
            });

            if (!isAlreadyAssigned) {
                optionsHtml += `<option value="${kp.id}" data-code="${kp.code}" data-kapasitas="${kp.kapasitas}" data-prodi="${kp.prodi}">
                    ${kp.name} - ${kp.mata_kuliah}
                </option>`;
            }
        });

        // If no options available, show message
        if (optionsHtml === '<option value="">Pilih Kelas Perkuliahan</option>') {
            alert('Semua kelas sudah di-assign atau tidak ada kelas yang tersedia.');
            return;
        }

        const kelasHtml = `
            <tr class="kelas-assign-item" data-index="${kelasIndex}">
                <td>
                    <select class="form-select" name="kelas_assign[${kelasIndex}][kelas_perkuliahan_id]" onchange="updateKelasInfo(this, ${kelasIndex})" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td><span class="kelas-code">-</span></td>
                <td><span class="kelas-kapasitas">-</span></td>
                <td><span class="kelas-prodi">-</span></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeKelasAssign(${kelasIndex})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        container.insertAdjacentHTML('beforeend', kelasHtml);
        kelasIndex++;
    }

    // Fungsi untuk menghapus assign kelas
    function removeKelasAssign(index) {
        const kelasItem = document.querySelector(`tr[data-index="${index}"]`);
        if (kelasItem) kelasItem.remove();
    }

    // Fungsi untuk mengupdate informasi kelas ketika dropdown berubah
    function updateKelasInfo(selectElement, index) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const row = selectElement.closest('tr');
        const selectedValue = selectedOption ? selectedOption.value : '';

        // Check for duplicate selection
        if (selectedValue) {
            const allSelects = document.querySelectorAll('select[name*="[kelas_perkuliahan_id]"]');
            let duplicateFound = false;

            allSelects.forEach(select => {
                if (select !== selectElement && select.value === selectedValue) {
                    duplicateFound = true;
                }
            });

            if (duplicateFound) {
                alert('Kelas ini sudah di-assign. Silakan pilih kelas yang berbeda.');
                selectElement.value = '';
                row.querySelector('.kelas-code').textContent = '-';
                row.querySelector('.kelas-kapasitas').textContent = '-';
                row.querySelector('.kelas-prodi').textContent = '-';
                return;
            }
        }

        if (selectedOption && selectedValue) {
            row.querySelector('.kelas-code').textContent = selectedOption.dataset.code || 'N/A';
            row.querySelector('.kelas-kapasitas').textContent = selectedOption.dataset.kapasitas || 'N/A';
            row.querySelector('.kelas-prodi').textContent = selectedOption.dataset.prodi || 'N/A';
        } else {
            row.querySelector('.kelas-code').textContent = '-';
            row.querySelector('.kelas-kapasitas').textContent = '-';
            row.querySelector('.kelas-prodi').textContent = '-';
        }
    }

    // Fungsi untuk menghitung tanggal pertemuan berdasarkan hari dan tanggal mulai
    function calculatePertemuanDate(pertemuanKe, tanggalMulai, hari) {
        if (!tanggalMulai || !hari) return '';

        const hariMapping = {
            'Minggu': 0,
            'Senin': 1,
            'Selasa': 2,
            'Rabu': 3,
            'Kamis': 4,
            'Jumat': 5,
            'Sabtu': 6
        };

        const targetDay = hariMapping[hari];
        if (targetDay === undefined) return '';

        let currentDate = new Date(tanggalMulai);

        // Jika tanggal mulai bukan hari target, cari hari target berikutnya
        if (currentDate.getDay() !== targetDay) {
            const daysToAdd = (targetDay - currentDate.getDay() + 7) % 7;
            currentDate.setDate(currentDate.getDate() + daysToAdd);
        }

        // Tambahkan minggu berdasarkan pertemuan ke
        currentDate.setDate(currentDate.getDate() + (pertemuanKe - 1) * 7);

        return currentDate.toISOString().split('T')[0];
    }

    // Fungsi untuk memformat time ke H:i
    function formatTimeToHi(timeString) {
        if (!timeString) return '08:00';
        // Jika sudah dalam format H:i, kembalikan apa adanya
        if (timeString.length === 5) return timeString;
        // Jika dalam format H:i:s, ambil hanya H:i
        if (timeString.length === 8) return timeString.substring(0, 5);
        return timeString;
    }

    // Fungsi untuk menambah pertemuan
    function addPertemuan() {
        const container = document.getElementById('pertemuan-container');

        // Hitung tanggal default dari form input
        const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]').value;
        const hari = document.querySelector('select[name="hari"]').value;
        const defaultTanggal = calculatePertemuanDate(pertemuanIndex + 1, tanggalMulai, hari);

        const pertemuanHtml = `
            <tr class="pertemuan-item" data-index="${pertemuanIndex}">
                <td>
                    <input type="number" class="form-control form-control-sm"
                           name="pertemuan[${pertemuanIndex}][pertemuan_ke]"
                           value="${pertemuanIndex + 1}" min="1" required>
                </td>
                <td>
                    <input type="date" class="form-control form-control-sm"
                           name="pertemuan[${pertemuanIndex}][tanggal]"
                           value="${defaultTanggal}" required>
                </td>
                <td>
                    <input type="time" class="form-control form-control-sm"
                           name="pertemuan[${pertemuanIndex}][jam_mulai]"
                           value="${formatTimeToHi(document.querySelector('input[name=\"jam_mulai\"]').value) || '08:00'}">
                </td>
                <td>
                    <input type="time" class="form-control form-control-sm"
                           name="pertemuan[${pertemuanIndex}][jam_selesai]"
                           value="${formatTimeToHi(document.querySelector('input[name=\"jam_selesai\"]').value) || '10:00'}">
                </td>
                <td>
                    <select class="form-select form-select-sm" name="pertemuan[${pertemuanIndex}][ruang_id]">
                        <option value="">Pilih Ruangan</option>
                        @foreach($ruangans as $ruang)
                            <option value="{{ $ruang->id }}">{{ $ruang->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-select form-select-sm" name="pertemuan[${pertemuanIndex}][dosen_id]">
                        <option value="">Pilih Dosen</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-select form-select-sm" name="pertemuan[${pertemuanIndex}][metode]">
                        <option value="">Pilih Metode</option>
                        <option value="Tatap Muka">Tatap Muka</option>
                        <option value="Teleconference">Teleconference</option>
                        <option value="Hybrid">Hybrid</option>
                    </select>
                </td>
                <td>
                    <textarea class="form-control form-control-sm"
                              name="pertemuan[${pertemuanIndex}][materi]"
                              rows="2"
                              placeholder="Materi pertemuan ke-${pertemuanIndex + 1}"></textarea>
                </td>
                <td>
                    <select class="form-select form-select-sm" name="pertemuan[${pertemuanIndex}][status]">
                        <option value="Terjadwal" selected>Terjadwal</option>
                        <option value="Terlaksana">Terlaksana</option>
                        <option value="Ditunda">Ditunda</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </td>
                <td>
                    <input type="url" class="form-control form-control-sm"
                           name="pertemuan[${pertemuanIndex}][link]" placeholder="https://...">
                </td>
                <td class="text-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="pertemuan[${pertemuanIndex}][is_realisasi]" value="1">
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removePertemuan(${pertemuanIndex})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
        `;

        container.insertAdjacentHTML('beforeend', pertemuanHtml);
        pertemuanIndex++;
    }

    // Fungsi untuk menghapus pertemuan
    function removePertemuan(index) {
        const pertemuanItem = document.querySelector(`tr[data-index="${index}"]`);
        if (pertemuanItem) pertemuanItem.remove();
    }

    // Fungsi untuk mengupdate tanggal pertemuan ketika tanggal mulai atau hari berubah
    function updatePertemuanDates() {
        const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]').value;
        const hari = document.querySelector('select[name="hari"]').value;
        
        if (!tanggalMulai || !hari) return;
        
        // Update semua pertemuan yang ada
        const pertemuanRows = document.querySelectorAll('#pertemuan-container tr');
        pertemuanRows.forEach((row, index) => {
            const pertemuanKe = index + 1;
            const defaultTanggal = calculatePertemuanDate(pertemuanKe, tanggalMulai, hari);
            const tanggalInput = row.querySelector('input[name*="[tanggal]"]');
            if (tanggalInput && !tanggalInput.value) { // Hanya update jika kosong
                tanggalInput.value = defaultTanggal;
            }
        });
    }

    // Event listener untuk field tanggal mulai dan hari
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalMulaiInput = document.querySelector('input[name="tanggal_mulai"]');
        const hariSelect = document.querySelector('select[name="hari"]');
        
        if (tanggalMulaiInput) {
            tanggalMulaiInput.addEventListener('change', updatePertemuanDates);
        }
        if (hariSelect) {
            hariSelect.addEventListener('change', updatePertemuanDates);
        }
    });
</script>

@endsection