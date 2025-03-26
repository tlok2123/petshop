@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Thêm dịch vụ</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Tên dịch vụ</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá</label>
                                <input type="number" name="price" class="form-control" required min="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Loại dịch vụ</label>
                                <select name="type" class="form-control" required>
                                    <option value="1">Chăm sóc</option>
                                    <option value="2">Khám</option>
                                    <option value="3">Kí gửi</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-control" rows="4" required></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-4">Thêm mới</button>
                                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
