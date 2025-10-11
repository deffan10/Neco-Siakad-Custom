# Export/Import Implementation Guide for Alamat, Keluarga, Pendidikan, Role

## 📋 Overview
Implementasi fitur export (CSV, Excel, PDF) dan import (Excel) untuk modul:
- ✅ Alamat (COMPLETED)
- ⏳ Keluarga  
- ⏳ Pendidikan
- ⏳ Role

## 📁 File Structure

### Per Module Structure:
```
app/Services/
├── Export/
│   ├── CSV/
│   │   └── Export{Module}CSVService.php
│   ├── Excel/
│   │   └── Export{Module}ExcelService.php
│   └── PDF/
│       └── Export{Module}PDFService.php
├── Import/
│   └── Excel/
│       └── Import{Module}ExcelService.php

resources/views/exports/pdf/
└── {module}.blade.php

app/Http/Controllers/Master/Users/
└── {Module}Controller.php (add export/import methods)

routes/
└── web.php (add export/import routes)

resources/views/master/users/
└── {module}-index.blade.php (add buttons & modal)
```

## ✅ Alamat Module (COMPLETED)

### Files Created:
- ✅ app/Services/Export/CSV/ExportAlamatCSVService.php
- ✅ app/Services/Export/Excel/ExportAlamatExcelService.php
- ✅ app/Services/Export/PDF/ExportAlamatPDFService.php
- ✅ app/Services/Import/Excel/ImportAlamatExcelService.php
- ✅ resources/views/exports/pdf/alamat.blade.php

### Next Steps for Alamat:
1. Update AlamatController with export/import methods
2. Add routes for export/import
3. Update alamat-index.blade.php view with buttons & modal

---

## 📝 Keluarga Module Files to Create

### 1. ExportKeluargaCSVService.php
```php
<?php

namespace App\Services\Export\CSV;

use App\Models\User\Keluarga;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Collection;

class ExportKeluargaCSVService
{
    public function export()
    {
        $keluargas = Keluarga::with(['user'])->get();
        $data = $this->transformData($keluargas);
        $filename = 'keluarga_'.date('Y-m-d_His').'.csv';
        return (new FastExcel($data))->download($filename);
    }

    private function transformData(Collection $keluargas): Collection
    {
        return $keluargas->map(function ($keluarga) {
            return [
                'ID' => $keluarga->id,
                'User' => $keluarga->user->name ?? '-',
                'Email User' => $keluarga->user->email ?? '-',
                'Hubungan' => $keluarga->hubungan,
                'Nama' => $keluarga->nama,
                'Pekerjaan' => $keluarga->pekerjaan,
                'Telepon' => $keluarga->telepon,
                'Tempat Lahir' => $keluarga->tempat_lahir,
                'Tanggal Lahir' => $keluarga->tanggal_lahir,
                'Penghasilan' => $keluarga->penghasilan,
                'Alamat' => $keluarga->alamat,
                'Dibuat Pada' => $keluarga->created_at?->format('Y-m-d H:i:s'),
            ];
        });
    }
}
```

### 2. ExportKeluargaExcelService.php
```php
<?php

namespace App\Services\Export\Excel;

use App\Models\User\Keluarga;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Collection;

class ExportKeluargaExcelService
{
    public function export()
    {
        $keluargas = Keluarga::with(['user'])->get();
        $data = $this->transformData($keluargas);
        $filename = 'keluarga_'.date('Y-m-d_His').'.xlsx';
        return (new FastExcel($data))->download($filename);
    }

    private function transformData(Collection $keluargas): Collection
    {
        return $keluargas->map(function ($keluarga) {
            return [
                'ID' => $keluarga->id,
                'User' => $keluarga->user->name ?? '-',
                'Email User' => $keluarga->user->email ?? '-',
                'Hubungan' => $keluarga->hubungan,
                'Nama' => $keluarga->nama,
                'Pekerjaan' => $keluarga->pekerjaan,
                'Telepon' => $keluarga->telepon,
                'Tempat Lahir' => $keluarga->tempat_lahir,
                'Tanggal Lahir' => $keluarga->tanggal_lahir,
                'Penghasilan' => $keluarga->penghasilan,
                'Alamat' => $keluarga->alamat,
                'Dibuat Pada' => $keluarga->created_at?->format('Y-m-d H:i:s'),
            ];
        });
    }
}
```

