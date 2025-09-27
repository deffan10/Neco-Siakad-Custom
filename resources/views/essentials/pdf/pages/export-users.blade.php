<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengguna SIAKAD</title>
    <style>
        @page {
            margin: 1cm 1.5cm;
            @top-left {
                content: "SISTEM INFORMASI AKADEMIK";
                font-size: 10px;
                color: #666;
            }
            @top-right {
                content: "Tanggal: " counter(page);
                font-size: 10px;
                color: #666;
            }
            @bottom-center {
                content: "Halaman " counter(page) " dari " counter(pages);
                font-size: 10px;
                color: #666;
            }
        }
        
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10px; 
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }
        
        .info-row {
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .info-row .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 10px;
            font-size: 9px;
        }
        
        th, td { 
            border: 1px solid #333; 
            padding: 4px; 
            text-align: left;
            vertical-align: top;
        }
        
        th { 
            background: #f2f2f2; 
            font-weight: bold;
            text-align: center;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .status-active { 
            background: #d4edda; 
            color: #155724; 
            padding: 2px 6px; 
            border-radius: 3px;
            font-size: 8px;
        }
        
        .status-inactive { 
            background: #f8d7da; 
            color: #721c24; 
            padding: 2px 6px; 
            border-radius: 3px;
            font-size: 8px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>SISTEM INFORMASI AKADEMIK</h1>
        <h2>LAPORAN DATA PENGGUNA</h2>
    </div>
    
    <!-- Info Summary -->
    <div class="info-row">
        <span class="label">Tanggal Export:</span>
        {{ date('d F Y H:i:s') }}
    </div>
    <div class="info-row">
        <span class="label">Total Pengguna:</span>
        {{ $users->count() }} orang
    </div>
    <div class="info-row">
        <span class="label">Pengguna Aktif:</span>
        {{ $users->where('is_active', true)->count() }} orang
    </div>
    <div class="info-row">
        <span class="label">Pengguna Non-Aktif:</span>
        {{ $users->where('is_active', false)->count() }} orang
    </div>
    
    @php
        // Group users by roles
        $usersByRole = collect();
        foreach($users as $user) {
            foreach($user->roles as $role) {
                if(!$usersByRole->has($role->name)) {
                    $usersByRole->put($role->name, collect());
                }
                $usersByRole->get($role->name)->push($user);
            }
        }
    @endphp
    
    @foreach($usersByRole as $roleName => $roleUsers)
        <div class="page-break-before" style="margin-top: 40px;">
            <h3 style="background: #f8f9fa; padding: 10px; margin: 0 0 20px 0; border-left: 4px solid #007bff; font-size: 14px;">
                {{ strtoupper($roleName) }} ({{ $roleUsers->count() }} Orang)
            </h3>
            
            <!-- Main Table -->
            <table>
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th width="15%">Nama Lengkap</th>
                        <th width="15%">Email</th>
                        <th width="10%">Telepon</th>
                        <th width="5%">L/P</th>
                        <th width="10%">Agama</th>
                        <th width="12%">Tempat Lahir</th>
                        <th width="8%">Tgl Lahir</th>
                        <th width="12%">No KTP</th>
                        <th width="6%">Status</th>
                        <th width="4%">Setup</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roleUsers as $key => $user)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td style="font-size: 8px;">{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td class="text-center">{{ $user->jenisKelamin?->nama ? substr($user->jenisKelamin->nama, 0, 1) : '-' }}</td>
                            <td>{{ $user->agama?->nama ?? '-' }}</td>
                            <td>{{ $user->tempat_lahir ?? '-' }}</td>
                            <td class="text-center">{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                            <td style="font-size: 8px;">{{ $user->nomor_ktp ?? '-' }}</td>
                            <td class="text-center">
                                <span class="{{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span style="font-size: 8px; color: {{ $user->fst_setup ? '#28a745' : '#dc3545' }};">
                                    {{ $user->fst_setup ? '✓' : '✗' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Role Summary -->
            <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; font-size: 10px;">
                <strong>Ringkasan {{ $roleName }}:</strong>
                <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                    <div>
                        Aktif: {{ $roleUsers->where('is_active', true)->count() }} |
                        Non-Aktif: {{ $roleUsers->where('is_active', false)->count() }}
                    </div>
                    <div>
                        Setup Selesai: {{ $roleUsers->where('fst_setup', true)->count() }} |
                        Belum Setup: {{ $roleUsers->where('fst_setup', false)->count() }}
                    </div>
                    <div>
                        L: {{ $roleUsers->filter(function($user) { return $user->jenisKelamin && str_contains(strtolower($user->jenisKelamin->nama), 'laki'); })->count() }} |
                        P: {{ $roleUsers->filter(function($user) { return $user->jenisKelamin && str_contains(strtolower($user->jenisKelamin->nama), 'perempuan'); })->count() }}
                    </div>
                </div>
            </div>
        </div>
        
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
    
    <!-- Overall Summary Section -->
    <div class="page-break" style="margin-top: 30px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6;">
        <h3 style="margin: 0 0 15px 0; font-size: 14px; text-align: center;">RINGKASAN KESELURUHAN</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 10px;">
            <div>
                <h4 style="margin: 0 0 10px 0; font-size: 11px;">Distribusi Berdasarkan Role:</h4>
                @foreach($usersByRole as $roleName => $roleUsers)
                    <div style="margin-bottom: 5px;">
                        <strong>{{ $roleName }}:</strong> {{ $roleUsers->count() }} orang 
                        ({{ number_format(($roleUsers->count() / $users->count()) * 100, 1) }}%)
                    </div>
                @endforeach
            </div>
            
            <div>
                <h4 style="margin: 0 0 10px 0; font-size: 11px;">Status Keseluruhan:</h4>
                <div style="margin-bottom: 5px;">
                    <strong>Status Aktif:</strong> {{ $users->where('is_active', true)->count() }} orang
                    ({{ number_format(($users->where('is_active', true)->count() / $users->count()) * 100, 1) }}%)
                </div>
                <div style="margin-bottom: 5px;">
                    <strong>Setup Selesai:</strong> {{ $users->where('fst_setup', true)->count() }} orang
                    ({{ number_format(($users->where('fst_setup', true)->count() / $users->count()) * 100, 1) }}%)
                </div>
                <div>
                    <strong>Gender:</strong> 
                    L: {{ $users->filter(function($user) { return $user->jenisKelamin && str_contains(strtolower($user->jenisKelamin->nama), 'laki'); })->count() }}, 
                    P: {{ $users->filter(function($user) { return $user->jenisKelamin && str_contains(strtolower($user->jenisKelamin->nama), 'perempuan'); })->count() }}
                </div>
            </div>
        </div>
        
        <div style="margin-top: 20px; text-align: center; font-size: 9px; color: #666;">
            <em>Laporan ini digenerate pada {{ date('d F Y, H:i:s') }} WIB</em>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Informasi Akademik - {{ date('Y') }}</p>
    </div>
</body>
</html>
