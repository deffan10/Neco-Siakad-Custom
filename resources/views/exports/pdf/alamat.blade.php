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
                <th style="width: 10%">Tipe</th>
                <th style="width: 25%">Alamat Lengkap</th>
                <th style="width: 5%">RT/RW</th>
                <th style="width: 15%">Kelurahan</th>
                <th style="width: 15%">Kecamatan</th>
                <th style="width: 10%">Kode Pos</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alamats as $index => $alamat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $alamat->user->name ?? '-' }}</td>
                    <td>{{ ucfirst($alamat->tipe) }}</td>
                    <td>{{ $alamat->alamat_lengkap }}</td>
                    <td>{{ $alamat->rt }}/{{ $alamat->rw }}</td>
                    <td>{{ $alamat->kelurahan }}</td>
                    <td>{{ $alamat->kecamatan }}</td>
                    <td>{{ $alamat->kode_pos }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Data: {{ $alamats->count() }}</p>
    </div>
</body>
</html>
