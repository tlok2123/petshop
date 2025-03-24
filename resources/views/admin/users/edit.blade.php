@extends('admin.layouts.master')

@section('content')
    <div class="container mt-5">
        <h2>Chỉnh sửa Người dùng</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Tên:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại:</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ:</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $user->address) }}">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Vai trò:</label>
                <select name="role" id="role" class="form-control">
                    <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Admin</option>
                    <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Khách hàng</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="avatar" class="form-label">Ảnh đại diện:</label>
                <input type="file" name="avatar" id="avatar" class="form-control">
                @if($user->avatar)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded" width="100">
                    </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
