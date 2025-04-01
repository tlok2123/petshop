<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        return response()->json(['status' => 200, 'orders' => $orders], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 1,
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

        return response()->json(['status' => 201, 'message' => 'Đơn hàng đã được tạo.', 'order' => $order], 201);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['status' => 403, 'message' => 'Bạn không có quyền truy cập đơn hàng này.'], 403);
        }
        return response()->json(['status' => 200, 'order' => $order], 200);
    }

    public function update(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['status' => 403, 'message' => 'Bạn không có quyền chỉnh sửa đơn hàng này.'], 403);
        }

        $request->validate([
            'status' => 'required|in:1,2,3',
        ]);

        $order->status = $request->status;
        $order->save();

        return response()->json(['status' => 200, 'message' => 'Đơn hàng đã được cập nhật.', 'order' => $order], 200);
    }

    public function destroy(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['status' => 403, 'message' => 'Bạn không có quyền hủy đơn hàng này.'], 403);
        }

        $order->delete();

        return response()->json(['status' => 200, 'message' => 'Đơn hàng đã được hủy.'], 200);
    }

    // API mới để cập nhật trạng thái đơn hàng từ VNPay
    public function updateStatus(Request $request)
    {
        // Validate request từ frontend
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'transaction_status' => 'required|string',
        ]);

        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');

        // Tìm đơn hàng
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Không tìm thấy đơn hàng!',
            ], 404);
        }

        // Kiểm tra trạng thái giao dịch từ VNPay
        if ($transactionStatus === '00') {
            // Kiểm tra trạng thái hiện tại của đơn hàng
            if ($order->status == 1) {
                $order->status = 2; // Cập nhật từ 1 thành 2 (hoàn thành)
                $order->save();

                Log::info('Order status updated via VNPay', ['order_id' => $order->id, 'new_status' => 2]);

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
