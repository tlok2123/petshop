<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 1;
    }

    public function rules(): array
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'date' => 'required|date',
            'status' => 'required|integer|in:1,2,3,4',
            'note' => 'nullable|string',
        ];

        if ($this->method() === 'POST') {
            $rules['services'] = 'required|array';
            $rules['services.*'] = 'exists:services,id';
        }
        elseif (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['services'] = 'required|array';
            $rules['services.*.id'] = 'exists:services,id';
            $rules['services.*.quantity'] = 'required|integer|min:1';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Vui lòng chọn khách hàng',
            'user_id.exists' => 'Khách hàng không tồn tại',
            'pet_id.required' => 'Vui lòng chọn thú cưng',
            'pet_id.exists' => 'Thú cưng không tồn tại',
            'date.required' => 'Vui lòng chọn ngày hẹn',
            'date.date' => 'Ngày không hợp lệ',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
            'services.required' => 'Vui lòng chọn ít nhất một dịch vụ',
            'services.*.exists' => 'Dịch vụ không hợp lệ',
            'services.*.id.exists' => 'Dịch vụ không hợp lệ',
            'services.*.quantity.required' => 'Vui lòng nhập số lượng',
            'services.*.quantity.integer' => 'Số lượng phải là số nguyên',
            'services.*.quantity.min' => 'Số lượng phải lớn hơn 0',
        ];
    }
}
