@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2>Chi tiết danh mục</h2>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tên danh mục: {{ $category->name }}</h5>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('admin.category.index') }}" class="btn btn-secondary px-4">Quay lại danh sách</a>
            <a href="{{ route('admin.category.edit', $category) }}" class="btn btn-warning px-4">
                <i class="bi bi-pencil-square"></i> Chỉnh sửa
            </a>
            <form action="{{ route('admin.category.destroy', $category) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger px-4"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                    <i class="bi bi-trash"></i> Xóa
                </button>
            </form>
        </div>
    </div>
@endsection
