@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2>Chi tiết sản phẩm</h2>

        <p><strong>ID:</strong> {{ $product->id }}</p>
        <p><strong>Tên sản phẩm:</strong> {{ $product->name }}</p>
        <p><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
        <p><strong>Mô tả:</strong> {{ $product->description }}</p>

        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
@endsection
