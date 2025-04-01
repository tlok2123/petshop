<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use App\Helpers\Helper;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $products = Product::with('category')->paginate(10);
            $products->getCollection()->transform(function ($product) {
                $product->image_url = $product->photo ? asset('storage/' . $product->photo) : null;
                return $product;
            });
            return Helper::apiResponse(200, 'Lấy danh sách sản phẩm thành công', ['products' => $products]);
        } catch (Exception $e) {
            return Helper::apiResponse(500, 'Không thể lấy danh sách sản phẩm', ['error' => $e->getMessage()]);
        }
    }

    public function show(Product $product): JsonResponse
    {
        try {
            $product->load('category');
            $product->image_url = $product->photo ? asset('storage/' . $product->photo) : null;
            return Helper::apiResponse(200, 'Lấy sản phẩm thành công', ['product' => $product]);
        } catch (Exception $e) {
            return Helper::apiResponse(500, 'Không thể lấy thông tin sản phẩm', ['error' => $e->getMessage()]);
        }
    }

    public function getByCategory(Request $request, int $category_id): JsonResponse
    {
        try {
            $products = Product::where('category_id', $category_id)
                ->select('id', 'name', 'description', 'price', 'stock', 'category_id', 'photo', 'created_at', 'updated_at')
                ->paginate(10);
            $products->getCollection()->transform(function ($product) {
                $product->image_url = $product->photo ? asset('storage/' . $product->photo) : null;
                return $product;
            });
            return Helper::apiResponse(200, 'Lấy danh sách sản phẩm theo danh mục thành công', ['products' => $products]);
        } catch (Exception $e) {
            return Helper::apiResponse(500, 'Lỗi lấy danh sách sản phẩm', ['error' => $e->getMessage()]);
        }
    }
}
