@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách đơn hàng</h2>

        <!-- Nút tạo đơn hàng -->
        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Tạo đơn hàng
        </a>

        <!-- Thông báo thành công -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Bộ lọc và tìm kiếm -->
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm theo tên người đặt..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                </form>
            </div>
            <div class="col-md-3">
                <form method="GET" action="{{ route('admin.orders.index') }}">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Bảng danh sách đơn hàng -->
        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 20%;">Người đặt</th>
                        <th style="width: 15%;">Tổng tiền</th>
                        <th style="width: 15%;">Trạng thái</th>
                        <th style="width: 25%;">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
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
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Chỉnh sửa đơn hàng">
                                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" data-bs-toggle="tooltip" title="Xóa đơn hàng">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Không có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $orders->onEachSide(2)->links() }}
        </div>
    </div>

    <!-- Khởi tạo tooltip -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
