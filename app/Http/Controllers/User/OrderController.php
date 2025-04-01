<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreOrderRequest;
use App\Http\Requests\User\UpdateOrderRequest;
use App\Http\Requests\User\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'message' => 'Lấy danh sách đơn hàng thành công',
            'orders' => $orders
        ], 200);
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 1,
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

        return response()->json([
            'status' => 201,
            'message' => 'Đơn hàng đã được tạo.',
            'order' => $order
        ], 201);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'status' => 403,
                'message' => 'Bạn không có quyền truy cập đơn hàng này.'
            ], 403);
        }

        $order->load('items.product');

        return response()->json([
            'status' => 200,
            'message' => 'Lấy thông tin đơn hàng thành công',
            'order' => $order
        ], 200);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'status' => 403,
                'message' => 'Bạn không có quyền chỉnh sửa đơn hàng này.'
            ], 403);
        }

        // Chỉ cho phép chỉnh sửa nếu đơn hàng đang ở trạng thái chờ xử lý (status = 1)
        if ($order->status != 1) {
            return response()->json([
                'status' => 400,
                'message' => 'Không thể chỉnh sửa đơn hàng đã xử lý.'
            ], 400);
        }

        $data = $request->validated();

        // Xóa các items cũ
        $order->items()->delete();

        // Thêm các items mới
        foreach ($data['products'] as $item) {
            $product = Product::find($item['id']);
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price * $item['quantity'],
            ]);
        }

        $order->updateTotalPrice();

        return response()->json([
            'status' => 200,
            'message' => 'Đơn hàng đã được cập nhật.',
            'order' => $order
        ], 200);
    }

    public function destroy(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'status' => 403,
                'message' => 'Bạn không có quyền hủy đơn hàng này.'
            ], 403);
        }

        $order->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Đơn hàng đã được hủy.'
        ], 200);
    }

    public function updateStatus(UpdateOrderStatusRequest $request)
    {
        $data = $request->validated();
        $order = Order::find($data['order_id']);

        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Không tìm thấy đơn hàng!',
            ], 404);
        }

        if ($data['transaction_status'] === '00') {
            if ($order->status == 1) {
                $order->status = 2;
                $order->save();

                Log::info('Order status updated via VNPay', [
                    'order_id' => $order->id,
                    'new_status' => 2
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Cập nhật trạng thái đơn hàng thành công!',
                    'data' => $order,
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Đơn hàng không ở trạng thái chờ xử lý!',
                ], 400);
            }
        }

        return response()->json([
            'status' => 400,
            'message' => 'Giao dịch không thành công!',
        ], 400);
    }
}
