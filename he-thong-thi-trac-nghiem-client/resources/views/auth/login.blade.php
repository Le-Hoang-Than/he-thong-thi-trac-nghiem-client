<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - {{ config('app.name', 'Hệ thống thi trắc nghiệm') }}</title>
    <h1 style="color: red; font-size: 50px;">ĐÂY LÀ BẢN CODE MỚI NHẤT!</h1>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.95;
            margin-bottom: 0;
        }

        .login-body {
            padding: 40px 30px;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .alert-danger {
            background-color: #fff5f5;
            color: #721c24;
        }

        .alert-danger .alert-link {
            color: #721c24;
        }

        .alert-success {
            background-color: #f0f9f5;
            color: #155724;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 14px;
            display: block;
        }

        .form-control {
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background-color: white;
        }

        .form-control::placeholder {
            color: #999;
            font-size: 13px;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            background-color: #ffe5e5;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 12px;
            margin-top: 6px;
            display: block;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 15px;
            letter-spacing: 0.3px;
            margin-top: 10px;
            cursor: pointer;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.35);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .login-footer {
            text-align: center;
            padding: 20px 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            font-size: 14px;
            color: #666;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            pointer-events: none;
        }

        @media (max-width: 576px) {
            .login-wrapper {
                padding: 15px;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .login-body {
                padding: 30px 20px;
            }

            .login-footer {
                padding: 15px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header">
                <h1><i class="fas fa-graduation-cap"></i> Hệ thống thi trắc nghiệm</h1>
                <p>Vui lòng đăng nhập để tiếp tục</p>
            </div>

            <div class="login-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Lỗi đăng nhập!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label for="studentid" class="form-label">
                            <i class="fas fa-id-card me-2" style="color: #667eea;"></i>Mã sinh viên
                        </label>
                        <div class="input-icon">
                            <input 
                                type="text" 
                                id="studentid" 
                                name="studentid" 
                                class="form-control @error('studentid') is-invalid @enderror"
                                value="{{ old('studentid') }}"
                                required
                                placeholder="Nhập mã sinh viên"
                                autocomplete="username"
                            >
                            <i class="fas fa-user"></i>
                        </div>
                        @error('studentid')
                            <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2" style="color: #667eea;"></i>Mật khẩu
                        </label>
                        <div class="input-icon">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control @error('password') is-invalid @enderror"
                                required
                                placeholder="Nhập mật khẩu"
                                autocomplete="current-password"
                            >
                            <i class="fas fa-lock"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                    </button>
                </form>
            </div>

            <div class="login-footer">
                <p class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Liên hệ quản trị viên để được cấp tài khoản
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Focus on first input
        document.getElementById('studentid').focus();
    </script>
</body>
</html>
