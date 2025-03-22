<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 1){
                return redirect()->route('admin.dashboard');
            }else{
                Auth::logout();
                return back()->withErrors(['email' => 'Bạn không có quyền truy cập']);
            }
        }
        return back()->withErrors(['email' => 'Thông tin đăng nhập không hợp lệ']);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // ✅ Đăng xuất user
        $request->session()->invalidate(); // ✅ Hủy session
        $request->session()->regenerateToken(); // ✅ Tạo CSRF token mới

        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công!');
    }

}
