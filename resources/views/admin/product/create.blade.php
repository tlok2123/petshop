@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Thêm sản phẩm</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
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
                                <label class="form-label">Hình ảnh sản phẩm</label>
                                <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-4">Thêm mới</button>
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
