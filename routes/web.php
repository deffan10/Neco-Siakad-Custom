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
    Route::delete('/users/pendidikan/{id}', [App\Http\Controllers\Master\Users\UsersController::class, 'deletePendidikan'])->name('users.delete-pendidikan');
    Route::delete('/users/keluarga/{id}', [App\Http\Controllers\Master\Users\UsersController::class, 'deleteKeluarga'])->name('users.delete-keluarga');

    Route::get('/users/role', [App\Http\Controllers\Master\Users\RoleController::class, 'index'])->name('users.role-index');
    Route::get('/users/role/{id}/view', [App\Http\Controllers\Master\Users\RoleController::class, 'show'])->name('users.role-view');
    Route::get('/users/role/trashed', [App\Http\Controllers\Master\Users\RoleController::class, 'trash'])->name('users.role-trash');
    Route::post('/users/role', [App\Http\Controllers\Master\Users\RoleController::class, 'store'])->name('users.role-store');
    Route::patch('/users/role/{id}/update', [App\Http\Controllers\Master\Users\RoleController::class, 'update'])->name('users.role-update');
    Route::delete('/users/role/{id}/delete', [App\Http\Controllers\Master\Users\RoleController::class, 'destroy'])->name('users.role-destroy');
    Route::post('/users/role/{id}/restore', [App\Http\Controllers\Master\Users\RoleController::class, 'restore'])->name('users.role-restore');
    Route::delete('/users/role/{id}/force-delete', [App\Http\Controllers\Master\Users\RoleController::class, 'forceDelete'])->name('users.role-force-delete');
    // Export/Import Role
    Route::get('/users/role/export-excel', [App\Http\Controllers\Master\Users\RoleController::class, 'exportExcel'])->name('users.role-export-excel');
    Route::get('/users/role/export-csv', [App\Http\Controllers\Master\Users\RoleController::class, 'exportCSV'])->name('users.role-export-csv');
    Route::get('/users/role/export-pdf', [App\Http\Controllers\Master\Users\RoleController::class, 'exportPDF'])->name('users.role-export-pdf');
    Route::post('/users/role/import-excel', [App\Http\Controllers\Master\Users\RoleController::class, 'importExcel'])->name('users.role-import-excel');
    Route::get('/users/role/download-template', [App\Http\Controllers\Master\Users\RoleController::class, 'downloadTemplate'])->name('users.role-template');

    Route::get('/users/subrole', [App\Http\Controllers\Master\Users\SubroleController::class, 'index'])->name('users.subrole-index');
    Route::get('/users/subrole/{id}/view', [App\Http\Controllers\Master\Users\SubroleController::class, 'show'])->name('users.subrole-view');
    Route::get('/users/subrole/trashed', [App\Http\Controllers\Master\Users\SubroleController::class, 'trash'])->name('users.subrole-trash');
    Route::post('/users/subrole', [App\Http\Controllers\Master\Users\SubroleController::class, 'store'])->name('users.subrole-store');
    Route::patch('/users/subrole/{id}/update', [App\Http\Controllers\Master\Users\SubroleController::class, 'update'])->name('users.subrole-update');
    Route::delete('/users/subrole/{id}/delete', [App\Http\Controllers\Master\Users\SubroleController::class, 'destroy'])->name('users.subrole-destroy');
    Route::post('/users/subrole/{id}/restore', [App\Http\Controllers\Master\Users\SubroleController::class, 'restore'])->name('users.subrole-restore');

    Route::get('/users/alamat', [App\Http\Controllers\Master\Users\AlamatController::class, 'index'])->name('users.alamat-index');
    Route::get('/users/alamat/export-excel', [App\Http\Controllers\Master\Users\AlamatController::class, 'exportExcel'])->name('users.alamat-export-excel');
    Route::get('/users/alamat/export-csv', [App\Http\Controllers\Master\Users\AlamatController::class, 'exportCSV'])->name('users.alamat-export-csv');
    Route::get('/users/alamat/export-pdf', [App\Http\Controllers\Master\Users\AlamatController::class, 'exportPDF'])->name('users.alamat-export-pdf');
    Route::post('/users/alamat/import-excel', [App\Http\Controllers\Master\Users\AlamatController::class, 'importExcel'])->name('users.alamat-import');
    Route::get('/users/alamat/template', [App\Http\Controllers\Master\Users\AlamatController::class, 'downloadTemplate'])->name('users.alamat-template');
    Route::get('/users/alamat/trashed', [App\Http\Controllers\Master\Users\AlamatController::class, 'trash'])->name('users.alamat-trash');
    Route::post('/users/alamat', [App\Http\Controllers\Master\Users\AlamatController::class, 'store'])->name('users.alamat-store');
    Route::patch('/users/alamat/{id}/update', [App\Http\Controllers\Master\Users\AlamatController::class, 'update'])->name('users.alamat-update');
    Route::delete('/users/alamat/{id}/delete', [App\Http\Controllers\Master\Users\AlamatController::class, 'destroy'])->name('users.alamat-destroy');
    Route::post('/users/alamat/{id}/restore', [App\Http\Controllers\Master\Users\AlamatController::class, 'restore'])->name('users.alamat-restore');
    Route::delete('/users/alamat/{id}/force-delete', [App\Http\Controllers\Master\Users\AlamatController::class, 'forceDelete'])->name('users.alamat-force-delete');

    Route::get('/users/keluarga', [App\Http\Controllers\Master\Users\KeluargaController::class, 'index'])->name('users.keluarga-index');
    Route::get('/users/keluarga/export-excel', [App\Http\Controllers\Master\Users\KeluargaController::class, 'exportExcel'])->name('users.keluarga-export-excel');
    Route::get('/users/keluarga/export-csv', [App\Http\Controllers\Master\Users\KeluargaController::class, 'exportCSV'])->name('users.keluarga-export-csv');
    Route::get('/users/keluarga/export-pdf', [App\Http\Controllers\Master\Users\KeluargaController::class, 'exportPDF'])->name('users.keluarga-export-pdf');
    Route::post('/users/keluarga/import-excel', [App\Http\Controllers\Master\Users\KeluargaController::class, 'importExcel'])->name('users.keluarga-import');
    Route::get('/users/keluarga/template', [App\Http\Controllers\Master\Users\KeluargaController::class, 'downloadTemplate'])->name('users.keluarga-template');
    Route::get('/users/keluarga/trashed', [App\Http\Controllers\Master\Users\KeluargaController::class, 'trash'])->name('users.keluarga-trash');
    Route::post('/users/keluarga', [App\Http\Controllers\Master\Users\KeluargaController::class, 'store'])->name('users.keluarga-store');
    Route::patch('/users/keluarga/{id}/update', [App\Http\Controllers\Master\Users\KeluargaController::class, 'update'])->name('users.keluarga-update');
    Route::delete('/users/keluarga/{id}/delete', [App\Http\Controllers\Master\Users\KeluargaController::class, 'destroy'])->name('users.keluarga-destroy');
    Route::post('/users/keluarga/{id}/restore', [App\Http\Controllers\Master\Users\KeluargaController::class, 'restore'])->name('users.keluarga-restore');
    Route::delete('/users/keluarga/{id}/force-delete', [App\Http\Controllers\Master\Users\KeluargaController::class, 'forceDelete'])->name('users.keluarga-force-delete');

    Route::get('/users/pendidikan', [App\Http\Controllers\Master\Users\PendidikanController::class, 'index'])->name('users.pendidikan-index');
    Route::get('/users/pendidikan/export-excel', [App\Http\Controllers\Master\Users\PendidikanController::class, 'exportExcel'])->name('users.pendidikan-export-excel');
    Route::get('/users/pendidikan/export-csv', [App\Http\Controllers\Master\Users\PendidikanController::class, 'exportCSV'])->name('users.pendidikan-export-csv');
    Route::get('/users/pendidikan/export-pdf', [App\Http\Controllers\Master\Users\PendidikanController::class, 'exportPDF'])->name('users.pendidikan-export-pdf');
    Route::post('/users/pendidikan/import-excel', [App\Http\Controllers\Master\Users\PendidikanController::class, 'importExcel'])->name('users.pendidikan-import');
    Route::get('/users/pendidikan/template', [App\Http\Controllers\Master\Users\PendidikanController::class, 'downloadTemplate'])->name('users.pendidikan-template');
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

    Route::get('/referensi/role', [App\Http\Controllers\Referensi\RoleController::class, 'index'])->name('referensi.role-index');
    Route::get('/referensi/role/export-excel', [App\Http\Controllers\Referensi\RoleController::class, 'exportExcel'])->name('referensi.role-export-excel');
    Route::get('/referensi/role/export-csv', [App\Http\Controllers\Referensi\RoleController::class, 'exportCSV'])->name('referensi.role-export-csv');
    Route::get('/referensi/role/export-pdf', [App\Http\Controllers\Referensi\RoleController::class, 'exportPDF'])->name('referensi.role-export-pdf');
    Route::post('/referensi/role/import-excel', [App\Http\Controllers\Referensi\RoleController::class, 'importExcel'])->name('referensi.role-import');
    Route::get('/referensi/role/template', [App\Http\Controllers\Referensi\RoleController::class, 'downloadTemplate'])->name('referensi.role-template');
    Route::get('/referensi/role/trashed', [App\Http\Controllers\Referensi\RoleController::class, 'trash'])->name('referensi.role-trash');
    Route::post('/referensi/role', [App\Http\Controllers\Referensi\RoleController::class, 'store'])->name('referensi.role-store');
    Route::patch('/referensi/role/{id}/update', [App\Http\Controllers\Referensi\RoleController::class, 'update'])->name('referensi.role-update');
    Route::delete('/referensi/role/{id}/delete', [App\Http\Controllers\Referensi\RoleController::class, 'destroy'])->name('referensi.role-destroy');
    Route::post('/referensi/role/{id}/restore', [App\Http\Controllers\Referensi\RoleController::class, 'restore'])->name('referensi.role-restore');

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
    Route::get('/akademik/jadwal-perkuliahan/ruang-kosong', [App\Http\Controllers\Master\Akademik\JadwalPerkuliahanController::class, 'ruangKosong'])->name('akademik.jadwal-perkuliahan-ruang-kosong');

    Route::get('/akademik/kelas-mahasiswa', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'index'])->name('akademik.kelas-mahasiswa-index');
    Route::get('/akademik/kelas-mahasiswa/trashed', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'trash'])->name('akademik.kelas-mahasiswa-trash');
    Route::post('/akademik/kelas-mahasiswa', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'store'])->name('akademik.kelas-mahasiswa-store');
    Route::patch('/akademik/kelas-mahasiswa/{id}/update', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'update'])->name('akademik.kelas-mahasiswa-update');
    Route::delete('/akademik/kelas-mahasiswa/{id}/delete', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'destroy'])->name('akademik.kelas-mahasiswa-destroy');
    Route::post('/akademik/kelas-mahasiswa/{id}/restore', [App\Http\Controllers\Master\Akademik\KelasMahasiswaController::class, 'restore'])->name('akademik.kelas-mahasiswa-restore');

    // Modul Keuangan (Admin)
    Route::get('/keuangan/tarif', [App\Http\Controllers\Keuangan\KeuanganController::class, 'indexTarif'])->name('keuangan.tarif-index');
    Route::post('/keuangan/komponen', [App\Http\Controllers\Keuangan\KeuanganController::class, 'storeKomponen'])->name('keuangan.komponen-store');
    Route::post('/keuangan/tarif', [App\Http\Controllers\Keuangan\KeuanganController::class, 'storeTarif'])->name('keuangan.tarif-store');
    Route::get('/keuangan/tagihan', [App\Http\Controllers\Keuangan\KeuanganController::class, 'indexTagihan'])->name('keuangan.tagihan-index');
    Route::post('/keuangan/tagihan', [App\Http\Controllers\Keuangan\KeuanganController::class, 'storeTagihan'])->name('keuangan.tagihan-store');
    Route::post('/keuangan/tagihan/generate-massal', [App\Http\Controllers\Keuangan\KeuanganController::class, 'generateTagihanMassal'])->name('keuangan.tagihan-generate-massal');
    Route::get('/keuangan/pembayaran', [App\Http\Controllers\Keuangan\KeuanganController::class, 'indexPembayaran'])->name('keuangan.pembayaran-index');
    Route::get('/keuangan/pembayaran/{id}/verify/{action}', [App\Http\Controllers\Keuangan\KeuanganController::class, 'verifikasiPembayaran'])->name('keuangan.pembayaran-verify');

    // Modul KRS (Admin)
    Route::get('/krs/settings', [App\Http\Controllers\Akademik\KrsController::class, 'indexAdminKrs'])->name('krs.settings');
    Route::post('/krs/waktu', [App\Http\Controllers\Akademik\KrsController::class, 'storeWaktuKrs'])->name('krs.waktu-store');
    Route::post('/krs/syarat', [App\Http\Controllers\Akademik\KrsController::class, 'storeSyaratSks'])->name('krs.syarat-store');
    Route::get('/krs/perwalian', [App\Http\Controllers\Akademik\KrsController::class, 'indexBimbinganPa'])->name('krs.perwalian');
    Route::post('/krs/approve/{id}', [App\Http\Controllers\Akademik\KrsController::class, 'approveKrs'])->name('krs.approve');

    // Modul Nilai (Admin)
    Route::get('/nilai/kelas', [App\Http\Controllers\Akademik\NilaiController::class, 'indexKelas'])->name('nilai.kelas-index');
    Route::get('/nilai/kelas/{id}/form', [App\Http\Controllers\Akademik\NilaiController::class, 'showFormNilai'])->name('nilai.kelas-form');
    Route::post('/nilai/kelas/{id}/store', [App\Http\Controllers\Akademik\NilaiController::class, 'storeNilai'])->name('nilai.kelas-store');
    Route::get('/nilai/proses-ipk', [App\Http\Controllers\Akademik\NilaiController::class, 'showProsesIpk'])->name('nilai.proses-ipk');
    Route::post('/nilai/hitung-ipk', [App\Http\Controllers\Akademik\NilaiController::class, 'hitungIpsIpkMassal'])->name('nilai.hitung-ipk');
    Route::get('/nilai/komponen', [App\Http\Controllers\Akademik\NilaiController::class, 'komponen'])->name('nilai.komponen');
    Route::get('/nilai/entri-aturan', [App\Http\Controllers\Akademik\NilaiController::class, 'entri'])->name('nilai.entri');

    // Impersonate (Admin trigger)
    Route::get('/impersonate/{id}', [App\Http\Controllers\Admin\ImpersonateController::class, 'impersonate'])->name('impersonate');

    // Modul Wisuda (Admin)
    Route::get('/wisuda/settings', [App\Http\Controllers\Akademik\WisudaController::class, 'indexAdmin'])->name('wisuda.settings');
    Route::post('/wisuda/settings', [App\Http\Controllers\Akademik\WisudaController::class, 'storeKegiatan'])->name('wisuda.settings.store');
    Route::post('/wisuda/{id}/toggle', [App\Http\Controllers\Akademik\WisudaController::class, 'toggleKegiatan'])->name('wisuda.toggle');
    Route::get('/wisuda/{id}/applicants', [App\Http\Controllers\Akademik\WisudaController::class, 'applicants'])->name('wisuda.applicants');
    Route::post('/wisuda/{id}/verify', [App\Http\Controllers\Akademik\WisudaController::class, 'verifyApplicant'])->name('wisuda.verify');
    Route::get('/wisuda/{id}/presensi', [App\Http\Controllers\Akademik\WisudaController::class, 'presensi'])->name('wisuda.presensi');
    Route::post('/wisuda/{id}/presensi', [App\Http\Controllers\Akademik\WisudaController::class, 'storePresensi'])->name('wisuda.presensi.store');
    Route::get('/wisuda/syarat', [App\Http\Controllers\Akademik\WisudaController::class, 'syarat'])->name('wisuda.syarat');
    Route::get('/wisuda/pengaturan', [App\Http\Controllers\Akademik\WisudaController::class, 'pengaturan'])->name('wisuda.pengaturan');

    // Modul SKL (Admin)
    Route::get('/skl', [App\Http\Controllers\Akademik\SklController::class, 'indexAdmin'])->name('skl.index');
    Route::get('/skl/create', [App\Http\Controllers\Akademik\SklController::class, 'createForm'])->name('skl.create');
    Route::post('/skl', [App\Http\Controllers\Akademik\SklController::class, 'store'])->name('skl.store');
    Route::get('/skl/{id}/print', [App\Http\Controllers\Akademik\SklController::class, 'print'])->name('skl.print');
    Route::delete('/skl/{id}', [App\Http\Controllers\Akademik\SklController::class, 'destroy'])->name('skl.destroy');
    Route::get('/skl/get-student-data/{id}', [App\Http\Controllers\Akademik\SklController::class, 'getStudentData'])->name('skl.student-data');

    // Modul Kuesioner (Admin)
    Route::get('/kuesioner', [App\Http\Controllers\Akademik\KuesionerController::class, 'indexAdmin'])->name('kuesioner.index');
    Route::post('/kuesioner', [App\Http\Controllers\Akademik\KuesionerController::class, 'store'])->name('kuesioner.store');
    Route::post('/kuesioner/{id}/toggle', [App\Http\Controllers\Akademik\KuesionerController::class, 'togglePublish'])->name('kuesioner.toggle');
    Route::delete('/kuesioner/{id}', [App\Http\Controllers\Akademik\KuesionerController::class, 'destroy'])->name('kuesioner.destroy');
    Route::get('/kuesioner/{id}/questions', [App\Http\Controllers\Akademik\KuesionerController::class, 'questions'])->name('kuesioner.questions');
    Route::post('/kuesioner/{id}/questions', [App\Http\Controllers\Akademik\KuesionerController::class, 'storeQuestion'])->name('kuesioner.questions.store');
    Route::delete('/kuesioner/questions/{id}', [App\Http\Controllers\Akademik\KuesionerController::class, 'destroyQuestion'])->name('kuesioner.questions.destroy');
    Route::get('/kuesioner/{id}/results', [App\Http\Controllers\Akademik\KuesionerController::class, 'results'])->name('kuesioner.results');

    // Lock Jurnal (Admin)
    Route::get('/lock-jurnal', [App\Http\Controllers\Akademik\LockJurnalController::class, 'indexAdmin'])->name('lock-jurnal.index');
    Route::post('/lock-jurnal/{id}/toggle', [App\Http\Controllers\Akademik\LockJurnalController::class, 'toggleLock'])->name('lock-jurnal.toggle');
    Route::post('/lock-jurnal/bulk', [App\Http\Controllers\Akademik\LockJurnalController::class, 'bulkLock'])->name('lock-jurnal.bulk');
    Route::get('/lock-jurnal/{id}/presensi', [App\Http\Controllers\Akademik\LockJurnalController::class, 'presensiAdmin'])->name('lock-jurnal.presensi');
    Route::post('/lock-jurnal/{id}/presensi', [App\Http\Controllers\Akademik\LockJurnalController::class, 'storePresensiAdmin'])->name('lock-jurnal.presensi-store');

    // Cuti Akademik (Admin)
    Route::get('/cuti', [App\Http\Controllers\Akademik\CutiController::class, 'indexAdmin'])->name('cuti.index');
    Route::post('/cuti/{id}/approve', [App\Http\Controllers\Akademik\CutiController::class, 'approveAdmin'])->name('cuti.approve');
    Route::post('/cuti/{id}/reject', [App\Http\Controllers\Akademik\CutiController::class, 'rejectAdmin'])->name('cuti.reject');

    // Tugas Akhir (Admin)
    Route::get('/tugas-akhir', [App\Http\Controllers\Akademik\TugasAkhirController::class, 'indexAdmin'])->name('tugas-akhir.index');
    Route::post('/tugas-akhir/{id}/approve', [App\Http\Controllers\Akademik\TugasAkhirController::class, 'approveAdmin'])->name('tugas-akhir.approve');
    Route::post('/tugas-akhir/{id}/reject', [App\Http\Controllers\Akademik\TugasAkhirController::class, 'rejectAdmin'])->name('tugas-akhir.reject');

    // Broadcast Email (Admin)
    Route::get('/broadcast', [App\Http\Controllers\Akademik\BroadcastController::class, 'index'])->name('broadcast.index');
    Route::get('/broadcast/create', [App\Http\Controllers\Akademik\BroadcastController::class, 'createForm'])->name('broadcast.create');
    Route::post('/broadcast/send', [App\Http\Controllers\Akademik\BroadcastController::class, 'send'])->name('broadcast.send');

    // Event Seminar (Admin)
    Route::get('/seminar', [App\Http\Controllers\Akademik\EventSeminarController::class, 'indexAdmin'])->name('seminar.index');
    Route::post('/seminar', [App\Http\Controllers\Akademik\EventSeminarController::class, 'store'])->name('seminar.store');
    Route::post('/seminar/{id}/toggle', [App\Http\Controllers\Akademik\EventSeminarController::class, 'toggleOpen'])->name('seminar.toggle');
    Route::delete('/seminar/{id}', [App\Http\Controllers\Akademik\EventSeminarController::class, 'destroy'])->name('seminar.destroy');
    Route::get('/seminar/{id}/peserta', [App\Http\Controllers\Akademik\EventSeminarController::class, 'peserta'])->name('seminar.peserta');
    Route::post('/seminar/peserta/{id}/status', [App\Http\Controllers\Akademik\EventSeminarController::class, 'updateStatusPeserta'])->name('seminar.update-peserta');
    Route::get('/seminar/kuota', [App\Http\Controllers\Akademik\EventSeminarController::class, 'kuota'])->name('seminar.kuota');
    Route::get('/seminar/cari', [App\Http\Controllers\Akademik\EventSeminarController::class, 'cari'])->name('seminar.cari');

    // Sertifikasi (Admin)
    Route::get('/sertifikasi', [App\Http\Controllers\Akademik\SertifikasiController::class, 'indexAdmin'])->name('sertifikasi.index');
    Route::post('/sertifikasi/{id}/verifikasi', [App\Http\Controllers\Akademik\SertifikasiController::class, 'updateVerifikasi'])->name('sertifikasi.verifikasi');

    // PMB Dashboard (Admin)
    Route::get('/pmb/dashboard', [App\Http\Controllers\Akademik\PmbDashboardController::class, 'index'])->name('pmb.dashboard');
    Route::get('/pmb/landing', [App\Http\Controllers\Akademik\PmbDashboardController::class, 'landing'])->name('pmb.landing');
    Route::get('/pmb/settings', [App\Http\Controllers\Akademik\PmbDashboardController::class, 'settings'])->name('pmb.settings');
    Route::get('/pmb/tuition', [App\Http\Controllers\Akademik\PmbDashboardController::class, 'tuition'])->name('pmb.tuition');
    Route::get('/pmb/admission', [App\Http\Controllers\Akademik\PmbDashboardController::class, 'admission'])->name('pmb.admission');
    Route::get('/pmb/promo', [App\Http\Controllers\Akademik\PmbDashboardController::class, 'promo'])->name('pmb.promo');
    Route::get('/pmb/affiliate', [App\Http\Controllers\Akademik\PmbDashboardController::class, 'affiliate'])->name('pmb.affiliate');

    // Export & Laporan (Admin)
    Route::get('/export', [App\Http\Controllers\Akademik\ExportController::class, 'index'])->name('export.index');
    Route::get('/export/mahasiswa/excel', [App\Http\Controllers\Akademik\ExportController::class, 'mahasiswaExcel'])->name('export.mahasiswa-excel');
    Route::get('/export/mahasiswa/pdf', [App\Http\Controllers\Akademik\ExportController::class, 'mahasiswaPdf'])->name('export.mahasiswa-pdf');
    Route::get('/export/keuangan/excel', [App\Http\Controllers\Akademik\ExportController::class, 'keuanganExcel'])->name('export.keuangan-excel');
    Route::get('/export/jurnal/excel', [App\Http\Controllers\Akademik\ExportController::class, 'jurnalDosenExcel'])->name('export.jurnal-excel');
    Route::get('/export/wisuda/excel', [App\Http\Controllers\Akademik\ExportController::class, 'wisudaExcel'])->name('export.wisuda-excel');
    Route::get('/export/pmb/excel', [App\Http\Controllers\Akademik\ExportController::class, 'pmbExcel'])->name('export.pmb-excel');
    Route::get('/export/sertifikasi/excel', [App\Http\Controllers\Akademik\ExportController::class, 'sertifikasiExcel'])->name('export.sertifikasi-excel');
    Route::get('/export/seminar/{eventId}/excel', [App\Http\Controllers\Akademik\ExportController::class, 'seminarPesertaExcel'])->name('export.seminar-peserta-excel');

    // Presensi Kuliah Admin (tab: dosen, mahasiswa, setting)
    Route::get('/presensi-kuliah', [App\Http\Controllers\Akademik\PresensiKuliahAdminController::class, 'indexDosen'])->name('presensi-kuliah.dosen');
    Route::get('/presensi-kuliah/mahasiswa', [App\Http\Controllers\Akademik\PresensiKuliahAdminController::class, 'indexMahasiswa'])->name('presensi-kuliah.mahasiswa');
    Route::get('/presensi-kuliah/setting', [App\Http\Controllers\Akademik\PresensiKuliahAdminController::class, 'settingPresensi'])->name('presensi-kuliah.setting');
    Route::post('/presensi-kuliah/pertemuan/{id}/toggle', [App\Http\Controllers\Akademik\PresensiKuliahAdminController::class, 'toggleLockPertemuan'])->name('presensi-kuliah.toggle-lock');

    // Pesan Internal (tab: masuk, buat, terkirim)
    Route::get('/pesan/masuk', [App\Http\Controllers\Akademik\PesanController::class, 'masuk'])->name('pesan.masuk');
    Route::get('/pesan/buat', [App\Http\Controllers\Akademik\PesanController::class, 'buat'])->name('pesan.buat');
    Route::post('/pesan/kirim', [App\Http\Controllers\Akademik\PesanController::class, 'kirim'])->name('pesan.kirim');
    Route::get('/pesan/terkirim', [App\Http\Controllers\Akademik\PesanController::class, 'terkirim'])->name('pesan.terkirim');
    Route::post('/pesan/{id}/read', [App\Http\Controllers\Akademik\PesanController::class, 'markRead'])->name('pesan.read');

    // Beasiswa (Admin) - Jenis, Data Penerima, Salin
    Route::get('/beasiswa', [App\Http\Controllers\Akademik\BeasiswaController::class, 'indexData'])->name('beasiswa.data');
    Route::post('/beasiswa/data', [App\Http\Controllers\Akademik\BeasiswaController::class, 'storeData'])->name('beasiswa.data-store');
    Route::delete('/beasiswa/data/{id}', [App\Http\Controllers\Akademik\BeasiswaController::class, 'destroyData'])->name('beasiswa.data-destroy');
    Route::get('/beasiswa/jenis', [App\Http\Controllers\Akademik\BeasiswaController::class, 'indexJenis'])->name('beasiswa.jenis');
    Route::post('/beasiswa/jenis', [App\Http\Controllers\Akademik\BeasiswaController::class, 'storeJenis'])->name('beasiswa.jenis-store');
    Route::delete('/beasiswa/jenis/{id}', [App\Http\Controllers\Akademik\BeasiswaController::class, 'destroyJenis'])->name('beasiswa.jenis-destroy');
    Route::get('/beasiswa/salin', [App\Http\Controllers\Akademik\BeasiswaController::class, 'salin'])->name('beasiswa.salin');
    Route::post('/beasiswa/salin', [App\Http\Controllers\Akademik\BeasiswaController::class, 'processSalin'])->name('beasiswa.salin-process');

    // Bimbingan PA Admin
    Route::get('/bimbingan-pa', [App\Http\Controllers\Akademik\BimbinganPaController::class, 'indexAdmin'])->name('bimbingan-pa.index');

    // Pengumuman & Info (Admin)
    Route::get('/pengumuman', [App\Http\Controllers\Akademik\PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/buat', [App\Http\Controllers\Akademik\PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('/pengumuman', [App\Http\Controllers\Akademik\PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::delete('/pengumuman/{id}', [App\Http\Controllers\Akademik\PengumumanController::class, 'destroy'])->name('pengumuman.destroy');
    Route::post('/pengumuman/{id}/toggle', [App\Http\Controllers\Akademik\PengumumanController::class, 'togglePublish'])->name('pengumuman.toggle');
    Route::get('/pengumuman/kategori', [App\Http\Controllers\Akademik\PengumumanController::class, 'kategori'])->name('pengumuman.kategori');
    Route::post('/pengumuman/kategori', [App\Http\Controllers\Akademik\PengumumanController::class, 'storeKategori'])->name('pengumuman.kategori-store');
    Route::delete('/pengumuman/kategori/{id}', [App\Http\Controllers\Akademik\PengumumanController::class, 'destroyKategori'])->name('pengumuman.kategori-destroy');

    // Dispensasi Mahasiswa (Admin)
    Route::get('/dispensasi', [App\Http\Controllers\Akademik\DispensasiController::class, 'index'])->name('dispensasi.index');
    Route::post('/dispensasi', [App\Http\Controllers\Akademik\DispensasiController::class, 'store'])->name('dispensasi.store');
    Route::post('/dispensasi/{id}/approve', [App\Http\Controllers\Akademik\DispensasiController::class, 'approve'])->name('dispensasi.approve');
    Route::post('/dispensasi/{id}/reject', [App\Http\Controllers\Akademik\DispensasiController::class, 'reject'])->name('dispensasi.reject');
    Route::delete('/dispensasi/{id}', [App\Http\Controllers\Akademik\DispensasiController::class, 'destroy'])->name('dispensasi.destroy');

    // Sesi Kuliah (Admin) - Kuliah, Ujian, Kelompok tabs
    Route::get('/sesi-kuliah', [App\Http\Controllers\Akademik\SesiKuliahController::class, 'indexKuliah'])->name('sesi-kuliah.kuliah');
    Route::get('/sesi-kuliah/ujian', [App\Http\Controllers\Akademik\SesiKuliahController::class, 'indexUjian'])->name('sesi-kuliah.ujian');
    Route::get('/sesi-kuliah/kelompok', [App\Http\Controllers\Akademik\SesiKuliahController::class, 'indexKelompok'])->name('sesi-kuliah.kelompok');
    Route::post('/sesi-kuliah', [App\Http\Controllers\Akademik\SesiKuliahController::class, 'store'])->name('sesi-kuliah.store');
    Route::delete('/sesi-kuliah/{id}', [App\Http\Controllers\Akademik\SesiKuliahController::class, 'destroy'])->name('sesi-kuliah.destroy');

    // Surat Tugas Mengajar (Admin)
    Route::get('/surat-tugas', [App\Http\Controllers\Akademik\SuratTugasController::class, 'index'])->name('surat-tugas.index');
    Route::post('/surat-tugas', [App\Http\Controllers\Akademik\SuratTugasController::class, 'store'])->name('surat-tugas.store');
    Route::delete('/surat-tugas/{id}', [App\Http\Controllers\Akademik\SuratTugasController::class, 'destroy'])->name('surat-tugas.destroy');

    // Konversi Nilai (Admin)
    Route::get('/konversi-nilai', [App\Http\Controllers\Akademik\KonversiNilaiController::class, 'index'])->name('konversi-nilai.index');
    Route::post('/konversi-nilai', [App\Http\Controllers\Akademik\KonversiNilaiController::class, 'store'])->name('konversi-nilai.store');
    Route::delete('/konversi-nilai/{id}', [App\Http\Controllers\Akademik\KonversiNilaiController::class, 'destroy'])->name('konversi-nilai.destroy');

    // Cetak Kartu & Absensi (Admin)
    Route::get('/cetak-kartu', [App\Http\Controllers\Akademik\CetakKartuController::class, 'index'])->name('cetak-kartu.index');
    Route::get('/cetak-kartu/krs', [App\Http\Controllers\Akademik\CetakKartuController::class, 'printKrs'])->name('cetak-kartu.krs');
    Route::get('/cetak-kartu/ujian', [App\Http\Controllers\Akademik\CetakKartuController::class, 'printKartuUjian'])->name('cetak-kartu.ujian');
    Route::get('/cetak-kartu/absensi-kuliah', [App\Http\Controllers\Akademik\CetakKartuController::class, 'printAbsensiKuliah'])->name('cetak-kartu.absensi-kuliah');
    Route::get('/cetak-kartu/absensi-ujian', [App\Http\Controllers\Akademik\CetakKartuController::class, 'printAbsensiUjian'])->name('cetak-kartu.absensi-ujian');

    // Event Seminar (Admin)
    Route::get('/event-seminar', [App\Http\Controllers\Akademik\EventSeminarController::class, 'indexAdmin'])->name('event-seminar.index');
    Route::post('/event-seminar', [App\Http\Controllers\Akademik\EventSeminarController::class, 'store'])->name('event-seminar.store');
    Route::post('/event-seminar/{id}/toggle-open', [App\Http\Controllers\Akademik\EventSeminarController::class, 'toggleOpen'])->name('event-seminar.toggle-open');
    Route::delete('/event-seminar/{id}', [App\Http\Controllers\Akademik\EventSeminarController::class, 'destroy'])->name('event-seminar.destroy');
    Route::get('/event-seminar/{id}/peserta', [App\Http\Controllers\Akademik\EventSeminarController::class, 'peserta'])->name('event-seminar.peserta');
    Route::post('/event-seminar/peserta/{pesertaId}/status', [App\Http\Controllers\Akademik\EventSeminarController::class, 'updateStatusPeserta'])->name('event-seminar.peserta.status');
    Route::get('/event-seminar/kuota', [App\Http\Controllers\Akademik\EventSeminarController::class, 'kuota'])->name('event-seminar.kuota');
    Route::get('/event-seminar/cari', [App\Http\Controllers\Akademik\EventSeminarController::class, 'cari'])->name('event-seminar.cari');

    // Alumni (Admin)
    Route::get('/alumni', [App\Http\Controllers\Akademik\AlumniController::class, 'index'])->name('alumni.index');
    Route::post('/alumni', [App\Http\Controllers\Akademik\AlumniController::class, 'store'])->name('alumni.store');
    Route::get('/alumni/album', [App\Http\Controllers\Akademik\AlumniController::class, 'album'])->name('alumni.album');
    Route::delete('/alumni/{id}', [App\Http\Controllers\Akademik\AlumniController::class, 'destroy'])->name('alumni.destroy');

    // Asisten Lab (Admin)
    Route::get('/asisten-lab', [App\Http\Controllers\Akademik\AsistenLabController::class, 'index'])->name('asisten-lab.index');
    Route::post('/asisten-lab', [App\Http\Controllers\Akademik\AsistenLabController::class, 'store'])->name('asisten-lab.store');
    Route::delete('/asisten-lab/{id}', [App\Http\Controllers\Akademik\AsistenLabController::class, 'destroy'])->name('asisten-lab.destroy');
    Route::post('/asisten-lab/assign', [App\Http\Controllers\Akademik\AsistenLabController::class, 'assignMk'])->name('asisten-lab.assign');
    Route::delete('/asisten-lab/assign/{id}', [App\Http\Controllers\Akademik\AsistenLabController::class, 'destroyAssignment'])->name('asisten-lab.assign-destroy');

    // Badan Hukum & Perguruan Tinggi Setting (Admin)
    Route::get('/badan-hukum-pt', [App\Http\Controllers\Akademik\KampusSettingController::class, 'index'])->name('kampus-setting.index');
    Route::post('/badan-hukum-pt', [App\Http\Controllers\Akademik\KampusSettingController::class, 'update'])->name('kampus-setting.update');

    // Pegawai / Staff Management (Admin)
    Route::get('/pegawai', [App\Http\Controllers\Akademik\PegawaiController::class, 'index'])->name('pegawai.index');
    Route::post('/pegawai', [App\Http\Controllers\Akademik\PegawaiController::class, 'store'])->name('pegawai.store');
    Route::put('/pegawai/{id}', [App\Http\Controllers\Akademik\PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{id}', [App\Http\Controllers\Akademik\PegawaiController::class, 'destroy'])->name('pegawai.destroy');

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
    
    // Perwalian Dosen
    Route::get('/krs/perwalian', [App\Http\Controllers\Akademik\KrsController::class, 'indexBimbinganPa'])->name('krs.perwalian');
    Route::post('/krs/approve/{id}', [App\Http\Controllers\Akademik\KrsController::class, 'approveKrs'])->name('krs.approve');

    // Input Nilai Dosen
    Route::get('/nilai/kelas', [App\Http\Controllers\Akademik\NilaiController::class, 'indexKelas'])->name('nilai.kelas-index');
    Route::get('/nilai/kelas/{id}/form', [App\Http\Controllers\Akademik\NilaiController::class, 'showFormNilai'])->name('nilai.kelas-form');
    Route::post('/nilai/kelas/{id}/store', [App\Http\Controllers\Akademik\NilaiController::class, 'storeNilai'])->name('nilai.kelas-store');

    // Jurnal Mengajar Dosen
    Route::get('/jurnal', [App\Http\Controllers\Akademik\LockJurnalController::class, 'indexDosen'])->name('jurnal.index');
    Route::post('/jurnal/{id}/update', [App\Http\Controllers\Akademik\LockJurnalController::class, 'updateJurnal'])->name('jurnal.update');
    Route::get('/jurnal/{id}/presensi', [App\Http\Controllers\Akademik\LockJurnalController::class, 'presensiDosen'])->name('jurnal.presensi');
    Route::post('/jurnal/{id}/presensi', [App\Http\Controllers\Akademik\LockJurnalController::class, 'storePresensiDosen'])->name('jurnal.presensi-store');
    Route::get('/jurnal/{id}/bahan-tugas', [App\Http\Controllers\Akademik\BahanTugasController::class, 'indexDosen'])->name('jurnal.bahan-tugas');
    Route::post('/jurnal/{id}/bahan-tugas', [App\Http\Controllers\Akademik\BahanTugasController::class, 'storeDosen'])->name('jurnal.bahan-tugas-store');
    Route::get('/tugas/{id}/submisi', [App\Http\Controllers\Akademik\BahanTugasController::class, 'indexSubmissions'])->name('tugas.submisi');
    Route::post('/tugas/{submission_id}/nilai', [App\Http\Controllers\Akademik\BahanTugasController::class, 'gradeSubmission'])->name('tugas.nilai');

    // Perwalian Dosen (Advisees)
    Route::get('/perwalian', [App\Http\Controllers\Akademik\BimbinganPaController::class, 'indexDosen'])->name('perwalian.index');
    Route::get('/perwalian/{mahasiswa_id}', [App\Http\Controllers\Akademik\BimbinganPaController::class, 'showAdvising'])->name('perwalian.show');
    Route::post('/perwalian/{mahasiswa_id}', [App\Http\Controllers\Akademik\BimbinganPaController::class, 'storeDosen'])->name('perwalian.store');
});

Route::middleware(['auth', 'active_role:mahasiswa'])->prefix('mahasiswa')->as('mahasiswa.')->group(function () {
    require __DIR__.'/basic-routes.php';
    
    // Keuangan Mahasiswa
    Route::get('/keuangan', [App\Http\Controllers\Keuangan\KeuanganController::class, 'indexMahasiswaKeuangan'])->name('keuangan.index');
    Route::post('/keuangan/{id}/pay', [App\Http\Controllers\Keuangan\KeuanganController::class, 'paySimulated'])->name('keuangan.pay');

    // KRS Mahasiswa
    Route::get('/krs', [App\Http\Controllers\Akademik\KrsController::class, 'indexMahasiswaKrs'])->name('krs.index');
    Route::post('/krs/submit', [App\Http\Controllers\Akademik\KrsController::class, 'submitKrsDraft'])->name('krs.submit');

    // KHS & Transkrip Mahasiswa
    Route::get('/khs', [App\Http\Controllers\Akademik\NilaiController::class, 'indexKhs'])->name('khs.index');
    Route::get('/transkrip', [App\Http\Controllers\Akademik\NilaiController::class, 'indexTranskrip'])->name('transkrip.index');

    // Wisuda Mahasiswa
    Route::get('/wisuda', [App\Http\Controllers\Akademik\WisudaController::class, 'indexStudent'])->name('wisuda.index');
    Route::post('/wisuda/store', [App\Http\Controllers\Akademik\WisudaController::class, 'storePendaftaran'])->name('wisuda.store');
    Route::get('/wisuda/{id}/submit', [App\Http\Controllers\Akademik\WisudaController::class, 'submitPendaftaran'])->name('wisuda.submit');

    // SKL Mahasiswa
    Route::get('/skl', [App\Http\Controllers\Akademik\SklController::class, 'indexStudent'])->name('skl.index');
    Route::get('/skl/{id}/print', [App\Http\Controllers\Akademik\SklController::class, 'print'])->name('skl.print');

    // Kuesioner Mahasiswa
    Route::get('/kuesioner', [App\Http\Controllers\Akademik\KuesionerController::class, 'indexPortal'])->name('kuesioner.index');
    Route::get('/kuesioner/{id}', [App\Http\Controllers\Akademik\KuesionerController::class, 'showForm'])->name('kuesioner.show');
    Route::post('/kuesioner/{id}/submit', [App\Http\Controllers\Akademik\KuesionerController::class, 'submitForm'])->name('kuesioner.submit');

    // Event Seminar Mahasiswa
    Route::get('/seminar', [App\Http\Controllers\Akademik\EventSeminarController::class, 'indexPortal'])->name('seminar.index');
    Route::post('/seminar/{id}/daftar', [App\Http\Controllers\Akademik\EventSeminarController::class, 'daftar'])->name('seminar.daftar');

    // Sertifikasi Mahasiswa (SKPI)
    Route::get('/sertifikasi', [App\Http\Controllers\Akademik\SertifikasiController::class, 'indexPortal'])->name('sertifikasi.index');
    Route::post('/sertifikasi', [App\Http\Controllers\Akademik\SertifikasiController::class, 'store'])->name('sertifikasi.store');
    Route::delete('/sertifikasi/{id}', [App\Http\Controllers\Akademik\SertifikasiController::class, 'destroy'])->name('sertifikasi.destroy');

    // Jadwal & Presensi Mahasiswa
    Route::get('/jadwal-presensi', [App\Http\Controllers\Akademik\JadwalPresensiController::class, 'index'])->name('jadwal-presensi.index');

    // Cuti Mahasiswa
    Route::get('/cuti', [App\Http\Controllers\Akademik\CutiController::class, 'indexStudent'])->name('cuti.index');
    Route::post('/cuti', [App\Http\Controllers\Akademik\CutiController::class, 'storeStudent'])->name('cuti.store');

    // Bahan & Tugas Mahasiswa
    Route::get('/bahan-tugas', [App\Http\Controllers\Akademik\BahanTugasController::class, 'indexStudent'])->name('bahan-tugas.index');
    Route::get('/bahan-tugas/{kelas_id}', [App\Http\Controllers\Akademik\BahanTugasController::class, 'showClassMaterials'])->name('bahan-tugas.show');
    Route::post('/bahan-tugas/{task_id}/submit', [App\Http\Controllers\Akademik\BahanTugasController::class, 'submitAssignment'])->name('bahan-tugas.submit');

    // PA Online Mahasiswa
    Route::get('/pa-online', [App\Http\Controllers\Akademik\BimbinganPaController::class, 'indexStudent'])->name('pa-online.index');
    Route::post('/pa-online', [App\Http\Controllers\Akademik\BimbinganPaController::class, 'storeStudent'])->name('pa-online.store');

    // Tugas Akhir Mahasiswa
    Route::get('/tugas-akhir', [App\Http\Controllers\Akademik\TugasAkhirController::class, 'indexStudent'])->name('tugas-akhir.index');
    Route::post('/tugas-akhir', [App\Http\Controllers\Akademik\TugasAkhirController::class, 'storeStudent'])->name('tugas-akhir.store');
});

Route::middleware(['auth', 'active_role:peserta-pmb'])->prefix('peserta-pmb')->as('peserta-pmb.')->group(function () {
    require __DIR__.'/basic-routes.php';
});

Route::middleware(['auth', 'active_role:alumni'])->prefix('alumni')->as('alumni.')->group(function () {
    require __DIR__.'/basic-routes.php';

    // Kuesioner Alumni
    Route::get('/kuesioner', [App\Http\Controllers\Akademik\KuesionerController::class, 'indexPortal'])->name('kuesioner.index');
    Route::get('/kuesioner/{id}', [App\Http\Controllers\Akademik\KuesionerController::class, 'showForm'])->name('kuesioner.show');
    Route::post('/kuesioner/{id}/submit', [App\Http\Controllers\Akademik\KuesionerController::class, 'submitForm'])->name('kuesioner.submit');
});

// Global impersonation leave session
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/impersonate/leave', [App\Http\Controllers\Admin\ImpersonateController::class, 'leave'])->name('admin.impersonate.leave');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [App\Http\Controllers\NotifikasiController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotifikasiController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/clear-all', [App\Http\Controllers\NotifikasiController::class, 'clearAll'])->name('notifications.clear-all');
});

// Route::group(['prefix' => 'superuser', 'middleware' => ['auth:web','role:Super Admin'], 'as' => 'super.'],function(){

//     // Global Route
//     require __DIR__.'/basic-routes.php';

//     // Master Authority
//     require __DIR__.'/master-routes.php';

// });