### 3. ExportKeluargaPDFService.php
```php
<?php

namespace App\Services\Export\PDF;

use App\Models\User\Keluarga;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportKeluargaPDFService
{
    public function export()
    {
        $keluargas = Keluarga::with(['user'])->get();

        $data = [
            'title' => 'Data Keluarga',
            'date' => date('d F Y H:i:s'),
            'keluargas' => $keluargas,
        ];

        $pdf = Pdf::loadView('exports.pdf.keluarga', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('keluarga_'.date('Y-m-d_His').'.pdf');
    }
}
```

### 4. ImportKeluargaExcelService.php
```php
<?php

namespace App\Services\Import\Excel;

use App\Models\User;
use App\Models\User\Keluarga;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportKeluargaExcelService
{
    public function import(string $filePath, array $options = []): array
    {
        $skipDuplicates = $options['skip_duplicates'] ?? true;
        
        $results = [
            'success' => false,
            'message' => '',
            'success_count' => 0,
            'skip_count' => 0,
            'errors' => [],
        ];

        try {
            $rows = (new FastExcel)->import($filePath);
            
            if ($rows->isEmpty()) {
                $results['message'] = 'File Excel kosong atau tidak valid';
                return $results;
            }

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                try {
                    $user = User::where('email', $row['Email User'] ?? '')->first();
                    
                    if (!$user) {
                        if ($skipDuplicates) {
                            $results['skip_count']++;
                            continue;
                        }
                        throw new \Exception("User dengan email '{$row['Email User']}' tidak ditemukan");
                    }

                    $keluargaData = [
                        'user_id' => $user->id,
                        'hubungan' => $row['Hubungan'] ?? null,
                        'nama' => $row['Nama'] ?? null,
                        'pekerjaan' => $row['Pekerjaan'] ?? null,
                        'telepon' => $row['Telepon'] ?? null,
                        'tempat_lahir' => $row['Tempat Lahir'] ?? null,
                        'tanggal_lahir' => $row['Tanggal Lahir'] ?? null,
                        'penghasilan' => $row['Penghasilan'] ?? null,
                        'alamat' => $row['Alamat'] ?? null,
                        'created_by' => auth()->id(),
                    ];

                    $validator = Validator::make($keluargaData, [
                        'user_id' => 'required|exists:users,id',
                        'hubungan' => 'required|string',
                        'nama' => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        throw new \Exception('Baris '.$rowNumber.': '.implode(', ', $validator->errors()->all()));
                    }

                    Keluarga::create($keluargaData);
                    $results['success_count']++;

                } catch (\Exception $e) {
                    $results['errors'][] = "Baris {$rowNumber}: {$e->getMessage()}";
                }
            }

            DB::commit();

            $results['success'] = true;
            $results['message'] = "Import berhasil! {$results['success_count']} data berhasil diimport";
            
            if ($results['skip_count'] > 0) {
                $results['message'] .= ", {$results['skip_count']} data dilewati";
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $results['message'] = 'Terjadi kesalahan: '.$e->getMessage();
            $results['errors'][] = $e->getMessage();
        }

        return $results;
    }
}
```

### 5. keluarga.blade.php (PDF View)
```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background-color: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <div>Dicetak pada: {{ $date }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Hubungan</th>
                <th>Nama</th>
                <th>Pekerjaan</th>
                <th>Telepon</th>
                <th>Penghasilan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keluargas as $index => $keluarga)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $keluarga->user->name ?? '-' }}</td>
                    <td>{{ $keluarga->hubungan }}</td>
                    <td>{{ $keluarga->nama }}</td>
                    <td>{{ $keluarga->pekerjaan }}</td>
                    <td>{{ $keluarga->telepon }}</td>
                    <td>Rp {{ number_format($keluarga->penghasilan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right; font-size: 10px;">
        <p>Total Data: {{ $keluargas->count() }}</p>
    </div>
</body>
</html>
```

---

Karena terlalu banyak file untuk satu response, saya akan buat script generator untuk mempermudah. Apakah Anda ingin saya:

1. **Lanjutkan membuat semua file satu per satu** (akan butuh beberapa response karena banyak file)
2. **Buat script generator** yang akan generate semua files dengan satu command
3. **Fokus selesaikan Alamat dulu** (update controller, routes, view) baru lanjut ke modul lain

Pilihan mana yang Anda prefer? 🤔
