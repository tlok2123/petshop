@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-warning text-white text-center">
                        <h3>Chỉnh sửa sản phẩm</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Tên sản phẩm</label>
                                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá</label>
                                <input type="number" name="price" class="form-control" value="{{ $product->price }}" required min="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Danh mục</label>
                                <select name="category_id" class="form-control" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tồn kho</label>
                                <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required min="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-control" rows="4" required>{{ $product->description }}</textarea>
                            </div>

                            <!-- Hiển thị ảnh hiện tại -->
                            <div class="mb-3 text-center">
                                <label class="form-label d-block">Ảnh hiện tại</label>
                                <img id="currentPhoto" src="{{ $product->photo ? asset('storage/' . $product->photo) : asset('images/default.jpg') }}"
                                     alt="Ảnh sản phẩm" class="img-thumbnail" width="200">
                            </div>

                            <!-- Upload ảnh mới -->
                            <div class="mb-3">
                                <label class="form-label">Chọn ảnh mới (nếu có)</label>
                                <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-4">Cập nhật</button>
                                <a href="{{ route('admin.product.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("photo").addEventListener("change", function(event) {
                let file = event.target.files[0]; // Lấy file đầu tiên

                if (file) {
                    let allowedExtensions = ["jpg", "jpeg", "png"]; // Định dạng hợp lệ
                    let fileExtension = file.name.split('.').pop().toLowerCase(); // Lấy phần mở rộng file

                    if (!allowedExtensions.includes(fileExtension)) {
                        alert("❌ Định dạng ảnh không hợp lệ! Chỉ hỗ trợ JPG, JPEG, PNG.");
                        event.target.value = ""; // Xóa file vừa chọn
                    }
                }
            });
        });
    </script>
@endsection
