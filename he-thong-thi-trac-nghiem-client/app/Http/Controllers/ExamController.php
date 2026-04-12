<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        // Vá lỗi: Khai báo biến apiUrl để không bị sập ngầm
        // Lấy link từ .env, nếu không có thì dùng link cứng của Service
        $this->apiUrl = rtrim(env('BASE_API_URL', 'https://he-thong-thi-trac-nghiem-service-lnup.onrender.com'), '/');
    }

    public function index()
    {
        // Check authentication
        if (!session()->has('auth_token') || !session()->has('user')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $user = session('user');
        $token = session('auth_token');

        // Fetch exam detail from backend API
        $exams = [];
        $examHistory = [];
        
        try {
            // Call /api/quiz-detail endpoint to get quiz info (quid = 61)
            $response = Http::withToken($token)->get($this->apiUrl . '/api/quiz-detail');
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Transform backend response format to frontend format
                if (isset($data['data'])) {
                    $quizData = $data['data'];
                    $exams = [
                        [
                            'quid' => 61,
                            'quiz_name' => $quizData['Tên đề thi:'] ?? 'Bài thi',
                            'description' => $quizData['Tên đề thi:'] ?? 'Bài thi mặc định',
                            'noq' => $quizData['Số lượng câu hỏi:'] ?? 40,
                            'duration' => $quizData['Thời lượng(phút)'] ?? 35,
                            'pass_percentage' => 60
                        ]
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching quiz detail: ' . $e->getMessage());
        }

        // If no exams from API, use default quiz
        if (empty($exams)) {
            $exams = [
                [
                    'quid' => 61,
                    'quiz_name' => 'Bài thi mặc định',
                    'description' => 'Bài thi mặc định (quid = 61)',
                    'noq' => 40,
                    'duration' => 35,
                    'pass_percentage' => 60
                ]
            ];
        }

        // Fetch exam history to check if exam already taken
        try {
            $historyResponse = Http::withToken($token)->get($this->apiUrl . '/api/exam-history');
            
            if ($historyResponse->successful()) {
                $historyData = $historyResponse->json();
                Log::info('Exam history fetched', ['count' => count($historyData['data'] ?? [])]);
                if (isset($historyData['data']) && is_array($historyData['data'])) {
                    foreach ($historyData['data'] as $result) {
                        // Convert object to array if needed
                        if (is_object($result)) {
                            $result = json_decode(json_encode($result), true);
                        }
                        // Store by rid to track individual attempts, but keep array of all attempts
                        // This ensures multiple attempts are all preserved
                        if (isset($result['rid'])) {
                            // Group by quid, but keep array of all attempts
                            $quid = $result['quid'] ?? null;
                            if ($quid) {
                                if (!isset($examHistory[$quid])) {
                                    $examHistory[$quid] = [];
                                }
                                $examHistory[$quid][] = $result;
                                Log::debug('Added exam result to history', ['rid' => $result['rid'], 'quid' => $quid, 'status' => $result['result_status'] ?? 'unknown']);
                            }
                        }
                    }
                }
            } else {
                Log::error('Failed to fetch exam history', ['status' => $historyResponse->status()]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching exam history: ' . $e->getMessage());
        }

        // Add exam status to each exam
        foreach ($exams as &$exam) {
            $quid = $exam['quid'];
            // Check if exam has ever been attempted
            $exam['attempted'] = isset($examHistory[$quid]) && !empty($examHistory[$quid]);
            if ($exam['attempted']) {
                // Get the most recent attempt (first in array since ordered by rid DESC)
                $exam['last_result'] = $examHistory[$quid][0];
                // Store all attempts for users who want to see history
                $exam['all_results'] = $examHistory[$quid];
            }
        }

        return view('exams.index', compact('exams', 'user'));
    }

    public function allResults()
    {
        // Check authentication
        if (!session()->has('auth_token') || !session()->has('user')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $user = session('user');
        $token = session('auth_token');

        $allResults = [];
        $fetchedRids = []; // Track which RIDs we've already fetched
        $debugInfo = [];
        
        try {
            $response = Http::withToken($token)->get($this->apiUrl . '/api/exam-history');
            
            $debugInfo['api_response_status'] = $response->status();
            $debugInfo['api_response_body'] = $response->body();
            
            if ($response->successful()) {
                $data = $response->json();
                $debugInfo['api_data_keys'] = isset($data['data']) ? 'Present' : 'Missing';
                $debugInfo['api_data_count'] = isset($data['data']) ? count($data['data']) : 0;
                
                if (isset($data['data']) && is_array($data['data'])) {
                    foreach ($data['data'] as $idx => $result) {
                        // Convert object to array if needed
                        if (is_object($result)) {
                            $result = json_decode(json_encode($result), true);
                        }
                        
                        // Convert timestamps if they are integers (Unix timestamps)
                        if (isset($result['start_time']) && is_numeric($result['start_time']) && $result['start_time'] > 0) {
                            $result['start_time_formatted'] = date('d/m/Y H:i', (int)$result['start_time']);
                        }
                        
                        if (isset($result['end_time']) && is_numeric($result['end_time']) && $result['end_time'] > 0) {
                            $result['end_time_formatted'] = date('d/m/Y H:i', (int)$result['end_time']);
                        }
                        
                        // Ensure required fields exist with defaults
                        $result['rid'] = $result['rid'] ?? null;
                        $result['score_obtained'] = $result['score_obtained'] ?? 0;
                        $result['result_status'] = $result['result_status'] ?? 'Open';
                        $result['total_answer'] = $result['total_answer'] ?? 0;
                        $result['quiz'] = $result['quiz'] ?? ['quiz_name' => 'Bài thi'];
                        $result['source'] = 'api_history';
                        
                        if ($result['rid']) {
                            $fetchedRids[] = $result['rid'];
                        }
                        
                        $allResults[] = $result;
                        
                        Log::debug("Result $idx details from API", [
                            'rid' => $result['rid'],
                            'status' => $result['result_status'],
                            'score' => $result['score_obtained'],
                            'has_quiz_name' => isset($result['quiz']['quiz_name'])
                        ]);
                    }
                }
                
                Log::info('API exam history fetched', ['count' => count($allResults), 'debug' => $debugInfo]);
            } else {
                Log::warning('API exam-history returned non-200', ['status' => $response->status()]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching exam history from API', ['message' => $e->getMessage()]);
        }

        // FALLBACK: Fetch recently submitted results that may not be in history API yet
        try {
            $recentlySubmitted = session('recently_submitted_results', []);
            Log::info('Checking recently submitted results', ['count' => count($recentlySubmitted), 'rids' => $recentlySubmitted]);
            
            foreach ($recentlySubmitted as $rid) {
                // Skip if already fetched from API
                if (in_array($rid, $fetchedRids)) {
                    Log::debug('Result already in API history, skipping', ['rid' => $rid]);
                    continue;
                }
                
                // Fetch individual result
                try {
                    $resultResponse = Http::withToken($token)->get($this->apiUrl . '/api/result/' . $rid);
                    
                    if ($resultResponse->successful()) {
                        $resultData = $resultResponse->json();
                        
                        if (isset($resultData['info'])) {
                            $info = $resultData['info'];
                            
                            // Build result object from individual result API
                            $result = [
                                'rid' => $rid,
                                'quid' => $info['quid'] ?? null,
                                'score_obtained' => (int)($info['score_obtained'] ?? 0),
                                'total_answer' => (int)($info['total_answer'] ?? 0),
                                'result_status' => 'Closed', // Recently submitted results are always Closed
                                'start_time' => $info['start_time'] ?? null,
                                'end_time' => $info['end_time'] ?? null,
                                'quiz' => ['quiz_name' => $info['quiz_name'] ?? 'Bài thi'],
                                'source' => 'fallback_individual_fetch',
                                'from_session' => true
                            ];
                            
                            // Format timestamps
                            if ($info['end_time'] && is_numeric($info['end_time']) && $info['end_time'] > 0) {
                                $result['end_time_formatted'] = date('d/m/Y H:i', (int)$info['end_time']);
                            } elseif ($info['start_time'] && is_numeric($info['start_time']) && $info['start_time'] > 0) {
                                $result['start_time_formatted'] = date('d/m/Y H:i', (int)$info['start_time']);
                            }
                            
                            array_unshift($allResults, $result); // Add to beginning (most recent first)
                            Log::info('Added recently submitted result from fallback', ['rid' => $rid]);
                        }
                    } else {
                        Log::warning('Failed to fetch individual result', ['rid' => $rid, 'status' => $resultResponse->status()]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error fetching individual result', ['rid' => $rid, 'message' => $e->getMessage()]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error processing recently submitted results', ['message' => $e->getMessage()]);
        }

        // Remove duplicates (in case same result was in both API and fallback)
        $uniqueResults = [];
        $seenRids = [];
        foreach ($allResults as $result) {
            if (!in_array($result['rid'], $seenRids)) {
                $uniqueResults[] = $result;
                $seenRids[] = $result['rid'];
            }
        }
        $allResults = $uniqueResults;

        Log::info('Final results count', ['total' => count($allResults), 'from_api' => count(array_filter($allResults, fn($r) => $r['source'] === 'api_history'))]);

        return view('exams.exam-history-list', compact('allResults', 'user'));
    }

    public function show($id)
    {
        // Check authentication
        if (!session()->has('auth_token') || !session()->has('user')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $token = session('auth_token');
        $user = session('user');

        // Fetch exam questions from /api/exam-questions/{quid}
        // Note: $id here is the quid (quiz ID), not rid
        try {
            $response = Http::withToken($token)->get($this->apiUrl . '/api/exam-questions/' . $id);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Response format: { status, rid, total, data: [...questions] }
                // Ensure rid is present - it's critical for saving answers
                if (!isset($data['rid']) || empty($data['rid'])) {
                    Log::error('API did not return rid: ' . json_encode($data));
                    
                    // VÁ LỖI: In thẳng sự thật ra màn hình để bắt bệnh cục Service
                    return redirect('/exams')->with('error', 'Service lỗi: Trả về thiếu mã lượt thi (rid). Dữ liệu Service trả về là: ' . json_encode($data));
                }
                
                $exam = [
                    'quid' => $id,
                    'rid' => (int)$data['rid'],  // Cast to int to ensure it's valid
                    'total' => $data['total'] ?? 40,
                    'questions' => $data['data'] ?? [],
                    'quiz_name' => $data['quiz_name'] ?? 'Bài thi',
                    'duration' => $data['duration'] ?? 35,
                    'time_left' => isset($data['time_left']) ? (int)$data['time_left'] : null,  // For resumed exams
                    'user' => $user
                ];
            } else {
                Log::error('API error fetching questions: ' . $response->status());
                return redirect('/exams')->with('error', 'Không tìm thấy bài thi. API trả về lỗi: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error fetching exam questions: ' . $e->getMessage());
            return redirect('/exams')->with('error', 'Lỗi tải bài thi: ' . $e->getMessage());
        }

        return view('exams.show', compact('exam'));
    }

    public function status($id)
    {
        // Check authentication
        if (!session()->has('auth_token') || !session()->has('user')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $user = session('user');
        $token = session('auth_token');

        // Get exam info
        $examInfo = [
            'quid' => $id,
            'quiz_name' => 'Bài thi (quid=' . $id . ')',
            'noq' => 40,
            'duration' => 35
        ];

        // Status: 'not_started', 'in_progress', 'completed'
        // Currently: hardcoded as 'not_started' (can be updated with API later)
        $status = [
            'quid' => $id,
            'rid' => 1,  // Default result ID (should come from API)
            'state' => 'not_started',  // not_started, in_progress, completed
            'attempts' => 0,
            'best_score' => null,
            'last_attempt' => null
        ];

        return view('exams.status', compact('examInfo', 'status', 'user'));
    }

    public function result($rid)
    {
        // Check authentication
        if (!session()->has('auth_token') || !session()->has('user')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $user = session('user');
        $token = session('auth_token');

        // Validate rid parameter
        if (!is_numeric($rid) || $rid <= 0) {
            return redirect('/exams')->with('error', 'ID bài thi không hợp lệ');
        }

        // Default result structure
        $result = [
            'rid' => $rid,
            'quid' => 61,  // Default quiz ID
            'student_name' => $user['first_name'] . ' ' . ($user['last_name'] ?? ''),
            'quiz_name' => 'Bài thi',
            'total_questions' => 40,
            'total_correct' => 0,
            'total_answer' => 0,
            'score' => 0,
            'percentage' => 0,
            'submitted_at' => now(),
            'error' => null,
            'can_retry' => false,
            'retry_status' => 'not_retried'
        ];

        try {
            $response = Http::withToken($token)->get($this->apiUrl . '/api/result/' . $rid);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Check if data has 'info' key with result information
                if (isset($data['info'])) {
                    $info = $data['info'];
                    
                    // Parse result information
                    $result['score'] = isset($info['score_obtained']) ? (int)$info['score_obtained'] : 0;
                    $result['total_correct'] = isset($info['total_correct']) ? (int)$info['total_correct'] : $result['score'];
                    $result['total_questions'] = isset($info['total_questions']) ? (int)$info['total_questions'] : 40;
                    $result['total_answer'] = isset($info['total_answer']) ? (int)$info['total_answer'] : 0;
                    $result['quiz_name'] = $info['quiz_name'] ?? 'Bài thi';
                    $result['quid'] = $info['quid'] ?? 61;
                    
                    // Calculate percentage
                    $result['percentage'] = $result['total_questions'] > 0 
                        ? round(($result['score'] / $result['total_questions']) * 100, 2)
                        : 0;
                    
                    // Sanity check: if no questions answered but total_answer > 0, backend may have issue
                    if ($result['score'] == 0 && $result['total_answer'] > 0) {
                        $result['result_error'] = 'Cảnh báo: Bạn trả lời ' . $result['total_answer'] . ' câu nhưng không câu nào đúng, hoặc server chưa lưu đúng điểm. Vui lòng kiểm tra lại.';
                    }
                    
                    // Parse submitted_at timestamp
                    if (isset($info['submitted_at'])) {
                        try {
                            $result['submitted_at'] = new \DateTime($info['submitted_at']);
                        } catch (\Exception $e) {
                            $result['submitted_at'] = now();
                        }
                    }
                    
                    // Check retry status from session - check at quiz level (quid), not result level (rid)
                    // This ensures only 1 retry per quiz, regardless of which attempt (rid) is viewed
                    $quid = $result['quid'];
                    $retryKey = 'quid_' . $quid . '_retry_used';
                    $result['retry_status'] = session($retryKey) ? 'retried' : 'not_retried';
                    $result['can_retry'] = $result['retry_status'] === 'not_retried';
                    
                    Log::info('Result fetched successfully', ['rid' => $rid, 'quid' => $quid, 'score' => $result['score'], 'total_answer' => $result['total_answer'], 'can_retry' => $result['can_retry'], 'retry_used' => session($retryKey)]);
                } else {
                    // No info in response
                    $result['error'] = 'Không tìm thấy thông tin bài thi';
                    Log::warning('API response missing info key for rid: ' . $rid);
                }
            } else if ($response->status() === 404) {
                Log::warning('Result not found in API for rid: ' . $rid . ', response: ' . $response->body());
                return redirect('/exams')->with('error', 'Không tìm thấy kết quả bài thi. Bài thi có thể chưa được lưu lên máy chủ. Vui lòng quay lại danh sách bài thi và thử lại.');
            } else {
                Log::error('API error fetching result', ['rid' => $rid, 'status' => $response->status(), 'body' => $response->body()]);
                $result['error'] = 'Lỗi lấy kết quả bài thi từ server (HTTP ' . $response->status() . ')';
            }
        } catch (\Exception $e) {
            Log::error('Error fetching result: ' . $e->getMessage(), ['rid' => $rid]);
            $result['error'] = 'Lỗi kết nối: ' . $e->getMessage();
        }

        return view('exams.exam-result-detail', compact('result', 'user'));
    }

    public function submit($rid, \Illuminate\Http\Request $request)
    {
        // Check authentication
        if (!session()->has('auth_token') || !session()->has('user')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $token = session('auth_token');
        
        try {
            // Submit exam to backend API
            // Backend already has all answers from save-answer calls
            $response = Http::withToken($token)->post(
                $this->apiUrl . '/api/submit-exam/' . $rid,
                [
                    'is_timeout' => $request->input('is_timeout', 0)
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                // Redirect to result page with the returned result ID
                $resultId = $data['result']['rid'] ?? $rid;
                
                // Store recently submitted result ID in session as backup for history display
                $recentlySubmitted = session('recently_submitted_results', []);
                if (!in_array($resultId, $recentlySubmitted)) {
                    array_unshift($recentlySubmitted, $resultId); // Add to beginning
                    $recentlySubmitted = array_slice($recentlySubmitted, 0, 10); // Keep last 10
                    session(['recently_submitted_results' => $recentlySubmitted]);
                    Log::info('Stored result in session', ['rid' => $resultId, 'total_stored' => count($recentlySubmitted)]);
                }
                
                Log::info('Exam submitted successfully', ['rid' => $rid, 'resultId' => $resultId]);
                return redirect(route('exam.result', $resultId))
                    ->with('success', $data['message'] ?? 'Nộp bài thi thành công');
            } else {
                $apiError = $response->json();
                $errorMsg = $apiError['message'] ?? 'Vui lòng thử lại';
                Log::error('Backend API error submitting exam', ['status' => $response->status(), 'rid' => $rid, 'error' => $errorMsg]);
                return redirect("/exams")->with('error', 'Lỗi nộp bài thi (HTTP ' . $response->status() . '): ' . $errorMsg);
            }
        } catch (\Exception $e) {
            Log::error('Error submitting exam: ' . $e->getMessage());
            return redirect("/exams")->with('error', 'Lỗi nộp bài thi: ' . $e->getMessage());
        }
    }

    public function saveAnswer(\Illuminate\Http\Request $request)
    {
        // Check authentication
        if (!session()->has('auth_token')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $token = session('auth_token');

        // Validate input
        $validated = $request->validate([
            'qid' => 'required|numeric',
            'oid' => 'required|numeric',
            'rid' => 'required|numeric'
        ]);

        try {
            // Forward the request to backend API
            $response = Http::withToken($token)->post(
                $this->apiUrl . '/api/save-answer',
                [
                    'qid' => $validated['qid'],
                    'oid' => $validated['oid'],
                    'rid' => $validated['rid']
                ]
            );

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Câu trả lời đã được lưu',
                    'data' => $response->json()
                ]);
            } else {
                $errorMsg = 'Lỗi API: HTTP ' . $response->status();
                $apiErrorBody = $response->json();
                if (isset($apiErrorBody['message'])) {
                    $errorMsg = $apiErrorBody['message'];
                }
                Log::error('Backend API error saving answer: ' . $errorMsg, ['status' => $response->status(), 'body' => $apiErrorBody]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể lưu câu trả lời: ' . $errorMsg
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error saving answer: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markRetry($rid, \Illuminate\Http\Request $request)
    {
        // Check authentication
        if (!session()->has('auth_token') || !session()->has('user')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $quid = $request->input('quid');
            
            if (!$quid) {
                return response()->json(['success' => false, 'message' => 'Missing quid parameter'], 400);
            }
            
            // Mark this quiz as retry-used at the quiz level (not result level)
            // This ensures only 1 retry per quiz across all attempts
            $retryKey = 'quid_' . $quid . '_retry_used';
            session([$retryKey => true]);
            
            Log::info('Marked quiz as retry-used', ['rid' => $rid, 'quid' => $quid, 'retryKey' => $retryKey]);
            
            return response()->json([
                'success' => true,
                'message' => 'Marked as retry-used',
                'rid' => $rid,
                'quid' => $quid
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking retry: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}