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
