<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
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
            return Helper::apiResponse(409,  'Email đã tồn tại');
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
        return Helper::apiResponse(
            201,
            'Đăng ký thành công, kiểm tra email để xác thực tài khoản',
            ['token' => $token, 'user' => $user]
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        if (!$token = JWTAuth::attempt($credentials)) {
            return Helper::apiResponse(401, 'Sai thông tin đăng nhập');
        }
        $user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            return Helper::apiResponse(
                403,
                'Email chưa được xác thực. Vui lòng kiểm tra email của bạn để xác thực.',
                ['token' => $token]
            );
        }
        return Helper::apiResponse(200, 'Đăng nhập thành công', ['token' => $token]);
    }

    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return Helper::apiResponse(200, 'Đăng xuất thành công');
        } catch (\Exception $e) {
            return Helper::apiResponse(500, 'Lỗi khi đăng xuất, vui lòng thử lại');
        }
    }
}
