<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ - {{ config('app.name', 'Hệ thống thi trắc nghiệm') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            min-height: 100vh;
            padding-top: 20px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #f0f2f5;
            border-left-color: #667eea;
            color: #667eea;
        }

        .main-content {
            padding: 30px 20px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .profile-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 20px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }

        .profile-info h2 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .profile-info p {
            color: #666;
            margin-bottom: 3px;
        }

        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .info-text {
            color: #999;
            font-size: 13px;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/exams">
                <i class="fas fa-graduation-cap me-2"></i>Hệ thống thi trắc nghiệm
            </a>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn btn-link text-white text-decoration-none dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" style="font-weight: 600;">
                        {{ session('studentid') ?? $user['uid'] ?? 'N/A' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>Hồ sơ</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 d-none d-md-block sidebar">
                <ul class="sidebar-menu">
                    <li>
                        <a href="/exams">
                            <i class="fas fa-file-alt me-3"></i>Bài thi
                        </a>
                    </li>
                    <li>
                        <a href="/results">
                            <i class="fas fa-chart-bar me-3"></i>Kết quả
                        </a>
                    </li>
                    <li>
                        <a href="/profile" class="active">
                            <i class="fas fa-user-circle me-3"></i>Hồ sơ
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="page-header">
                    <h1><i class="fas fa-user-circle me-2"></i>Hồ sơ của tôi</h1>
                    <p style="color: #666; margin: 5px 0 0 0;">Chỉnh sửa thông tin cá nhân</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Edit Profile Form -->
                <div class="form-card">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        
                        <!-- Profile Avatar -->
                        <div style="text-align: center; margin-bottom: 30px;">
                            <div class="profile-avatar" style="margin: 0 auto;">
                                <i class="fas fa-user"></i>
                            </div>
                            <p style="color: #999; margin-top: 15px; font-size: 13px;">
                                <i class="fas fa-id-card me-2"></i>
                                Mã sinh viên: <strong>{{ session('studentid') ?? $user['uid'] ?? 'N/A' }}</strong>
                            </p>
                        </div>

                        <hr>

                        <!-- Form Fields -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user me-2"></i>Họ
                                    </label>
                                    <input type="text" class="form-control" name="last_name" 
                                           value="{{ $user['last_name'] ?? '' }}" 
                                           placeholder="Nhập họ">
                                    <p class="info-text">Họ của bạn</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user me-2"></i>Tên
                                    </label>
                                    <input type="text" class="form-control" name="first_name" 
                                           value="{{ $user['first_name'] ?? '' }}" 
                                           placeholder="Nhập tên">
                                    <p class="info-text">Tên của bạn</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope me-2"></i>Email
                                    </label>
                                    <input type="email" class="form-control" name="email" 
                                           value="{{ $user['email'] ?? '' }}" 
                                           placeholder="Nhập email">
                                    <p class="info-text">Địa chỉ email của bạn</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-phone me-2"></i>Điện thoại
                                    </label>
                                    <input type="text" class="form-control" name="phone" 
                                           value="{{ $user['phone'] ?? '' }}" 
                                           placeholder="Nhập số điện thoại">
                                    <p class="info-text">Số điện thoại liên lạc</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div style="margin-top: 30px; display: flex; gap: 10px;">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                            <a href="/exams" class="btn btn-outline-secondary" style="padding: 12px 30px; border-radius: 6px; text-decoration: none;">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
