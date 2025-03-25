<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\DashboardController;
Route::get('/', function () {
    return view('welcome');
});

// Routes cho Admin Authentication
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Routes dành cho Admin (có middleware bảo vệ)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('product', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::resource('users', UserController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('pets', PetController::class);

    // Route Logout Admin
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});
