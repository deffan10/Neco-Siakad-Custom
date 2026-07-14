# Neco Siakad
**New Ecosystem - Sistem Informasi Akademik**

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
  <img src="https://img.shields.io/badge/Status-Under%20Development-orange.svg" alt="Development Status">
</p>

## 📖 About Neco Siakad

Neco Siakad (**New Ecosystem - Sistem Informasi Akademik**) adalah sistem informasi akademik open source yang dibangun menggunakan Laravel 12. Sistem ini dirancang untuk memenuhi kebutuhan pengelolaan data akademik di institusi pendidikan tinggi dengan fitur yang komprehensif dan modern.

### 🎯 Tujuan Proyek
- Menyediakan sistem akademik yang mudah digunakan dan dapat dikustomisasi
- Membangun ekosistem baru untuk pengelolaan data akademik yang terintegrasi
- Memberikan solusi open source untuk institusi pendidikan di Indonesia
- Menciptakan platform yang scalable dan maintainable

## ✨ Fitur Utama

### 🔐 Sistem Autentikasi & Otorisasi ✅
- Manajemen user dengan role-based access control
- Sistem login/logout yang aman
- Proteksi terhadap CSRF attacks
- Two Factor Authentication (2FA) support
- Dukungan multi-user (Admin, Dosen, Mahasiswa - dengan struktur terpisah)

### 👥 Manajemen Pengguna ✅
- Profil pengguna lengkap dengan foto
- Manajemen data pribadi (alamat KTP & domisili, pendidikan, keluarga)
- Pembatasan: Setiap user hanya boleh memiliki 1 alamat KTP dan 1 alamat domisili
- Setiap user dapat memiliki banyak data pendidikan dan keluarga
- Manajemen kontak dan media sosial

### 📚 Manajemen Akademik ✅
- Kurikulum dan mata kuliah
- Jadwal perkuliahan dan Presensi Mahasiswa/Dosen
- Sistem registrasi dan KRS online
- Manajemen kelas dan ruangan
- Bahan Ajar & Tugas Kuliah (Materi download & Tugas upload)
- Pengajuan Cuti Akademik & PA Online (Bimbingan Akademik Chat)
- Proposal / Tugas Akhir / Skripsi (Registrasi Judul & Draft File)

### 📊 Sistem Penilaian & Kelulusan ✅
- Input dan monitoring nilai akademik
- Sistem KHS (Kartu Hasil Studi) & Transkrip Nilai
- Laporan akademik dan dashboard analytics
- Pendaftaran Wisuda & Cetak SKL
- Pengisian kuesioner pembelajaran (EDOM) & Tracer Study

### 🏢 Manajemen Infrastruktur ✅
- Gedung dan ruangan kampus
- Inventaris barang
- Sistem pemeliharaan dan perawatan
- Transaksi barang (peminjaman, pengecekan, pengajuan perbaikan)
- Riwayat perbaikan dan histori pemeliharaan

### ⚙️ Pengaturan Sistem ✅
- Konfigurasi kampus dan aplikasi yang fleksibel
- Manajemen tahun akademik dan semester
- Manajemen referensi data (agama, golongan darah, jenis kelamin, kewarganegaraan, jabatan, role)
- Pengaturan sistem dan kampus

### 📱 Responsive Design ✅
- Interface yang mobile-friendly
- Dashboard yang informatif
- Navigasi yang intuitif

## 🛠️ Technology Stack

### Backend
- **Laravel 12.x** - PHP Web Framework
- **PHP 8.2+** - Programming Language
- **SQLite/MySQL** - Database Management
- **Eloquent ORM** - Database Object-Relational Mapping

### Frontend
- **Blade Templates** - Laravel's templating engine
- **Tailwind CSS** - Utility-first CSS framework
- **Vite.js** - Frontend build tool
- **Axios** - HTTP client for API calls

### Development Tools
- **Composer** - PHP dependency manager
- **NPM** - Node package manager
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite or MySQL

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/mjaya69703/neeco-siakad.git
   cd neeco-siakad
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   # Terminal 1 - Laravel server
   php artisan serve
   
   # Terminal 2 - Vite dev server
   npm run dev
   ```

## 🔐 Informasi Login Default

Setelah menjalankan proses instalasi dan seeding database, sistem akan menyediakan akun default untuk testing:

### Administrator
- **Username**: superuser
- **Email**: superuser@example.com
- **Password**: admin123

> ⚠️ **PENTING**: Ganti password default ini setelah instalasi untuk keamanan sistem.
> Untuk akun dosen dan mahasiswa, perlu dibuat melalui sistem administrasi setelah login dengan akun administrator.

## 📝 Usage

### Development Mode
```bash
# Start Laravel development server
php artisan serve

# Start Vite development server with hot reload
npm run dev
```

### Production Build
```bash
# Build assets for production
npm run build

# Optimize Laravel for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run tests with coverage
php artisan test --coverage
```

## 📁 Project Structure

```
neeco-siakad/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   │   ├── Infra/           # Infrastructure models
│   │   ├── Inventaris/      # Inventory models
│   │   ├── Mahasiswa/       # Student-related models
│   │   ├── Pengaturan/      # Settings models
│   │   ├── Perawatan/       # Maintenance models
│   │   ├── Referensi/       # Reference data models
│   │   ├── Transaksi/       # Transaction models
│   │   └── User/            # User-related models
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
└── routes/                 # Application routes
```

## 🤝 Contributing

Kami menyambut kontribusi dari komunitas! Silakan ikuti langkah berikut:

1. Fork repository ini
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan Anda (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

### Development Guidelines
- Ikuti PSR-12 coding standards
- Tulis tests untuk fitur baru
- Update dokumentasi jika diperlukan
- Gunakan conventional commits

## 📄 License

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## 📞 Contact & Support

- **Email**: info@idev-fun.org
- **Website**: https://neco-siakad.idev-fun.org
- **Issues**: [GitHub Issues](https://github.com/mjaya69703/neeco-siakad/issues)

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com) - The PHP framework for web artisans
- [Tailwind CSS](https://tailwindcss.com) - A utility-first CSS framework
- [Vite.js](https://vitejs.dev) - Next generation frontend tooling

---

<p align="center">
  Made with ❤️ for Indonesian Educational Institutions
</p>