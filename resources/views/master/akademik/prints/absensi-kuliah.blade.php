<!DOCTYPE html>
<html>
<head>
    <title>Cetak Absensi Kuliah - {{ $kelas->mataKuliah->name ?? '' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 16px; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px; vertical-align: top; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 4px; text-align: left; }
        .data-table th { bg-color: #f2f2f2; text-align: center; }
        .footer { margin-top: 30px; width: 100%; }
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
        <h3>PRESENSI KULIAH MAHASISWA</h3>
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
                <th rowspan="2" style="width: 3%">No</th>
                <th rowspan="2" style="width: 10%">NIM</th>
                <th rowspan="2" style="width: 25%">Nama Mahasiswa</th>
                <th colspan="16">Pertemuan Ke (Tanda Tangan / Paraf)</th>
            </tr>
            <tr>
                @for($i = 1; $i <= 16; $i++)
                    <th style="width: 3.8%">{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $idx => $m)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="text-align: center;">{{ $m->username }}</td>
                    <td>{{ $m->name }}</td>
                    @for($i = 1; $i <= 16; $i++)
                        <td style="height: 25px;"></td>
                    @endfor
                </tr>
            @empty
                <tr>
                    <td colspan="19" style="text-align: center; padding: 20px;">Belum ada mahasiswa terdaftar di kelas ini.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td style="width: 50%">
            </td>
            <td style="width: 50%">
                Dosen Pengampu,<br><br><br><br>
                <strong>{{ $kelas->dosen->name ?? '........................................' }}</strong>
            </td>
        </tr>
    </table>
</body>
</html>
