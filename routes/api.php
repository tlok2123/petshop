<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VerifyEmailController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// 🔹 Đăng ký & đăng nhập
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// 🔹 Xác thực email bằng JWT
Route::get('/email/verify/{id}/{token}', function ($id, $token) {
    try {
        $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

        if ($decoded->id != $id) {
            return response()->json(['message' => 'Token không hợp lệ'], 400);
        }

        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email đã được xác minh trước đó'], 200);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Xác minh email thành công']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Token không hợp lệ hoặc đã hết hạn'], 400);
    }
})->middleware('throttle:6,1')->name('verification.verify');

// 🔹 Xác thực email bằng JWT (token từ query params)
Route::get('/verify-email', function (Request $request) {
    $token = $request->query('token');

    if (!$token) {
        return response()->json(['message' => 'Token không hợp lệ'], 400);
    }

    try {
        $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

        $user = User::findOrFail($decoded->id);

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email đã được xác minh trước đó'], 200);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Xác minh email thành công']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Token không hợp lệ hoặc đã hết hạn'], 400);
    }
})->middleware('throttle:6,1')->name('verification.verify.query');

// 🔹 Gửi lại email xác minh
Route::post('/email/verify/resend', function (Request $request) {
    if (!$request->user()) {
        return response()->json([
            'status' => '401',
            'message' => 'Vui lòng đăng nhập để tiếp tục'
        ], 401);
    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json([
        'status' => '200',
        'message' => 'Mail đã được gửi lại'
    ], 200);
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

// 🔹 API yêu cầu đăng nhập
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => 200,
            'message' => 'Đăng nhập thành công',
            'data' => $request->user()
        ], 200);
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // 🔹 Quản lý Pet
    Route::post('/pets', [PetController::class, 'store']);
    Route::get('/pets', [PetController::class, 'index']);
    Route::get('/pets/{pet}', [PetController::class, 'show']);
    Route::put('/pets/{pet}', [PetController::class, 'update']);
    Route::delete('/pets/{pet}', [PetController::class, 'destroy']);
});

// 🔹 Lấy danh sách sản phẩm (Không yêu cầu đăng nhập)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
