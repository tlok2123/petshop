<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 1;
    }

    public function rules(): array
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:1,2,3',
        ];

        if ($this->method() === 'POST') {
            $rules['products'] = 'required|array';
            $rules['products.*.id'] = 'exists:products,id';
            $rules['products.*.quantity'] = 'required|integer|min:1';
        } else {
            $rules['products'] = 'nullable|array';
            $rules['products.*.id'] = 'exists:products,id';
            $rules['products.*.quantity'] = 'integer|min:1';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Vui lòng chọn khách hàng',
            'user_id.exists' => 'Khách hàng không tồn tại',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
            'products.required' => 'Vui lòng chọn ít nhất một sản phẩm',
            'products.array' => 'Danh sách sản phẩm phải là mảng',
            'products.*.id.exists' => 'Sản phẩm không hợp lệ',
            'products.*.quantity.required' => 'Vui lòng nhập số lượng',
            'products.*.quantity.integer' => 'Số lượng phải là số nguyên',
            'products.*.quantity.min' => 'Số lượng phải lớn hơn 0',
        ];
    }
}
