# User Profile Update - Complete Implementation

## Overview
Implementasi lengkap untuk update profile user dengan multi-role support, alamat (KTP & Domisili), pendidikan, dan keluarga menggunakan Select2 untuk multi-select role.

## Files Modified

### 1. **app/Services/Manager/Users/UserService.php**
**Purpose:** Enhanced service layer untuk handle comprehensive user profile updates

**Key Changes:**
- Enhanced `updateUser()` method dengan support untuk:
  - Password update dengan validasi password lama
  - Upload foto profile
  - Multi-role assignment dengan Spatie Permission
  - Update alamat KTP dan Domisili
  - Update/create pendidikan records
  - Update/create keluarga records
- Added `deletePendidikan()` method untuk hapus data pendidikan
- Added `deleteKeluarga()` method untuk hapus data keluarga
- Transaction management untuk semua operasi
- Proper error handling dengan Exception

**Features:**
```php
// Password Update
- Validates current password before update
- Requires password confirmation
- Secure hashing with bcrypt

// Photo Upload
- Stores in storage/app/public/photos/users
- Returns public URL path

// Multi-Role Support
- Accepts array of role IDs
- Syncs roles using Spatie syncRoles()
- Maintains existing roles if not provided

// Alamat Management
- Separate handling for KTP and Domisili
- Update existing or create new
- Preserves tipe field ('ktp' or 'domisili')

// Pendidikan & Keluarga
- Iterates through arrays
- Update by ID if exists, create if new
- Preserves relationships
```

### 2. **app/Http/Controllers/Master/Users/UsersController.php**
**Purpose:** Added endpoints untuk delete pendidikan dan keluarga

**New Methods:**
```php
public function deletePendidikan($id)
public function deleteKeluarga($id)
```

**Response Format:**
```json
{
    "success": true|false,
    "message": "Success or error message"
}
```

### 3. **routes/web.php**
**Purpose:** Added routes untuk delete operations

**New Routes:**
```php
Route::delete('/users/pendidikan/{id}', [UsersController::class, 'deletePendidikan'])
    ->name('users.delete-pendidikan');
    
Route::delete('/users/keluarga/{id}', [UsersController::class, 'deleteKeluarga'])
    ->name('users.delete-keluarga');
```

### 4. **resources/views/master/users/user-view.blade.php**
**Purpose:** Enhanced user profile view dengan Select2 multi-role

**Key Enhancements:**

#### A. Multi-Role Select with Select2
```blade
<div class="col-md-6 mb-3">
    <label class="form-label">Role <span class="text-danger">*</span></label>
    <select class="form-select select2-roles" name="roles[]" multiple="multiple" required>
        @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ $users->hasRole($role->name) ? 'selected' : '' }}>
                {{ ucfirst($role->name) }}
            </option>
        @endforeach
    </select>
    <small class="text-muted">Pilih satu atau lebih role untuk user</small>
</div>
```

**Features:**
- Multiple selection support
- Pre-select existing user roles
- Bootstrap 5 theme styling
- Required validation

#### B. Select2 Initialization
```javascript
function initializeSelect2() {
    if (jQuery('.select2-roles').length > 0) {
        jQuery('.select2-roles').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Role',
            allowClear: false,
            width: '100%',
            closeOnSelect: false
        });
    }
}
```

#### C. AJAX Delete for Pendidikan & Keluarga
```javascript
// Updated routes to use /${activeRole}/users/pendidikan/${id}
// Updated routes to use /${activeRole}/users/keluarga/${id}
```

## Data Flow

### Update User Profile Flow
```
1. User submits form from user-view.blade.php
   ↓
2. UserRequest validates input
   ↓
3. UsersController::update() receives validated data
   ↓
4. UserService::updateUser() processes:
   - Password validation & hashing
   - Photo upload & storage
   - Role synchronization
   - Alamat KTP/Domisili update/create
   - Pendidikan records update/create
   - Keluarga records update/create
   ↓
5. DB transaction commits all changes
   ↓
6. Success alert & redirect back
```

### Delete Pendidikan/Keluarga Flow
```
1. User clicks delete button (SweetAlert confirmation)
   ↓
2. JavaScript sends AJAX DELETE request
   ↓
3. UsersController::deletePendidikan() or ::deleteKeluarga()
   ↓
4. UserService::deletePendidikan() or ::deleteKeluarga()
   ↓
5. Soft delete record
   ↓
6. Return JSON response
   ↓
7. JavaScript removes DOM element & shows success
```

## Form Structure

### User Profile Form Tabs

1. **Biodata**
   - Nama, Username, Role (Multi-Select)
   - Foto Profile
   - Jenis Kelamin
   - Tempat & Tanggal Lahir
   - Agama, Golongan Darah
   - Kewarganegaraan
   - Tinggi & Berat Badan

2. **Kontak & Sosial Media**
   - Email, Phone
   - Instagram, Facebook, LinkedIn

3. **Identitas**
   - Nomor KK, KTP, NPWP

4. **Alamat**
   - Alamat KTP (full fields)
   - Alamat Domisili (full fields)
   - Checkbox: "Sama dengan alamat KTP"

