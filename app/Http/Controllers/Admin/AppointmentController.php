<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Pet;
use App\Models\Service;

class AppointmentController extends Controller
{
    /**
     * Hiển thị danh sách cuộc hẹn.
     */
    public function index()
    {
        $appointments = Appointment::latest()->paginate(10);
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Hiển thị form tạo cuộc hẹn mới.
     */
    public function create()
    {
        $services = Service::all();
        $users = User::all();
        $products = Product::all();

        return view('admin.appointments.create', compact('users', 'products', 'services'));
    }

    /**
     * Lưu cuộc hẹn mới vào database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'date' => 'required|date',
            'status' => 'required|integer|in:1,2,3,4',
            'note' => 'nullable|string',
            'services' => 'required|array',
            'services.*' => 'exists:services,id'
        ]);

        // Tạo cuộc hẹn
        $appointment = Appointment::create([
            'user_id' => $request->user_id,
            'pet_id' => $request->pet_id,
            'date' => $request->date,
            'status' => $request->status,
            'note' => $request->note,
            'total_price' => 0
        ]);

        $totalPrice = 0;

        // Lưu dịch vụ vào bảng `appointment_services`
        foreach ($request->services as $service_id) {
            $service = Service::find($service_id);
            if ($service) {
                $appointment->services()->create($service->id, ['price' => $service->price]);
                $totalPrice += $service->price;
            }
        }

        // Cập nhật tổng tiền
        $appointment->update(['total_price' => $totalPrice]);

        return redirect()->route('admin.appointments.index')->with('success', 'Cuộc hẹn đã được tạo.');
    }

    /**
     * Hiển thị chi tiết một cuộc hẹn.
     */
    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Hiển thị form chỉnh sửa cuộc hẹn.
     */
    public function edit(Appointment $appointment)
    {
        $services = Service::all();
        $users = User::all();
        $products = Product::all();
        $pets = Pet::where('user_id', $appointment->user_id)->get(); // Chỉ lấy thú cưng của user

        return view('admin.appointments.edit', compact(
            'appointment', 'users', 'products', 'pets', 'services'
        ));
    }

    /**
     * Cập nhật cuộc hẹn.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'date' => 'required|date',
            'status' => 'required|integer|in:1,2,3,4',
            'note' => 'nullable|string',
            'services' => 'required|array',
            'services.*.id' => 'exists:services,id',
            'services.*.quantity' => 'required|integer|min:1'
        ]);

        // Cập nhật thông tin cuộc hẹn
        $appointment->update($request->only(['user_id', 'pet_id', 'date', 'status', 'note']));

        $totalPrice = 0;

        // Xóa tất cả dịch vụ cũ
        $appointment->services()->delete();

        // Thêm lại dịch vụ mới
        foreach ($request->services as $serviceData) {
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

        // Cập nhật tổng tiền của cuộc hẹn
        $appointment->update(['total_price' => $totalPrice]);

        return redirect()->route('admin.appointments.index')->with('success', 'Cập nhật thành công.');
    }



    /**
     * Xóa cuộc hẹn.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->services()->detach();
        $appointment->delete();
        return redirect()->route('admin.appointments.index')->with('success', 'Cuộc hẹn đã bị xóa.');
    }

    /**
     * Lấy danh sách thú cưng theo user_id (API).
     */
    public function getPets($user_id)
    {
        $pets = Pet::where('user_id', $user_id)->get();
        return response()->json($pets);
    }
}
