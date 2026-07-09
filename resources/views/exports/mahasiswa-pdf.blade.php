<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; }
        body { color: #222; }
        .header { text-align: center; border-bottom: 2px solid #1a56db; padding-bottom: 10px; margin-bottom: 16px; }
        .header h1 { font-size: 16px; font-weight: 700; color: #1a56db; }
        .header p  { font-size: 10px; color: #666; margin-top: 2px; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 9px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #1a56db; color: #fff; }
        thead th { padding: 6px 8px; text-align: left; font-size: 9px; }
        tbody tr:nth-child(even) { background: #f4f6fb; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 9px; }
        .footer { margin-top: 16px; text-align: right; font-size: 9px; color: #888; border-top: 1px solid #e5e7eb; padding-top: 6px; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: 600; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Mahasiswa</h1>
        <p>{{ $filterProdi }} &bull; Angkatan {{ $filterAngkatan }}</p>
        <p>Dicetak: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <div class="meta">
        <span>Total Mahasiswa: <strong>{{ $mahasiswas->count() }}</strong></span>
        <span>Sistem Informasi Akademik</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>NIM</th>
                <th>Nama Lengkap</th>
                <th>Program Studi</th>
                <th>Angkatan</th>
                <th>Status</th>
                <th>IPK</th>
                <th>SKS Lulus</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $i => $u)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $u->dataMahasiswa?->nim ?? '-' }}</td>
                <td><strong>{{ $u->name }}</strong></td>
                <td>{{ $u->dataMahasiswa?->programStudi?->name ?? '-' }}</td>
                <td>{{ $u->dataMahasiswa?->angkatan ?? '-' }}</td>
                <td>
                    <span class="badge badge-{{ $u->dataMahasiswa?->statusMahasiswa?->name === 'Aktif' ? 'green' : 'yellow' }}">
                        {{ $u->dataMahasiswa?->statusMahasiswa?->name ?? '-' }}
                    </span>
                </td>
                <td>{{ $u->dataMahasiswa?->ipk ?? '-' }}</td>
                <td>{{ $u->dataMahasiswa?->sks_lulus ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; color:#999; padding:20px">Tidak ada data mahasiswa.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan ini digenerate secara otomatis oleh Sistem Informasi Akademik &mdash; {{ now()->format('d/m/Y') }}
    </div>
</body>
</html>
