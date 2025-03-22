<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifyEmailController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => 200,
            'message' => 'User fetched successfully',
            'data' => $request->user()
        ], 200);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

// 🔹 Đăng ký & đăng nhập
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// 🔹 Xác thực email
Route::get('/email/verify', function () {
    return response()->json([
        'status' => 200,
        'message' => 'Please verify your email'
    ], 200);
})->middleware('auth:sanctum')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verify/resend', function (Request $request) {
    if (!$request->user()) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json([
        'message' => 'Verification link sent!'
    ], 200);
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

// 🔹 Lấy danh sách sản phẩm
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
