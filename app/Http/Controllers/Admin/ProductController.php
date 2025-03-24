<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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
        if ($categories->isEmpty()) {
            return redirect()->route('admin.product.index')->with('error', 'Chưa có danh mục nào. Hãy thêm danh mục trước.');
        }
        return view('admin.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoName = time() . '.' . $request->file('photo')->extension();
            $photoPath = $request->file('photo')->storeAs('photos', $photoName, 'public');
        }

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'stock' => $request->stock,
            'description' => $request->description,
            'photo' => $photoPath,
        ]);

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

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $updateData = $request->only(['name', 'price', 'category_id', 'stock', 'description']);

        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }

            $photoName = time() . '.' . $request->file('photo')->extension();
            $updateData['photo'] = $request->file('photo')->storeAs('photos', $photoName, 'public');
        }

        $product->update($updateData);

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
