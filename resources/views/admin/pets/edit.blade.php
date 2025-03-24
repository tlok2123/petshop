@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Chỉnh sửa thú cưng</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.pets.update', $pet) }}" method="POST" id="petForm">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Tên thú cưng</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $pet->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Khách hàng</label>
                                <select name="user_id" class="form-control" required>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $pet->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Loài</label>
                                <select name="species" class="form-control" required>
                                    <option value="1" {{ $pet->species == 1 ? 'selected' : '' }}>Chó</option>
                                    <option value="2" {{ $pet->species == 2 ? 'selected' : '' }}>Mèo</option>
                                    <option value="3" {{ $pet->species == 3 ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tuổi</label>
                                <input type="number" name="age" class="form-control" value="{{ old('age', $pet->age) }}" required min="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tình trạng sức khỏe</label>
                                <textarea name="health_status" class="form-control" rows="3" required>{{ old('health_status', $pet->health_status) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ngày hết hạn gửi thú cưng</label>
                                <input type="date" name="boarding_expiry" class="form-control" value="{{ old('boarding_expiry', $pet->boarding_expiry ? \Carbon\Carbon::parse($pet->boarding_expiry)->format('Y-m-d') : '') }}">
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-4">Cập nhật</button>
                                <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
