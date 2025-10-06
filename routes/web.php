<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\RootController::class, 'renderHomePage'])->name('root.home-index');

Route::get('/signin', [App\Http\Controllers\AuthController::class, 'renderSignin'])->name('auth.render-signin');
Route::get('/gateway/choose-role', [App\Http\Controllers\AuthController::class, 'renderChooseRole'])->name('auth.choose-role');
Route::post('/gateway/set-role', [App\Http\Controllers\AuthController::class, 'handleSetRole'])->name('auth.set-role');
Route::post('/signin', [App\Http\Controllers\AuthController::class, 'handleSignin'])->name('auth.handle-signin');

Route::middleware(['auth', 'active_role:admin'])->prefix('admin')->as('admin.')->group(function () {
    require __DIR__.'/basic-routes.php';
    // Other Menus for Tendik can be added here

    Route::get('/dashboard/infra', [App\Http\Controllers\RootController::class, 'indexInfra'])->name('dashboard-infra');
    Route::get('/dashboard/referensi', [App\Http\Controllers\RootController::class, 'indexReferensi'])->name('dashboard-referensi');
    Route::get('/dashboard/akademik', [App\Http\Controllers\RootController::class, 'indexAkademik'])->name('dashboard-akademik');

    Route::get('/pengaturan', [App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan-index');
    Route::post('/pengaturan', [App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan-update');

    // Master Data Users
    Route::get('/users', [App\Http\Controllers\Master\Users\UsersController::class, 'index'])->name('users.user-index');
    Route::get('/users/export-excel', [App\Http\Controllers\Master\Users\UsersController::class, 'exportExcel'])->name('users.user-export-excel');
    Route::get('/users/export-csv', [App\Http\Controllers\Master\Users\UsersController::class, 'exportCSV'])->name('users.user-export-csv');
    Route::get('/users/export-pdf', [App\Http\Controllers\Master\Users\UsersController::class, 'exportPDF'])->name('users.user-export-pdf');
    Route::post('/users/import-excel', [App\Http\Controllers\Master\Users\UsersController::class, 'importExcel'])->name('users.user-import');
    Route::get('/users/template', [App\Http\Controllers\Master\Users\UsersController::class, 'downloadTemplate'])->name('users.user-template');
    Route::get('/users/{id}/view', [App\Http\Controllers\Master\Users\UsersController::class, 'show'])->name('users.user-view');
    Route::get('/users/trashed', [App\Http\Controllers\Master\Users\UsersController::class, 'trash'])->name('users.user-trash');
    Route::post('/users', [App\Http\Controllers\Master\Users\UsersController::class, 'store'])->name('users.user-store');
    Route::patch('/users/{id}/update', [App\Http\Controllers\Master\Users\UsersController::class, 'update'])->name('users.user-update');
    Route::post('/users/{id}/restore', [App\Http\Controllers\Master\Users\UsersController::class, 'restore'])->name('users.user-restore');
    Route::delete('/users/{id}/delete', [App\Http\Controllers\Master\Users\UsersController::class, 'destroy'])->name('users.user-destroy');
    Route::delete('/users/{id}/force-delete', [App\Http\Controllers\Master\Users\UsersController::class, 'forceDelete'])->name('users.user-force-delete');

    Route::get('/users/role', [App\Http\Controllers\Master\Users\RoleController::class, 'index'])->name('users.role-index');
    Route::get('/users/role/{id}/view', [App\Http\Controllers\Master\Users\RoleController::class, 'show'])->name('users.role-view');
    Route::get('/users/role/trashed', [App\Http\Controllers\Master\Users\RoleController::class, 'trash'])->name('users.role-trash');
    Route::post('/users/role', [App\Http\Controllers\Master\Users\RoleController::class, 'store'])->name('users.role-store');
    Route::patch('/users/role/{id}/update', [App\Http\Controllers\Master\Users\RoleController::class, 'update'])->name('users.role-update');
    Route::delete('/users/role/{id}/delete', [App\Http\Controllers\Master\Users\RoleController::class, 'destroy'])->name('users.role-destroy');
    Route::post('/users/role/{id}/restore', [App\Http\Controllers\Master\Users\RoleController::class, 'restore'])->name('users.role-restore');
    Route::delete('/users/role/{id}/force-delete', [App\Http\Controllers\Master\Users\RoleController::class, 'forceDelete'])->name('users.role-force-delete');

    Route::get('/users/subrole', [App\Http\Controllers\Master\Users\SubroleController::class, 'index'])->name('users.subrole-index');
    Route::get('/users/subrole/{id}/view', [App\Http\Controllers\Master\Users\SubroleController::class, 'show'])->name('users.subrole-view');
    Route::get('/users/subrole/trashed', [App\Http\Controllers\Master\Users\SubroleController::class, 'trash'])->name('users.subrole-trash');
    Route::post('/users/subrole', [App\Http\Controllers\Master\Users\SubroleController::class, 'store'])->name('users.subrole-store');
    Route::patch('/users/subrole/{id}/update', [App\Http\Controllers\Master\Users\SubroleController::class, 'update'])->name('users.subrole-update');
    Route::delete('/users/subrole/{id}/delete', [App\Http\Controllers\Master\Users\SubroleController::class, 'destroy'])->name('users.subrole-destroy');
    Route::post('/users/subrole/{id}/restore', [App\Http\Controllers\Master\Users\SubroleController::class, 'restore'])->name('users.subrole-restore');

    Route::get('/users/alamat', [App\Http\Controllers\Master\Users\AlamatController::class, 'index'])->name('users.alamat-index');
    Route::get('/users/alamat/trashed', [App\Http\Controllers\Master\Users\AlamatController::class, 'trash'])->name('users.alamat-trash');
    Route::post('/users/alamat', [App\Http\Controllers\Master\Users\AlamatController::class, 'store'])->name('users.alamat-store');
    Route::patch('/users/alamat/{id}/update', [App\Http\Controllers\Master\Users\AlamatController::class, 'update'])->name('users.alamat-update');
    Route::delete('/users/alamat/{id}/delete', [App\Http\Controllers\Master\Users\AlamatController::class, 'destroy'])->name('users.alamat-destroy');
    Route::post('/users/alamat/{id}/restore', [App\Http\Controllers\Master\Users\AlamatController::class, 'restore'])->name('users.alamat-restore');
    Route::delete('/users/alamat/{id}/force-delete', [App\Http\Controllers\Master\Users\AlamatController::class, 'forceDelete'])->name('users.alamat-force-delete');
    Route::delete('/users/alamat/{id}/force-delete', [App\Http\Controllers\Master\Users\AlamatController::class, 'forceDelete'])->name('users.alamat-force-delete');

    Route::get('/users/keluarga', [App\Http\Controllers\Master\Users\KeluargaController::class, 'index'])->name('users.keluarga-index');
    Route::get('/users/keluarga/trashed', [App\Http\Controllers\Master\Users\KeluargaController::class, 'trash'])->name('users.keluarga-trash');
    Route::post('/users/keluarga', [App\Http\Controllers\Master\Users\KeluargaController::class, 'store'])->name('users.keluarga-store');
    Route::patch('/users/keluarga/{id}/update', [App\Http\Controllers\Master\Users\KeluargaController::class, 'update'])->name('users.keluarga-update');
    Route::delete('/users/keluarga/{id}/delete', [App\Http\Controllers\Master\Users\KeluargaController::class, 'destroy'])->name('users.keluarga-destroy');
    Route::post('/users/keluarga/{id}/restore', [App\Http\Controllers\Master\Users\KeluargaController::class, 'restore'])->name('users.keluarga-restore');
    Route::delete('/users/keluarga/{id}/force-delete', [App\Http\Controllers\Master\Users\KeluargaController::class, 'forceDelete'])->name('users.keluarga-force-delete');

    Route::get('/users/pendidikan', [App\Http\Controllers\Master\Users\PendidikanController::class, 'index'])->name('users.pendidikan-index');
    Route::get('/users/pendidikan/trashed', [App\Http\Controllers\Master\Users\PendidikanController::class, 'trash'])->name('users.pendidikan-trash');
    Route::post('/users/pendidikan', [App\Http\Controllers\Master\Users\PendidikanController::class, 'store'])->name('users.pendidikan-store');
    Route::patch('/users/pendidikan/{id}/update', [App\Http\Controllers\Master\Users\PendidikanController::class, 'update'])->name('users.pendidikan-update');
    Route::delete('/users/pendidikan/{id}/delete', [App\Http\Controllers\Master\Users\PendidikanController::class, 'destroy'])->name('users.pendidikan-destroy');
    Route::post('/users/pendidikan/{id}/restore', [App\Http\Controllers\Master\Users\PendidikanController::class, 'restore'])->name('users.pendidikan-restore');
    Route::delete('/users/pendidikan/{id}/force-delete', [App\Http\Controllers\Master\Users\PendidikanController::class, 'forceDelete'])->name('users.pendidikan-force-delete');

    // Master Data Referensi
    Route::get('/referensi/agama', [App\Http\Controllers\Referensi\AgamaController::class, 'index'])->name('referensi.agama-index');
    Route::get('/referensi/agama/trashed', [App\Http\Controllers\Referensi\AgamaController::class, 'trash'])->name('referensi.agama-trash');
    Route::post('/referensi/agama', [App\Http\Controllers\Referensi\AgamaController::class, 'store'])->name('referensi.agama-store');
    Route::patch('/referensi/agama/{id}/update', [App\Http\Controllers\Referensi\AgamaController::class, 'update'])->name('referensi.agama-update');
    Route::delete('/referensi/agama/{id}/delete', [App\Http\Controllers\Referensi\AgamaController::class, 'destroy'])->name('referensi.agama-destroy');
    Route::post('/referensi/agama/{id}/restore', [App\Http\Controllers\Referensi\AgamaController::class, 'restore'])->name('referensi.agama-restore');

    Route::get('/referensi/golongan-darah', [App\Http\Controllers\Referensi\GolonganDarahController::class, 'index'])->name('referensi.golongan-darah-index');
    Route::get('/referensi/golongan-darah/trashed', [App\Http\Controllers\Referensi\GolonganDarahController::class, 'trash'])->name('referensi.golongan-darah-trash');
    Route::post('/referensi/golongan-darah', [App\Http\Controllers\Referensi\GolonganDarahController::class, 'store'])->name('referensi.golongan-darah-store');
    Route::patch('/referensi/golongan-darah/{id}/update', [App\Http\Controllers\Referensi\GolonganDarahController::class, 'update'])->name('referensi.golongan-darah-update');
    Route::delete('/referensi/golongan-darah/{id}/delete', [App\Http\Controllers\Referensi\GolonganDarahController::class, 'destroy'])->name('referensi.golongan-darah-destroy');
    Route::post('/referensi/golongan-darah/{id}/restore', [App\Http\Controllers\Referensi\GolonganDarahController::class, 'restore'])->name('referensi.golongan-darah-restore');

    Route::get('/referensi/jenis-kelamin', [App\Http\Controllers\Referensi\JenisKelaminController::class, 'index'])->name('referensi.jenis-kelamin-index');
    Route::get('/referensi/jenis-kelamin/trashed', [App\Http\Controllers\Referensi\JenisKelaminController::class, 'trash'])->name('referensi.jenis-kelamin-trash');
    Route::post('/referensi/jenis-kelamin', [App\Http\Controllers\Referensi\JenisKelaminController::class, 'store'])->name('referensi.jenis-kelamin-store');
    Route::patch('/referensi/jenis-kelamin/{id}/update', [App\Http\Controllers\Referensi\JenisKelaminController::class, 'update'])->name('referensi.jenis-kelamin-update');
    Route::delete('/referensi/jenis-kelamin/{id}/delete', [App\Http\Controllers\Referensi\JenisKelaminController::class, 'destroy'])->name('referensi.jenis-kelamin-destroy');
    Route::post('/referensi/jenis-kelamin/{id}/restore', [App\Http\Controllers\Referensi\JenisKelaminController::class, 'restore'])->name('referensi.jenis-kelamin-restore');

    Route::get('/referensi/kewarganegaraan', [App\Http\Controllers\Referensi\KewarganegaraanController::class, 'index'])->name('referensi.kewarganegaraan-index');
    Route::get('/referensi/kewarganegaraan/trashed', [App\Http\Controllers\Referensi\KewarganegaraanController::class, 'trash'])->name('referensi.kewarganegaraan-trash');
    Route::post('/referensi/kewarganegaraan', [App\Http\Controllers\Referensi\KewarganegaraanController::class, 'store'])->name('referensi.kewarganegaraan-store');
    Route::patch('/referensi/kewarganegaraan/{id}/update', [App\Http\Controllers\Referensi\KewarganegaraanController::class, 'update'])->name('referensi.kewarganegaraan-update');
    Route::delete('/referensi/kewarganegaraan/{id}/delete', [App\Http\Controllers\Referensi\KewarganegaraanController::class, 'destroy'])->name('referensi.kewarganegaraan-destroy');
    Route::post('/referensi/kewarganegaraan/{id}/restore', [App\Http\Controllers\Referensi\KewarganegaraanController::class, 'restore'])->name('referensi.kewarganegaraan-restore');

    Route::get('/referensi/semester', [App\Http\Controllers\Referensi\SemesterController::class, 'index'])->name('referensi.semester-index');
    Route::get('/referensi/semester/trashed', [App\Http\Controllers\Referensi\SemesterController::class, 'trash'])->name('referensi.semester-trash');
    Route::post('/referensi/semester', [App\Http\Controllers\Referensi\SemesterController::class, 'store'])->name('referensi.semester-store');
    Route::patch('/referensi/semester/{id}/update', [App\Http\Controllers\Referensi\SemesterController::class, 'update'])->name('referensi.semester-update');
    Route::delete('/referensi/semester/{id}/delete', [App\Http\Controllers\Referensi\SemesterController::class, 'destroy'])->name('referensi.semester-destroy');
    Route::post('/referensi/semester/{id}/restore', [App\Http\Controllers\Referensi\SemesterController::class, 'restore'])->name('referensi.semester-restore');

    Route::get('/referensi/status-mahasiswa', [App\Http\Controllers\Referensi\StatusMahasiswaController::class, 'index'])->name('referensi.status-mahasiswa-index');
    Route::get('/referensi/status-mahasiswa/trashed', [App\Http\Controllers\Referensi\StatusMahasiswaController::class, 'trash'])->name('referensi.status-mahasiswa-trash');
    Route::post('/referensi/status-mahasiswa', [App\Http\Controllers\Referensi\StatusMahasiswaController::class, 'store'])->name('referensi.status-mahasiswa-store');
    Route::patch('/referensi/status-mahasiswa/{id}/update', [App\Http\Controllers\Referensi\StatusMahasiswaController::class, 'update'])->name('referensi.status-mahasiswa-update');
    Route::delete('/referensi/status-mahasiswa/{id}/delete', [App\Http\Controllers\Referensi\StatusMahasiswaController::class, 'destroy'])->name('referensi.status-mahasiswa-destroy');
    Route::post('/referensi/status-mahasiswa/{id}/restore', [App\Http\Controllers\Referensi\StatusMahasiswaController::class, 'restore'])->name('referensi.status-mahasiswa-restore');

    Route::get('/referensi/jabatan', [App\Http\Controllers\Referensi\JabatanController::class, 'index'])->name('referensi.jabatan-index');
    Route::get('/referensi/jabatan/trashed', [App\Http\Controllers\Referensi\JabatanController::class, 'trash'])->name('referensi.jabatan-trash');
    Route::post('/referensi/jabatan', [App\Http\Controllers\Referensi\JabatanController::class, 'store'])->name('referensi.jabatan-store');
    Route::patch('/referensi/jabatan/{id}/update', [App\Http\Controllers\Referensi\JabatanController::class, 'update'])->name('referensi.jabatan-update');
    Route::delete('/referensi/jabatan/{id}/delete', [App\Http\Controllers\Referensi\JabatanController::class, 'destroy'])->name('referensi.jabatan-destroy');
    Route::post('/referensi/jabatan/{id}/restore', [App\Http\Controllers\Referensi\JabatanController::class, 'restore'])->name('referensi.jabatan-restore');
    // Infra Routes
    Route::get('/infra/gedung', [App\Http\Controllers\Master\Infra\GedungController::class, 'index'])->name('infra.gedung-index');
    Route::get('/infra/gedung/trashed', [App\Http\Controllers\Master\Infra\GedungController::class, 'trash'])->name('infra.gedung-trash');
    Route::post('/infra/gedung', [App\Http\Controllers\Master\Infra\GedungController::class, 'store'])->name('infra.gedung-store');
    Route::patch('/infra/gedung/{id}/update', [App\Http\Controllers\Master\Infra\GedungController::class, 'update'])->name('infra.gedung-update');
    Route::delete('/infra/gedung/{id}/delete', [App\Http\Controllers\Master\Infra\GedungController::class, 'destroy'])->name('infra.gedung-destroy');
    Route::post('/infra/gedung/{id}/restore', [App\Http\Controllers\Master\Infra\GedungController::class, 'restore'])->name('infra.gedung-restore');

    Route::get('/infra/ruangan', [App\Http\Controllers\Master\Infra\RuanganController::class, 'index'])->name('infra.ruangan-index');
    Route::get('/infra/ruangan/trashed', [App\Http\Controllers\Master\Infra\RuanganController::class, 'trash'])->name('infra.ruangan-trash');
    Route::post('/infra/ruangan', [App\Http\Controllers\Master\Infra\RuanganController::class, 'store'])->name('infra.ruangan-store');
    Route::patch('/infra/ruangan/{id}/update', [App\Http\Controllers\Master\Infra\RuanganController::class, 'update'])->name('infra.ruangan-update');
    Route::delete('/infra/ruangan/{id}/delete', [App\Http\Controllers\Master\Infra\RuanganController::class, 'destroy'])->name('infra.ruangan-destroy');
    Route::post('/infra/ruangan/{id}/restore', [App\Http\Controllers\Master\Infra\RuanganController::class, 'restore'])->name('infra.ruangan-restore');

    // Inventaris Routes
    Route::get('/inventaris/kategori-barang', [App\Http\Controllers\Master\Infra\Inventaris\KategoriBarangController::class, 'index'])->name('inventaris.kategori-barang-index');
    Route::get('/inventaris/kategori-barang/trashed', [App\Http\Controllers\Master\Infra\Inventaris\KategoriBarangController::class, 'trash'])->name('inventaris.kategori-barang-trash');
    Route::post('/inventaris/kategori-barang', [App\Http\Controllers\Master\Infra\Inventaris\KategoriBarangController::class, 'store'])->name('inventaris.kategori-barang-store');
    Route::patch('/inventaris/kategori-barang/{id}/update', [App\Http\Controllers\Master\Infra\Inventaris\KategoriBarangController::class, 'update'])->name('inventaris.kategori-barang-update');
    Route::delete('/inventaris/kategori-barang/{id}/delete', [App\Http\Controllers\Master\Infra\Inventaris\KategoriBarangController::class, 'destroy'])->name('inventaris.kategori-barang-destroy');
    Route::post('/inventaris/kategori-barang/{id}/restore', [App\Http\Controllers\Master\Infra\Inventaris\KategoriBarangController::class, 'restore'])->name('inventaris.kategori-barang-restore');

    Route::get('/inventaris/barang', [App\Http\Controllers\Master\Infra\Inventaris\BarangController::class, 'index'])->name('inventaris.barang-index');
    Route::get('/inventaris/barang/trashed', [App\Http\Controllers\Master\Infra\Inventaris\BarangController::class, 'trash'])->name('inventaris.barang-trash');
    Route::post('/inventaris/barang', [App\Http\Controllers\Master\Infra\Inventaris\BarangController::class, 'store'])->name('inventaris.barang-store');
    Route::patch('/inventaris/barang/{id}/update', [App\Http\Controllers\Master\Infra\Inventaris\BarangController::class, 'update'])->name('inventaris.barang-update');
    Route::delete('/inventaris/barang/{id}/delete', [App\Http\Controllers\Master\Infra\Inventaris\BarangController::class, 'destroy'])->name('inventaris.barang-destroy');
    Route::post('/inventaris/barang/{id}/restore', [App\Http\Controllers\Master\Infra\Inventaris\BarangController::class, 'restore'])->name('inventaris.barang-restore');

    Route::get('/inventaris/barang-inventaris', [App\Http\Controllers\Master\Infra\Inventaris\BarangInventarisController::class, 'index'])->name('inventaris.barang-inventaris-index');
    Route::get('/inventaris/barang-inventaris/trashed', [App\Http\Controllers\Master\Infra\Inventaris\BarangInventarisController::class, 'trash'])->name('inventaris.barang-inventaris-trash');
    Route::post('/inventaris/barang-inventaris', [App\Http\Controllers\Master\Infra\Inventaris\BarangInventarisController::class, 'store'])->name('inventaris.barang-inventaris-store');
    Route::patch('/inventaris/barang-inventaris/{id}/update', [App\Http\Controllers\Master\Infra\Inventaris\BarangInventarisController::class, 'update'])->name('inventaris.barang-inventaris-update');
    Route::delete('/inventaris/barang-inventaris/{id}/delete', [App\Http\Controllers\Master\Infra\Inventaris\BarangInventarisController::class, 'destroy'])->name('inventaris.barang-inventaris-destroy');
    Route::post('/inventaris/barang-inventaris/{id}/restore', [App\Http\Controllers\Master\Infra\Inventaris\BarangInventarisController::class, 'restore'])->name('inventaris.barang-inventaris-restore');

    // Transaksi Barang Routes
    // Peminjaman Barang
    Route::get('/transaksi-barang/peminjaman', [App\Http\Controllers\Master\Infra\Transaksi\PeminjamanBarangController::class, 'index'])->name('transaksi-barang.peminjaman-index');
    Route::get('/transaksi-barang/peminjaman/trashed', [App\Http\Controllers\Master\Infra\Transaksi\PeminjamanBarangController::class, 'trash'])->name('transaksi-barang.peminjaman-trash');
    Route::post('/transaksi-barang/peminjaman', [App\Http\Controllers\Master\Infra\Transaksi\PeminjamanBarangController::class, 'store'])->name('transaksi-barang.peminjaman-store');
    Route::patch('/transaksi-barang/peminjaman/{id}/update', [App\Http\Controllers\Master\Infra\Transaksi\PeminjamanBarangController::class, 'update'])->name('transaksi-barang.peminjaman-update');
    Route::delete('/transaksi-barang/peminjaman/{id}/delete', [App\Http\Controllers\Master\Infra\Transaksi\PeminjamanBarangController::class, 'destroy'])->name('transaksi-barang.peminjaman-destroy');
    Route::post('/transaksi-barang/peminjaman/{id}/restore', [App\Http\Controllers\Master\Infra\Transaksi\PeminjamanBarangController::class, 'restore'])->name('transaksi-barang.peminjaman-restore');

    // Pengecekan Barang
    Route::get('/transaksi-barang/pengecekan', [App\Http\Controllers\Master\Infra\Transaksi\PengecekanBarangController::class, 'index'])->name('transaksi-barang.pengecekan-index');
    Route::get('/transaksi-barang/pengecekan/trashed', [App\Http\Controllers\Master\Infra\Transaksi\PengecekanBarangController::class, 'trash'])->name('transaksi-barang.pengecekan-trash');
    Route::post('/transaksi-barang/pengecekan', [App\Http\Controllers\Master\Infra\Transaksi\PengecekanBarangController::class, 'store'])->name('transaksi-barang.pengecekan-store');
    Route::patch('/transaksi-barang/pengecekan/{id}/update', [App\Http\Controllers\Master\Infra\Transaksi\PengecekanBarangController::class, 'update'])->name('transaksi-barang.pengecekan-update');
    Route::delete('/transaksi-barang/pengecekan/{id}/delete', [App\Http\Controllers\Master\Infra\Transaksi\PengecekanBarangController::class, 'destroy'])->name('transaksi-barang.pengecekan-destroy');
    Route::post('/transaksi-barang/pengecekan/{id}/restore', [App\Http\Controllers\Master\Infra\Transaksi\PengecekanBarangController::class, 'restore'])->name('transaksi-barang.pengecekan-restore');

    // Pengajuan Perbaikan
    Route::get('/transaksi-barang/pengajuan', [App\Http\Controllers\Master\Infra\Transaksi\PengajuanPerbaikanController::class, 'index'])->name('transaksi-barang.pengajuan-index');
    Route::get('/transaksi-barang/pengajuan/trashed', [App\Http\Controllers\Master\Infra\Transaksi\PengajuanPerbaikanController::class, 'trash'])->name('transaksi-barang.pengajuan-trash');
    Route::post('/transaksi-barang/pengajuan', [App\Http\Controllers\Master\Infra\Transaksi\PengajuanPerbaikanController::class, 'store'])->name('transaksi-barang.pengajuan-store');
    Route::patch('/transaksi-barang/pengajuan/{id}/update', [App\Http\Controllers\Master\Infra\Transaksi\PengajuanPerbaikanController::class, 'update'])->name('transaksi-barang.pengajuan-update');
    Route::delete('/transaksi-barang/pengajuan/{id}/delete', [App\Http\Controllers\Master\Infra\Transaksi\PengajuanPerbaikanController::class, 'destroy'])->name('transaksi-barang.pengajuan-destroy');
    Route::post('/transaksi-barang/pengajuan/{id}/restore', [App\Http\Controllers\Master\Infra\Transaksi\PengajuanPerbaikanController::class, 'restore'])->name('transaksi-barang.pengajuan-restore');

    // Riwayat Perbaikan
    Route::get('/transaksi-barang/riwayat', [App\Http\Controllers\Master\Infra\Transaksi\RiwayatPerbaikanController::class, 'index'])->name('transaksi-barang.riwayat-index');
    Route::get('/transaksi-barang/riwayat/trashed', [App\Http\Controllers\Master\Infra\Transaksi\RiwayatPerbaikanController::class, 'trash'])->name('transaksi-barang.riwayat-trash');
    Route::post('/transaksi-barang/riwayat', [App\Http\Controllers\Master\Infra\Transaksi\RiwayatPerbaikanController::class, 'store'])->name('transaksi-barang.riwayat-store');
    Route::patch('/transaksi-barang/riwayat/{id}/update', [App\Http\Controllers\Master\Infra\Transaksi\RiwayatPerbaikanController::class, 'update'])->name('transaksi-barang.riwayat-update');
    Route::delete('/transaksi-barang/riwayat/{id}/delete', [App\Http\Controllers\Master\Infra\Transaksi\RiwayatPerbaikanController::class, 'destroy'])->name('transaksi-barang.riwayat-destroy');
    Route::post('/transaksi-barang/riwayat/{id}/restore', [App\Http\Controllers\Master\Infra\Transaksi\RiwayatPerbaikanController::class, 'restore'])->name('transaksi-barang.riwayat-restore');

    // Perawatan Barang Routes
    // Jadwal Pemeliharaan
    Route::get('/perawatan/jadwal', [App\Http\Controllers\Master\Infra\Perawatan\JadwalPemeliharaanController::class, 'index'])->name('perawatan.jadwal-index');
    Route::get('/perawatan/jadwal/trashed', [App\Http\Controllers\Master\Infra\Perawatan\JadwalPemeliharaanController::class, 'trash'])->name('perawatan.jadwal-trash');
    Route::post('/perawatan/jadwal', [App\Http\Controllers\Master\Infra\Perawatan\JadwalPemeliharaanController::class, 'store'])->name('perawatan.jadwal-store');
    Route::patch('/perawatan/jadwal/{id}/update', [App\Http\Controllers\Master\Infra\Perawatan\JadwalPemeliharaanController::class, 'update'])->name('perawatan.jadwal-update');
    Route::delete('/perawatan/jadwal/{id}/delete', [App\Http\Controllers\Master\Infra\Perawatan\JadwalPemeliharaanController::class, 'destroy'])->name('perawatan.jadwal-destroy');
    Route::post('/perawatan/jadwal/{id}/restore', [App\Http\Controllers\Master\Infra\Perawatan\JadwalPemeliharaanController::class, 'restore'])->name('perawatan.jadwal-restore');

    // Histori Pemeliharaan
    Route::get('/perawatan/histori', [App\Http\Controllers\Master\Infra\Perawatan\HistoriPemeliharaanController::class, 'index'])->name('perawatan.histori-index');
    Route::get('/perawatan/histori/trashed', [App\Http\Controllers\Master\Infra\Perawatan\HistoriPemeliharaanController::class, 'trash'])->name('perawatan.histori-trash');
    Route::post('/perawatan/histori', [App\Http\Controllers\Master\Infra\Perawatan\HistoriPemeliharaanController::class, 'store'])->name('perawatan.histori-store');
    Route::patch('/perawatan/histori/{id}/update', [App\Http\Controllers\Master\Infra\Perawatan\HistoriPemeliharaanController::class, 'update'])->name('perawatan.histori-update');
    Route::delete('/perawatan/histori/{id}/delete', [App\Http\Controllers\Master\Infra\Perawatan\HistoriPemeliharaanController::class, 'destroy'])->name('perawatan.histori-destroy');
    Route::post('/perawatan/histori/{id}/restore', [App\Http\Controllers\Master\Infra\Perawatan\HistoriPemeliharaanController::class, 'restore'])->name('perawatan.histori-restore');

    // Akademik Routes
    Route::get('/akademik/tahun-akademik', [App\Http\Controllers\Master\Akademik\TahunAkademikController::class, 'index'])->name('akademik.tahun-akademik-index');
    Route::get('/akademik/tahun-akademik/trashed', [App\Http\Controllers\Master\Akademik\TahunAkademikController::class, 'trash'])->name('akademik.tahun-akademik-trash');
    Route::post('/akademik/tahun-akademik', [App\Http\Controllers\Master\Akademik\TahunAkademikController::class, 'store'])->name('akademik.tahun-akademik-store');
    Route::patch('/akademik/tahun-akademik/{id}/update', [App\Http\Controllers\Master\Akademik\TahunAkademikController::class, 'update'])->name('akademik.tahun-akademik-update');
    Route::delete('/akademik/tahun-akademik/{id}/delete', [App\Http\Controllers\Master\Akademik\TahunAkademikController::class, 'destroy'])->name('akademik.tahun-akademik-destroy');
    Route::post('/akademik/tahun-akademik/{id}/restore', [App\Http\Controllers\Master\Akademik\TahunAkademikController::class, 'restore'])->name('akademik.tahun-akademik-restore');

    Route::get('/akademik/fakultas', [App\Http\Controllers\Master\Akademik\FakultasController::class, 'index'])->name('akademik.fakultas-index');
    Route::get('/akademik/fakultas/view/{id}', [App\Http\Controllers\Master\Akademik\FakultasController::class, 'view'])->name('akademik.fakultas-view');
    Route::get('/akademik/fakultas/trashed', [App\Http\Controllers\Master\Akademik\FakultasController::class, 'trash'])->name('akademik.fakultas-trash');
    Route::post('/akademik/fakultas', [App\Http\Controllers\Master\Akademik\FakultasController::class, 'store'])->name('akademik.fakultas-store');
    Route::patch('/akademik/fakultas/{id}/update', [App\Http\Controllers\Master\Akademik\FakultasController::class, 'update'])->name('akademik.fakultas-update');
    Route::delete('/akademik/fakultas/{id}/delete', [App\Http\Controllers\Master\Akademik\FakultasController::class, 'destroy'])->name('akademik.fakultas-destroy');
    Route::post('/akademik/fakultas/{id}/restore', [App\Http\Controllers\Master\Akademik\FakultasController::class, 'restore'])->name('akademik.fakultas-restore');

    Route::get('/akademik/program-studi', [App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'index'])->name('akademik.program-studi-index');
    Route::get('/akademik/program-studi/view/{id}', [App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'view'])->name('akademik.program-studi-view');
    Route::get('/akademik/program-studi/trashed', [App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'trash'])->name('akademik.program-studi-trash');
    Route::post('/akademik/program-studi', [App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'store'])->name('akademik.program-studi-store');
    Route::patch('/akademik/program-studi/{id}/update', [App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'update'])->name('akademik.program-studi-update');
    Route::delete('/akademik/program-studi/{id}/delete', [App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'destroy'])->name('akademik.program-studi-destroy');
    Route::post('/akademik/program-studi/{id}/restore', [App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'restore'])->name('akademik.program-studi-restore');

    Route::get('/akademik/kurikulum', [App\Http\Controllers\Master\Akademik\KurikulumController::class, 'index'])->name('akademik.kurikulum-index');
    Route::get('/akademik/kurikulum/view/{id}', [App\Http\Controllers\Master\Akademik\KurikulumController::class, 'view'])->name('akademik.kurikulum-view');
    Route::get('/akademik/kurikulum/trashed', [App\Http\Controllers\Master\Akademik\KurikulumController::class, 'trash'])->name('akademik.kurikulum-trash');
    Route::post('/akademik/kurikulum', [App\Http\Controllers\Master\Akademik\KurikulumController::class, 'store'])->name('akademik.kurikulum-store');
    Route::patch('/akademik/kurikulum/{id}/update', [App\Http\Controllers\Master\Akademik\KurikulumController::class, 'update'])->name('akademik.kurikulum-update');
    Route::delete('/akademik/kurikulum/{id}/delete', [App\Http\Controllers\Master\Akademik\KurikulumController::class, 'destroy'])->name('akademik.kurikulum-destroy');
    Route::post('/akademik/kurikulum/{id}/restore', [App\Http\Controllers\Master\Akademik\KurikulumController::class, 'restore'])->name('akademik.kurikulum-restore');

    // Kurikulum Mata Kuliah Management within Kurikulum Detail
    Route::post('/akademik/kurikulum/{id}/mata-kuliah', [App\Http\Controllers\Master\Akademik\KurikulumController::class, 'storeMataKuliah'])->name('akademik.kurikulum-matakuliah-store');
    Route::patch('/akademik/kurikulum/{kurikulumId}/mata-kuliah/{mataKuliahId}', [App\Http\Controllers\Master\Akademik\KurikulumController::class, 'updateMataKuliah'])->name('akademik.kurikulum-matakuliah-update');
    Route::delete('/akademik/kurikulum/{kurikulumId}/mata-kuliah/{mataKuliahId}', [App\Http\Controllers\Master\Akademik\KurikulumController::class, 'removeMataKuliah'])->name('akademik.kurikulum-matakuliah-remove');

    Route::get('/akademik/matakuliah', [App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'index'])->name('akademik.matakuliah-index');
    Route::get('/akademik/matakuliah/view/{id}', [App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'view'])->name('akademik.matakuliah-view');
    Route::get('/akademik/matakuliah/trashed', [App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'trash'])->name('akademik.matakuliah-trash');
    Route::post('/akademik/matakuliah', [App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'store'])->name('akademik.matakuliah-store');
    Route::patch('/akademik/matakuliah/{id}/update', [App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'update'])->name('akademik.matakuliah-update');
    Route::delete('/akademik/matakuliah/{id}/delete', [App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'destroy'])->name('akademik.matakuliah-destroy');
    Route::post('/akademik/matakuliah/{id}/restore', [App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'restore'])->name('akademik.matakuliah-restore');

    Route::get('/akademik/kelas-perkuliahan', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'index'])->name('akademik.kelas-perkuliahan-index');
    Route::get('/akademik/kelas-perkuliahan/view/{id}', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'view'])->name('akademik.kelas-perkuliahan-view');
    Route::get('/akademik/kelas-perkuliahan/trashed', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'trash'])->name('akademik.kelas-perkuliahan-trash');
    Route::post('/akademik/kelas-perkuliahan', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'store'])->name('akademik.kelas-perkuliahan-store');
    Route::patch('/akademik/kelas-perkuliahan/{id}/update', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'update'])->name('akademik.kelas-perkuliahan-update');
    Route::delete('/akademik/kelas-perkuliahan/{id}/delete', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'destroy'])->name('akademik.kelas-perkuliahan-destroy');
    Route::post('/akademik/kelas-perkuliahan/{id}/restore', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'restore'])->name('akademik.kelas-perkuliahan-restore');

    Route::post('/akademik/kelas-perkuliahan/{id}/mahasiswa', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'storeMahasiswa'])->name('akademik.kelas-perkuliahan-store-mahasiswa');
    Route::delete('/akademik/kelas-perkuliahan/{kelasId}/mahasiswa/{mahasiswaId}', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'removeMahasiswa'])->name('akademik.kelas-perkuliahan-remove-mahasiswa');
    Route::post('/akademik/kelas-perkuliahan/{id}/jadwal', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'storeJadwal'])->name('akademik.kelas-perkuliahan-store-jadwal');
    Route::delete('/akademik/kelas-perkuliahan/{kelasId}/jadwal/{jadwalId}', [App\Http\Controllers\Master\Akademik\KelasPerkuliahanController::class, 'removeJadwal'])->name('akademik.kelas-perkuliahan-remove-jadwal');

    Route::get('/akademik/jadwal-perkuliahan', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'index'])->name('akademik.jadwal-perkuliahan-index');
    Route::get('/akademik/jadwal-perkuliahan/view/{id}', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'view'])->name('akademik.jadwal-perkuliahan-view');
    Route::get('/akademik/jadwal-perkuliahan/trashed', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'trash'])->name('akademik.jadwal-perkuliahan-trash');
    Route::post('/akademik/jadwal-perkuliahan', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'store'])->name('akademik.jadwal-perkuliahan-store');
    Route::patch('/akademik/jadwal-perkuliahan/{id}/update', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'update'])->name('akademik.jadwal-perkuliahan-update');
    Route::delete('/akademik/jadwal-perkuliahan/{id}/delete', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'destroy'])->name('akademik.jadwal-perkuliahan-destroy');
    Route::post('/akademik/jadwal-perkuliahan/{id}/restore', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'restore'])->name('akademik.jadwal-perkuliahan-restore');

    Route::get('/akademik/kelas-mahasiswa', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'index'])->name('akademik.kelas-mahasiswa-index');
    Route::get('/akademik/kelas-mahasiswa/trashed', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'trash'])->name('akademik.kelas-mahasiswa-trash');
    Route::post('/akademik/kelas-mahasiswa', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'store'])->name('akademik.kelas-mahasiswa-store');
    Route::patch('/akademik/kelas-mahasiswa/{id}/update', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'update'])->name('akademik.kelas-mahasiswa-update');
    Route::delete('/akademik/kelas-mahasiswa/{id}/delete', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'destroy'])->name('akademik.kelas-mahasiswa-destroy');
    Route::post('/akademik/kelas-mahasiswa/{id}/restore', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'restore'])->name('akademik.kelas-mahasiswa-restore');

});

