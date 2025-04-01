<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (User::where('email', $data['email'])->exists()) {
            return response()->json([
                'status' => 409,
                'message' => 'Email đã tồn tại',
            ], 409);
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'address' => $data['address'],
            'role' => User::ROLE_CUSTOMER,
        ]);
        event(new Registered($user));
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'status' => 201,
            'message' => 'Đăng ký thành công, kiểm tra email để xác thực tài khoản',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        // Kiểm tra thông tin đăng nhập
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 401,
                'message' => 'Sai thông tin đăng nhập'
            ], 401);
        }
        $user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'token' => $token,
                'status' => 403,
                'message' => 'Email chưa được xác thực. Vui lòng kiểm tra email của bạn để xác thực.'
            ], 403);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Đăng nhập thành công',
            'token' => $token,
        ], 200);
    }

    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status' => 200,
                'message' => 'Đăng xuất thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi khi đăng xuất, vui lòng thử lại'
            ], 500);
        }
    }
}
