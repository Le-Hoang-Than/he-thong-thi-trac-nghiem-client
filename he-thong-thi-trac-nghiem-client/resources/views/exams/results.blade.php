<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử bài thi - {{ config('app.name', 'Hệ thống thi trắc nghiệm') }}</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .result-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .result-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .result-card-title {
            font-size: 20px;
            font-weight: 700;
        }

        .result-card-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: rgba(255,255,255,0.2);
        }

        .result-card-body {
            padding: 25px;
        }

        .result-info {
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

        .score-display {
            background: linear-gradient(135deg, #00b300 0%, #00e600 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px;
        }

        .score-display.fail {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8787 100%);
        }

        .score-number {
            font-size: 32px;
            font-weight: 700;
        }

        .score-percent {
            font-size: 14px;
            opacity: 0.9;
        }

        .result-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .btn-view {
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

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .empty-state {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            text-align: center;
            padding: 80px 40px;
        }

        .empty-state-icon {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state-text {
            font-size: 16px;
            color: #999;
            margin-bottom: 30px;
        }

        .empty-state-action {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .empty-state-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/exams">
                <i class="fas fa-graduation-cap me-2"></i>{{ config('app.name', 'Hệ thống thi trắc nghiệm') }}
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
            <div class="col-md-2 sidebar">
                <ul class="sidebar-menu">
                    <li>
                        <a href="/exams">
                            <i class="fas fa-file-alt me-2"></i>Bài thi
                        </a>
                    </li>
                    <li>
                        <a href="/results" class="active">
                            <i class="fas fa-chart-bar me-2"></i>Kết quả
                        </a>
                    </li>
                    <li>
                        <a href="/profile">
                            <i class="fas fa-user me-2"></i>Hồ sơ
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <div class="main-content">
                    <!-- Page Header -->
                    <div class="page-header">
                        <h1>
                            <i class="fas fa-history me-2" style="color: #667eea;"></i>Lịch sử bài thi
                        </h1>
                        <button onclick="location.reload();" class="btn btn-outline-primary">
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

                    <!-- Results -->
                    @if (count($allResults) > 0)

                        <!-- Results Cards -->
                        <div id="resultsContainer">
                            @foreach ($allResults as $result)
                                @php
                                    $rid = $result['rid'] ?? null;
                                    $isClosed = ($result['result_status'] ?? 'Open') === 'Closed';
                                    $score = (int)($result['score_obtained'] ?? 0);
                                    $total = 40;
                                    $percentage = $total > 0 ? round(($score / $total) * 100, 2) : 0;
                                    $isPassed = $percentage >= 60;
                                    
                                    // Use formatted timestamp if available, otherwise compute from unix timestamp
                                    if (isset($result['end_time_formatted'])) {
                                        $dateTime = $result['end_time_formatted'];
                                    } elseif (isset($result['start_time_formatted'])) {
                                        $dateTime = $result['start_time_formatted'];
                                    } elseif (isset($result['end_time']) && is_numeric($result['end_time']) && $result['end_time'] > 0) {
                                        $dateTime = date('d/m/Y H:i', (int)$result['end_time']);
                                    } elseif (isset($result['start_time']) && is_numeric($result['start_time']) && $result['start_time'] > 0) {
                                        $dateTime = date('d/m/Y H:i', (int)$result['start_time']);
                                    } else {
                                        $dateTime = 'Không rõ';
                                    }
                                    
                                    $quizName = $result['quiz']['quiz_name'] ?? ($result['quiz_name'] ?? 'Bài thi');
                                @endphp
                                <div class="result-card result-item">
                                    <div class="result-card-header">
                                        <div>
                                            <div class="result-card-title">
                                                {{ $quizName }}
                                            </div>
                                            <p class="mb-0" style="font-size: 14px; opacity: 0.9;">
                                                {{ $dateTime }}
                                            </p>
                                        </div>
                                        <div class="result-card-status">
                                            {{ $isClosed ? '✓ Đã nộp' : '⏳ Đang làm' }}
                                        </div>
                                    </div>

                                    <div class="result-card-body">
                                        <!-- Score Display -->
                                        @if ($isClosed)
                                            <div class="score-display {{ $isPassed ? '' : 'fail' }}">
                                                <div class="score-number">{{ $score }}/{{ $total }}</div>
                                                <div class="score-percent">{{ $percentage }}%</div>
                                            </div>
                                        @else
                                            <div class="score-display fail" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                                                <div class="score-number">-</div>
                                                <div class="score-percent">Chưa hoàn thành</div>
                                            </div>
                                        @endif

                                        <!-- Result Info -->
                                        <div class="result-info">
                                            <div class="info-item">
                                                <div class="info-icon">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Câu đúng</span>
                                                    <span class="info-value">@if ($isClosed) {{ $score }} câu @else - @endif</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon">
                                                    <i class="fas fa-pencil"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Câu làm</span>
                                                    <span class="info-value">{{ $result['total_answer'] ?? '0' }} câu</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Tổng câu</span>
                                                    <span class="info-value">{{ $total }} câu</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Kết quả</span>
                                                    @if ($isClosed)
                                                        <span class="info-value" @if ($isPassed) style="color: #28a745;" @else style="color: #dc3545;" @endif>
                                                            {{ $isPassed ? 'ĐẬU' : 'KHÔNG ĐẬU' }}
                                                        </span>
                                                    @else
                                                        <span class="info-value" style="color: #999;">
                                                            Chưa nộp
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Footer -->
                                        <div class="result-card-footer">
                                            @if ($isClosed && $rid)
                                                <a href="/exam-result/{{ $rid }}" class="btn-view">
                                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                </a>
                                            @else
                                                <span style="color: #999; font-weight: 600;">Bài thi chưa hoàn thành</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <div class="empty-state-title">Không có lịch sử bài thi</div>
                            <p class="empty-state-text">
                                Bạn chưa hoàn thành bất kỳ bài thi nào. 
                                @if (config('app.debug'))
                                    <br><small style="color: #666;">Nếu vừa nộp bài, vui lòng click "Làm mới" để cập nhật.</small>
                                @endif
                            </p>
                            <a href="/exams" class="empty-state-action">
                                <i class="fas fa-play me-1"></i>Bắt đầu làm bài thi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
