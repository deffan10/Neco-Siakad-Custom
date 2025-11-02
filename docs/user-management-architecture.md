# User Management Module Architecture

## Overview
Implementasi modul User Management mengikuti arsitektur yang sama dengan modul Alamat, menggunakan pola Repository-Service dengan Laravel DataTables untuk tampilan data yang efisien.

## Struktur Arsitektur

### 1. Controller Layer
**File:** `app/Http/Controllers/Master/Users/UsersController.php`

Controller bertindak sebagai orchestrator yang menghubungkan request dengan service layer. Menggunakan dependency injection untuk UserService dan UserDataTable.

**Metode Utama:**
- `index(UserDataTable $dataTable)` - Menampilkan daftar user aktif
- `trash(UserDataTable $dataTable)` - Menampilkan daftar user yang dihapus
- `store(UserRequest $request)` - Membuat user baru
- `update(UserRequest $request, $id)` - Update data user
- `destroy($id)` - Soft delete user
- `restore($id)` - Restore user yang dihapus
- `forceDelete($id)` - Hapus permanen user
- `show($id)` - Detail user

### 2. Service Layer
**File:** `app/Services/Manager/Users/UserService.php`

Service layer menangani business logic dan orchestration. Semua operasi database dibungkus dalam DB transaction untuk memastikan data consistency.

**Tanggung Jawab:**
- Business logic validation
- Transaction management
- Orchestrasi operasi kompleks (create user + assign roles + send email)
- Integration dengan repository layer

**Fitur Khusus:**
- Auto-generate username dari phone jika tidak diisi
- Auto-generate unique code untuk setiap user
- Hash password otomatis
- Assign roles menggunakan Spatie Permission
- Kirim welcome email setelah user dibuat
- Soft delete dengan cascade ke relasi (alamats, keluargas, pendidikans)
- Restore dengan cascade restore relasi

### 3. Repository Layer
**File:** `app/Repositories/Manager/Users/UserRepository.php`

Repository layer menangani semua interaksi dengan database. Memisahkan database logic dari business logic.

**Metode:**
- `getAll()` - Get semua user dengan eager loading roles
- `getTrashed()` - Get user yang dihapus dengan eager loading roles dan deletedBy
- `findById($id)` - Find user by ID
- `findTrashedById($id)` - Find deleted user by ID
- `create($data)` - Create user baru
- `update($user, $data)` - Update user
- `delete($user)` - Soft delete
- `restore($user)` - Restore deleted user
- `forceDelete($user)` - Force delete permanen

### 4. Request Validation Layer
**File:** `app/Http/Requests/Manager/Users/UserRequest.php`

Form Request untuk validasi data user dengan custom error messages dalam bahasa Indonesia.

**Validasi:**
- `name` - Required, string, max 255
- `email` - Required, unique, email format, max 255
- `phone` - Required, unique, max 20
- `username` - Optional, unique, max 255
- `password` - Optional untuk update, min 8, confirmed
- `roles` - Required, array, exists in roles table

**Fitur:**
- Dynamic unique validation (ignore current user saat update)
- Custom error messages dalam bahasa Indonesia

### 5. DataTables Layer
**File:** `app/DataTables/Manager/Users/UserDataTable.php`

Laravel DataTables untuk menampilkan data dengan fitur server-side processing, filtering, sorting, dan export.

**Fitur:**
- Server-side processing untuk performa optimal
- Custom column rendering (HTML badges untuk roles)
- Searchable columns dengan custom filter logic
- Export ke Excel, PDF, Print
- Responsive design dengan data-label attributes
- Trash mode untuk menampilkan deleted records
- Action buttons (View, Delete, Restore, Force Delete)
- Localization (bahasa Indonesia)

**Kolom:**
- No (auto increment)
- Nama (dengan username)
- Email
- No Telepon
- Roles (badges)
- Dihapus Oleh (trash mode only)
- Dihapus Pada (trash mode only)
- Aksi (View/Delete atau Restore/Force Delete)

### 6. View Layer
**File:** `resources/views/master/users/user-index.blade.php`

Blade view dengan integrasi DataTables, filtering, dan form management.

**Fitur:**
- Statistics cards (Total Users, Active Users, Deleted Users, Total Admins)
- Collapsible form untuk tambah user
- Advanced filters:
  - Filter by Role
  - Filter by Name
  - Quick Search (global)
- DataTables integration
- Select2 untuk multi-select roles
- Responsive design dengan mobile-friendly table
- SweetAlert untuk notifikasi
- Bootstrap 5 styling

## Data Flow

