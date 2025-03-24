<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị chi tiết một người dùng.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Hiển thị form chỉnh sửa người dùng.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'role' => 'required|integer|in:1,2',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Avatar phải là ảnh, tối đa 2MB
        ]);

        // Nếu có ảnh mới, lưu vào storage và cập nhật DB
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath; // Lưu đường dẫn ảnh vào DB
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công.');
    }

    /**
     * Xóa người dùng.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã bị xóa.');
    }
}
