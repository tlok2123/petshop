<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'products' => 'required|array',
            'products.*.id' => 'exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' => 'Vui lòng chọn ít nhất một sản phẩm',
            'products.array' => 'Danh sách sản phẩm phải là mảng',
            'products.*.id.exists' => 'Sản phẩm không hợp lệ',
            'products.*.quantity.required' => 'Vui lòng nhập số lượng',
            'products.*.quantity.integer' => 'Số lượng phải là số nguyên',
            'products.*.quantity.min' => 'Số lượng phải lớn hơn 0',
        ];
    }
}
