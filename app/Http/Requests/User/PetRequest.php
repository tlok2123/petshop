<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'boarding_expiry' => 'nullable|date',
        ];

        if ($this->method() === 'POST') {
            $rules['name'] = 'required|string|max:255';
            $rules['species'] = 'required|integer|in:1,2';
            $rules['age'] = 'required|integer|min:0';
            $rules['health_status'] = 'required|string';
        } else {
            $rules['name'] = 'sometimes|string|max:255';
            $rules['species'] = 'sometimes|integer|in:1,2';
            $rules['age'] = 'sometimes|integer|min:0';
            $rules['health_status'] = 'sometimes|string';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên thú cưng là bắt buộc',
            'name.string' => 'Tên thú cưng phải là chuỗi ký tự',
            'name.max' => 'Tên thú cưng không được vượt quá 255 ký tự',
            'species.required' => 'Loài thú cưng là bắt buộc',
            'species.integer' => 'Loài thú cưng phải là số nguyên',
            'species.in' => 'Loài thú cưng không hợp lệ (chỉ được là 1 hoặc 2)',
            'age.required' => 'Tuổi thú cưng là bắt buộc',
            'age.integer' => 'Tuổi thú cưng phải là số nguyên',
            'age.min' => 'Tuổi thú cưng không được nhỏ hơn 0',
            'health_status.required' => 'Tình trạng sức khỏe là bắt buộc',
            'health_status.string' => 'Tình trạng sức khỏe phải là chuỗi ký tự',
            'boarding_expiry.date' => 'Ngày hết hạn nội trú không hợp lệ',
        ];
    }
}
