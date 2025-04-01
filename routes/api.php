<?php

use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\AppointmentController;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\PetController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\ServicesController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\VNPayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});


Route::prefix('email')->middleware('throttle:6,1')->group(function () {
    Route::get('/verify/{id}/{token}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::get('/verify', [EmailVerificationController::class, 'verifyByQuery'])->name('verification.verify.query');
    Route::post('/verify/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('auth:api')
        ->name('verification.send');
});


Route::group([], function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category_id}/products', [ProductController::class, 'getByCategory'])->name('categories.products');
    Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
    Route::get('/services/{id}', [ServicesController::class, 'show'])->name('services.show');
});


Route::middleware('auth:api')->group(function () {
    // User Profile
    Route::prefix('user')->group(function () {
        Route::get('/', function (Request $request) {
            return response()->json(['status' => 200, 'message' => 'Đăng nhập thành công'], 200);
        })->name('user.check');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/profile', [UserController::class, 'getProfile'])->name('user.profile.get');
        Route::post('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    });


    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::post('/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    });

    // Pets (CRUD)
    Route::prefix('pets')->group(function () {
        Route::get('/', [PetController::class, 'index'])->name('pets.index');
        Route::post('/', [PetController::class, 'store'])->name('pets.store');
        Route::get('/{pet}', [PetController::class, 'show'])->name('pets.show');
        Route::put('/{pet}', [PetController::class, 'update'])->name('pets.update');
        Route::delete('/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');
    });

    // Appointments
    Route::prefix('book')->group(function () {
        Route::get('/', [AppointmentController::class, 'book'])->name('appointments.book');
        Route::post('/', [AppointmentController::class, 'store'])->name('appointments.store');
    });

    // Payment
    Route::post('/checkout', [VNPayController::class, 'createPayment'])->name('checkout.create');
    Route::get('/vnpay/return', [VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
});