5. **Pendidikan**
   - Dynamic array of education records
   - Add/Remove functionality
   - Fields: Jenjang, Institusi, Jurusan, Tahun, IPK, Alamat

6. **Keluarga**
   - Dynamic array of family records
   - Add/Remove functionality
   - Fields: Hubungan, Nama, Pekerjaan, Telepon, TTL, Penghasilan, Alamat

7. **Keamanan**
   - Password Change (old + new + confirm)
   - First Time Setup toggle
   - Two Factor Auth toggle

## Validation Rules

### Password Update
```php
// In UserService::updateUser()
if (isset($data['current_password'])) {
    // Verify current password
    if (!Hash::check($data['current_password'], $user->password)) {
        throw new Exception('Password lama tidak sesuai.');
    }
    
    // Validate new password
    if (empty($data['new_password'])) {
        throw new Exception('Password baru wajib diisi.');
    }
    
    // Validate confirmation
    if ($data['new_password'] !== $data['new_password_confirmation']) {
        throw new Exception('Konfirmasi password baru tidak sesuai.');
    }
}
```

### Role Validation
```php
// Roles must be array of valid role IDs
$roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
$user->syncRoles($roles);
```

## Usage Examples

### Update Basic Profile
```php
POST /admin/users/123/update
{
    "name": "John Doe",
    "username": "johndoe",
    "roles": [1, 3], // Multiple roles
    "email": "john@example.com",
    "phone": "08123456789"
}
```

### Update with Password
```php
POST /admin/users/123/update
{
    "current_password": "old_password",
    "new_password": "new_password123",
    "new_password_confirmation": "new_password123",
    "name": "John Doe"
}
```

### Update with Photo
```php
POST /admin/users/123/update
FormData: {
    "photo": File,
    "name": "John Doe"
}
```

### Update Alamat
```php
POST /admin/users/123/update
{
    "alamat_ktp": {
        "id": 456, // Optional, for update
        "alamat_lengkap": "Jl. Example No. 123",
        "rt": "001",
        "rw": "002",
        "kelurahan": "Kebayoran",
        "kecamatan": "Kebayoran Baru",
        "kota_kabupaten": "Jakarta Selatan",
        "provinsi": "DKI Jakarta",
        "kode_pos": "12345"
    },
    "alamat_domisili": {
        // Same structure
    }
}
```

### Add New Pendidikan
```php
POST /admin/users/123/update
{
    "pendidikan": [
        {
            // Existing record
            "id": 789,
            "jenjang": "S1",
            "nama_institusi": "Universitas Indonesia",
            "jurusan": "Teknik Informatika",
            "tahun_masuk": 2018,
            "tahun_lulus": 2022,
            "ipk": "3.75"
        },
        {
            // New record (no id)
            "jenjang": "S2",
            "nama_institusi": "Institut Teknologi Bandung",
            "jurusan": "Magister Informatika",
            "tahun_masuk": 2023
        }
    ]
}
```

### Delete Pendidikan via AJAX
```javascript
fetch('/admin/users/pendidikan/789', {
    method: 'DELETE',
    headers: {
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
    }
})
```

## Security Features

1. **CSRF Protection** - All forms include @csrf token
2. **Password Hashing** - bcrypt via Hash::make()
3. **Password Verification** - Hash::check() before update
4. **Role Permission** - Spatie Permission integration
5. **Transaction Safety** - DB::transaction() for data integrity
6. **Validation** - UserRequest handles input validation
7. **Authorization** - Controller middleware checks permissions

## Testing Checklist

- [ ] Load user profile page
- [ ] Select multiple roles via Select2
- [ ] Upload profile photo
- [ ] Update basic information
- [ ] Change password with correct old password
- [ ] Try change password with wrong old password (should fail)
- [ ] Update alamat KTP
- [ ] Copy KTP to Domisili using checkbox
- [ ] Add new pendidikan record
- [ ] Update existing pendidikan
- [ ] Delete pendidikan (with confirmation)
- [ ] Add new keluarga record
- [ ] Update existing keluarga
- [ ] Delete keluarga (with confirmation)
- [ ] Toggle security settings
- [ ] Submit form and verify all data saved
- [ ] Check role synchronization in database
- [ ] Verify transaction rollback on error

## Dependencies

- **Select2 4.1.0** - Multi-select dropdown
- **jQuery** - DOM manipulation & AJAX
- **SweetAlert2** - User confirmations & alerts
- **Bootstrap 5** - UI framework
- **Spatie Laravel Permission** - Role & permission management
- **Laravel File Storage** - Photo upload handling

## Notes

- All updates wrapped in DB transactions for data integrity
- Soft deletes for pendidikan and keluarga (can be restored)
- Photo stored in `storage/app/public/photos/users/`
- Select2 initialized on DOMContentLoaded
- AJAX deletes use SweetAlert for confirmation
- Role sync preserves other assigned roles if not in update array
- Password update is optional (only if current_password provided)

## Future Enhancements

1. Add photo cropper for profile pictures
2. Add validation for maximum file size
3. Add image compression before upload
4. Add bulk delete for pendidikan/keluarga
5. Add export profile to PDF
6. Add profile completion percentage
7. Add email verification on email change
8. Add activity log for profile changes
