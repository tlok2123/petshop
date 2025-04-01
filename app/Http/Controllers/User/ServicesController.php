<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $services = $query->latest()->paginate(10);
        return response()->json([
            'status' => 200,
            'message' => 'Danh sách dịch vụ',
            'data' => $services
        ]);
    }

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
