<?php

namespace App\Helpers;

class Helper
{
    /**
     * Trả về phản hồi JSON chuẩn hóa cho API
     *
     * @param int $statusCode Mã trạng thái HTTP (200, 201, 403, v.v.)
     * @param string $message Thông điệp phản hồi
     * @param mixed $data Dữ liệu bổ sung (mảng, object, hoặc null)
     * @return \Illuminate\Http\JsonResponse
     */
    public static function apiResponse($statusCode, $message, $data = null)
    {
        $response = [
            'status' => $statusCode,
            'message' => $message,
        ];

        if (!is_null($data)) {
            if (is_array($data) && array_keys($data) !== range(0, count($data) - 1)) {
                $response = array_merge($response, $data);
            } else {
                $response['data'] = $data;
            }
        }

        return response()->json($response, $statusCode);
    }
}
