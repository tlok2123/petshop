<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge(['category_id' => 1]);
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image_url' => 'required|string',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.product.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }
    public function edit(Product $product)
    {
        $categories = Category::all(); // 🛠 Lấy danh sách danh mục
        return view('admin.product.edit', compact('product', 'categories')); // 🛠 Truyền vào view
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image_url' => 'required|string',
        ]);

        $product->update($request->all());
        return redirect()->route('admin.product.index')->with('success', 'Đã cập nhật sản phẩm');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.product.index')->with('success', 'Sản phẩm đã xóa');
    }
}
