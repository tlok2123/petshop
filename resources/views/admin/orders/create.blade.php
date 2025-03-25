@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Tạo đơn hàng</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Người dùng</label>
                                <select name="user_id" class="form-control" required>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control">
                                    <option value="1">Đang xử lý</option>
                                    <option value="2">Hoàn thành</option>
                                    <option value="3">Đã hủy</option>
                                </select>
                            </div>

                            <h4>Chọn sản phẩm</h4>
                            <div id="product-list">
                                <div class="row align-items-center product-item">
                                    <div class="col-md-4">
                                        <select name="products[0][id]" class="form-control product-select" required>
                                            <option value="">Chọn sản phẩm</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                    {{ $product->name }} - {{ number_format($product->price) }} VNĐ
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="products[0][quantity]" class="form-control quantity-input" placeholder="Số lượng" min="1" value="1">
                                    </div>
                                    <div class="col-md-3">
                                        <span class="total-price">0 VNĐ</span>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-product">Xóa</button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success" id="add-product">Thêm sản phẩm</button>
                            </div>

                            <div class="text-center mt-4">
                                <h4>Tổng giá trị đơn hàng: <span id="total-order-price">0 VNĐ</span></h4>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success">Tạo đơn hàng</button>
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
            let productIndex = 1;

            function updateTotalPrice() {
                let total = 0;
                document.querySelectorAll(".product-item").forEach(function(item) {
                    let price = parseFloat(item.querySelector(".product-select").selectedOptions[0].dataset.price || 0);
                    let quantity = parseInt(item.querySelector(".quantity-input").value) || 1;
                    let itemTotal = price * quantity;
                    item.querySelector(".total-price").textContent = new Intl.NumberFormat().format(itemTotal) + " VNĐ";
                    total += itemTotal;
                });
                document.getElementById("total-order-price").textContent = new Intl.NumberFormat().format(total) + " VNĐ";
            }

            document.getElementById("add-product").addEventListener("click", function () {
                let productList = document.getElementById("product-list");
                let newProduct = document.createElement("div");
                newProduct.classList.add("row", "align-items-center", "product-item", "mt-2");
                newProduct.innerHTML = `
                    <div class="col-md-4">
                        <select name="products[\${productIndex}][id]" class="form-control product-select" required>
                            <option value="">Chọn sản phẩm</option>
                            @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                    {{ $product->name }} - {{ number_format($product->price) }} VNĐ
                                </option>
                            @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="products[\${productIndex}][quantity]" class="form-control quantity-input" placeholder="Số lượng" min="1" value="1">
            </div>
            <div class="col-md-3">
                <span class="total-price">0 VNĐ</span>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-product">Xóa</button>
            </div>
`;
                productList.appendChild(newProduct);
                productIndex++;
            });

            document.getElementById("product-list").addEventListener("input", function (event) {
                if (event.target.classList.contains("product-select") || event.target.classList.contains("quantity-input")) {
                    updateTotalPrice();
                }
            });

            document.getElementById("product-list").addEventListener("click", function (event) {
                if (event.target.classList.contains("remove-product")) {
                    event.target.closest(".product-item").remove();
                    updateTotalPrice();
                }
            });
        });
    </script>
@endsection
