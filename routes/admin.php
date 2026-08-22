<?php

use App\Http\Controllers\Admin\AuthController;
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

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ColorSettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\SchoolProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\VideoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware(['auth:admin', 'role:admin'])->group(function () {
    Route::get('/', function () {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login');
    });
    Route::get('/login', [AuthController::class, 'create'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'store']);
});

Route::post('/logout', [AuthController::class, 'destroy'])->name('admin.logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// =============================================================================
// AD-01 — Manajemen Banner Web User
// =============================================================================
Route::resource('banners', BannerController::class)
    ->except(['show'])
    ->names([
        'index' => 'admin.banners.index',
        'create' => 'admin.banners.create',
        'store' => 'admin.banners.store',
        'edit' => 'admin.banners.edit',
        'update' => 'admin.banners.update',
        'destroy' => 'admin.banners.destroy',
    ]);
Route::patch('banners/{banner}/toggle-active', [BannerController::class, 'toggleActive'])
    ->name('admin.banners.toggleActive');

// =============================================================================
// AD-02 — Manajemen Pop-up Event
// =============================================================================
Route::resource('popups', PopupController::class)
    ->except(['show'])
    ->names([
        'index' => 'admin.popups.index',
        'create' => 'admin.popups.create',
        'store' => 'admin.popups.store',
        'edit' => 'admin.popups.edit',
        'update' => 'admin.popups.update',
        'destroy' => 'admin.popups.destroy',
    ]);
Route::patch('popups/{popup}/toggle-active', [PopupController::class, 'toggleActive'])
    ->name('admin.popups.toggleActive');

// =============================================================================
// AD-03 — Profil Sekolah (School Profile)
// =============================================================================
Route::get('school-profile', [SchoolProfileController::class, 'edit'])
    ->name('admin.school_profile.edit');
Route::put('school-profile', [SchoolProfileController::class, 'update'])
    ->name('admin.school_profile.update');

// =============================================================================
// AD-04 — Manajemen Data Pegawai (Employees)
// =============================================================================
Route::resource('employees', EmployeeController::class)
    ->except(['show', 'create', 'edit'])
    ->names([
        'index' => 'admin.employees.index',
        'store' => 'admin.employees.store',
        'update' => 'admin.employees.update',
        'destroy' => 'admin.employees.destroy',
    ]);
Route::patch('employees/{employee}/toggle-active', [EmployeeController::class, 'toggleActive'])
    ->name('admin.employees.toggleActive');

// =============================================================================
// AD-05 — Manajemen Data Siswa (Students & Import)
// =============================================================================
Route::get('students/template', [StudentController::class, 'downloadTemplate'])
    ->name('admin.students.downloadTemplate');
Route::post('students/import', [StudentController::class, 'import'])
    ->name('admin.students.import');
Route::resource('students', StudentController::class)
    ->except(['show', 'create', 'edit'])
    ->names([
        'index' => 'admin.students.index',
        'store' => 'admin.students.store',
        'update' => 'admin.students.update',
        'destroy' => 'admin.students.destroy',
    ]);
Route::patch('students/{student}/toggle-public', [StudentController::class, 'togglePublic'])
    ->name('admin.students.togglePublic');

// =============================================================================
// AD-06 — Manajemen Galeri Foto (Galleries)
// =============================================================================
Route::resource('galleries', GalleryController::class)
    ->except(['show', 'create', 'edit'])
    ->names([
        'index' => 'admin.galleries.index',
        'store' => 'admin.galleries.store',
        'update' => 'admin.galleries.update',
        'destroy' => 'admin.galleries.destroy',
    ]);

// =============================================================================
// AD-07 — Manajemen Video YouTube (Videos)
// =============================================================================
Route::resource('videos', VideoController::class)
    ->except(['show', 'create', 'edit'])
    ->names([
        'index' => 'admin.videos.index',
        'store' => 'admin.videos.store',
        'update' => 'admin.videos.update',
        'destroy' => 'admin.videos.destroy',
    ]);

// =============================================================================
// AD-08 — Kustomisasi Warna Tema (Color Settings)
// =============================================================================
Route::get('color-settings', [ColorSettingController::class, 'edit'])
    ->name('admin.color_settings.edit');
Route::put('color-settings', [ColorSettingController::class, 'update'])
    ->name('admin.color_settings.update');

// =============================================================================
// AD-09 — Manajemen Pengumuman (Announcements)
// =============================================================================
use App\Http\Controllers\Admin\AnnouncementController;

Route::resource('announcements', AnnouncementController::class)
    ->except(['show', 'create', 'edit'])
    ->names([
        'index' => 'admin.announcements.index',
        'store' => 'admin.announcements.store',
        'update' => 'admin.announcements.update',
        'destroy' => 'admin.announcements.destroy',
    ]);

// =============================================================================
// AD-10 — Manajemen Berita (News)
// =============================================================================
use App\Http\Controllers\Admin\NewsController;

Route::resource('news', NewsController::class)
    ->except(['show', 'create', 'edit'])
    ->names([
        'index' => 'admin.news.index',
        'store' => 'admin.news.store',
        'update' => 'admin.news.update',
        'destroy' => 'admin.news.destroy',
    ]);

// =============================================================================
// AD-11 — Manajemen Pesan Contact (Contact Messages)
// =============================================================================
use App\Http\Controllers\Admin\ContactController;

Route::get('contact', [ContactController::class, 'index'])->name('admin.contact.index');
Route::get('contact/export-csv', [ContactController::class, 'exportCsv'])->name('admin.contact.exportCsv');
Route::get('contact/{id}', [ContactController::class, 'show'])->name('admin.contact.show');
Route::patch('contact/{id}/toggle-status', [ContactController::class, 'toggleStatus'])->name('admin.contact.toggleStatus');
Route::delete('contact/{id}', [ContactController::class, 'destroy'])->name('admin.contact.destroy');

// =============================================================================
// AD-12 — Manajemen Akun Admin (Admin Accounts)
// =============================================================================
use App\Http\Controllers\Admin\AdminAccountController;

Route::patch('admin-accounts/{id}/toggle-active', [AdminAccountController::class, 'toggleActive'])->name('admin.admin_accounts.toggleActive');
Route::resource('admin-accounts', AdminAccountController::class)
    ->except(['show', 'create', 'edit'])
    ->names([
        'index' => 'admin.admin_accounts.index',
        'store' => 'admin.admin_accounts.store',
        'update' => 'admin.admin_accounts.update',
        'destroy' => 'admin.admin_accounts.destroy',
    ]);

// =============================================================================
// AD-13 — Info & Dokumen SPMB (SPMB Info Packages & Documents)
// =============================================================================
use App\Http\Controllers\Admin\SpmbDocumentController;
use App\Http\Controllers\Admin\SpmbInfoController;

Route::get('spmb', function () {
    return redirect()->route('admin.spmb_info.index');
})->name('admin.spmb.index');

Route::resource('spmb-info', SpmbInfoController::class)
    ->except(['show', 'create', 'edit'])
    ->names([
        'index' => 'admin.spmb_info.index',
        'store' => 'admin.spmb_info.store',
        'update' => 'admin.spmb_info.update',
        'destroy' => 'admin.spmb_info.destroy',
    ]);

Route::resource('spmb-info.documents', SpmbDocumentController::class)
    ->except(['show', 'create', 'edit'])
    ->names([
        'index' => 'admin.spmb_info.documents.index',
        'store' => 'admin.spmb_info.documents.store',
        'update' => 'admin.spmb_info.documents.update',
        'destroy' => 'admin.spmb_info.documents.destroy',
    ]);
