<?php

use Illuminate\Support\Facades\Route;

// Auth Routes (Login, Logout)
require __DIR__.'/auth.php';

// Route Role Admin (Developer Admin)
Route::middleware(['auth:admin', 'role:admin'])
    ->prefix('admin')
    ->group(base_path('routes/admin.php'));

// Route Role User (Developer User - Publik, tanpa middleware auth)
Route::group([], base_path('routes/user.php'));
