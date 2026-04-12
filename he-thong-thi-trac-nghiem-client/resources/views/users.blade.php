<!DOCTYPE html>
<html>
<head>
    <title>Users</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .content-wrapper {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
        }
        .user-info {
            color: white;
            font-size: 14px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-graduation-cap me-2"></i>Hệ thống thi trắc nghiệm
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (session()->has('auth_token'))
                        <li class="nav-item">
                            <span class="user-info">
                                <i class="fas fa-user me-1"></i>
                                {{ session('student id') ?? 'Người dùng' }}
                            </span>
                        </li>
                        <li class="nav-item ms-3">
                            <a class="nav-link btn btn-light btn-sm text-dark" href="{{ route('logout') }}">
                                <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link btn btn-light btn-sm text-dark" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="content-wrapper">
            <h2 class="mb-4">
                <i class="fas fa-users me-2" style="color: #667eea;"></i>Danh sách người dùng
            </h2>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="/add-user" method="POST" class="mb-4">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Nhập tên" required>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i>Thêm người dùng
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>ID</th>
                            <th><i class="fas fa-user me-2"></i>Tên</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">
                                <i class="fas fa-inbox me-2"></i>Không có dữ liệu
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <div style="position: fixed; bottom: 10px; right: 10px; background: #ffeb3b; color: #000; padding: 10px 15px; font-weight: bold; border-radius: 5px; z-index: 9999; box-shadow: 0 2px 4px rgba(0,0,0,0.2); font-size: 12px;">
        API: {{ config('app.base_api') }}/api
    </div>
</form>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
    </thead>

    <tbody>
       @foreach ($users as $user)
    <tr>
        <td>{{ $user['uid'] }}</td>

        <td>{{ $user['first_name'] }} {{ $user['last_name'] }}</td>
    </tr>
@endforeach
    </tbody>
</table>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<div style="position: fixed; bottom: 10px; right: 10px; background: #ffeb3b; color: #000; padding: 5px 10px; font-weight: bold; border-radius: 5px; z-index: 9999; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
    Base API: https://he-thong-thi-trac-nghiem-service-lnup.onrender.com/api/test-users
</div>
</body>
</html>