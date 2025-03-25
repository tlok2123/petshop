@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách đơn hàng</h2>

        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Tạo đơn hàng
        </a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Người đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr class="align-middle text-center">
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td><span class="badge bg-success">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</span></td>
                            <td>
                                @if($order->status == 1)
                                    <span class="badge bg-warning">Đang xử lý</span>
                                @elseif($order->status == 2)
                                    <span class="badge bg-success">Hoàn thành</span>
                                @else
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
