@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-warning text-white text-center">
                        <h3>Chỉnh sửa dịch vụ</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.services.update', $service) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Tên dịch vụ</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $service->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price', $service->price) }}" required min="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Loại dịch vụ</label>
                                <select name="type" class="form-control" required>
                                    <option value="1" {{ $service->type == 1 ? 'selected' : '' }}>Chăm sóc</option>
                                    <option value="2" {{ $service->type == 2 ? 'selected' : '' }}>Khám</option>
                                    <option value="3" {{ $service->type == 3 ? 'selected' : '' }}>Kí gửi</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-control" rows="4" required>{{ old('description', $service->description) }}</textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-4">Lưu thay đổi</button>
                                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
