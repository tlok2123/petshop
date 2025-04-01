<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 1;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm là bắt buộc',
            'name.string' => 'Tên sản phẩm phải là chuỗi ký tự',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
            'price.required' => 'Giá sản phẩm là bắt buộc',
            'price.integer' => 'Giá sản phẩm phải là số nguyên',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'stock.required' => 'Số lượng tồn kho là bắt buộc',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên',
            'stock.min' => 'Số lượng tồn kho không được nhỏ hơn 0',
            'description.required' => 'Mô tả sản phẩm là bắt buộc',
            'description.string' => 'Mô tả sản phẩm phải là chuỗi ký tự',
            'photo.image' => 'File tải lên phải là hình ảnh',
            'photo.mimes' => 'Hình ảnh chỉ hỗ trợ định dạng jpg, png, jpeg',
            'photo.max' => 'Hình ảnh không được vượt quá 2MB',
        ];
    }
}
