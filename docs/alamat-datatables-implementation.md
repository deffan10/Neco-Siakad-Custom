# Implementasi DataTables untuk Alamat Module

## 📋 Overview

Module Alamat sekarang menggunakan **Laravel Yajra DataTables** untuk menampilkan data dengan fitur:
- ✅ Server-side processing
- ✅ Ajax-based data loading
- ✅ Search & filter
- ✅ Sorting
- ✅ Pagination
- ✅ Export Excel, PDF, Print
- ✅ Responsive design
- ✅ Bahasa Indonesia

## 🗂️ File Structure

```
app/
├── DataTables/
│   └── Manager/
│       └── Users/
│           └── AlamatDataTable.php       # DataTable Class
├── Http/
│   └── Controllers/
│       └── Master/
│           └── Users/
│               └── AlamatController.php   # Controller dengan DataTable
resources/
└── views/
    └── master/
        └── users/
            └── alamat-index.blade.php     # View dengan DataTable render
```

## 📝 AlamatDataTable.php

### Features

#### 1. **Server-side Processing**
Data diambil langsung dari database secara dinamis saat user browse table.

```php
public function query(Alamat $model): QueryBuilder
{
    $query = $model->newQuery()->with(['user', 'deletedBy']);
    
    if ($this->isTrash) {
        $query->onlyTrashed();
    }
    
    return $query;
}
```

#### 2. **Custom Columns**
Setiap kolom di-customize dengan HTML dan logic:

```php
->addColumn('tipe', function ($row) {
    $badgeClass = $row->tipe == 'ktp' ? 'primary' : 'info';
    return '<span class="badge bg-' . $badgeClass . '">' . ucfirst($row->tipe) . '</span>';
})
```

#### 3. **Action Buttons**
Dynamic action buttons berdasarkan kondisi (trash atau tidak):

```php
->addColumn('action', function ($row) {
    if ($this->isTrash) {
        // Show Restore button
    } else {
        // Show Edit & Delete buttons
    }
})
```

#### 4. **Export Features**
Export ke Excel, PDF, dan Print:

```php
->buttons([
    Button::make('excel')->className('btn btn-sm btn-success'),
    Button::make('pdf')->className('btn btn-sm btn-danger'),
    Button::make('print')->className('btn btn-sm btn-info'),
])
```

#### 5. **Responsive & Mobile-Friendly**
```php
->responsive(true)
->autoWidth(false)
```

#### 6. **Bahasa Indonesia**
```php
'language' => [
    'emptyTable' => 'Tidak ada data alamat',
    'search' => 'Cari:',
    'paginate' => [
        'first' => 'Pertama',
        'last' => 'Terakhir',
        // ...
    ],
]
```

### Methods

#### `dataTable()`
Mengatur kolom-kolom dan logic untuk setiap kolom:
- `addIndexColumn()` - Auto numbering
- `addColumn()` - Custom column dengan HTML
- `rawColumns()` - Kolom yang boleh render HTML

#### `query()`
Query ke database dengan:
- Eager loading (`with()`)
- Kondisi trash (`onlyTrashed()`)

#### `html()`
Konfigurasi HTML table:
- Table ID
- Columns
- Buttons (Export)
- DOM layout
- Responsive
- Language

#### `getColumns()`
Definisi kolom-kolom table:
- `DT_RowIndex` - Auto numbering
- `tipe` - Badge untuk tipe alamat
- `pemilik` - Nama user pemilik
- `alamat` - Alamat (limited)
- `kota` - Kota/Kabupaten
- `deleted_by_name` - Siapa yang hapus (hanya di trash)
- `deleted_at_formatted` - Kapan dihapus (hanya di trash)
- `action` - Action buttons

#### `setTrash(bool)`
Set mode trash untuk mengubah kolom dan query:
```php
$dataTable->setTrash(true); // Mode trash
$dataTable->setTrash(false); // Mode normal
```

## 🎮 Controller Usage

### Index (Normal Mode)
```php
public function index(AlamatDataTable $dataTable)
{
    $data['is_trash'] = false;
    
    $dataTable->setTrash(false);
    return $dataTable->render('master.users.alamat-index', $data);
}
```

### Trash Mode
```php
public function trash(AlamatDataTable $dataTable)
{
    $data['is_trash'] = true;
    
    $dataTable->setTrash(true);
    return $dataTable->render('master.users.alamat-index', $data);
}
```

### Key Points:
- ✅ Inject `AlamatDataTable` di parameter
- ✅ Set trash mode dengan `setTrash()`
- ✅ Render dengan `render()`
- ✅ Pass data lain yang dibutuhkan view

## 🖼️ View Implementation

### Render DataTable
```blade
<!-- DataTable -->
<div class="mt-3">
    {{ $dataTable->table(['class' => 'table table-striped table-bordered dt-responsive nowrap', 'style' => 'width:100%']) }}
</div>
```

### Load Scripts
```blade
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    
    <script>
        // Custom JavaScript here
    </script>
@endpush
```

