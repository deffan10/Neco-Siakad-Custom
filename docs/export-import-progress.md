# Export/Import Feature Implementation - COMPLETED FOR ALAMAT

## ✅ Alamat Module - COMPLETED

### Files Created:
1. ✅ `app/Services/Export/CSV/ExportAlamatCSVService.php`
2. ✅ `app/Services/Export/Excel/ExportAlamatExcelService.php`
3. ✅ `app/Services/Export/PDF/ExportAlamatPDFService.php`
4. ✅ `app/Services/Import/Excel/ImportAlamatExcelService.php`
5. ✅ `resources/views/exports/pdf/alamat.blade.php`

### Files Updated:
6. ✅ `app/Http/Controllers/Master/Users/AlamatController.php`
   - Added exportExcel()
   - Added exportCSV()
   - Added exportPDF()
   - Added importExcel()
   - Added downloadTemplate()

7. ✅ `routes/web.php`
   - Added export-excel route
   - Added export-csv route
   - Added export-pdf route
   - Added import-excel route
   - Added template download route

### Still TODO for Alamat:
8. ⏳ Update `resources/views/master/users/alamat-index.blade.php`
   - Add export buttons (CSV, Excel, PDF, Print)
   - Add import modal (like user-index.blade.php)
   - Add JavaScript for button handlers

---

## 📋 Quick Implementation Guide for Other Modules

To implement export/import for **Keluarga, Pendidikan, Role**, follow these steps:

### Step 1: Create Export Services

For each module (replace `{Module}` with actual name):

**CSV Export Service:**
```php
// app/Services/Export/CSV/Export{Module}CSVService.php
<?php

namespace App\Services\Export\CSV;

use App\Models\User\{Module};
use Rap2hpoutre\FastExcel\FastExcel;

class Export{Module}CSVService
{
    public function export()
    {
        $data = {Module}::with(['user'])->get()->map(function ($item) {
            return [
                // Map your columns here
            ];
        });

        return (new FastExcel($data))->download('{module}_'.date('Y-m-d_His').'.csv');
    }
}
```

### Step 2: Update Controller

Add these methods to your controller:

```php
use App\Services\Export\CSV\Export{Module}CSVService;
use App\Services\Export\Excel\Export{Module}ExcelService;
use App\Services\Export\PDF\Export{Module}PDFService;
use App\Services\Import\Excel\Import{Module}ExcelService;

public function exportExcel(Export{Module}ExcelService $exporter)
{
    return $exporter->export();
}

public function exportCSV(Export{Module}CSVService $exporter)
{
    return $exporter->export();
}

public function exportPDF(Export{Module}PDFService $exporter)
{
    return $exporter->export();
}

public function importExcel(Request $request, Import{Module}ExcelService $importer)
{
    // Same implementation as AlamatController
}

public function downloadTemplate()
{
    // Same implementation as AlamatController
}
```

### Step 3: Add Routes

```php
Route::get('/users/{module}/export-excel', [Controller::class, 'exportExcel'])->name('users.{module}-export-excel');
Route::get('/users/{module}/export-csv', [Controller::class, 'exportCSV'])->name('users.{module}-export-csv');
Route::get('/users/{module}/export-pdf', [Controller::class, 'exportPDF'])->name('users.{module}-export-pdf');
Route::post('/users/{module}/import-excel', [Controller::class, 'importExcel'])->name('users.{module}-import');
Route::get('/users/{module}/template', [Controller::class, 'downloadTemplate'])->name('users.{module}-template');
```

### Step 4: Update View

Copy JavaScript from `user-index.blade.php` and adapt routes:

```javascript
// Add export/import buttons
table.button().add(0, {
    text: '<i class="fas fa-file-csv"></i> CSV',
    action: function() {
        window.open('{{ route($activeRole . ".users.{module}-export-csv") }}', '_blank');
    }
});
// ... add other buttons
```

---

## 🚀 Next Steps

### For Keluarga:
1. Create 4 export/import services
2. Update KeluargaController
3. Add routes
4. Update keluarga-index.blade.php view

### For Pendidikan:
1. Create 4 export/import services
2. Update PendidikanController
3. Add routes
4. Update pendidikan-index.blade.php view

### For Role:
1. Create 4 export/import services
2. Update RoleController
3. Add routes
4. Update role-index.blade.php view

---

## 📝 Template Data Examples

### Alamat Template:
```php
[
    'Email User' => 'user@example.com',
    'Tipe' => 'ktp',
    'Alamat Lengkap' => 'Jl. Contoh No. 123',
    'RT' => '001',
    'RW' => '002',
    'Kelurahan' => 'Kelurahan Contoh',
    'Kecamatan' => 'Kecamatan Contoh',
    'Kota/Kabupaten' => 'Jakarta Selatan',
    'Provinsi' => 'DKI Jakarta',
    'Kode Pos' => '12345',
]
```

### Keluarga Template:
```php
[
    'Email User' => 'user@example.com',
    'Hubungan' => 'Ayah',
    'Nama' => 'John Doe',
    'Pekerjaan' => 'Wiraswasta',
    'Telepon' => '081234567890',
    'Tempat Lahir' => 'Jakarta',
    'Tanggal Lahir' => '1970-01-15',
    'Penghasilan' => '5000000',
    'Alamat' => 'Jl. Contoh',
]
```

### Pendidikan Template:
```php
[
    'Email User' => 'user@example.com',
    'Jenjang' => 'S1',
    'Nama Institusi' => 'Universitas Indonesia',
    'Jurusan' => 'Teknik Informatika',
    'Tahun Masuk' => '2018',
    'Tahun Lulus' => '2022',
    'IPK' => '3.75',
    'Alamat' => 'Depok, Jawa Barat',
]
```

### Role Template:
```php
[
    'Nama Role' => 'Mahasiswa',
    'Guard Name' => 'web',
    'Deskripsi' => 'Role untuk mahasiswa',
]
```

---

## ✅ Checklist

- [x] Alamat Export CSV
- [x] Alamat Export Excel
- [x] Alamat Export PDF
- [x] Alamat Import Excel
- [x] Alamat Template Download
- [x] Alamat Controller Updated
- [x] Alamat Routes Added
- [ ] Alamat View Updated
- [ ] Keluarga Export/Import (All)
- [ ] Pendidikan Export/Import (All)
- [ ] Role Export/Import (All)

**Total Progress: 7/28 tasks completed (25%)**

Silakan lanjutkan implementasi untuk modul lainnya mengikuti pattern yang sama!