Route::middleware(['auth', 'active_role:tendik'])->prefix('tendik')->as('tendik.')->group(function () {
    require __DIR__.'/basic-routes.php';
    // Other Menus for Tendik can be added here

    Route::middleware('active_subrole:sekprodi')->group(function () {
        Route::get('/akademik/kelas-mahasiswa', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'index'])->name('akademik.kelas-mahasiswa-index');
        Route::get('/akademik/kelas-mahasiswa/trashed', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'trash'])->name('akademik.kelas-mahasiswa-trash');
        Route::post('/akademik/kelas-mahasiswa', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'store'])->name('akademik.kelas-mahasiswa-store');
        Route::patch('/akademik/kelas-mahasiswa/{id}/update', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'update'])->name('akademik.kelas-mahasiswa-update');
        Route::delete('/akademik/kelas-mahasiswa/{id}/delete', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'destroy'])->name('akademik.kelas-mahasiswa-destroy');
        Route::post('/akademik/kelas-mahasiswa/{id}/restore', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'restore'])->name('akademik.kelas-mahasiswa-restore');

    });
    Route::middleware('active_subrole:baak')->group(function () {
        Route::get('/akademik/jadwal-perkuliahan', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'index'])->name('akademik.jadwal-perkuliahan-index');
        Route::get('/akademik/jadwal-perkuliahan/view/{id}', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'view'])->name('akademik.jadwal-perkuliahan-view');
        Route::get('/akademik/jadwal-perkuliahan/trashed', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'trash'])->name('akademik.jadwal-perkuliahan-trash');
        Route::post('/akademik/jadwal-perkuliahan', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'store'])->name('akademik.jadwal-perkuliahan-store');
        Route::patch('/akademik/jadwal-perkuliahan/{id}/update', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'update'])->name('akademik.jadwal-perkuliahan-update');
        Route::delete('/akademik/jadwal-perkuliahan/{id}/delete', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'destroy'])->name('akademik.jadwal-perkuliahan-destroy');
        Route::post('/akademik/jadwal-perkuliahan/{id}/restore', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'restore'])->name('akademik.jadwal-perkuliahan-restore');

    });

});

Route::middleware(['auth', 'active_role:dosen'])->prefix('dosen')->as('dosen.')->group(function () {
    require __DIR__.'/basic-routes.php';
    // Other Menus for Dosen can be added here

});
Route::middleware(['auth', 'active_role:mahasiswa'])->prefix('mahasiswa')->as('mahasiswa.')->group(function () {
    require __DIR__.'/basic-routes.php';
    // Other Menus for Mahasiswa can be added here
});
Route::middleware(['auth', 'active_role:peserta-pmb'])->prefix('peserta-pmb')->as('peserta-pmb.')->group(function () {
    require __DIR__.'/basic-routes.php';
    // Other Menus for Peserta PMB can be added here

});
Route::middleware(['auth', 'active_role:alumni'])->prefix('alumni')->as('alumni.')->group(function () {
    require __DIR__.'/basic-routes.php';
    // Other Menus for Alumni can be added here

});

// Route::group(['prefix' => 'superuser', 'middleware' => ['auth:web','role:Super Admin'], 'as' => 'super.'],function(){

//     // Global Route
//     require __DIR__.'/basic-routes.php';

//     // Master Authority
//     require __DIR__.'/master-routes.php';

// });
