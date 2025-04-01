<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Services\VNPayServices;
use App\Models\Order;

class VNPayController extends Controller
{
    protected VNPayServices $vnPayService;

    public function __construct(VNPayServices $vnPayService)
    {
        $this->vnPayService = $vnPayService;
    }

    public function createPayment(CreatePaymentRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $order = Order::where('id', $data['order_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();
        if ($order->status != 1) {
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
            return redirect()->away(config('app.frontend_url') . '/success');
        }

        return response()->json([
            'status' => 400,
            'message' => 'Giao dịch thất bại!'
        ], 400);
    }
}
