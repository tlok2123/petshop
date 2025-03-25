@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-warning text-white text-center">
                        <h3>Cập nhật đơn hàng #{{ $order->id }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>

                            <h4>Chọn sản phẩm</h4>
                            <div id="product-list">
                                @foreach($order->items as $index => $item)
                                    <div class="row align-items-center product-item mb-2">
                                        <div class="col-md-6">
                                            <select name="products[{{ $index }}][id]" class="form-control product-select" required>
                                                <option value="">Chọn sản phẩm</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                                        {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} - {{ number_format($product->price) }} VNĐ
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" name="products[{{ $index }}][quantity]" class="form-control quantity-input"
                                                   placeholder="Số lượng" min="1" value="{{ $item->quantity }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-product">Xóa</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success" id="add-product">Thêm sản phẩm</button>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-warning">Cập nhật</button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let productIndex = {{ $order->items->count() }};
            document.getElementById("add-product").addEventListener("click", function () {
                let productList = document.getElementById("product-list");
                let newProduct = document.createElement("div");
                newProduct.classList.add("row", "align-items-center", "product-item", "mt-2");
                newProduct.innerHTML = `
                    <div class="col-md-6">
                        <select name="products[\${productIndex}][id]" class="form-control product-select" required>
                            <option value="">Chọn sản phẩm</option>
                            @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                    {{ $product->name }} - {{ number_format($product->price) }} VNĐ
                                </option>
                            @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="products[\${productIndex}][quantity]" class="form-control quantity-input" placeholder="Số lượng" min="1" value="1">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-product">Xóa</button>
            </div>
`;
                productList.appendChild(newProduct);
                productIndex++;
            });

            document.getElementById("product-list").addEventListener("click", function (event) {
                if (event.target.classList.contains("remove-product")) {
                    event.target.closest(".product-item").remove();
                }
            });
        });
    </script>
@endsection
