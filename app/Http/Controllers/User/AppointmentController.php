<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function book(Request $request)
    {
        if (!auth()->check()) {
            return Helper::apiResponse(401, null, 'Vui lòng đăng nhập.');
        }

        $services = Service::all(['id', 'name', 'price']);
        $pets = Pet::where('user_id', auth()->id())->get(['id', 'name']);

        return Helper::apiResponse(200, ['services' => $services, 'pets' => $pets]);
    }

    public function store(AppointmentRequest $request)
    {
        if (!auth()->check()) {
            return Helper::apiResponse(401, null, 'Vui lòng đăng nhập.');
        }
        $data = $request->validated();
        if (empty($data['services'])) {
            return Helper::apiResponse(400, null, 'Vui lòng chọn ít nhất một dịch vụ.');
        }

        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'pet_id' => $data['pet_id'],
            'date' => $data['date'],
            'status' => '1',
            'note' => $data['note'] ?? '',
            'total_price' => 0,
        ]);

        $totalPrice = 0;
        $validServices = [];
        foreach ($data['services'] as $serviceId) {
            $service = Service::find($serviceId);
            if ($service) {
                $validServices[] = [
                    'service_id' => $service->id,
                    'price' => $service->price,
                ];
                $totalPrice += $service->price;
            }
        }

        if (empty($validServices)) {
            $appointment->delete();
            return Helper::apiResponse(400, null, 'Không có dịch vụ nào hợp lệ.');
        }

        $appointment->services()->createMany($validServices);
        $appointment->update(['total_price' => $totalPrice]);

        return Helper::apiResponse(201, $appointment->load('services'), 'Cuộc hẹn đã được đặt thành công, vui lòng chờ xác nhận.');
    }
}
