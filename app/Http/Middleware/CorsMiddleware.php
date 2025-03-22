<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Thêm header CORS
        $response->headers->set('Access-Control-Allow-Origin', 'http://192.168.x.xxx:8080'); // Đổi thành IP frontend
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Nếu dùng cookies hoặc HTTP-Only JWT

        return $response;
    }
}
