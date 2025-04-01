@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách sản phẩm</h2>

        <!-- Nút thêm sản phẩm -->
        <a href="{{ route('admin.product.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Thêm sản phẩm
        </a>

        <!-- Thông báo thành công -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Bộ lọc và tìm kiếm -->
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.product.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm theo tên sản phẩm..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                </form>
            </div>
        </div>

        <!-- Bảng danh sách sản phẩm -->
        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 25%;">Tên sản phẩm</th>
                        <th style="width: 15%;">Giá</th>
                        <th style="width: 15%;">Số lượng còn lại</th>
                        <th style="width: 25%;">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $product)
                        <tr class="align-middle text-center">
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td><span class="badge bg-success">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span></td>
                            <td>
                                <span class="badge
                                    @if($product->stock <= 5)
                                        bg-danger
                                    @elseif($product->stock <= 20)
                                        bg-warning
                                    @else
                                        bg-success
                                    @endif">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Chỉnh sửa sản phẩm">
                                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" data-bs-toggle="tooltip" title="Xóa sản phẩm">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Không có sản phẩm nào.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $products->onEachSide(2)->links() }}
        </div>
    </div>

    <!-- Khởi tạo tooltip -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
