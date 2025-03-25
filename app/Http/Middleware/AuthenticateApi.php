<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Auth\AuthenticationException;

class AuthenticateApi
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        try {
            // Kiểm tra token hợp lệ
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized - Token không hợp lệ hoặc đã hết hạn'
                ], 401);
            }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized - Token không hợp lệ hoặc đã hết hạn'
            ], 401);
        }
    }
}
