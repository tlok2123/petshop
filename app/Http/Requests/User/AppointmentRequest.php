<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'pet_id' => 'required|exists:pets,id,user_id,' . auth()->id(),
            'date' => 'required|date|after:now',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'note' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'pet_id.required' => 'Vui lòng chọn thú cưng.',
            'pet_id.exists' => 'Thú cưng không hợp lệ.',
            'date.required' => 'Vui lòng chọn ngày giờ.',
            'date.after' => 'Ngày giờ phải trong tương lai.',
            'services.required' => 'Vui lòng chọn ít nhất một dịch vụ.',
            'services.*.exists' => 'Dịch vụ không hợp lệ.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
