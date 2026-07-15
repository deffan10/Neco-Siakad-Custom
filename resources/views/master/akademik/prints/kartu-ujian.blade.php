<!DOCTYPE html>
<html>
<head>
    <title>Cetak Kartu Ujian - {{ $student->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 30px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px; vertical-align: top; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .data-table th { bg-color: #f2f2f2; }
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
        <h3>KARTU PESERTA UJIAN (KPU) - {{ $jenisUjian }}</h3>
        <p>Tahun Akademik / Semester: {{ $ta->name }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 15%">NIM</td>
            <td style="width: 35%">: <strong>{{ $student->username }}</strong></td>
            <td style="width: 15%">Jenjang/Prodi</td>
            <td style="width: 35%">: {{ $student->programStudi->jenjang ?? '' }} - {{ $student->programStudi->name ?? '' }}</td>
        </tr>
        <tr>
            <td>Nama Mahasiswa</td>
            <td>: {{ $student->name }}</td>
            <td>Jenis Ujian</td>
            <td>: <strong>{{ $jenisUjian }}</strong></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Kode MK</th>
                <th style="width: 50%">Mata Kuliah</th>
                <th style="width: 15%">SKS</th>
                <th style="width: 15%">Paraf Pengawas</th>
            </tr>
        </thead>
        <tbody>
            @if($krs && $krs->details->count() > 0)
                @foreach($krs->details as $idx => $detail)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $detail->kelas->mataKuliah->code ?? '-' }}</td>
                        <td>{{ $detail->kelas->mataKuliah->name ?? '-' }}</td>
                        <td>{{ $detail->kelas->mataKuliah->sks ?? 0 }} SKS</td>
                        <td style="height: 35px;"></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada krs terdaftar.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td style="width: 50%">
                <br>
                Mahasiswa Yang Bersangkutan,<br><br><br><br>
                <strong>{{ $student->name }}</strong>
            </td>
            <td style="width: 50%">
                {{ $academy->city ?? 'Kota' }}, {{ date('d F Y') }}<br>
                Kepala Bagian Akademik,<br><br><br><br>
                <strong>( ........................................ )</strong>
            </td>
        </tr>
    </table>
</body>
</html>
