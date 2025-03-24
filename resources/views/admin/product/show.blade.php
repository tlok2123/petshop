@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Chi tiết sản phẩm</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ $product->photo ? asset('storage/' . $product->photo) : asset('images/default.jpg') }}"
                                 alt="Ảnh sản phẩm" class="img-fluid rounded shadow" width="300">
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>ID:</strong> {{ $product->id }}</li>
                            <li class="list-group-item"><strong>Tên sản phẩm:</strong> {{ $product->name }}</li>
                            <li class="list-group-item"><strong>Giá:</strong>
                                <span class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                            </li>
                            <li class="list-group-item"><strong>Mô tả:</strong> {{ $product->description }}</li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
