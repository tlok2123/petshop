<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthenticateApi;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'auth.api' => AuthenticateApi::class,
        ]);
        $middleware->append(App\Http\Middleware\CorsMiddleware::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Xử lý lỗi khi xác thực thất bại
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized'
            ], 401);
        });

        // Xử lý lỗi khi truy cập bị từ chối
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            return response()->json([
                'status' => 403,
                'message' => 'Forbidden'
            ], 403);
        });
    })
    ->create();
