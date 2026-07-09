@extends('themes.core-backpage')

@section('custom-css')
<style>
    .krs-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        padding: 2rem;
        border-radius: 10px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('content')
<div class="container-xl">
    <!-- KRS Header -->
    <div class="krs-header">
        <h2 class="mb-1">Rencana Studi Online (KRS)</h2>
        <p class="mb-0">Pilih kelas mata kuliah yang ditawarkan untuk rencana perkuliahan semester aktif.</p>
    </div>

    @if(isset($blocked) && $blocked)
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 17h.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
            <div>
                <strong>Akses Terblokir:</strong> {{ $message }}
            </div>
        </div>
    @else
        <div class="row">
            <!-- Form Pengisian KRS -->
            <div class="col-md-8">
                <form action="{{ route('mahasiswa.krs.submit') }}" method="POST" id="krsForm">
                    @csrf
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Daftar Kelas Perkuliahan Ditawarkan</h3>
                            <div>
                                <span class="badge bg-purple">Maksimal SKS Anda: {{ $maxSks }} SKS</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter">
                                <thead>
                                    <tr>
                                        <th class="w-1">Pilih</th>
                                        <th>Kode</th>
                                        <th>Mata Kuliah</th>
                                        <th>SKS</th>
                                        <th>Kelas</th>
                                        <th>Kapasitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kelasPerkuliahan as $kelas)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input krs-cb" name="kelas_ids[]" value="{{ $kelas->id }}" 
                                                    data-sks="{{ $kelas->mataKuliah->beban_sks }}"
                                                    {{ in_array($kelas->id, $selectedClassIds) ? 'checked' : '' }}
                                                    {{ $krs->status !== 'Draft' ? 'disabled' : '' }}>
                                            </td>
                                            <td>{{ $kelas->code }}</td>
                                            <td><strong>{{ $kelas->mataKuliah->name }}</strong></td>
                                            <td>{{ $kelas->mataKuliah->beban_sks }} SKS</td>
                                            <td>{{ $kelas->name }}</td>
                                            <td>{{ $kelas->kapasitas ?? 'Unlimited' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada kelas ditawarkan untuk prodi Anda pada semester ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($krs->status === 'Draft' && !$kelasPerkuliahan->isEmpty())
                            <div class="card-footer d-flex justify-content-end gap-2">
                                <button type="submit" name="action" value="save" class="btn btn-outline-primary">Simpan Draft</button>
                                <button type="submit" name="action" value="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin mengajukan KRS ini ke Dosen PA? Data tidak akan bisa diubah setelah dikirim.')">Ajukan KRS ke DPA</button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Status KRS & Detail -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <div class="subheader">Status KRS</div>
                        <div class="h1 my-2">
                            @if($krs->status === 'Disetujui')
                                <span class="text-success font-weight-bold">DISETUJUI</span>
                            @elseif($krs->status === 'Diajukan')
                                <span class="text-warning font-weight-bold">DIAJUKAN</span>
                            @else
                                <span class="text-secondary font-weight-bold">DRAFT</span>
                            @endif
                        </div>
                        @if($krs->dosenPa)
                            <div class="text-muted small">Dosen PA: <strong>{{ $krs->dosenPa->name }}</strong></div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ringkasan Pengambilan</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>SKS Terpilih:</span>
                            <strong id="sksTerpilih">{{ $currentSks }} / {{ $maxSks }} SKS</strong>
                        </div>
                        <div class="progress progress-sm mb-3">
                            @php 
                                $percent = ($maxSks > 0) ? ($currentSks / $maxSks) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-purple" id="sksProgressBar" style="width: {{ $percent }}%" role="progressbar" aria-valuenow="{{ $currentSks }}" aria-valuemin="0" aria-valuemax="{{ $maxSks }}"></div>
                        </div>

                        @if($krs->catatan_dosen)
                            <div class="alert alert-info py-2 px-3 mb-0" role="alert">
                                <strong>Catatan Wali:</strong> {{ $krs->catatan_dosen }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('custom-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.krs-cb');
        const sksTerpilih = document.getElementById('sksTerpilih');
        const progressBar = document.getElementById('sksProgressBar');
        const maxSks = {{ $maxSks }};

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                let total = 0;
                document.querySelectorAll('.krs-cb:checked').forEach(checkedCb => {
                    total += parseInt(checkedCb.dataset.sks);
                });

                if (total > maxSks) {
                    alert('SKS yang Anda pilih melebihi batas maksimal Anda (' + maxSks + ' SKS).');
                    this.checked = false;
                    return;
                }

                sksTerpilih.textContent = total + ' / ' + maxSks + ' SKS';
                const percent = (total / maxSks) * 100;
                progressBar.style.width = percent + '%';
            });
        });
    });
</script>
@endsection
