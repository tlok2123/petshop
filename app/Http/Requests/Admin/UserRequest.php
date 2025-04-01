<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 1;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user()->id,
            'phone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'role' => 'required|integer|in:1,2',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên người dùng là bắt buộc',
            'name.string' => 'Tên người dùng phải là chuỗi ký tự',
            'name.max' => 'Tên người dùng không được vượt quá 255 ký tự',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'role.required' => 'Vai trò là bắt buộc',
            'role.integer' => 'Vai trò phải là số nguyên',
            'role.in' => 'Vai trò không hợp lệ',
            'avatar.image' => 'Avatar phải là hình ảnh',
            'avatar.mimes' => 'Avatar chỉ hỗ trợ định dạng jpeg, png, jpg, gif',
            'avatar.max' => 'Avatar không được vượt quá 2MB',
        ];
    }
}
