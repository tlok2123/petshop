@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2>Chỉnh sửa danh mục</h2>

        <form action="{{ route('admin.category.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
            </div>

            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="{{ route('admin.category.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
