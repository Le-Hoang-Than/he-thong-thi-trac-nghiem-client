<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách bài thi - {{ config('app.name', 'Hệ thống thi trắc nghiệm') }}</title>
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

        .exam-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .exam-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .exam-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }

        .exam-card-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .exam-card-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .exam-card-body {
            padding: 25px;
        }

        .exam-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 18px;
            margin-right: 12px;
        }

        .info-content {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .exam-description {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }

        .exam-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .btn-start {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .exam-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-not-started {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-in-progress {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .filter-section h5 {
            color: #333;
            font-weight: 600;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .exam-info {
                grid-template-columns: repeat(2, 1fr);
            }

            .exam-card-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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
                        {{ session('studentid') ?? 'N/A' }}
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
                        <a href="/exams" class="active">
                            <i class="fas fa-file-alt me-3"></i>Bài thi
                        </a>
                    </li>
                    <li>
                        <a href="/results">
                            <i class="fas fa-chart-bar me-3"></i>Kết quả
                        </a>
                    </li>
                    <li>
                        <a href="/profile">
                            <i class="fas fa-user me-3"></i>Hồ sơ
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <div class="main-content">
                    <!-- Page Header -->
                    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h1 style="margin: 0;">
                                <i class="fas fa-file-alt me-2" style="color: #667eea;"></i>
                                Danh sách bài thi
                            </h1>
                            <p style="margin: 5px 0 0 0;">Chọn một bài thi để bắt đầu</p>
                        </div>
                        <button class="btn btn-outline-primary" onclick="location.reload();" title="Tải lại danh sách bài thi">
                            <i class="fas fa-sync-alt me-1"></i>Làm mới
                        </button>
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

                    <!-- Filter Section -->
                    <div class="filter-section">
                        <h5>
                            <i class="fas fa-filter me-2" style="color: #667eea;"></i>Bộ lọc
                        </h5>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="filterStatus" class="form-label">Trạng thái</label>
                                <select id="filterStatus" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="not-started">Chưa bắt đầu</option>
                                    <option value="in-progress">Đang làm</option>
                                    <option value="completed">Đã hoàn thành</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filterDifficulty" class="form-label">Độ khó</label>
                                <select id="filterDifficulty" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="easy">Dễ</option>
                                    <option value="medium">Trung bình</option>
                                    <option value="hard">Khó</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Exams List -->
                    @if(count($exams) > 0)
                        @foreach($exams as $exam)
                            <div class="exam-card">
                                <div class="exam-card-header">
                                    <div class="exam-card-title">
                                        {{ $exam['quiz_name'] ?? 'Bài thi không có tên' }}
                                    </div>
                                    <p class="exam-card-subtitle">
                                        {{ isset($exam['description']) ? substr($exam['description'], 0, 50) : 'Mô tả' }}
                                    </p>
                                </div>

                                <div class="exam-card-body">
                                    <div class="exam-info">
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-question"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Số câu hỏi</span>
                                                <span class="info-value">{{ $exam['noq'] ?? 0 }}</span>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-hourglass-end"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Thời gian</span>
                                                <span class="info-value">{{ $exam['duration'] ?? 60 }} phút</span>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-percent"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Điểm đạt</span>
                                                <span class="info-value">{{ $exam['pass_percentage'] ?? 70 }}%</span>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Trạng thái</span>
                                                @if(isset($exam['attempted']) && $exam['attempted'])
                                                    <span class="info-value" style="color: #28a745;">
                                                        <i class="fas fa-check-circle"></i> Đã làm
                                                    </span>
                                                @else
                                                    <span class="info-value" style="color: #667eea;">Sẵn sàng</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(isset($exam['last_result']))
                                        <div class="exam-description" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px;">
                                            <strong>Kết quả gần nhất:</strong><br>
                                            <small>
                                                Câu đúng: <strong>{{ $exam['last_result']['score_obtained'] ?? 0 }}/{{ $exam['noq'] ?? 0 }}</strong> | 
                                                Điểm: <strong>{{ round((($exam['last_result']['score_obtained'] ?? 0) / ($exam['noq'] ?? 40)) * 10, 2) }}</strong>/10
                                            </small>
                                        </div>
                                    @elseif(isset($exam['description']) && $exam['description'])
                                        <div class="exam-description">
                                            {{ $exam['description'] }}
                                        </div>
                                    @endif

                                    <div class="exam-card-footer">
                                        @if(isset($exam['attempted']) && $exam['attempted'])
                                            <a href="/exam-result/{{ $exam['last_result']['rid'] ?? '#' }}" class="btn-start" style="background: #28a745;">
                                                <i class="fas fa-eye me-2"></i>Xem kết quả
                                            </a>
                                            <span class="exam-status status-completed">
                                                <i class="fas fa-check-circle me-1"></i>Đã hoàn thành
                                            </span>
                                        @else
                                            <a href="/exams/{{ $exam['quid'] ?? '#' }}/status" class="btn-start">
                                                <i class="fas fa-play me-2"></i>Bắt đầu làm bài
                                            </a>
                                            <span class="exam-status status-not-started">
                                                <i class="fas fa-clock me-1"></i>Chưa bắt đầu
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h3>Không có bài thi nào</h3>
                            <p>Hiện tại chưa có bài thi khả dụng. Vui lòng kiểm tra lại sau.</p>
                        </div>
                    @endif
                </div>
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
    </script>
</body>
</html>
