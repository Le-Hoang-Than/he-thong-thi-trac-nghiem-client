<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả bài thi - {{ config('app.name', 'Hệ thống thi trắc nghiệm') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .result-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
        }

        .result-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .result-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .score-display {
            background: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }

        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 48px;
            font-weight: 700;
            color: white;
        }

        .score-circle.pass {
            background: linear-gradient(135deg, #00b300 0%, #00e600 100%);
        }

        .score-circle.fail {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8787 100%);
        }

        .score-text {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .score-label {
            color: #666;
            font-size: 14px;
        }

        .result-body {
            padding: 30px;
        }

        .result-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
            align-items: center;
        }

        .result-row:last-child {
            border-bottom: none;
        }

        .result-label {
            font-weight: 600;
            color: #666;
            display: flex;
            align-items: center;
        }

        .result-label i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
            color: #667eea;
        }

        .result-value {
            color: #333;
            font-weight: 600;
        }

        .result-actions {
            padding: 20px 30px;
            background: #f8f9fa;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-result {
            flex: 1;
            min-width: 150px;
            padding: 12px 20px;
            border-radius: 6px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #d0d0d0;
            color: #333;
            text-decoration: none;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pass {
            background: #e6ffe6;
            color: #00b300;
        }

        .status-fail {
            background: #ffe6e6;
            color: #ff0000;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        body {
            padding-top: 70px;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 70px);
            padding: 20px;
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
        <div class="result-card">
            <div class="result-header">
                <h1>{{ $result['quiz_name'] ?? 'Bài thi' }}</h1>
                <p class="mb-0">Kết quả bài thi của bạn</p>
            </div>

            @if(isset($result['error']) && $result['error'])
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Cảnh báo:</strong> {{ $result['error'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($result['result_error']) && $result['result_error'])
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Lỗi:</strong> {{ $result['result_error'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="alert alert-info alert-dismissible fade show" role="alert" style="margin-top: 15px;">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Thông tin:</strong> Kết quả bài thi của bạn đã được lưu. Quay lại danh sách bài thi để xem lịch sử hoặc bấm "Làm mới" để cập nhật lịch sử.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Score Section -->
            <div class="score-display">
                <div class="score-circle {{ ($result['percentage'] ?? 0) >= 60 ? 'pass' : 'fail' }}">
                    {{ round($result['score'] ?? 0) }}
                </div>
                <div class="score-text">{{ round($result['percentage'] ?? 0) }}%</div>
                <div class="score-label">
                    @if(($result['percentage'] ?? 0) >= 60)
                        <span class="status-badge status-pass">
                            <i class="fas fa-check-circle me-1"></i>ĐẬU
                        </span>
                    @else
                        <span class="status-badge status-fail">
                            <i class="fas fa-times-circle me-1"></i>KHÔNG ĐẬU
                        </span>
                    @endif
                </div>
            </div>

            <!-- Result Details -->
            <div class="result-body">
                <div class="result-row">
                    <span class="result-label">
                        <i class="fas fa-user"></i>Sinh viên
                    </span>
                    <span class="result-value">{{ $result['student_name'] ?? 'N/A' }}</span>
                </div>

                <div class="result-row">
                    <span class="result-label">
                        <i class="fas fa-list"></i>Tổng câu hỏi
                    </span>
                    <span class="result-value">{{ $result['total_questions'] ?? 40 }} câu</span>
                </div>

                <div class="result-row">
                    <span class="result-label">
                        <i class="fas fa-check"></i>Câu đúng
                    </span>
                    <span class="result-value">{{ $result['total_correct'] ?? 0 }} câu</span>
                </div>

                <div class="result-row">
                    <span class="result-label">
                        <i class="fas fa-pencil"></i>Câu làm
                    </span>
                    <span class="result-value">{{ $result['total_answer'] ?? 0 }} câu</span>
                </div>

                <div class="result-row">
                    <span class="result-label">
                        <i class="fas fa-clock"></i>Nộp bài lúc
                    </span>
                    <span class="result-value">{{ $result['submitted_at']->format('H:i:s d/m/Y') }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="result-actions">
                <a href="{{ route('exams') }}" class="btn-result btn-primary">
                    <i class="fas fa-arrow-left"></i>Quay lại
                </a>
                @if($result['can_retry'] ?? false)
                    <button class="btn-result btn-secondary" id="retryBtn" data-quid="{{ $result['quid'] ?? 61 }}" data-rid="{{ $result['rid'] ?? 0 }}">
                        <i class="fas fa-sync"></i>Làm lại
                    </button>
                @else
                    <button class="btn-result btn-secondary" disabled style="opacity: 0.6; cursor: not-allowed;">
                        <i class="fas fa-ban"></i>Đã làm lại rồi
                    </button>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle retry button click
        document.getElementById('retryBtn')?.addEventListener('click', function() {
            const quid = this.dataset.quid;
            const rid = this.dataset.rid;
            retryExam(quid, rid);
        });

        function retryExam(quid, rid) {
            // Mark result as retried in session
            fetch('/api/mark-retry/' + rid, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quid: quid })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to start new exam
                    window.location.href = '/exams/' + quid;
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể làm lại bài thi'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Lỗi kết nối tới máy chủ');
            });
        }
    </script>
