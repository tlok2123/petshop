<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class ServicesController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $services = $query->latest()->paginate(10);
        return Helper::apiResponse(200, 'Danh sách dịch vụ', ['services' => $services]);
    }

    public function show($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return Helper::apiResponse(404, 'Không tìm thấy dịch vụ');
        }

        return Helper::apiResponse(200, 'Chi tiết dịch vụ', ['service' => $service]);
    }
}
