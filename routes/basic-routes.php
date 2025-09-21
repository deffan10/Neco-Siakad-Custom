<?php

use Illuminate\Support\Facades\Route;


    Route::get('/logout', [App\Http\Controllers\AuthController::class, 'handleLogout'])->name('auth.handle-logout');
    // GLOBAL MENU AUTHENTIKASI
    Route::get('/dashboard', [App\Http\Controllers\Private\User\RootController::class, 'renderDashboard'])->name('dashboard-index');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Private\User\RootController::class, 'renderProfile'])->name('profile-index');
    Route::post('/profile', [App\Http\Controllers\Private\User\RootController::class, 'handleProfile'])->name('profile-update');
    Route::delete('/profile/pendidikan/{id}', [App\Http\Controllers\Private\User\RootController::class, 'deletePendidikan'])->name('profile.delete-pendidikan');
    Route::delete('/profile/keluarga/{id}', [App\Http\Controllers\Private\User\RootController::class, 'deleteKeluarga'])->name('profile.delete-keluarga');