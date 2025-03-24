@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4 border-0 rounded-4">
            <div class="row g-4">
                <!-- Avatar -->
                <div class="col-md-4 text-center">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                         class="img-fluid rounded-circle border shadow"
                         alt="Avatar" width="150">
                </div>

                <!-- Thông tin người dùng -->
                <div class="col-md-8">
                    <h2 class="fw-bold text-primary">Thông tin Người dùng</h2>
                    <hr>
                    <p><strong>Tên:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $user->address ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Vai trò:</strong>
                        <span class="badge {{ $user->role == 1 ? 'bg-success' : 'bg-secondary' }}">
                            {{ $user->role == 1 ? 'Admin' : 'Khách hàng' }}
                        </span>
                    </p>
                    <div class="text-center mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">Quay lại danh sách</a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning px-4">
                            <i class="bi bi-pencil-square"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
