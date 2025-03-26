<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect(config('app.frontend_url') . '/email-verification-failed?error=user_not_found');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(config('app.frontend_url') . '/login?status=already_verified');
        }

        if (!hash_equals(sha1($user->email), (string)$request->route('hash'))) {
            return redirect(config('app.frontend_url') . '/email-verification-failed?error=invalid_link');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            return redirect(config('app.frontend_url') . '/login?status=success');
        }

        return redirect(config('app.frontend_url') . '/email-verification-failed?error=failed');
    }

}
