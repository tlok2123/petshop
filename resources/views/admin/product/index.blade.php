@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2>Danh sách sản phẩm</h2>
        <a href="{{ route('admin.product.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                    <td>
                        <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-info">Xem</a>
                        <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
@endsection
