<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify($id, $token)
    {
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            if ($decoded->id != $id) {
                return Helper::apiResponse(400, null, 'Token không hợp lệ');
            }
            $user = User::findOrFail($id);
            if ($user->hasVerifiedEmail()) {
                return Helper::apiResponse(200, null, 'Email đã được xác minh trước đó');
            }
            $user->markEmailAsVerified();
            return Helper::apiResponse(200, null, 'Xác minh email thành công');
        } catch (\Exception $e) {
            return Helper::apiResponse(400, null, 'Token không hợp lệ hoặc đã hết hạn');
        }
    }

    public function verifyByQuery(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return Helper::apiResponse(400, null, 'Token không hợp lệ');
        }
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $user = User::findOrFail($decoded->id);
            if ($user->hasVerifiedEmail()) {
                return Helper::apiResponse(200, null, 'Email đã được xác minh trước đó');
            }
            $user->markEmailAsVerified();
            return Helper::apiResponse(200, null, 'Xác minh email thành công');
        } catch (\Exception $e) {
            return Helper::apiResponse(400, null, 'Token không hợp lệ hoặc đã hết hạn');
        }
    }

    public function resend(Request $request)
    {
        if (!$request->user()) {
            return Helper::apiResponse(401, null, 'Vui lòng đăng nhập để tiếp tục');
        }
        $request->user()->sendEmailVerificationNotification();
        return Helper::apiResponse(200, null, 'Mail đã được gửi lại');
    }
}
