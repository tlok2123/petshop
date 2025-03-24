@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Thêm thú cưng</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.pets.store') }}" method="POST" id="petForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Tên thú cưng</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Khách hàng</label>
                                <select name="user_id" class="form-control" required>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Loài</label>
                                <select name="species" class="form-control" required>
                                    <option value="1">Chó</option>
                                    <option value="2">Mèo</option>
                                    <option value="3">Khác</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tuổi</label>
                                <input type="number" name="age" class="form-control" required min="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tình trạng sức khỏe</label>
                                <textarea name="health_status" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày hết hạn gửi thú cưng</label>
                                <input type="date" name="boarding_expiry" class="form-control">
                            </div>


                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-4">Thêm mới</button>
                                <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
