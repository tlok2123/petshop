@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách người dùng</h2>

        <!-- Bộ lọc và tìm kiếm -->
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm theo tên hoặc email..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                </form>
            </div>
        </div>

        <!-- Bảng danh sách người dùng -->
        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 20%;">Họ và Tên</th>
                        <th style="width: 25%;">Email</th>
                        <th style="width: 15%;">Vai trò</th>
                        <th style="width: 25%;">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr class="align-middle text-center">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->role == 1 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->role == 1 ? 'Admin' : 'Khách hàng' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Chỉnh sửa người dùng">
                                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')" data-bs-toggle="tooltip" title="Xóa người dùng">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Không có người dùng nào.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $users->onEachSide(2)->links() }}
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
