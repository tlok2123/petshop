@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Bảng điều khiển</h2>

        <!-- Biểu đồ doanh thu theo tuần -->
        <div class="card shadow-lg mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Doanh thu theo tuần</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="max-height: 300px;"></canvas>
            </div>
        </div>

        <div class="row">
            <!-- Đơn hàng chưa xử lý -->
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">Hôm nay bạn có {{ $pendingOrders->total() }} đơn chưa xử lý, xử lý ngay!</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($pendingOrders as $order)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Đơn #{{ $order->id }} - {{ number_format($order->total_price) }} VNĐ
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-primary">Xử lý</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $pendingOrders->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cảnh báo sản phẩm sắp hết hàng -->
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Cảnh báo sắp hết hàng</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($lowStockProducts as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $product->name }}
                                    <span class="badge bg-danger">Còn {{ $product->stock }} sản phẩm</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $lowStockProducts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($weeklyLabels ?? []) !!},
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: {!! json_encode($weeklyRevenue ?? []) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
