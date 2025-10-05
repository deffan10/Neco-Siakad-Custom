# Fitur Search & Filter di DataTables Alamat

## 🔍 Cara Menggunakan Fitur Search

### 1️⃣ **Global Search (Bawaan DataTables)**

**Lokasi:** Pojok kanan atas table, box dengan label "Cari:"

**Cara Pakai:**
```
1. Ketik kata kunci di search box
2. DataTables otomatis filter semua kolom
3. Hasil muncul real-time (tanpa refresh)
```

**Contoh:**
- Ketik "Jakarta" → Cari di semua kolom (alamat, kota, dll)
- Ketik "John" → Cari nama pemilik
- Ketik "ktp" → Cari tipe alamat

**Fitur:**
- ✅ Case-insensitive (huruf besar/kecil sama saja)
- ✅ Real-time search (instant)
- ✅ Search di semua kolom sekaligus
- ✅ Support partial match ("jak" akan cocok dengan "Jakarta")

---

### 2️⃣ **Advanced Filter (Custom)**

**Lokasi:** Di atas table, ada 3 filter box dengan background abu-abu

#### A. Filter Tipe
**Dropdown untuk filter berdasarkan tipe alamat**

```
Pilihan:
- Semua Tipe (default - tampilkan semua)
- KTP (hanya alamat KTP)
- Domisili (hanya alamat domisili)
```

**Cara Pakai:**
1. Klik dropdown "Filter Tipe"
2. Pilih tipe yang diinginkan
3. Table otomatis filter

**Use Case:**
- Mau lihat hanya alamat KTP → Pilih "KTP"
- Mau lihat hanya alamat Domisili → Pilih "Domisili"
- Mau lihat semua → Pilih "Semua Tipe"

#### B. Filter Pemilik
**Dropdown untuk filter berdasarkan pemilik alamat**

```
Pilihan:
- Semua Pemilik (default)
- [List nama user dari database]
```

**Cara Pakai:**
1. Klik dropdown "Filter Pemilik"
2. Pilih nama user
3. Table otomatis tampilkan alamat user tersebut

**Use Case:**
- Mau lihat alamat milik "John Doe" → Pilih "John Doe"
- Mau lihat alamat semua user → Pilih "Semua Pemilik"

#### C. Cepat Cari (Quick Search)
**Input box untuk pencarian cepat di seluruh table**

**Cara Pakai:**
1. Ketik kata kunci di box "Cepat Cari"
2. Table otomatis filter real-time

**Kelebihan vs Global Search:**
- Interface lebih prominent (menonjol)
- Icon search yang jelas
- Border kiri biru untuk highlight

---

### 3️⃣ **Column-Specific Search (Backend)**

**Fitur di balik layar yang membuat search lebih akurat**

#### Kolom yang Searchable:

**1. Tipe** (Column 1)
```php
// Search di field: tipe
// Contoh: "ktp", "domisili"
```

**2. Pemilik** (Column 2)
```php
// Search di:
// - user.name (nama user)
// - user.email (email user)
// Contoh: "john", "john@example.com"
```

**3. Alamat** (Column 3)
```php
// Search di field: alamat_lengkap
// Contoh: "Jl. Sudirman", "RT 01"
```

**4. Kota** (Column 4)
```php
// Search di:
// - kota_kabupaten
// - provinsi
// - kecamatan
// - kelurahan
// Contoh: "Jakarta", "Kebayoran", "DKI Jakarta"
```

---

## 🎯 Kombinasi Search & Filter

Anda bisa **combine** berbagai metode search untuk hasil yang lebih spesifik!

### Contoh Use Cases:

#### **Scenario 1: Cari alamat KTP di Jakarta**
```
1. Filter Tipe: Pilih "KTP"
2. Quick Search: Ketik "Jakarta"
→ Hasil: Hanya alamat KTP yang ada kata "Jakarta"
```

#### **Scenario 2: Cari alamat John Doe yang di Bandung**
```
1. Filter Pemilik: Pilih "John Doe"
2. Quick Search: Ketik "Bandung"
→ Hasil: Hanya alamat John Doe di Bandung
```

#### **Scenario 3: Cari alamat domisili dengan kode pos tertentu**
```
1. Filter Tipe: Pilih "Domisili"
2. Quick Search: Ketik "12345"
→ Hasil: Alamat domisili dengan kode pos 12345
```

---

## 💡 Tips & Tricks

### 1. **Clear All Filters**
Untuk reset semua filter:
```
1. Filter Tipe: Pilih "Semua Tipe"
2. Filter Pemilik: Pilih "Semua Pemilik"
3. Quick Search: Hapus text
4. Global Search: Hapus text
```

### 2. **Partial Search**
Tidak perlu ketik kata lengkap:
```
✅ "jak" → Cocok dengan "Jakarta"
✅ "john" → Cocok dengan "John Doe"
✅ "sudirman" → Cocok dengan "Jl. Sudirman No. 123"
```

### 3. **Multi-word Search**
Search dengan beberapa kata:
```
"Jakarta Selatan" → Cari yang mengandung "Jakarta" DAN "Selatan"
```

### 4. **Search by Email**
Karena search pemilik include email:
```
Ketik email di Quick Search → Akan filter by email
Contoh: "john@example.com"
```

