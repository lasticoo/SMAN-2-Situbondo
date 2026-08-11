<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EPIC 2 - WEBSITE ADMIN (DEVELOPER 1: RIZAL WIBOWO / LASTICO RIDHO ALPARESZ)
|--------------------------------------------------------------------------
| Daftar Rute Fitur Admin SMAN 2 Situbondo (Sesuai ERD 18 Tabel):
| AD - 01 : Banner Web User       -> App\Http\Controllers\Admin\BannerController
| AD - 02 : Pop-Up Event          -> App\Http\Controllers\Admin\PopupController
| AD - 03 : Profile               -> App\Http\Controllers\Admin\ProfileController
| AD - 04 : Pegawai               -> App\Http\Controllers\Admin\EmployeeController
| AD - 05 : Siswa                 -> App\Http\Controllers\Admin\StudentController
| AD - 06 : Pengumuman            -> App\Http\Controllers\Admin\AnnouncementController
| AD - 07 : Galeri                -> App\Http\Controllers\Admin\GalleryController
| AD - 08 : Video                 -> App\Http\Controllers\Admin\VideoController
| AD - 09 : Berita                -> App\Http\Controllers\Admin\NewsController
| AD - 10 : Contact               -> App\Http\Controllers\Admin\ContactController
| AD - 11 : SPMB                  -> App\Http\Controllers\Admin\SpmbController
| AD - 12 : Siklus (Kelulusan)    -> App\Http\Controllers\Admin\SiklusController
| AD - 13 : Manajemen Akun Admin  -> App\Http\Controllers\Admin\AdminAccountController
| AD - 14 : Dashboard Admin       -> App\Http\Controllers\Admin\DashboardController
| AD - 15 : Kustomisasi Warna     -> App\Http\Controllers\Admin\ColorSettingController
|
*/

// Developer Admin dapat langsung mendaftarkan route untuk masing-masing fitur di bawah ini:
// Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

// =============================================================================
// AD-01 — Manajemen Banner Web User
// =============================================================================
Route::resource('banners', App\Http\Controllers\Admin\BannerController::class)
    ->except(['show'])
    ->names([
        'index'   => 'admin.banners.index',
        'create'  => 'admin.banners.create',
        'store'   => 'admin.banners.store',
        'edit'    => 'admin.banners.edit',
        'update'  => 'admin.banners.update',
        'destroy' => 'admin.banners.destroy',
    ]);
Route::patch('banners/{banner}/toggle-active', [App\Http\Controllers\Admin\BannerController::class, 'toggleActive'])
    ->name('admin.banners.toggleActive');

// =============================================================================
// AD-02 — Manajemen Pop-up Event
// =============================================================================
Route::resource('popups', App\Http\Controllers\Admin\PopupController::class)
    ->except(['show'])
    ->names([
        'index'   => 'admin.popups.index',
        'create'  => 'admin.popups.create',
        'store'   => 'admin.popups.store',
        'edit'    => 'admin.popups.edit',
        'update'  => 'admin.popups.update',
        'destroy' => 'admin.popups.destroy',
    ]);
Route::patch('popups/{popup}/toggle-active', [App\Http\Controllers\Admin\PopupController::class, 'toggleActive'])
    ->name('admin.popups.toggleActive');
