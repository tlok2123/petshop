@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <h2>Chi tiết danh mục</h2>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tên danh mục: {{ $category->name }}</h5>
            </div>
        </div>

        <a href="{{ route('admin.category.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
    </div>
@endsection
