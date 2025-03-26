@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Chi tiết dịch vụ</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>ID:</strong> {{ $service->id }}</li>
                            <li class="list-group-item"><strong>Tên dịch vụ:</strong> {{ $service->name }}</li>
                            <li class="list-group-item"><strong>Giá:</strong>
                                <span class="text-danger fw-bold">{{ number_format($service->price, 0, ',', '.') }} VNĐ</span>
                            </li>
                            <li class="list-group-item"><strong>Loại dịch vụ:</strong>
                                @if ($service->type == 1)
                                    Chăm sóc
                                @elseif ($service->type == 2)
                                    Khám
                                @else
                                    Kí gửi
                                @endif
                            </li>
                            <li class="list-group-item"><strong>Mô tả:</strong> {{ $service->description }}</li>
                        </ul>
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary px-4">Quay lại danh sách</a>
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-warning px-4">
                            <i class="bi bi-pencil-square"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?')">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
