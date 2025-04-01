@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách cuộc hẹn</h2>

        <!-- Nút tạo cuộc hẹn -->
        <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Tạo cuộc hẹn
        </a>

        <!-- Thông báo thành công -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Bộ lọc và tìm kiếm -->
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.appointments.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm theo tên khách hàng hoặc thú cưng..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                </form>
            </div>
            <div class="col-md-3">
                <form method="GET" action="{{ route('admin.appointments.index') }}">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Đã liên hệ</option>
                        <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>Hủy</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Bảng danh sách cuộc hẹn -->
        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 15%;">Khách hàng</th>
                        <th style="width: 15%;">Thú cưng</th>
                        <th style="width: 15%;">Ngày hẹn</th>
                        <th style="width: 10%;">Tổng tiền</th>
                        <th style="width: 15%;">Trạng thái</th>
                        <th style="width: 25%;">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($appointments as $appointment)
                        <tr class="align-middle text-center">
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->user->name }}</td>
                            <td>{{ $appointment->pet->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y H:i') }}</td>
                            <td><span class="badge bg-success">{{ number_format($appointment->total_price, 0, ',', '.') }} VNĐ</span></td>
                            <td>
                                @if($appointment->status == 1)
                                    <span class="badge bg-warning">Đang xử lý</span>
                                @elseif($appointment->status == 2)
                                    <span class="badge bg-info">Đã liên hệ</span>
                                @elseif($appointment->status == 3)
                                    <span class="badge bg-primary">Đã xác nhận</span>
                                @elseif($appointment->status == 4)
                                    <span class="badge bg-success">Hoàn thành</span>
                                @else
                                    <span class="badge bg-danger">Hủy</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Chỉnh sửa cuộc hẹn">
                                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" data-bs-toggle="tooltip" title="Xóa cuộc hẹn">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Không có cuộc hẹn nào.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $appointments->onEachSide(2)->links() }}
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