### Custom Styling
```css
/* DataTables Buttons Styling */
.dt-buttons {
    margin-bottom: 1rem;
}

.dt-buttons .btn {
    margin-right: 5px;
    margin-bottom: 5px;
}
```

## 🔧 Customization Guide

### 1. Menambah Kolom Baru

**Di AlamatDataTable.php:**

```php
// Di method dataTable()
->addColumn('provinsi', function ($row) {
    return $row->provinsi ?? '-';
})

// Di method getColumns()
Column::computed('provinsi')
    ->title('Provinsi')
    ->width(150),
```

### 2. Mengubah Export Columns

```php
'exportOptions' => [
    'columns' => [0, 1, 2, 3, 4] // Index kolom yang mau di-export
]
```

Atau exclude tertentu:
```php
'exportOptions' => [
    'columns' => ':not(.no-export)' // Exclude kolom dengan class no-export
]
```

### 3. Custom Filter

```php
public function query(Alamat $model): QueryBuilder
{
    return $model->newQuery()
        ->with(['user', 'deletedBy'])
        ->when(request('tipe'), function($q, $tipe) {
            $q->where('tipe', $tipe);
        });
}
```

### 4. Custom Order

```php
->orderBy(0, 'desc') // Order by kolom pertama descending
```

### 5. Page Length Options

```php
->parameters([
    'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
])
```

## 🎨 Styling

### Bootstrap 5 Integration
DataTables sudah terintegrasi dengan Bootstrap 5:
```php
use Yajra\DataTables\Html\Builder as HtmlBuilder;
```

### Custom CSS
```css
/* Responsive table */
@media screen and (max-width: 600px) {
    table.dataTable thead {
        display: none;
    }
    
    table.dataTable tbody td {
        display: block;
        text-align: right;
    }
    
    table.dataTable tbody td::before {
        content: attr(data-label);
        float: left;
        font-weight: bold;
    }
}
```

## 🚀 Performance

### Server-side Processing
- ✅ Data di-load secara AJAX
- ✅ Hanya load data yang ditampilkan
- ✅ Mendukung ribuan bahkan jutaan records
- ✅ Filtering dan sorting di server

### Eager Loading
```php
->with(['user', 'deletedBy']) // Menghindari N+1 problem
```

### Caching (Optional)
```php
public function query(Alamat $model): QueryBuilder
{
    return Cache::remember('alamat_datatable', 60, function() use ($model) {
        return $model->newQuery()->with(['user', 'deletedBy']);
    });
}
```

## 🔍 Search & Filter

### Global Search
Otomatis tersedia untuk semua kolom yang searchable:
```php
Column::make('alamat')->searchable(true)
```

### Column Search
```php
->parameters([
    'initComplete' => "function() {
        this.api().columns().every(function() {
            // Add column search
        });
    }",
])
```

## 📊 Export Features

### Excel Export
- Format: `.xlsx`
- Include: All columns except `.no-export`
- Styling: Auto

### PDF Export
- Format: `.pdf`
- Orientation: Landscape (for wide tables)
- Include: All columns except `.no-export`

### Print
- Clean print layout
- Hide action buttons
- Auto page breaks

## 🐛 Troubleshooting

### 1. DataTable not loading
**Problem:** Table tidak muncul atau error 500

**Solution:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Check error log
tail -f storage/logs/laravel.log
```

### 2. Buttons not showing
**Problem:** Export buttons tidak muncul

**Solution:**
```bash
# Install DataTables buttons package
npm install datatables.net-buttons-bs5
```

Atau gunakan CDN di view:
```html
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
```

### 3. Search not working
**Problem:** Search tidak filter data

**Solution:**
Pastikan kolom searchable:
```php
Column::make('alamat')
    ->searchable(true)
    ->orderable(true)
```

## 📚 References

- [Laravel DataTables Docs](https://yajrabox.com/docs/laravel-datatables)
- [DataTables.net](https://datatables.net/)
- [Yajra DataTables GitHub](https://github.com/yajra/laravel-datatables)

## 🎯 Best Practices

1. ✅ **Always use eager loading** untuk avoid N+1 queries
2. ✅ **Use rawColumns()** untuk kolom yang render HTML
3. ✅ **Set proper column widths** untuk better layout
4. ✅ **Use responsive mode** untuk mobile support
5. ✅ **Add proper indexing** di database untuk kolom yang sering di-search
6. ✅ **Limit text** di kolom yang panjang (gunakan Str::limit())
7. ✅ **Use icons** untuk better UX
8. ✅ **Add tooltips** untuk action buttons

## 🔐 Security

### XSS Protection
```php
// Escape HTML di kolom yang user-generated
->addColumn('alamat', function ($row) {
    return e(Str::limit($row->alamat_lengkap, 50));
})
```

### CSRF Protection
```php
// Otomatis di-handle oleh Laravel
csrf_field()
method_field('DELETE')
```

### Authorization
```php
public function query(Alamat $model): QueryBuilder
{
    return $model->newQuery()
        ->where('created_by', auth()->id()); // Only show user's data
}
```
