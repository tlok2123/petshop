<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticateApi
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        try {
            return $next($request);
        } catch (AuthenticationException $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized - Token không hợp lệ hoặc đã hết hạn'
            ], 401);
        }
    }
}
