<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trạng thái bài thi - {{ config('app.name', 'Hệ thống thi trắc nghiệm') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .exam-status-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 40px 30px;
            margin-top: 30px;
        }

        .status-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .status-header h1 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            margin: 10px 0;
        }

        .status-badge.not-started {
            background-color: #e7f3ff;
            color: #0066cc;
        }

        .status-badge.in-progress {
            background-color: #fff4e6;
            color: #ff8c00;
        }

        .status-badge.completed {
            background-color: #e6ffe6;
            color: #00b300;
        }

        .exam-info {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .exam-info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .exam-info-row:last-child {
            border-bottom: none;
        }

        .exam-info-label {
            font-weight: 600;
            color: #666;
        }

        .exam-info-value {
            color: #333;
        }

        .btn-action {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 40px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 16px;
            margin: 10px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
            color: white;
        }

        .actions {
            text-align: center;
            margin-top: 30px;
        }

        .back-link {
            margin-bottom: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
            color: #764ba2;
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

    <div class="container">
        <div class="back-link">
            <a href="{{ route('exams') }}">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách bài thi
            </a>
        </div>

        <div class="exam-status-container">
            <div class="status-header">
                <h1> Bài thi</h1>
                
            </div>

            <!-- Exam Info -->
            <div class="exam-info">
                <div class="exam-info-row">
                    <span class="exam-info-label"><i class="fas fa-list me-2"></i>Số câu hỏi:</span>
                    <span class="exam-info-value">{{ $examInfo['noq'] ?? 40 }} câu</span>
                </div>
                <div class="exam-info-row">
                    <span class="exam-info-label"><i class="fas fa-clock me-2"></i>Thời gian làm bài:</span>
                    <span class="exam-info-value">{{ $examInfo['duration'] ?? 45 }} phút</span>
                </div>
                <div class="exam-info-row">
                    <span class="exam-info-label"><i class="fas fa-redo me-2"></i>Số lần đã làm:</span>
                    <span class="exam-info-value">{{ $status['attempts'] }} lần</span>
                </div>
                @if($status['best_score'])
                <div class="exam-info-row">
                    <span class="exam-info-label"><i class="fas fa-star me-2"></i>Điểm cao nhất:</span>
                    <span class="exam-info-value">{{ $status['best_score'] }}/100</span>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="actions">
                @if($status['state'] === 'not_started')
                    <a href="{{ route('exam.show', $status['quid']) }}" class="btn btn-action">
                        <i class="fas fa-play me-2"></i>Bắt đầu làm bài
                    </a>
                @elseif($status['state'] === 'in_progress')
                    <a href="{{ route('exam.show', $status['quid']) }}" class="btn btn-action">
                        <i class="fas fa-arrow-right me-2"></i>Tiếp tục làm bài
                    </a>
                @else
                    <a href="{{ route('exam.result', $status['rid'] ?? 1) }}" class="btn btn-action">
                        <i class="fas fa-chart-bar me-2"></i>Xem kết quả
                    </a>
                    <a href="{{ route('exam.show', $status['quid']) }}" class="btn btn-action btn-secondary">
                        <i class="fas fa-sync me-2"></i>Làm lại
                    </a>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
