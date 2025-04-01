<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use App\Helpers\Helper; // Import Helper

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::select('id', 'name', 'created_at', 'updated_at')->paginate(10);

            return Helper::apiResponse(200, 'Lấy danh sách danh mục thành công', ['categories' => $categories]);
        } catch (Exception $e) {
            return Helper::apiResponse(500, 'Lỗi lấy danh sách danh mục', ['error' => $e->getMessage()]);
        }
    }
}
