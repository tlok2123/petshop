<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('admin.orders.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:1,2,3',
            'products' => 'required|array',
            'products.*.id' => 'exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'total_price' => 0,
        ]);

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price * $item['quantity'],
            ]);
        }

        $order->updateTotalPrice();

        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được tạo.');
    }

    public function edit(Order $order)
    {
        $users = User::all();
        $products = Product::all();
        return view('admin.orders.edit', compact('order', 'products', 'users'));
    }
    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }


    public function update(Request $request, Order $order)
    {
        $users = User::all();
        $request->validate([
            'status' => 'required|in:1,2,3',
            'products' => 'array',
            'products.*.id' => 'exists:products,id',
            'products.*.quantity' => 'integer|min:1',
        ]);

        $order->status = $request->status;
        $order->items()->delete();

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price * $item['quantity'],
            ]);
        }

        $order->updateTotalPrice();

        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được cập nhật.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa.');
    }
}
