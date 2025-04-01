<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper; // Import Helper

class UserController extends Controller
{
    public function getProfile()
    {
        $user = Auth::user();
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null
        ];

        return Helper::apiResponse(200, 'Lấy thông tin hồ sơ thành công', ['user' => $userData]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        } else {
            $data['avatar'] = $user->avatar;
        }

        $user->update($data);

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null
        ];

        return Helper::apiResponse(200, 'Cập nhật thông tin thành công', ['user' => $userData]);
    }
}
