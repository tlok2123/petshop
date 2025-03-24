@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h2 class="mb-4">Chào mừng đến với Trang quản trị</h2>
                <p class="lead">Quản lý sản phẩm và danh mục một cách dễ dàng.</p>

                <a href="{{ route('admin.product.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-box-seam"></i> Danh sách sản phẩm
                </a>
                <a href="{{ route('admin.category.index') }}" class="btn btn-secondary mt-3">
                    <i class="bi bi-tags"></i> Danh sách danh mục
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-success mt-3">
                    <i class="bi bi-people"></i> Quản lý người dùng
                </a>
                <a href="{{ route('admin.pets.index') }}" class="btn btn-warning mt-3">
                    <i class="bi bi-heart"></i> Quản lý thú cưng
                </a>
            </div>
        </div>
    </div>
@endsection
