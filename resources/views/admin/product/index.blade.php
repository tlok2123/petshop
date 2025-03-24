@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Danh sách sản phẩm</h2>

        <a href="{{ route('admin.product.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Thêm sản phẩm
        </a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr class="align-middle text-center">
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td><span class="badge bg-success">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span></td>
                            <td>
                                <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
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
            {{ $products->links() }}
        </div>
    </div>
@endsection
