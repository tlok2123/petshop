<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VNPayServices
{
    protected $vnp_TmnCode;
    protected $vnp_HashSecret;
    protected $vnp_Url;
    protected $vnp_Returnurl;

    public function __construct()
    {
        $this->vnp_TmnCode = env('VNP_TMNCODE');
        $this->vnp_HashSecret = env('VNP_HASHSECRET');
        $this->vnp_Url = env('VNP_URL');
        $this->vnp_Returnurl = env('VNP_RETURNURL');
    }

    public function createPaymentUrl($order)
    {
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => (int) round($order->total_price * 100),
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toán đơn hàng #{$order->id}",
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => config('app.frontend_url') . '/success',
            "vnp_TxnRef" => $order->id,
        ];

        ksort($inputData);

        // Tạo chuỗi hash chuẩn VNPay - Encode cả key và value
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&';
            }
            $hashData .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }

        // Debug log
        Log::info('Hash Data for Payment:', ['hashData' => $hashData]);

        // Tạo mã hash SHA512
        $vnpSecureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        // Tạo URL
        $query = http_build_query($inputData);
        return "{$this->vnp_Url}?{$query}&vnp_SecureHash={$vnpSecureHash}";
    }

    public function validateResponse(Request $request)
    {
        Log::info('VNPay Response:', $request->all());
        $inputData = $request->all();

        if (!isset($inputData['vnp_SecureHash']) || !isset($inputData['vnp_TransactionStatus'])) {
            return ['status' => false, 'message' => 'Thiếu tham số cần thiết!'];
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

        ksort($inputData);

        // Tạo chuỗi hash - Encode cả key và value
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&';
            }
            $hashData .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }

        // Debug log
        Log::info('Hash Data for Validation:', ['hashData' => $hashData]);

        // Tạo mã hash SHA512
        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        Log::info('Generated Secure Hash for Validation:', [
            'secureHash' => $secureHash,
            'vnp_SecureHash' => $vnp_SecureHash
        ]);

        // So sánh chữ ký
        if ($secureHash !== $vnp_SecureHash) {
            return ['status' => false, 'message' => 'Sai chữ ký!'];
        }

        return [
            'status' => true,
            'message' => 'Hợp lệ',
            'transaction_status' => $inputData['vnp_TransactionStatus'] ?? null,
            'order_id' => $inputData['vnp_TxnRef'] ?? null,
        ];
    }
}
