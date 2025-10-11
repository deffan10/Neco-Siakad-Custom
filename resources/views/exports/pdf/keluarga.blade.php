<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 5px 0;
        }
        .info {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <div class="info">Dicetak pada: {{ $date }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">User</th>
                <th style="width: 10%">Hubungan</th>
                <th style="width: 15%">Nama</th>
                <th style="width: 15%">Pekerjaan</th>
                <th style="width: 12%">Telepon</th>
                <th style="width: 10%">Tanggal Lahir</th>
                <th style="width: 13%">Penghasilan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keluargas as $index => $keluarga)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $keluarga->user->name ?? '-' }}</td>
                    <td>{{ $keluarga->hubungan }}</td>
                    <td>{{ $keluarga->nama }}</td>
                    <td>{{ $keluarga->pekerjaan ?? '-' }}</td>
                    <td>{{ $keluarga->telepon ?? '-' }}</td>
                    <td>{{ $keluarga->tanggal_lahir ?? '-' }}</td>
                    <td>Rp {{ number_format($keluarga->penghasilan ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Data: {{ $keluargas->count() }}</p>
    </div>
</body>
</html>
