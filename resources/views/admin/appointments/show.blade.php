@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3>Chi tiết cuộc hẹn #{{ $appointment->id }}</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h5><strong>Khách hàng:</strong> {{ $appointment->user->name }}</h5>
                    <h5><strong>Thú cưng:</strong> {{ $appointment->pet->name }}</h5>
                    <h5><strong>Ngày hẹn:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y H:i') }}</h5>
                    <h5><strong>Tổng tiền:</strong> <span class="text-danger">{{ number_format($appointment->total_price) }} VNĐ</span></h5>
                    <h5><strong>Trạng thái:</strong>
                        <span class="badge
                            @if($appointment->status == 1) bg-warning
                            @elseif($appointment->status == 2) bg-info
                            @elseif($appointment->status == 3) bg-primary
                            @else bg-success @endif">
                            @if($appointment->status == 1) Đang xử lý
                            @elseif($appointment->status == 2) Đã liên hệ
                            @elseif($appointment->status == 3) Đã xác nhận
                            @else Hoàn thành
                            @endif
                        </span>
                    </h5>
                </div>

                <h4 class="mt-4">Dịch vụ trong cuộc hẹn</h4>
                <table class="table table-bordered mt-3">
                    <thead class="table-dark">
                    <tr>
                        <th>Tên dịch vụ</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($appointment->services as $service)
                        <tr>
                            <td>{{ $service->service->name }}</td>
                            <td>{{ $service->quantity }}</td>
                            <td>{{ number_format($service->price / $service->quantity) }} VNĐ</td>
                            <td class="text-end text-danger">{{ number_format($service->price) }} VNĐ</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if($appointment->note)
                    <h5 class="mt-4"><strong>Ghi chú:</strong></h5>
                    <p class="border p-3 bg-light">{{ $appointment->note }}</p>
                @endif

                <div class="text-center mt-4">
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
@endsection
