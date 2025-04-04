<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('product', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('get-pets-by-user/{user_id}', [AppointmentController::class, 'getPets']);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('services', ServicesController::class);
    Route::resource('users', UserController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('pets', PetController::class);

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});
