<!DOCTYPE html>
<html>
<head>
    <title>Cetak Absensi Ujian - {{ $kelas->mataKuliah->name ?? '' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 30px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px; vertical-align: top; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .data-table th { bg-color: #f2f2f2; text-align: center; }
        .footer { margin-top: 50px; width: 100%; }
        .footer td { text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()">Cetak Halaman</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="header">
        <h2>{{ $academy->name ?? 'INSTITUSI KAMPUS' }}</h2>
        <p>{{ $academy->address ?? '' }}</p>
        <hr>
        <h3>DAFTAR HADIR & BERITA ACARA UJIAN ({{ $jenisUjian }})</h3>
        <p>Tahun Akademik: {{ $kelas->tahunAkademik->name ?? '-' }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 15%">Mata Kuliah</td>
            <td style="width: 35%">: <strong>{{ $kelas->mataKuliah->name ?? '-' }} ({{ $kelas->mataKuliah->code ?? '' }})</strong></td>
            <td style="width: 15%">Kelas</td>
            <td style="width: 35%">: {{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>: {{ $kelas->programStudi->name ?? '-' }}</td>
            <td>Dosen Pengampu</td>
            <td>: {{ $kelas->dosen->name ?? '-' }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">NIM</th>
                <th style="width: 50%">Nama Mahasiswa</th>
                <th style="width: 30%" colspan="2">Tanda Tangan Peserta</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $idx => $m)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="text-align: center;">{{ $m->username }}</td>
                    <td>{{ $m->name }}</td>
                    @if(($idx + 1) % 2 != 0)
                        <td style="width: 15%; height: 35px; border-right: none; vertical-align: bottom; font-size: 9px;">{{ $idx + 1 }}. ......................</td>
                        <td style="width: 15%; height: 35px; border-left: none;"></td>
                    @else
                        <td style="width: 15%; height: 35px; border-right: none;"></td>
                        <td style="width: 15%; height: 35px; border-left: none; vertical-align: bottom; font-size: 9px;">{{ $idx + 1 }}. ......................</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Belum ada peserta terdaftar.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td style="width: 50%">
                Pengawas Ujian,<br><br><br><br>
                <strong>( ........................................ )</strong>
            </td>
            <td style="width: 50%">
                Dosen Pengampu,<br><br><br><br>
                <strong>{{ $kelas->dosen->name ?? '........................................' }}</strong>
            </td>
        </tr>
    </table>
</body>
</html>
