<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;
use App\Services\VNPayServices;
use App\Models\Order;

class VNPayController extends Controller
{
    protected VNPayServices $vnPayService;

    public function __construct(VNPayServices $vnPayService)
    {
        $this->vnPayService = $vnPayService;
    }

    public function createPayment(Request $request): \Illuminate\Http\JsonResponse
    {

        // Lấy đơn hàng từ order_id
        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->id()) // Đảm bảo đơn hàng thuộc về user
            ->firstOrFail();

        // Kiểm tra trạng thái đơn hàng
        if ($order->status != 1) { // Giả sử 1 là "Đang xử lý"
            return response()->json([
                'status' => 400,
                'message' => 'Đơn hàng không thể thanh toán do trạng thái không phù hợp!',
            ], 400);
        }

        return response()->json([
            'status' => 200,
            'payment_url' => $this->vnPayService->createPaymentUrl($order)
        ]);
    }

    public function vnpayReturn(Request $request)
    {
        $validation = $this->vnPayService->validateResponse($request);

        if (!$validation['status']) {
            return response()->json([
                'status' => 400,
                'message' => $validation['message'],
            ], 400);
        }

        $order = Order::find($validation['order_id']);
        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Không tìm thấy đơn hàng!',
            ], 404);
        }

        if ($validation['transaction_status'] === '00') {
            $order->status = 2;
            $order->save();
            // Redirect về trang success của frontend
            return redirect()->away(config('app.frontend_url') . '/success');
        }

        return response()->json([
            'status' => 400,
            'message' => 'Giao dịch thất bại!'
        ], 400);
    }
}
