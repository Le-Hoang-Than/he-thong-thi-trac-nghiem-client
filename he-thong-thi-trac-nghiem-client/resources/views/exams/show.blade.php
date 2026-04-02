<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $exam['title'] ?? 'Bài thi' }} - {{ config('app.name', 'Hệ thống thi trắc nghiệm') }}</title>
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

        .main-content {
            padding: 30px 20px;
        }

        .exam-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .exam-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
        }

        .exam-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .exam-body {
            padding: 40px 30px;
        }

        .question-container {
            margin-bottom: 30px;
        }

        .question-number {
            background: #667eea;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .question-text {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .option {
            padding: 12px 15px;
            margin-bottom: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .option:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }

        .option input[type="radio"] {
            margin-right: 10px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 40px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-top: 30px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .back-link {
            margin-bottom: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
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
        <div class="main-content">
            <div class="back-link">
                <a href="{{ route('exams') }}">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách bài thi
                </a>
            </div>

            <div class="exam-container">
                <div class="exam-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h1>{{ $exam['quiz_name'] ?? 'Bài thi' }}</h1>
                            <p class="mb-0">Tổng số câu: {{ $exam['total'] ?? 40 }} | Thời gian: {{ $exam['duration'] ?? 45 }} phút</p>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 14px; color: rgba(255,255,255,0.8); margin-bottom: 5px;">Thời gian còn lại:</div>
                            <div style="font-size: 32px; font-weight: bold; color: white;" id="timer">{{ $exam['duration'] ?? 45 }}:00</div>
                        </div>
                    </div>
                </div>

                <div class="exam-body">
                    @if(isset($exam['questions']) && is_array($exam['questions']) && count($exam['questions']) > 0)
                        @if(!isset($exam['rid']) || empty($exam['rid']))
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Lỗi:</strong> Không thể khởi tạo bài thi. Vui lòng quay lại danh sách và thử lại.
                            </div>
                            <a href="{{ route('exams') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                            </a>
                        @else
                        <form method="POST" action="/exams/{{ $exam['rid'] }}/submit" id="examForm">
                            @csrf
                            <input type="hidden" name="is_timeout" value="0" id="is_timeout_field">
                            @foreach($exam['questions'] as $index => $question)
                                <div class="question-container">
                                    <div class="question-number">{{ $index + 1 }}</div>
                                    <div class="question-text">
                                        @php
                                            // Get question text (HTML content)
                                            $questionText = $question['question'] ?? null;
                                            if ($questionText) {
                                                // Strip HTML tags and decode entities
                                                $questionText = html_entity_decode(strip_tags($questionText));
                                                // Clean up whitespace
                                                $questionText = trim(preg_replace('/\s+/', ' ', $questionText));
                                            }
                                        @endphp
                                        {{ $questionText ?: '(Câu hỏi)' }}
                                    </div>
                                    
                                    @if(isset($question['options']) && is_array($question['options']) && count($question['options']) > 0)
                                        @foreach($question['options'] as $optIndex => $option)
                                            <label class="option">
                                                <input type="radio" name="question_{{ $question['qid'] ?? $index }}" 
                                                       value="{{ $option['oid'] ?? $optIndex }}" 
                                                       required>
                                                {{ $option['q_option'] ?? 'Lựa chọn' }}
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                            
                            <div class="text-center">
                                <button type="submit" class="btn-submit">
                                    <i class="fas fa-check me-2"></i>Nộp bài thi
                                </button>
                            </div>
                        </form>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Bài thi chưa có câu hỏi hoặc chưa kích hoạt.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const rid = {{ isset($exam['rid']) && !empty($exam['rid']) ? $exam['rid'] : 'null' }};
        
        // Validate rid is present and valid
        if (!rid || rid === null || rid === 0) {
            console.error('Invalid rid:', rid);
            document.body.innerHTML = '<div class="alert alert-danger m-5"><strong>Lỗi:</strong> ID bài thi không hợp lệ. Vui lòng tải lại trang.</div>';
        }
        
        console.log('Exam started with rid:', rid);
        
        // Show toast notification
        function showToast(message, type = 'success') {
            const toastHtml = `
                <div class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
                    <div class="alert alert-${type} mb-0" role="alert" style="min-width: 300px;">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'warning-circle'} me-2"></i>
                        ${message}
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', toastHtml);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                const toast = document.querySelector('.alert-' + type);
                if (toast) {
                    toast.parentElement.remove();
                }
            }, 3000);
        }
        
        // Save answer via local endpoint
        function saveAnswer(questionId, optionId) {
            if (!rid || rid === null) {
                console.error('Cannot save answer: rid is not set', rid);
                showToast('Lỗi: ID bài thi không hợp lệ', 'warning');
                return;
            }
            
            const data = {
                qid: questionId,
                oid: optionId,
                rid: rid
            };
            
            console.log('Saving answer:', data);
            
            fetch('/exams/save-answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Save answer response status:', response.status);
                return response.json().then(data => ({ status: response.status, body: data }));
            })
            .then(result => {
                console.log('Save answer response:', result);
                if (result.body.status === 'success') {
                    showToast('Lưu câu trả lời thành công', 'success');
                } else {
                    showToast('Lỗi lưu: ' + (result.body.message || 'Không xác định'), 'warning');
                    console.error('Save answer failed:', result.body);
                }
            })
            .catch(error => {
                console.error('Error saving answer:', error);
                showToast('Lỗi khi lưu câu trả lời: ' + error.message, 'warning');
            });
        }
        
        // Countdown Timer
        document.addEventListener('DOMContentLoaded', function() {
            const durationMinutes = {{ $exam['duration'] ?? 45 }};
            let totalSeconds = durationMinutes * 60;
            const timerElement = document.getElementById('timer');
            const examForm = document.getElementById('examForm');
            
            // Add event listeners to radio buttons for auto-save
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const questionId = this.name.replace('question_', '');
                    const optionId = this.value;
                    saveAnswer(questionId, optionId);
                });
            });
            
            // Update timer every second
            const timerInterval = setInterval(() => {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                timerElement.textContent = `${minutes}:${String(seconds).padStart(2, '0')}`;
                
                // Change color when less than 5 minutes
                if (totalSeconds < 300) {
                    timerElement.style.color = '#ff6b6b';
                }
                
                // Auto submit when time is up
                if (totalSeconds <= 0) {
                    clearInterval(timerInterval);
                    timerElement.textContent = '0:00';
                    if (examForm) {
                        // Auto submit with timeout flag
                        document.getElementById('is_timeout_field').value = '1';
                        examForm.submit();
                    }
                }
                
                totalSeconds--;
            }, 1000);
        });
    </script>
</body>
</html>
