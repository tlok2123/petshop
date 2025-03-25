@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3>Chi tiết đơn hàng #{{ $order->id }}</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h5><strong>Người đặt:</strong> {{ $order->user->name }}</h5>
                    <h5><strong>Tổng tiền:</strong> <span class="text-danger">{{ number_format($order->total_price) }} VNĐ</span></h5>
                    <h5><strong>Trạng thái:</strong>
                        <span class="badge
                            @if($order->status == 1) bg-warning
                            @elseif($order->status == 2) bg-success
                            @else bg-danger @endif">
                            @if($order->status == 1) Đang xử lý
                            @elseif($order->status == 2) Hoàn thành
                            @else Đã hủy
                            @endif
                        </span>
                    </h5>
                </div>

                <h4 class="mt-4">Sản phẩm trong đơn</h4>
                <table class="table table-bordered mt-3">
                    <thead class="table-dark">
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price / $item->quantity) }} VNĐ</td>
                            <td class="text-end text-danger">{{ number_format($item->price) }} VNĐ</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="text-center mt-4">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
@endsection