### Create User Flow
```
1. User submit form → UserRequest validation
2. Controller → UserService::createUser()
3. Service → DB::transaction start
4. Service → Generate code & username
5. Service → Hash password
6. Service → UserRepository::create()
7. Service → Assign roles via Spatie Permission
8. Service → Send welcome email
9. Service → DB::transaction commit
10. Controller → Redirect with success message
```

### Update User Flow
```
1. User submit form → UserRequest validation
2. Controller → UserService::updateUser()
3. Service → DB::transaction start
4. Service → UserRepository::findById()
5. Service → Hash password (jika ada)
6. Service → UserRepository::update()
7. Service → Sync roles via Spatie Permission
8. Service → DB::transaction commit
9. Controller → Redirect with success message
```

### Delete User Flow
```
1. User click delete → Confirmation dialog
2. Controller → UserService::deleteUser()
3. Service → Check tidak boleh delete diri sendiri
4. Service → DB::transaction start
5. Service → Set deleted_by
6. Service → Soft delete user
7. Service → Cascade soft delete (alamats, keluargas, pendidikans)
8. Service → DB::transaction commit
9. Controller → Redirect with success message
```

### Restore User Flow
```
1. User click restore
2. Controller → UserService::restoreUser()
3. Service → DB::transaction start
4. Service → UserRepository::findTrashedById()
5. Service → Clear deleted_by
6. Service → Restore user
7. Service → Cascade restore relasi
8. Service → DB::transaction commit
9. Controller → Redirect with success message
```

## Best Practices Implemented

1. **Separation of Concerns**
   - Controller hanya handle HTTP
   - Service handle business logic
   - Repository handle database operations

2. **Transaction Management**
   - Semua operasi multi-step dibungkus dalam transaction
   - Rollback otomatis jika ada error

3. **Validation**
   - Form Request untuk validasi terpusat
   - Custom error messages
   - Dynamic unique validation

4. **Security**
   - Password auto-hashed
   - Prevent self-deletion
   - Authorization check via middleware

5. **User Experience**
   - Server-side DataTables untuk performa
   - Real-time filtering
   - Responsive design
   - Loading states
   - Informative error messages

6. **Code Quality**
   - Type hints untuk parameter dan return values
   - PHPDoc comments
   - Laravel Pint formatted
   - Consistent naming conventions

## Dependencies

- Laravel 12
- Laravel DataTables (yajra/laravel-datatables)
- Spatie Laravel Permission
- SweetAlert
- Select2
- Bootstrap 5
- Font Awesome

## Files Created/Modified

### New Files:
1. `app/DataTables/Manager/Users/UserDataTable.php`
2. `app/Services/Manager/Users/UserService.php`
3. `app/Repositories/Manager/Users/UserRepository.php`
4. `app/Http/Requests/Manager/Users/UserRequest.php`
5. `docs/user-management-architecture.md`

### Modified Files:
1. `app/Http/Controllers/Master/Users/UsersController.php`
2. `resources/views/master/users/user-index.blade.php`

## Migration Notes

Jika migrasi dari implementasi lama:
1. Backup database terlebih dahulu
2. Controller methods sudah backward compatible
3. View tetap support import/export existing
4. Routes tidak berubah
5. Export/Import services tetap berfungsi

## Future Improvements

1. Add unit tests untuk Service layer
2. Add feature tests untuk Controller
3. Implement caching untuk frequently accessed data
4. Add bulk operations (bulk delete, bulk assign roles)
5. Add audit log untuk perubahan data user
6. Implement real-time notifications
7. Add user activity tracking

## Comparison with Alamat Module

| Feature | User Module | Alamat Module |
|---------|-------------|---------------|
| DataTable | ✓ | ✓ |
| Repository | ✓ | ✓ |
| Service | ✓ | ✓ |
| Form Request | ✓ | ✓ |
| Soft Delete | ✓ | ✓ |
| Trash View | ✓ | ✓ |
| Force Delete | ✓ | ✓ |
| Restore | ✓ | ✓ |
| Advanced Filters | ✓ | ✓ |
| Export | ✓ (existing) | ✓ |
| Transaction | ✓ | ✓ |
| Validation | Form Request | Form Request |
| Email Notification | ✓ | ✗ |
| Role Management | ✓ | ✗ |
| Cascade Delete | ✓ (3 relations) | ✗ |

## Kesimpulan

Modul User Management telah berhasil diimplementasikan dengan arsitektur yang konsisten dengan modul Alamat. Implementasi ini mengikuti best practices Laravel, menggunakan design patterns yang sesuai, dan menyediakan user experience yang baik dengan performa optimal.
