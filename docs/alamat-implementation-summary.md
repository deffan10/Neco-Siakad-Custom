# Summary: Implementasi Service-Repository Pattern untuk Alamat Module

## ✅ File yang Dibuat

### 1. **AlamatRequest.php** (Form Request)
📁 Path: `/app/Http/Requests/AlamatRequest.php`

**Fungsi:**
- Validasi input dari user
- Custom error messages dalam Bahasa Indonesia
- Aturan validasi yang konsisten untuk create dan update

**Validasi Rules:**
- `tipe`: wajib, hanya ktp atau domisili
- `alamat_lengkap`: wajib
- `user_id`: wajib, harus exists di table users
- Field opsional: kelurahan, kecamatan, kota_kabupaten, provinsi, kode_pos, rt, rw

### 2. **AlamatRepository.php** (Data Access Layer)
📁 Path: `/app/Repositories/AlamatRepository.php`

**Fungsi:**
- Menangani semua operasi database
- Query optimization dengan eager loading
- Abstraksi dari Eloquent Model

**Methods:**
- `getAll()` - List semua alamat dengan relasi user
- `getTrashed()` - List alamat yang soft deleted
- `findById($id)` - Cari alamat by ID
- `findTrashedById($id)` - Cari alamat yang deleted by ID
- `create($data)` - Insert alamat baru
- `update($alamat, $data)` - Update alamat
- `delete($alamat)` - Soft delete
- `restore($alamat)` - Restore dari soft delete

### 3. **AlamatService.php** (Business Logic Layer)
📁 Path: `/app/Services/AlamatService.php`

**Fungsi:**
- Business logic dan orchestration
- Database transactions untuk data integrity
- Automatic auditing (created_by, updated_by, deleted_by)

**Methods:**
- `getAllAlamat()` - Get all dengan eager loading
- `getTrashedAlamat()` - Get soft deleted records
- `createAlamat($data)` - Create dengan transaction + audit
- `updateAlamat($id, $data)` - Update dengan transaction + audit
- `deleteAlamat($id)` - Soft delete + audit
- `restoreAlamat($id)` - Restore + audit

**Features:**
- Semua write operations dalam DB Transaction
- Otomatis set created_by, updated_by, deleted_by dari Auth::id()
- Exception handling

### 4. **AlamatController.php** (Refactored)
📁 Path: `/app/Http/Controllers/Master/Users/AlamatController.php`

**Perubahan:**
- ✅ Hapus semua validasi inline → Pindah ke AlamatRequest
- ✅ Hapus semua query database → Pindah ke Service & Repository
- ✅ Hapus business logic → Pindah ke Service
- ✅ Controller jadi lebih slim dan fokus pada HTTP handling

**Code Reduction:**
- Before: ~250 lines (dengan validasi dan query inline)
- After: ~100 lines (clean dan focused)

## 📊 Perbandingan Before & After

### Before (Monolithic)
```php
public function store(Request $request)
{
    // Validasi inline 15+ baris
    $request->validate([...], [...]);
    
    // Business logic + query langsung
    $user = Auth::user();
    $checkUser = User::where('id', $request->user_id)->first();
    
    Alamat::create([
        'tipe' => $request->tipe,
        'alamat_lengkap' => $request->alamat_lengkap,
        // ... 10+ fields
        'created_by' => $user->id
    ]);
}
```

### After (Layered)
```php
public function store(AlamatRequest $request)
{
    try {
        $this->alamatService->createAlamat($request->validated());
        Alert::success('Berhasil', 'Data alamat berhasil ditambahkan');
        return redirect()->back();
    } catch (\Throwable $th) {
        Alert::error('Error', 'Terjadi kesalahan: ' . $th->getMessage());
        return redirect()->back();
    }
}
```

## 🎯 Keuntungan

### 1. **Separation of Concerns**
- Controller: HTTP Request/Response
- Request: Validation
- Service: Business Logic
- Repository: Data Access

### 2. **Reusability**
```php
// Service bisa dipanggil dari mana saja
$alamatService->createAlamat($data); // dari Controller
$alamatService->createAlamat($data); // dari Command
$alamatService->createAlamat($data); // dari Job
```

### 3. **Testability**
```php
// Mock repository di service test
$mockRepo = Mockery::mock(AlamatRepository::class);
$service = new AlamatService($mockRepo);

// Mock service di controller test
$mockService = Mockery::mock(AlamatService::class);
$controller = new AlamatController($mockService);
```

### 4. **Maintainability**
- Bug di validasi? → Edit AlamatRequest
- Bug di query? → Edit AlamatRepository
- Bug di business logic? → Edit AlamatService
- Bug di HTTP handling? → Edit AlamatController

### 5. **Scalability**
```php
// Mudah tambah caching
class AlamatService {
    public function getAllAlamat() {
        return Cache::remember('alamats', 3600, function() {
            return $this->repository->getAll();
        });
    }
}

// Mudah tambah logging
class AlamatService {
    public function createAlamat($data) {
        $result = DB::transaction(function() use ($data) {
            return $this->repository->create($data);
        });
        
        Log::info('Alamat created', ['id' => $result->id]);
        return $result;
    }
}
```

## 🚀 Cara Penggunaan

### Register Service & Repository di Provider
Tambahkan di `app/Providers/AppServiceProvider.php`:

```php
public function register()
{
    $this->app->bind(AlamatRepository::class, AlamatRepository::class);
    $this->app->bind(AlamatService::class, AlamatService::class);
}
```

### Dependency Injection di Controller
```php
class AlamatController extends Controller
{
    protected AlamatService $alamatService;

    public function __construct(AlamatService $alamatService)
    {
        $this->alamatService = $alamatService;
    }
}
```

Laravel akan otomatis inject dependency ini!

## 📝 Next Steps

### 1. Testing
Buat unit test untuk setiap layer:
- `tests/Unit/Repositories/AlamatRepositoryTest.php`
- `tests/Unit/Services/AlamatServiceTest.php`
- `tests/Feature/Controllers/AlamatControllerTest.php`

### 2. Caching (Optional)
Tambah caching di Service layer untuk improve performance

### 3. Events & Listeners (Optional)
```php
// Di AlamatService
event(new AlamatCreated($alamat));
event(new AlamatUpdated($alamat));
event(new AlamatDeleted($alamat));
```

### 4. API Resource (Optional)
Jika perlu API:
```php
class AlamatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tipe' => $this->tipe,
            'alamat_lengkap' => $this->alamat_lengkap,
            // ...
        ];
    }
}
```

### 5. Apply ke Module Lain
Pattern ini bisa diterapkan ke module lain:
- User Management
- Mahasiswa
- Akademik
- dll

## 📚 Resources

- [Laravel Service Layer Pattern](https://laravel.com/docs/11.x/container)
- [Repository Pattern in Laravel](https://dev.to/ashallendesign/using-the-repository-pattern-in-laravel-4805)
- [Form Request Validation](https://laravel.com/docs/11.x/validation#form-request-validation)

## 🎉 Kesimpulan

Sekarang kode Alamat Module lebih:
- ✅ Clean & Organized
- ✅ Easy to Test
- ✅ Easy to Maintain
- ✅ Reusable
- ✅ Scalable
- ✅ Follow Best Practices
