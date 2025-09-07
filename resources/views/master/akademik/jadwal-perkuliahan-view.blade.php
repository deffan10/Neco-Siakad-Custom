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
                        <div class="col-md-12">
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
                                                    @php
                                                        $kelasPerkuliahan = $kelasPerkuliahans->find($assignedKelas->kelas_perkuliahan_id);
                                                    @endphp
                                                    @if($kelasPerkuliahan)
                                                        <tr class="kelas-assign-item" data-index="{{ $index }}">
                                                            <td>
                                                                <select class="form-select" name="kelas_assign[{{ $index }}][kelas_perkuliahan_id]" required>
                                                                    <option value="">Pilih Kelas Perkuliahan</option>
                                                                    @foreach($kelasPerkuliahans as $kp)
                                                                        <option value="{{ $kp->id }}" {{ $assignedKelas->kelas_perkuliahan_id == $kp->id ? 'selected' : '' }}>
                                                                            {{ $kp->name }} - {{ $kp->mataKuliah->name ?? 'N/A' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <span class="kelas-code">{{ $kelasPerkuliahan->code ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="kelas-kapasitas">{{ $kelasPerkuliahan->kapasitas ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="kelas-prodi">{{ $kelasPerkuliahan->programStudi->name ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeKelasAssign({{ $index }})">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
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
                                                <th width="10%">Pertemuan</th>
                                                <th width="15%">Tanggal</th>
                                                <th width="10%">Jam Mulai</th>
                                                <th width="10%">Jam Selesai</th>
                                                <th width="25%">Materi</th>
                                                <th width="10%">Status</th>
                                                <th width="15%">Link</th>
                                                <th width="5%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pertemuan-container">
                                            @if(isset($jadwal->jadwalPertemuan) && $jadwal->jadwalPertemuan->count() > 0)
                                @foreach($jadwal->jadwalPertemuan as $index => $pertemuan)
                                                    <tr class="pertemuan-item" data-index="{{ $index }}">
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm" name="pertemuan[{{ $index }}][pertemuan_ke]" value="{{ $pertemuan->pertemuan_ke }}" min="1" required>
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control form-control-sm" name="pertemuan[{{ $index }}][tanggal]" value="{{ $pertemuan->tanggal }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control form-control-sm" name="pertemuan[{{ $index }}][jam_mulai]" value="{{ $pertemuan->jam_mulai ? \Carbon\Carbon::parse($pertemuan->jam_mulai)->format('H:i') : '' }}">
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control form-control-sm" name="pertemuan[{{ $index }}][jam_selesai]" value="{{ $pertemuan->jam_selesai ? \Carbon\Carbon::parse($pertemuan->jam_selesai)->format('H:i') : '' }}">
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control form-control-sm" name="pertemuan[{{ $index }}][materi]" rows="2">{{ $pertemuan->materi }}</textarea>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="pertemuan[{{ $index }}][status]">
                                                                <option value="Belum Terlaksana" {{ $pertemuan->status == 'Belum Terlaksana' ? 'selected' : '' }}>Belum Terlaksana</option>
                                                                <option value="Terlaksana" {{ $pertemuan->status == 'Terlaksana' ? 'selected' : '' }}>Terlaksana</option>
                                                                <option value="Dibatalkan" {{ $pertemuan->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="url" class="form-control form-control-sm" name="pertemuan[{{ $index }}][link]" value="{{ $pertemuan->link }}" placeholder="https://...">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="removePertemuan({{ $index }})">
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
                                                            <input type="date" class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][tanggal]" required>
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][jam_mulai]" value="{{ $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '08:00' }}">
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][jam_selesai]" value="{{ $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '10:00' }}">
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][materi]" rows="2" placeholder="Materi pertemuan ke-{{ $i }}"></textarea>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="pertemuan[{{ $i-1 }}][status]">
                                                                <option value="Belum Terlaksana" selected>Belum Terlaksana</option>
                                                                <option value="Terlaksana">Terlaksana</option>
                                                                <option value="Dibatalkan">Dibatalkan</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="url" class="form-control form-control-sm" name="pertemuan[{{ $i-1 }}][link]" placeholder="https://...">
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

        let optionsHtml = '<option value="">Pilih Kelas Perkuliahan</option>';
        kelasOptions.forEach(kp => {
            optionsHtml += `<option value="${kp.id}" data-code="${kp.code}" data-kapasitas="${kp.kapasitas}" data-prodi="${kp.prodi}">
                ${kp.name} - ${kp.mata_kuliah}
            </option>`;
        });

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

        if (selectedOption && selectedOption.value) {
            row.querySelector('.kelas-code').textContent = selectedOption.dataset.code || 'N/A';
            row.querySelector('.kelas-kapasitas').textContent = selectedOption.dataset.kapasitas || 'N/A';
            row.querySelector('.kelas-prodi').textContent = selectedOption.dataset.prodi || 'N/A';
        } else {
            row.querySelector('.kelas-code').textContent = '-';
            row.querySelector('.kelas-kapasitas').textContent = '-';
            row.querySelector('.kelas-prodi').textContent = '-';
        }
    }

    // Fungsi untuk menambah pertemuan
    function addPertemuan() {
        const container = document.getElementById('pertemuan-container');

        const pertemuanHtml = `
            <tr class="pertemuan-item" data-index="${pertemuanIndex}">
                <td>
                    <input type="number" class="form-control form-control-sm"
                           name="pertemuan[${pertemuanIndex}][pertemuan_ke]"
                           value="${pertemuanIndex + 1}" min="1" required>
                </td>
                <td>
                    <input type="date" class="form-control form-control-sm"
                           name="pertemuan[${pertemuanIndex}][tanggal]" required>
                </td>
                <td>
                    <input type="time" class="form-control form-control-sm"
                           name="pertemuan[${pertemuanIndex}][jam_mulai]"
                           value="{{ $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '08:00' }}">
                </td>
                <td>
                    <input type="time" class="form-control form-control-sm"
                           name="pertemuan[${pertemuanIndex}][jam_selesai]"
                           value="{{ $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '10:00' }}">
                </td>
                <td>
                    <textarea class="form-control form-control-sm"
                              name="pertemuan[${pertemuanIndex}][materi]"
                              rows="2"
                              placeholder="Materi pertemuan ke-${pertemuanIndex + 1}"></textarea>
                </td>
                <td>
                    <select class="form-select form-select-sm" name="pertemuan[${pertemuanIndex}][status]">
                        <option value="Belum Terlaksana" selected>Belum Terlaksana</option>
                        <option value="Terlaksana">Terlaksana</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </td>
                <td>
                    <input type="url" class="form-control form-control-sm"
                           name="pertemuan[${pertemuanIndex}][link]" placeholder="https://...">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removePertemuan(${pertemuanIndex})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        container.insertAdjacentHTML('beforeend', pertemuanHtml);
        pertemuanIndex++;
    }

    // Fungsi untuk menghapus pertemuan
    function removePertemuan(index) {
        const pertemuanItem = document.querySelector(`tr[data-index="${index}"]`);
        if (pertemuanItem) pertemuanItem.remove();
    }
</script>

@endsection