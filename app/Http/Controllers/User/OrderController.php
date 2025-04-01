<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
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

        return Helper::apiResponse(200, 'Lấy danh sách đơn hàng thành công', ['orders' => $orders]);
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

        return Helper::apiResponse(201, 'Đơn hàng đã được tạo.', ['order' => $order]);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return Helper::apiResponse(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        $order->load('items.product');

        return Helper::apiResponse(200, 'Lấy thông tin đơn hàng thành công', ['order' => $order]);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return Helper::apiResponse(403, 'Bạn không có quyền chỉnh sửa đơn hàng này.');
        }

        if ($order->status != 1) {
            return Helper::apiResponse(400, 'Không thể chỉnh sửa đơn hàng đã xử lý.');
        }

        $data = $request->validated();
        $order->items()->delete();
        foreach ($data['products'] as $item) {
            $product = Product::find($item['id']);
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price * $item['quantity'],
            ]);
        }

        $order->updateTotalPrice();

        return Helper::apiResponse(200, 'Đơn hàng đã được cập nhật.', ['order' => $order]);
    }

    public function destroy(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return Helper::apiResponse(403, 'Bạn không có quyền hủy đơn hàng này.');
        }

        $order->delete();

        return Helper::apiResponse(200, 'Đơn hàng đã được hủy.');
    }

    public function updateStatus(UpdateOrderStatusRequest $request)
    {
        $data = $request->validated();
        $order = Order::find($data['order_id']);

        if (!$order) {
            return Helper::apiResponse(404, 'Không tìm thấy đơn hàng!');
        }

        if ($data['transaction_status'] === '00') {
            if ($order->status == 1) {
                $order->status = 2;
                $order->save();

                Log::info('Order status updated via VNPay', [
                    'order_id' => $order->id,
                    'new_status' => 2
                ]);

                return Helper::apiResponse(200, 'Cập nhật trạng thái đơn hàng thành công!', ['data' => $order]);
            } else {
                return Helper::apiResponse(400, 'Đơn hàng không ở trạng thái chờ xử lý!');
            }
        }

        return Helper::apiResponse(400, 'Giao dịch không thành công!');
    }
}
