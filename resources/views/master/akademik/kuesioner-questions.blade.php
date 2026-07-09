@extends('themes.core-backpage')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <a href="{{ route('admin.kuesioner.index') }}" class="btn btn-outline-secondary mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Manajemen Kuesioner
                </a>
                <h2 class="page-title">Kelola Pertanyaan: {{ $kuesioner->judul }}</h2>
                <div class="text-muted mt-1">Tambahkan butir pertanyaan survei, pilih tipe jawaban, dan definisikan opsi pilihan.</div>
            </div>
        </div>
    </div>

    <div class="row row-cards mt-3">
        <!-- Tambah Pertanyaan Baru -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Tambah Butir Pertanyaan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.kuesioner.questions.store', $kuesioner->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Butir Pertanyaan</label>
                            <textarea class="form-control" name="pertanyaan" rows="3" placeholder="Contoh: Apakah Anda sudah bekerja setelah lulus?" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Tipe Jawaban</label>
                            <select class="form-select" name="tipe" id="tipeSelect" required>
                                <option value="text">Isian Bebas (Text/Deskriptif)</option>
                                <option value="radio">Pilihan Tunggal (Radio Button)</option>
                                <option value="checkbox">Pilihan Banyak (Checkbox)</option>
                            </select>
                        </div>
                        
                        <!-- Pilihan Jawaban (Hanya Tampil Jika Radio/Checkbox) -->
                        <div class="mb-3" id="pilihanContainer" style="display: none;">
                            <label class="form-label required">Pilihan Jawaban (Pisahkan dengan Koma)</label>
                            <textarea class="form-control" name="pilihan_pilihan" id="pilihanInput" rows="3" placeholder="Contoh: Sudah Bekerja, Belum Bekerja, Melanjutkan Kuliah"></textarea>
                            <small class="text-muted">Masukkan semua opsi jawaban dipisahkan menggunakan tanda koma ( , ).</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Pertanyaan</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Pertanyaan Aktif -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Pertanyaan Aktif</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th>Pertanyaan</th>
                                <th>Tipe</th>
                                <th>Opsi Jawaban</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kuesioner->pertanyaans as $index => $q)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $q->pertanyaan }}</strong></td>
                                    <td>
                                        @if($q->tipe === 'text')
                                            <span class="badge bg-secondary-lt">Text</span>
                                        @elseif($q->tipe === 'radio')
                                            <span class="badge bg-blue-lt">Radio</span>
                                        @else
                                            <span class="badge bg-purple-lt">Checkbox</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(is_array($q->pilihan_jawaban))
                                            <ul class="mb-0 ps-3 small text-muted">
                                                @foreach($q->pilihan_jawaban as $opt)
                                                    <li>{{ $opt }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted small">Isian bebas deskriptif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.kuesioner.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada pertanyaan yang ditambahkan untuk kuesioner ini.</td>
                                </tr>
                            @endforelse
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
    document.addEventListener("DOMContentLoaded", function() {
        var tipeSelect = document.getElementById('tipeSelect');
        var pilihanContainer = document.getElementById('pilihanContainer');
        var pilihanInput = document.getElementById('pilihanInput');

        if(tipeSelect) {
            tipeSelect.addEventListener('change', function() {
                if(this.value === 'radio' || this.value === 'checkbox') {
                    pilihanContainer.style.display = 'block';
                    pilihanInput.setAttribute('required', 'required');
                } else {
                    pilihanContainer.style.display = 'none';
                    pilihanInput.removeAttribute('required');
                    pilihanInput.value = '';
                }
            });
        }
    });
</script>
@endsection
