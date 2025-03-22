@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2>Thêm danh mục</h2>

        <form action="{{ route('admin.category.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Thêm mới</button>
            <a href="{{ route('admin.category.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
