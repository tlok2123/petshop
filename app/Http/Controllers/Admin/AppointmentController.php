<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // Lấy các tham số tìm kiếm và lọc
        $search = $request->query('search');
        $status = $request->query('status');

        // Query cơ bản
        $query = Appointment::query();

        // Tìm kiếm theo tên khách hàng hoặc tên thú cưng
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('pet', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($status) {
            $query->where('status', $status);
        }

        // Phân trang (10 cuộc hẹn mỗi trang)
        $appointments = $query->paginate(10);

        // Truyền dữ liệu sang view
        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $services = Service::all();
        $users = User::all();
        $products = Product::all();
        return view('admin.appointments.create', compact('users', 'products', 'services'));
    }

    public function store(AppointmentRequest $request)
    {
        $data = $request->validated();
        $appointment = Appointment::create([
            'user_id' => $data['user_id'],
            'pet_id' => $data['pet_id'],
            'date' => $data['date'],
            'status' => $data['status'],
            'note' => $data['note'],
            'total_price' => 0
        ]);
        $totalPrice = 0;
        foreach ($data['services'] as $service_id) {
            $service = Service::find($service_id);
            if ($service) {
                $appointment->services()->create([
                    'service_id' => $service->id,
                    'price' => $service->price
                ]);
                $totalPrice += $service->price;
            }
        }

        $appointment->update(['total_price' => $totalPrice]);

        return redirect()->route('admin.appointments.index')->with('success', 'Cuộc hẹn đã được tạo.');
    }

    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $services = Service::all();
        $users = User::all();
        $products = Product::all();
        $pets = Pet::where('user_id', $appointment->user_id)->get();
        return view('admin.appointments.edit', compact('appointment', 'users', 'products', 'pets', 'services'));
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $data = $request->validated();

        $appointment->update($request->only(['user_id', 'pet_id', 'date', 'status', 'note']));

        $totalPrice = 0;
        $appointment->services()->delete();

        foreach ($data['services'] as $serviceData) {
            $service = Service::find($serviceData['id']);
            if ($service) {
                $appointment->services()->create([
                    'service_id' => $service->id,
                    'quantity' => $serviceData['quantity'],
                    'price' => $service->price * $serviceData['quantity'],
                ]);
                $totalPrice += $service->price * $serviceData['quantity'];
            }
        }

        $appointment->update(['total_price' => $totalPrice]);

        return redirect()->route('admin.appointments.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->services()->delete();
        $appointment->delete();
        return redirect()->route('admin.appointments.index')->with('success', 'Cuộc hẹn đã bị xóa.');
    }

    public function getPets($user_id)
    {
        $pets = Pet::where('user_id', $user_id)->get();
        return response()->json($pets);
    }
}
