@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách danh mục</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.category.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Thêm danh mục
        </a>

        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-hover table-bordered text-center align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
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
            {{ $categories->links() }}
        </div>
    </div>
@endsection
