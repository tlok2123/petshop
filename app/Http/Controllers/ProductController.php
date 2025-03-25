<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $products = Product::with('category')->paginate(10);

            // Thêm đường dẫn ảnh cho từng sản phẩm
            $products->getCollection()->transform(function ($product) {
                $product->image_url = $product->photo ? asset('storage/' . $product->photo) : null;
                return $product;
            });

            return response()->json([
                'status' => '200',
                'message' => 'Lấy danh sách sản phẩm thành công',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '500',
                'message' => 'Không thể lấy danh sách sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Product $product): JsonResponse
    {
        try {
            $product->load('category');

            // Thêm đường dẫn ảnh
            $product->image_url = $product->photo ? asset('storage/' . $product->photo) : null;

            return response()->json([
                'status' => '200',
                'message' => 'Lấy sản phẩm thành công',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '500',
                'message' => 'Không thể lấy thông tin sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByCategory(Request $request, int $category_id): JsonResponse
    {
        try {
            $products = Product::where('category_id', $category_id)
                ->select('id', 'name', 'description', 'price', 'stock', 'category_id', 'photo', 'created_at', 'updated_at')
                ->paginate(10);
            $products->getCollection()->transform(function ($product) {
                $product->image_url = $product->photo ? url('storage/' . $product->photo) : null;
                return $product;
            });

            return response()->json([
                'status' => 200,
                'message' => 'Lấy danh sách sản phẩm theo danh mục thành công',
                'data' => $products
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Lỗi lấy danh sách sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
