<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with('category')->paginate(10));
    }

    public function create()
    {
        return view('product.create');
    }
    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

}
