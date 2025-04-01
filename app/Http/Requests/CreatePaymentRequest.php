<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'ID đơn hàng là bắt buộc',
            'order_id.exists' => 'Đơn hàng không tồn tại',
        ];
    }
}
