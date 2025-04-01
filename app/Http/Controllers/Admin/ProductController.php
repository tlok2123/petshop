<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Product::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        $products = $query->paginate(10);
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            return redirect()->route('admin.product.index')->with('error', 'Chưa có danh mục nào. Hãy thêm danh mục trước.');
        }
        return view('admin.product.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoName = time() . '.' . $request->file('photo')->extension();
            $photoPath = $request->file('photo')->storeAs('photos', $photoName, 'public');
        }

        Product::create(array_merge($data, ['photo' => $photoPath]));

        return redirect()->route('admin.product.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $photoName = time() . '.' . $request->file('photo')->extension();
            $data['photo'] = $request->file('photo')->storeAs('photos', `time() . '.' . $photoName`, 'public');
        }
        $product->update($data);
        return redirect()->route('admin.product.index')->with('success', 'Đã cập nhật sản phẩm');
    }

    public function destroy(Product $product)
    {
        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Sản phẩm đã xóa');
    }
}
