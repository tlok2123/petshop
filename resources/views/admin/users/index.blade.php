@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách người dùng</h2>

        <table class="table table-hover table-bordered">
            <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Họ và Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
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
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i> Xem
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
@endsection
