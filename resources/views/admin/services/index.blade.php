@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách dịch vụ</h2>

        <a href="{{ route('admin.services.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Thêm dịch vụ
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
                        <th>Tên dịch vụ</th>
                        <th>Giá</th>
                        <th>Loại</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($services as $service)
                        <tr class="align-middle text-center">
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->name }}</td>
                            <td><span class="badge bg-success">{{ number_format($service->price, 0, ',', '.') }} VNĐ</span></td>
                            <td>
                                @if ($service->isCare())
                                    <span class="badge bg-primary">Chăm sóc</span>
                                @elseif ($service->isExamination())
                                    <span class="badge bg-warning">Khám</span>
                                @elseif ($service->isConsignment())
                                    <span class="badge bg-danger">Kí gửi</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.services.show', $service->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline">
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
            {{ $services->links() }}
        </div>
    </div>
@endsection
