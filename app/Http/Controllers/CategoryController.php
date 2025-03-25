<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Exception;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::select('id', 'name', 'created_at', 'updated_at')->paginate(10);

            return response()->json([
                'status' => 200,
                'message' => 'Lấy danh sách danh mục thành công',
                'data' => $categories
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi lấy danh sách danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
