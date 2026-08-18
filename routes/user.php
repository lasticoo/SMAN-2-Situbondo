<?php

use App\Http\Controllers\User\AnnouncementController;
use App\Http\Controllers\User\EmployeeController;
use App\Http\Controllers\User\LandingPageController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EPIC 1 & EPIC 2 - WEBSITE USER / PUBLIK (DEVELOPER 2: RIZAL WIBOWO / LASTICO RIDHO ALPARESZ)
|--------------------------------------------------------------------------
| Daftar Rute Fitur User Publik SMAN 2 Situbondo:
| US - 01 : Landing Page          -> App\Http\Controllers\User\LandingPageController
| US - 02 : Profil                -> App\Http\Controllers\User\ProfileController
| US - 03 : Civitas Akademik      -> App\Http\Controllers\User\EmployeeController
| US - 04 : Data Siswa            -> App\Http\Controllers\User\StudentController
| US - 05 : Pengumuman            -> App\Http\Controllers\User\AnnouncementController
| US - 06 : Media                 -> App\Http\Controllers\User\MediaController
| US - 07 : Berita                -> App\Http\Controllers\User\NewsController
| US - 08 : Contact               -> App\Http\Controllers\User\ContactController
| US - 09 : SPMB                  -> App\Http\Controllers\User\SpmbController
| US - 10 : Siklus                -> App\Http\Controllers\User\SiklusController
|
*/

// US-01 Landing Page Utama
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/instagram-proxy', [LandingPageController::class, 'proxyInstagramImage'])->name('instagram.proxy');

// US-02 Profil Sekolah
Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/profile', [ProfileController::class, 'index']);

// US-03 Civitas Akademik (Data Pegawai & Guru)
Route::get('/civitas-akademik', [EmployeeController::class, 'index'])->name('civitas.index');
Route::get('/pegawai', [EmployeeController::class, 'index'])->name('employee.index');
Route::get('/guru-dan-pegawai', [EmployeeController::class, 'index']);

// US-04 Data Siswa Publik
Route::get('/siswa', [StudentController::class, 'index'])->name('student.index');
Route::get('/data-siswa', [StudentController::class, 'index']);
Route::get('/peserta-didik', [StudentController::class, 'index']);

// US-05 Pengumuman & Agenda Publik
Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('announcement.index');
Route::get('/pengumuman/{id}', [AnnouncementController::class, 'show'])->name('announcement.show');
Route::get('/agenda', [AnnouncementController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{id}', [AnnouncementController::class, 'show'])->name('agenda.show');
Route::get('/informasi', [AnnouncementController::class, 'index'])->name('information.index');
Route::get('/informasi/{id}', [AnnouncementController::class, 'show'])->name('information.show');
