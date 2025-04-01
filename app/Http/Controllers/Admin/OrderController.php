<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('id', 'asc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('admin.orders.create', compact('products', 'users'));
    }

    public function store(OrderRequest $request)
    {
        $data = $request->validated();

        $order = Order::create([
            'user_id' => $data['user_id'],
            'status' => $data['status'],
            'total_price' => 0,
        ]);

        foreach ($data['products'] as $item) {
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

    public function update(OrderRequest $request, Order $order)
    {
        $data = $request->validated();

        $order->status = $data['status'];
        $order->items()->delete();

        if (!empty($data['products'])) {
            foreach ($data['products'] as $item) {
                $product = Product::find($item['id']);
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price * $item['quantity'],
                ]);
            }
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
