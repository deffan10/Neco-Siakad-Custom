# Struktur Alamat Module - Service Layer & Repository Pattern

## Arsitektur

Modul Alamat menggunakan arsitektur layered dengan pemisahan tanggung jawab yang jelas:

```
┌─────────────────────┐
│   Controller        │ → Menangani HTTP Request/Response
├─────────────────────┤
│   Request           │ → Validasi Input
├─────────────────────┤
│   Service           │ → Business Logic & Transaction
├─────────────────────┤
│   Repository        │ → Data Access Layer
├─────────────────────┤
│   Model             │ → Eloquent Model
└─────────────────────┘
```

## File Structure

### 1. Controller
**Path:** `/app/Http/Controllers/Master/Users/AlamatController.php`

**Tanggung Jawab:**
- Menerima HTTP request
- Memanggil Service layer
- Mengembalikan response (view atau redirect)
- Menampilkan flash messages (SweetAlert)

**Contoh:**
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

### 2. Form Request
**Path:** `/app/Http/Requests/AlamatRequest.php`

**Tanggung Jawab:**
- Validasi input data
- Custom validation messages
- Authorization logic

**Rules:**
- `tipe`: required, in:ktp,domisili
- `alamat_lengkap`: required, string
- `user_id`: required, integer, exists:users,id
- `kelurahan`, `kecamatan`, `kota_kabupaten`, `provinsi`: nullable, string, max:255
- `kode_pos`: nullable, string, max:10
- `rt`, `rw`: nullable, string, max:10

### 3. Service Layer
**Path:** `/app/Services/AlamatService.php`

**Tanggung Jawab:**
- Business logic
- Database transactions
- Koordinasi antar repositories
- Menangani operasi kompleks

**Methods:**
- `getAllAlamat()`: Mengambil semua data alamat
- `getTrashedAlamat()`: Mengambil data alamat yang dihapus (soft delete)
- `createAlamat(array $data)`: Membuat alamat baru dengan transaction
- `updateAlamat(int $id, array $data)`: Update alamat dengan transaction
- `deleteAlamat(int $id)`: Soft delete alamat
- `restoreAlamat(int $id)`: Restore alamat yang dihapus

**Fitur:**
- Menggunakan DB Transaction untuk data integrity
- Otomatis mencatat created_by, updated_by, deleted_by
- Exception handling

### 4. Repository Layer
**Path:** `/app/Repositories/AlamatRepository.php`

**Tanggung Jawab:**
- Data access layer
- Query ke database
- CRUD operations
- Eloquent queries

**Methods:**
- `getAll()`: Query dengan eager loading user relation
- `getTrashed()`: Query untuk soft deleted records
- `findById(int $id)`: Find by ID dengan exception jika tidak ada
- `findTrashedById(int $id)`: Find trashed record by ID
- `create(array $data)`: Create record
- `update(Alamat $alamat, array $data)`: Update record
- `delete(Alamat $alamat)`: Soft delete
- `restore(Alamat $alamat)`: Restore deleted record

### 5. Model
**Path:** `/app/Models/User/Alamat.php`

**Fitur:**
- SoftDeletes trait
- Relations: user, createdBy, updatedBy, deletedBy
- Accessor: getTipeDisplayAttribute()

## Keuntungan Arsitektur Ini

### 1. Separation of Concerns
- **Controller:** Hanya handle HTTP
- **Request:** Hanya validasi
- **Service:** Business logic
- **Repository:** Data access

### 2. Testability
- Setiap layer bisa di-mock dan di-test secara independen
- Unit testing lebih mudah
- Integration testing lebih jelas

### 3. Reusability
- Service bisa dipanggil dari controller lain
- Repository bisa dipanggil dari service lain
- Validasi bisa digunakan kembali

### 4. Maintainability
- Code lebih terorganisir
- Mudah mencari bug
- Mudah menambah fitur baru

### 5. Flexibility
- Mudah mengganti implementation (misal: dari Eloquent ke Query Builder)
- Mudah menambah caching layer
- Mudah integrasi dengan external service

## Contoh Penggunaan

### Create Alamat
```php
// Controller
public function store(AlamatRequest $request)
{
    $this->alamatService->createAlamat($request->validated());
}

// Service (dengan transaction)
public function createAlamat(array $data): Alamat
{
    return DB::transaction(function () use ($data) {
        $data['created_by'] = Auth::id();
        return $this->alamatRepository->create($data);
    });
}

// Repository
public function create(array $data): Alamat
{
    return Alamat::create($data);
}
```

### Update Alamat
```php
// Controller
public function update(AlamatRequest $request, $id)
{
    $this->alamatService->updateAlamat($id, $request->validated());
}

// Service (dengan transaction)
public function updateAlamat(int $id, array $data): Alamat
{
    return DB::transaction(function () use ($id, $data) {
        $alamat = $this->alamatRepository->findById($id);
        $data['updated_by'] = Auth::id();
        $this->alamatRepository->update($alamat, $data);
        return $alamat->fresh();
    });
}

// Repository
public function update(Alamat $alamat, array $data): bool
{
    return $alamat->update($data);
}
```

## Database Transaction

Semua operasi write (create, update, delete, restore) dibungkus dalam database transaction untuk memastikan data consistency:

```php
DB::transaction(function () {
    // Multiple database operations
    // Jika error, akan rollback otomatis
});
```

## Auditing

Setiap perubahan data dicatat:
- **created_by**: User yang membuat record
- **updated_by**: User yang terakhir update
- **deleted_by**: User yang menghapus (soft delete)

## Best Practices

1. **Selalu gunakan Form Request** untuk validasi
2. **Service layer harus handle business logic**, bukan di controller
3. **Repository hanya query**, tidak ada business logic
4. **Gunakan DB Transaction** untuk operasi yang melibatkan multiple queries
5. **Type hinting** untuk semua method parameters dan return types
6. **Exception handling** di controller layer
7. **Eager loading** di repository untuk menghindari N+1 problem

## Extension Points

Jika ingin menambah fitur:

1. **Tambah validasi** → Edit AlamatRequest
2. **Tambah business logic** → Edit AlamatService
3. **Tambah query khusus** → Edit AlamatRepository
4. **Tambah endpoint** → Edit AlamatController + tambah route

## Testing Strategy

```php
// Unit Test - Service
public function test_create_alamat_sets_created_by()
{
    $service = new AlamatService(new AlamatRepository());
    // Mock repository, assert created_by is set
}

// Integration Test - Controller
public function test_store_creates_alamat_and_redirects()
{
    $response = $this->post('/alamat', $data);
    $this->assertDatabaseHas('alamats', $data);
}

// Feature Test - End to End
public function test_user_can_create_alamat()
{
    $this->actingAs($user)
         ->post('/alamat', $data)
         ->assertRedirect()
         ->assertSessionHas('success');
}
```
