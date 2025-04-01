<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 1;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'type' => 'required|integer|in:' . implode(',', [
                    Service::TYPE_CARE,
                    Service::TYPE_EXAMINATION,
                    Service::TYPE_CONSIGNMENT
                ]),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên dịch vụ là bắt buộc',
            'name.string' => 'Tên dịch vụ phải là chuỗi ký tự',
            'name.max' => 'Tên dịch vụ không được vượt quá 255 ký tự',
            'description.string' => 'Mô tả phải là chuỗi ký tự',
            'price.required' => 'Giá dịch vụ là bắt buộc',
            'price.integer' => 'Giá dịch vụ phải là số nguyên',
            'price.min' => 'Giá dịch vụ không được nhỏ hơn 0',
            'type.required' => 'Loại dịch vụ là bắt buộc',
            'type.integer' => 'Loại dịch vụ phải là số nguyên',
            'type.in' => 'Loại dịch vụ không hợp lệ',
        ];
    }
}
