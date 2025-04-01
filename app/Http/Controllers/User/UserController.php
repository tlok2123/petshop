<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Nếu có avatar mới thì cập nhật, nếu không thì giữ nguyên
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Lưu ảnh mới
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        } else {
            // Nếu không có avatar mới, giữ nguyên avatar cũ
            $validated['avatar'] = $user->avatar;
        }

        $user->update($validated);

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
