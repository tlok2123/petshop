<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Lấy thông tin người dùng hiện tại.
     */
    public function getProfile()
    {
        $user = Auth::user();

        return response()->json([
            'status' => 200,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null
            ]
        ], 200);
    }

    /**
     * Cập nhật thông tin cá nhân của người dùng.
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        // Nếu có avatar mới thì cập nhật, nếu không thì giữ nguyên
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Lưu ảnh mới
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        } else {
            // Nếu không có avatar mới, giữ nguyên avatar cũ
            $data['avatar'] = $user->avatar;
        }

        $user->update($data);

        return response()->json([
            'status' => 200,
            'message' => 'Cập nhật thông tin thành công.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null
            ]
        ], 200);
    }
}
