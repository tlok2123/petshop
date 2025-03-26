<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    /**
     * Lấy danh sách dịch vụ (có phân trang).
     */
    public function index(Request $request)
    {
        $query = Service::query();

        // Tìm kiếm theo tên dịch vụ (nếu có)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lấy danh sách dịch vụ, mỗi trang 10 mục
        $services = $query->latest()->paginate(10);

        return response()->json([
            'status' => 200,
            'message' => 'Danh sách dịch vụ',
            'data' => $services
        ]);
    }

    /**
     * Lấy chi tiết một dịch vụ.
     */
    public function show($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'status' => 404,
                'message' => 'Không tìm thấy dịch vụ'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Chi tiết dịch vụ',
            'data' => $service
        ]);
    }
}
