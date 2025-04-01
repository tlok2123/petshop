<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 1;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'health_status' => 'required|string|max:255',
            'boarding_expiry' => 'nullable|date',
            'user_id' => 'required|exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên thú cưng là bắt buộc',
            'name.string' => 'Tên thú cưng phải là chuỗi ký tự',
            'name.max' => 'Tên thú cưng không được vượt quá 255 ký tự',
            'species.required' => 'Loài thú cưng là bắt buộc',
            'species.string' => 'Loài thú cưng phải là chuỗi ký tự',
            'species.max' => 'Loài thú cưng không được vượt quá 255 ký tự',
            'age.required' => 'Tuổi thú cưng là bắt buộc',
            'age.integer' => 'Tuổi thú cưng phải là số nguyên',
            'age.min' => 'Tuổi thú cưng không được nhỏ hơn 0',
            'health_status.required' => 'Tình trạng sức khỏe là bắt buộc',
            'health_status.string' => 'Tình trạng sức khỏe phải là chuỗi ký tự',
            'health_status.max' => 'Tình trạng sức khỏe không được vượt quá 255 ký tự',
            'boarding_expiry.date' => 'Ngày hết hạn nội trú không hợp lệ',
            'user_id.required' => 'Chủ sở hữu thú cưng là bắt buộc',
            'user_id.exists' => 'Chủ sở hữu không tồn tại trong hệ thống',
        ];
    }
}
