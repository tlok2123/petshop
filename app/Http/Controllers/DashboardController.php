<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
class DashboardController extends Controller
{
    public function index()
    {
        // Lấy danh sách đơn hàng chưa xử lý, sắp xếp từ cũ đến mới
        $pendingOrders = Order::where('status', 1)->orderBy('created_at')->paginate(5);

        // Lấy danh sách sản phẩm sắp hết hàng
        $lowStockProducts = Product::where('stock', '<=', 5)->paginate(5);


        // Lấy doanh thu theo tuần
        $weeklyRevenue = Order::where('status', 2)
            ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->selectRaw('DATE(updated_at) as date, SUM(total_price) as revenue')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('revenue', 'date');
        $weeklyLabels = $weeklyRevenue->keys(); // Lấy danh sách ngày làm nhãn
        $weeklyData = $weeklyRevenue->values(); // Lấy danh sách doanh thu

        return view('admin.dashboard', compact('pendingOrders', 'lowStockProducts', 'weeklyRevenue', 'weeklyLabels', 'weeklyData'));
    }

}
