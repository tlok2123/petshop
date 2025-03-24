<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị Admin</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #007bff;
            border-radius: 5px;
        }
        .content {
            margin-left: 250px;
            width: 100%;
        }
        .navbar {
            background: #343a40;
            color: white;
            padding: 10px;
        }
        .navbar .dropdown-toggle::after {
            display: none;
        }
        .avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door"></i> Trang chủ
    </a>
    <a href="{{ route('admin.product.index') }}" class="{{ request()->routeIs('admin.product.index') ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i> Sản phẩm
    </a>
    <a href="{{ route('admin.category.index') }}" class="{{ request()->routeIs('admin.category.index') ? 'active' : '' }}">
        <i class="bi bi-tags"></i> Danh mục
    </a>
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Người dùng
    </a>
    <a href="{{ route('admin.pet.index') }}" class="{{ request()->routeIs('admin.pet.index') ? 'active' : '' }}">
        <i class="bi bi-heart"></i> Thú cưng
    </a>
</div>

<!-- Nội dung chính -->
<div class="content">
    <!-- Navbar -->
    <nav class="navbar d-flex justify-content-between">
        <div>
            <i class="bi bi-bell fs-4"></i>
        </div>
        <div class="dropdown">
            <a href="#" class="text-white text-decoration-none dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown">
                Xin chào, {{ Auth::user()->name }}!
                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('default-avatar.png') }}" alt="Avatar" class="avatar-img ms-2">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">Đăng xuất</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
</div>

<!-- Bootstrap Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
