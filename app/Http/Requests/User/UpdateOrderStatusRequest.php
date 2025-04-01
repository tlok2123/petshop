<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'transaction_status' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'ID đơn hàng là bắt buộc',
            'order_id.exists' => 'Đơn hàng không tồn tại',
            'transaction_status.required' => 'Trạng thái giao dịch là bắt buộc',
            'transaction_status.string' => 'Trạng thái giao dịch phải là chuỗi',
        ];
    }
}
