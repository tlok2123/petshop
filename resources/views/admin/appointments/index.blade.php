@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách cuộc hẹn</h2>

        <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Tạo cuộc hẹn
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
                        <th>Khách hàng</th>
                        <th>Thú cưng</th>
                        <th>Ngày hẹn</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($appointments as $appointment)
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
                                <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
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
            {{ $appointments->links() }}
        </div>
    </div>
@endsection