### 5. **Search by Kecamatan/Kelurahan**
Search kota juga mencari di wilayah:
```
"Kebayoran" → Akan cocok dengan kecamatan Kebayoran
"Senayan" → Akan cocok dengan kelurahan Senayan
```

---

## 🔧 Technical Details

### Filter Implementation

#### Frontend (JavaScript)
```javascript
// Filter Tipe - Search kolom index 1
$('#filter-tipe').on('change', function() {
    table.column(1).search(this.value).draw();
});

// Filter Pemilik - Search kolom index 2
$('#filter-user').on('change', function() {
    table.column(2).search(this.value).draw();
});

// Quick Search - Global search
$('#quick-search').on('keyup', function() {
    table.search(this.value).draw();
});
```

#### Backend (PHP - DataTable Class)
```php
// Custom filter untuk kolom Pemilik
->filterColumn('pemilik', function($query, $keyword) {
    $query->whereHas('user', function($q) use ($keyword) {
        $q->where('name', 'like', "%{$keyword}%")
          ->orWhere('email', 'like', "%{$keyword}%");
    });
})

// Custom filter untuk kolom Kota
->filterColumn('kota', function($query, $keyword) {
    $query->where('kota_kabupaten', 'like', "%{$keyword}%")
          ->orWhere('provinsi', 'like', "%{$keyword}%")
          ->orWhere('kecamatan', 'like', "%{$keyword}%")
          ->orWhere('kelurahan', 'like', "%{$keyword}%");
})
```

---

## 📊 Search Performance

### Server-side Processing Benefits:
- ✅ **Fast:** Search di server, bukan browser
- ✅ **Efficient:** Hanya load data yang ditampilkan
- ✅ **Scalable:** Support ribuan/jutaan records
- ✅ **Indexed:** Database indexing untuk search cepat

### Recommended Database Indexes:
```sql
-- Tambahkan index untuk improve search performance
ALTER TABLE alamats ADD INDEX idx_tipe (tipe);
ALTER TABLE alamats ADD INDEX idx_kota (kota_kabupaten);
ALTER TABLE alamats ADD INDEX idx_alamat (alamat_lengkap(255));
ALTER TABLE users ADD INDEX idx_name (name);
```

---

## 🎨 UI/UX Features

### Visual Feedback:
- **Loading Indicator:** Muncul saat search/filter
- **Row Count:** Update "Menampilkan X sampai Y dari Z data"
- **Highlight:** Search box dengan border biru
- **Icons:** Filter icons untuk better recognition

### Responsive Design:
- **Desktop:** 3 filter boxes horizontal
- **Tablet:** Stack vertical jika perlu
- **Mobile:** Full width filter boxes

---

## 🐛 Troubleshooting

### Problem 1: Search tidak bekerja
**Solution:**
```javascript
// Pastikan table variable initialized
console.log(window.LaravelDataTables["alamat-table"]);

// Jika undefined, tunggu table loaded
$(document).ready(function() {
    setTimeout(function() {
        // Reinitialize filters
    }, 1000);
});
```

### Problem 2: Filter tidak reset
**Solution:**
```javascript
// Reset semua filter
$('#filter-tipe').val('').trigger('change');
$('#filter-user').val('').trigger('change');
$('#quick-search').val('');
table.search('').columns().search('').draw();
```

### Problem 3: Search case-sensitive
**Solution:**
```php
// Di filterColumn, pastikan gunakan LIKE (case-insensitive)
->where('field', 'like', "%{$keyword}%")  // ✅ Good
// Bukan:
->where('field', '=', $keyword)  // ❌ Bad (exact match)
```

---

## 📱 Mobile Usage

### Touch-friendly:
- **Large tap targets** untuk dropdown
- **Auto-complete** di input box
- **Clear button** di input box (native browser)

### Keyboard:
- **Enter key** → Search
- **Escape key** → Clear search
- **Tab navigation** → Pindah antar filter

---

## 🚀 Advanced Features (Future Enhancement)

### 1. **Range Search**
```javascript
// Search by date range
$('#filter-date-from, #filter-date-to').on('change', function() {
    // Custom range filter
});
```

### 2. **Saved Filters**
```javascript
// Save filter preferences to localStorage
localStorage.setItem('alamat-filters', JSON.stringify(filters));
```

### 3. **Export Filtered Data**
```javascript
// Export hanya data yang terfilter
table.buttons().exportData({
    modifier: {
        search: 'applied'
    }
});
```

### 4. **Real-time Stats**
```javascript
// Show stats of filtered results
table.on('draw', function() {
    var info = table.page.info();
    $('#total-filtered').text(info.recordsDisplay);
});
```

---

## 📚 Reference

### DataTables Search API:
- [Column Search](https://datatables.net/reference/api/column().search())
- [Global Search](https://datatables.net/reference/api/search())
- [Filter Data](https://datatables.net/manual/server-side#Filtering-data)

### Laravel DataTables:
- [Column Filtering](https://yajrabox.com/docs/laravel-datatables/master/columns)
- [Custom Filter](https://yajrabox.com/docs/laravel-datatables/master/filter-column)

---

## ✅ Summary

Sekarang Alamat Module punya **3 cara search**:

1. **Global Search** → Search di semua kolom
2. **Advanced Filter** → Filter by Tipe & Pemilik
3. **Quick Search** → Pencarian cepat dengan UI yang jelas

Semua search method bisa di-**combine** untuk hasil yang lebih spesifik!

**Happy Searching! 🔍**
