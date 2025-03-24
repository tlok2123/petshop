@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-center">Chi tiết thú cưng</h2>

        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Thông tin thú cưng</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Tên thú cưng:</strong> {{ $pet->name }}
                </div>
                <div class="mb-3">
                    <strong>Khách hàng:</strong> {{ $pet->user->name ?? 'Không xác định' }}
                </div>
                <div class="mb-3">
                    <strong>Loài:</strong> {{ $pet->speciesLabel() }}
                </div>
                <div class="mb-3">
                    <strong>Tuổi:</strong> {{ $pet->age }}
                </div>
                <div class="mb-3">
                    <strong>Tình trạng sức khỏe:</strong> {{ $pet->health_status }}
                </div>
                <div class="mb-3">
                    <strong>Ngày hết hạn gửi thú cưng:</strong> {{ $pet->boarding_expiry ? \Carbon\Carbon::parse($pet->boarding_expiry)->format('d/m/Y') : 'Không có' }}
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary px-4">Quay lại danh sách</a>
                    <a href="{{ route('admin.pets.edit', $pet) }}" class="btn btn-warning px-4">
                        <i class="bi bi-pencil-square"></i> Chỉnh sửa
                    </a>
                    <form action="{{ route('admin.pets.destroy', $pet) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa thú cưng này?')">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
