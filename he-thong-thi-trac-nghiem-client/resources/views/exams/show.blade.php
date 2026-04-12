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
            padding: 30px;
        }

        .exam-layout {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
            align-items: start;
        }

        .question-section {
            flex: 1;
        }

        .question-container {
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            display: none;
        }

        .question-container.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .question-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .nav-button {
            background: #667eea;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-button:hover:not(:disabled) {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .nav-button:disabled {
            background: #ccc;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .question-counter {
            font-size: 16px;
            font-weight: 600;
            color: #333;
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
            line-height: 1.6;
        }

        .option {
            padding: 12px 15px;
            margin-bottom: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .option:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }

        .option input[type="radio"] {
            margin-right: 10px;
            cursor: pointer;
        }

        .question-grid {
            background: white;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 20px;
            position: sticky;
            top: 100px;
        }

        .grid-title {
            font-weight: 700;
            font-size: 14px;
            color: #333;
            margin-bottom: 15px;
            text-transform: uppercase;
            text-align: center;
        }

        .question-grid-items {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }

        .grid-item {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e0e0e0;
            background: white;
            color: #666;
        }

        .grid-item:hover {
            transform: scale(1.05);
            border-color: #667eea;
        }

        .grid-item.answered {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        .grid-item.unanswered {
            background: white;
            border: 2px solid #ddd;
            color: #999;
        }

        .grid-item.error {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .grid-item.current {
            border: 3px solid #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }

        .grid-legend {
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            margin-right: 10px;
            border: 1px solid #ddd;
        }

        .legend-color.answered {
            background: #28a745;
        }

        .legend-color.unanswered {
            background: white;
            border: 1px solid #ddd;
        }

        .legend-color.error {
            background: #dc3545;
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

        #timerContainer {
            position: fixed;
            top: 80px;
            right: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            z-index: 100;
            text-align: center;
            min-width: 150px;
        }

        #timerLabel {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        #timer {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        #timer.warning {
            color: #ffeb3b;
            animation: pulse 1s infinite;
        }

        #timer.danger {
            color: #ff6b6b;
            animation: pulse 0.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        @media (max-width: 992px) {
            .exam-layout {
                grid-template-columns: 1fr;
            }

            .question-grid {
                position: static;
                margin-top: 30px;
            }

            .question-grid-items {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 768px) {
            #timerContainer {
                top: 80px;
                right: 15px;
                padding: 15px 20px;
                min-width: 130px;
            }

            #timer {
                font-size: 28px;
            }

            .exam-layout {
                grid-template-columns: 1fr;
            }

            .question-grid {
                position: static;
            }

            .question-grid-items {
                grid-template-columns: repeat(3, 1fr);
            }

            .exam-body {
                padding: 20px;
            }

            .question-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Timer Display -->
    <div id="timerContainer">
        <div id="timerLabel">Thời gian còn lại</div>
        <div id="timer">45:00</div>
    </div>

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
                            <!-- Timer will be moved to fixed position by CSS -->
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
                        <form method="POST" action="/exams/{{ $exam['rid'] }}/submit" id="examForm" data-rid="{{ $exam['rid'] ?? 0 }}" data-duration="{{ $exam['duration'] ?? 45 }}" data-time-left="{{ $exam['time_left'] ?? null }}">
                            @csrf
                            <input type="hidden" name="is_timeout" value="0" id="is_timeout_field">
                            
                            <div class="exam-layout">
                                <!-- Left: Questions -->
                                <div class="question-section">
                                    @foreach($exam['questions'] as $index => $question)
                                        <div class="question-container {{ $index === 0 ? 'active' : '' }}" data-question-num="{{ $index + 1 }}" data-question-id="{{ $question['qid'] ?? $index }}">
                                            <div class="question-number">{{ $index + 1 }}</div>
                                            <div class="question-text">
                                                @php
                                                    $questionText = $question['question'] ?? null;
                                                    if ($questionText) {
                                                        $questionText = html_entity_decode(strip_tags($questionText));
                                                        $questionText = trim(preg_replace('/\s+/', ' ', $questionText));
                                                    }
                                                @endphp
                                                {{ $questionText ?: '(Câu hỏi)' }}
                                            </div>
                                            
                                            @if(isset($question['options']) && is_array($question['options']) && count($question['options']) > 0)
                                                @foreach($question['options'] as $optIndex => $option)
                                                    <label class="option">
                                                        <input type="radio" name="question_{{ $question['qid'] ?? $index }}" 
                                                               value="{{ $option['oid'] ?? $optIndex }}">
                                                        {{ $option['q_option'] ?? 'Lựa chọn' }}
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endforeach

                                    <!-- Navigation Buttons -->
                                    <div class="question-navigation">
                                        <button type="button" class="nav-button" id="prevBtn" onclick="previousQuestion()">
                                            <i class="fas fa-chevron-left me-2"></i>Câu trước
                                        </button>
                                        <span class="question-counter"><span id="currentQuestion">1</span>/<span id="totalQuestions">{{ count($exam['questions'] ?? []) }}</span></span>
                                        <button type="button" class="nav-button" id="nextBtn" onclick="nextQuestion()">
                                            Câu sau<i class="fas fa-chevron-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Right: Question Grid -->
                                <div class="question-grid">
                                    <div class="grid-title">Danh sách câu hỏi</div>
                                    <div class="question-grid-items">
                                        @foreach($exam['questions'] as $index => $question)
                                            <div class="grid-item unanswered" data-question-num="{{ $index + 1 }}" data-question-id="{{ $question['qid'] ?? $index }}">
                                                {{ $index + 1 }}
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="grid-legend">
                                        <div class="legend-item">
                                            <div class="legend-color answered"></div>
                                            <span>Đã trả lời</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color unanswered"></div>
                                            <span>Chưa trả lời</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color error"></div>
                                            <span>Vấn đề(Lỗi)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center" style="margin-top: 40px;">
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
        // Get exam data from form attributes
        const examForm = document.getElementById('examForm');
        const rid = parseInt(examForm?.dataset.rid || '0', 10) || 0;
        const durationMinutes = parseInt(examForm?.dataset.duration || '45', 10) || 45;
        const timeLeftSeconds = examForm?.dataset.timeLeft ? parseInt(examForm.dataset.timeLeft, 10) : null;
        
        // Use time_left from API if available (for resumed exams), otherwise use duration
        let initialSeconds = timeLeftSeconds !== null && timeLeftSeconds > 0 
            ? timeLeftSeconds 
            : durationMinutes * 60;
        
        console.log('Timer initialized:', {
            rid: rid,
            durationMinutes: durationMinutes,
            timeLeftFromAPI: timeLeftSeconds,
            initialSeconds: initialSeconds
        });
        
        // Validate rid is present and valid
        if (!rid || rid === null || rid === 0) {
            console.error('Invalid rid:', rid);
            document.body.innerHTML = '<div class="alert alert-danger m-5"><strong>Lỗi:</strong> ID bài thi không hợp lệ. Vui lòng tải lại trang.</div>';
        }
        
        console.log('Exam started with rid:', rid);
        
        // Track answered questions
        const answeredQuestions = new Set();
        
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
        
        // Question navigation
        let currentQuestionIndex = 0;
        const totalQuestions = document.querySelectorAll('.question-container').length;
        
        function showQuestion(index) {
            // Hide all questions
            document.querySelectorAll('.question-container').forEach(container => {
                container.classList.remove('active');
            });
            
            // Show selected question
            const containers = document.querySelectorAll('.question-container');
            if (containers[index]) {
                containers[index].classList.add('active');
                currentQuestionIndex = index;
                
                // Update counter
                document.getElementById('currentQuestion').textContent = index + 1;
                
                // Update grid highlight
                document.querySelectorAll('.grid-item').forEach(item => {
                    item.classList.remove('current');
                });
                const gridItems = document.querySelectorAll('.grid-item');
                if (gridItems[index]) {
                    gridItems[index].classList.add('current');
                }
            }
            
            // Update button states
            updateNavigationButtons();
        }
        
        function updateNavigationButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            if (prevBtn) prevBtn.disabled = currentQuestionIndex === 0;
            if (nextBtn) nextBtn.disabled = currentQuestionIndex === totalQuestions - 1;
        }
        
        function nextQuestion() {
            if (currentQuestionIndex < totalQuestions - 1) {
                showQuestion(currentQuestionIndex + 1);
            }
        }
        
        function previousQuestion() {
            if (currentQuestionIndex > 0) {
                showQuestion(currentQuestionIndex - 1);
            }
        }
        
        // Initialize question grid click handlers
        function initializeGridClickHandlers() {
            document.querySelectorAll('.grid-item').forEach(gridItem => {
                gridItem.addEventListener('click', function() {
                    const questionNum = parseInt(this.dataset.questionNum, 10);
                    if (questionNum > 0) {
                        showQuestion(questionNum - 1);
                    }
                });
            });
        }
        
        function updateGridItemStatus(questionId, isAnswered) {
            // Specifically search for grid-item elements with matching data-question-id
            const gridItem = document.querySelector(`.grid-item[data-question-id="${questionId}"]`);
            
            if (gridItem) {
                if (isAnswered) {
                    gridItem.classList.remove('unanswered', 'error');
                    gridItem.classList.add('answered');
                    answeredQuestions.add(questionId);
                    console.log(`✓ Grid item marked ANSWERED for question ${questionId}`, gridItem);
                } else {
                    gridItem.classList.remove('answered', 'error');
                    gridItem.classList.add('unanswered');
                    answeredQuestions.delete(questionId);
                    console.log(`○ Grid item marked UNANSWERED for question ${questionId}`, gridItem);
                }
            } else {
                console.warn(`✗ Grid item NOT found for question ${questionId}`);
                // Fallback: try document.querySelectorAll in case the first method fails
                const allGridItems = document.querySelectorAll('.grid-item');
                console.log(`Available grid items: ${allGridItems.length}, looking for qid: ${questionId}`);
                allGridItems.forEach((item, idx) => {
                    console.log(`  Grid item ${idx}: data-question-id="${item.dataset.questionId}"`);
                });
            }
        }
        
        // Save answer via local endpoint
        function saveAnswer(questionId, optionId) {
            if (!rid || rid === null) {
                console.error('Cannot save answer: rid is not set', rid);
                showToast('Lỗi: ID bài thi không hợp lệ', 'warning');
                return;
            }
            
            // Immediately update UI (optimistic update)
            updateGridItemStatus(questionId, true);
            console.log(`📝 Question ${questionId} selected, updating grid item immediately`);
            
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
                    console.log(`✅ Answer saved successfully for question ${questionId}`);
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
            let totalSeconds = initialSeconds;
            const timerElement = document.getElementById('timer');
            const examForm = document.getElementById('examForm');
            const questionSection = document.querySelector('.question-section');
            
            // Set initial timer value
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            timerElement.textContent = `${minutes}:${String(seconds).padStart(2, '0')}`;
            
            // Add event listeners to radio buttons for auto-save
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const questionId = this.name.replace('question_', '');
                    const optionId = this.value;
                    saveAnswer(questionId, optionId);
                });
            });
            
            // Initialize question navigation
            initializeGridClickHandlers();
            updateNavigationButtons();
            showQuestion(0);
            
            // Load existing answers from form and update grid status
            document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
                const questionId = radio.name.replace('question_', '');
                updateGridItemStatus(questionId, true);
            });
            
            // Update timer every second
            const timerInterval = setInterval(() => {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                timerElement.textContent = `${minutes}:${String(seconds).padStart(2, '0')}`;
                
                // Change color when less than 5 minutes (warning)
                if (totalSeconds < 300 && totalSeconds >= 60) {
                    timerElement.classList.remove('danger');
                    timerElement.classList.add('warning');
                }
                
                // Change color when less than 1 minute (danger)
                if (totalSeconds < 60) {
                    timerElement.classList.remove('warning');
                    timerElement.classList.add('danger');
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
