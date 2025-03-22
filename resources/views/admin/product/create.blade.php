@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2>Thêm sản phẩm</h2>

        <form action="{{ route('admin.product.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="number" name="price" class="form-control" required min="0">
            </div>

            <div class="mb-3">
                <label class="form-label">Danh mục</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tồn kho</label>
                <input type="number" name="stock" class="form-control" required min="0">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Hình ảnh (URL)</label>
                <input type="text" name="image_url" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
