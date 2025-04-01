<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy danh sách đơn hàng chưa xử lý
        $pendingOrders = Order::where('status', 1)->orderBy('created_at')->paginate(5);

        // Lấy danh sách sản phẩm sắp hết hàng
        $lowStockProducts = Product::where('stock', '<=', 5)->paginate(5);

        // Lấy khoảng thời gian tuần hiện tại
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        \Log::info('Week Range:', [
            'start' => $startOfWeek->toDateTimeString(),
            'end' => $endOfWeek->toDateTimeString()
        ]);

        // Doanh thu từ đơn hàng (Order)
        $orderRevenue = Order::where('status', 2)
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(updated_at) as date, SUM(total_price) as revenue')
            ->groupBy(\DB::raw('DATE(updated_at)'))
            ->orderBy('date', 'asc')
            ->pluck('revenue', 'date')
            ->map(fn($value) => (float) $value); // Ép kiểu thành số

        // Doanh thu từ dịch vụ (Appointment)
        $appointmentRevenue = Appointment::where('status', 4)
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(updated_at) as date, SUM(total_price) as revenue')
            ->groupBy(\DB::raw('DATE(updated_at)'))
            ->orderBy('date', 'asc')
            ->pluck('revenue', 'date')
            ->map(fn($value) => (float) $value); // Ép kiểu thành số

        // Tạo nhãn và dữ liệu cho 7 ngày trong tuần
        $weeklyLabels = [];
        $orderData = [];
        $appointmentData = [];
        $currentDate = $startOfWeek->copy();

        while ($currentDate <= $endOfWeek) {
            $dateKey = $currentDate->format('Y-m-d');
            $dayLabel = $currentDate->format('d/m');
            $weeklyLabels[] = $dayLabel;
            $orderData[] = $orderRevenue->get($dateKey, 0);
            $appointmentData[] = $appointmentRevenue->get($dateKey, 0);
            $currentDate->addDay();
        }

        \Log::info('Weekly Data:', [
            'labels' => $weeklyLabels,
            'order_data' => $orderData,
            'appointment_data' => $appointmentData,
        ]);

        return view('admin.dashboard', compact(
            'pendingOrders',
            'lowStockProducts',
            'weeklyLabels',
            'orderData',
            'appointmentData'
        ));
    }
}
