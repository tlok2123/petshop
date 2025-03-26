<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|min:10|max:10',
            'address' => 'required|string|max:255',
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => 409,
                'message' => 'Email đã tồn tại',
            ], 409);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => User::ROLE_CUSTOMER,
        ]);

        event(new Registered($user)); // Gửi email xác thực

        // Tạo JWT Token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 201,
            'message' => 'Đăng ký thành công, kiểm tra email để xác thực tài khoản',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        // Kiểm tra thông tin đăng nhập
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 401,
                'message' => 'Sai thông tin đăng nhập'
            ], 401);
        }

        $user = Auth::user();

        // Kiểm tra xác thực email
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 403,
                'message' => 'Email chưa được xác thực. Vui lòng kiểm tra email của bạn.'
            ], 403);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Đăng nhập thành công',
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken()); // Hủy token hiện tại
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
