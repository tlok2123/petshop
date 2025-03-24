@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách thú cưng</h2>

        <a href="{{ route('admin.pets.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Thêm thú cưng
        </a>

        <table class="table table-hover table-bordered">
            <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Khách hàng</th>
                <th>Loài</th>
                <th>Tuổi</th>
                <th>Tình trạng sức khỏe</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pets as $pet)
                <tr class="align-middle text-center">
                    <td>{{ $pet->id }}</td>
                    <td>{{ $pet->name }}</td>
                    <td>{{$pet->user->name ?? 'Không xác định'}}</td>
                    <td>{{ $pet->speciesLabel() }}</td>
                    <td>{{ $pet->age }}</td>
                    <td>{{ $pet->health_status }}</td>
                    <td>
                        <a href="{{ route('admin.pets.show', $pet) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i> Xem
                        </a>
                        <a href="{{ route('admin.pets.edit', $pet) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.pets.destroy', $pet) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa thú cưng này?')">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $pets->links() }}
        </div>
    </div>
@endsection
