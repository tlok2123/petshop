<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Services\VNPayServices;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\Helper;

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
            return Helper::apiResponse(400, 'Đơn hàng không thể thanh toán do trạng thái không phù hợp!');
        }

        return Helper::apiResponse(200, 'Tạo URL thanh toán thành công', [
            'payment_url' => $this->vnPayService->createPaymentUrl($order)
        ]);
    }

    public function vnpayReturn(Request $request)
    {
        $validation = $this->vnPayService->validateResponse($request);

        if (!$validation['status']) {
            return Helper::apiResponse(400, $validation['message']);
        }

        $order = Order::find($validation['order_id']);
        if (!$order) {
            return Helper::apiResponse(404, 'Không tìm thấy đơn hàng!');
        }

        if ($validation['transaction_status'] === '00') {
            $order->status = 2;
            $order->save();
            return redirect()->away(config('app.frontend_url') . '/success');
        }

        return Helper::apiResponse(400, 'Giao dịch thất bại!');
    }
}
