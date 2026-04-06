# 📖 Hướng Dẫn Hệ Thống Thi Trắc Nghiệm - Phần Client

## 📋 Mục Lục
1. [Tổng Quan Hệ Thống](#tổng-quan-hệ-thống)
2. [Trang Làm Bài Thi (show.blade.php)](#trang-làm-bài-thi)
3. [Trang Danh Sách Bài Thi (index.blade.php)](#trang-danh-sách-bài-thi)
4. [Trang Kết Quả (results.blade.php)](#trang-kết-quả)
5. [Tính Năng Chính](#tính-năng-chính)
6. [Luồng Hoạt Động](#luồng-hoạt-động)

---

## 🎯 Tổng Quan Hệ Thống

Hệ thống thi trắc nghiệm là ứng dụng web cho phép sinh viên:
- ✅ Xem danh sách bài thi
- ✅ Làm bài thi với giao diện thân thiện
- ✅ Lưu câu trả lời tự động
- ✅ Xem kết quả và lịch sử thi
- ✅ Làm lại bài thi (1 lần duy nhất)

**Tech Stack:**
- Backend: Laravel PHP
- Frontend: Laravel Blade + Bootstrap 5.3
- API Communication: AJAX/Fetch
- Timer: Vanilla JavaScript

---

## 🖥️ Trang Làm Bài Thi (show.blade.php)

### Mục Đích
Giao diện chính cho sinh viên **làm bài thi trắc nghiệm** với tất cả các tính năng cần thiết.

### Cấu Trúc Trang

```
┌─────────────────────────────────────────────────────┐
│  Navbar (Dropdown: Hồ sơ, Đăng xuất)               │
├─────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────┐ │
│  │ Tên Bài Thi | Số câu | Thời gian  [Timer]      │ │
│  └─────────────────────────────────────────────────┘ │
│                                                       │
│  Câu 1: ___________                                  │
│  ☐ Đáp án A                                         │
│  ☐ Đáp án B                                         │
│  ☑ Đáp án C                    ← Auto-save         │
│  ☐ Đáp án D                                         │
│                                                       │
│  Câu 2: ___________                                  │
│  ☐ Đáp án A                                         │
│  ...                                                 │
│                                                       │
│  [Nộp Bài Thi]                                      │
└─────────────────────────────────────────────────────┘

Timer hiển thị ở góc phải (fixed position)
```

### 1. HEAD & META TAGS

```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```
**Giải thích:**
- **CSRF Token**: Cross-Site Request Forgery token bảo vệ form
- Được dùng khi gửi dữ liệu AJAX
- Laravel tự động check token trên server

```html
<title>{{ $exam['title'] ?? 'Bài thi' }} - {{ config('app.name') }}</title>
```
**Giải thích:**
- `??` (null coalescing): Nếu `$exam['title']` rỗng → dùng `'Bài thi'`
- Hiển thị tên bài thi trên tab browser

### 2. CSS STYLING

**Gradient Background:**
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```
- Tạo hiệu ứng gradient từ xanh dương sang tím
- 135deg: Góc 135 độ từ trái dưới sang phải trên

**Timer Container (Fixed Position):**
```css
#timerContainer {
    position: fixed;  /* Cố định trên màn hình */
    top: 80px;        /* Cách navbar 80px */
    right: 30px;      /* Cách mép phải 30px */
    z-index: 100;     /* Nằm trên top layer */
}
```
- Timer **luôn hiển thị** khi scroll
- `z-index: 100`: Đảm bảo không bị phủ lên

**Animation Nhấp Nháy (Pulse):**
```css
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

#timer.warning {
    color: #ffeb3b;      /* Màu vàng */
    animation: pulse 1s infinite;  /* Nhấp nháy chậm */
}

#timer.danger {
    color: #ff6b6b;      /* Màu đỏ */
    animation: pulse 0.5s infinite;  /* Nhấp nháy nhanh */
}
```
- **<5 phút**: Vàng, nhấp nháy 1 giây
- **<1 phút**: Đỏ, nhấp nháy 0.5 giây

### 3. NAVBAR & HEADER

```html
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <a class="navbar-brand" href="/exams">
        <i class="fas fa-graduation-cap me-2"></i>Hệ thống thi trắc nghiệm
    </a>
    <div class="dropdown">
        <button class="btn btn-link" data-bs-toggle="dropdown">
            {{ session('studentid') ?? 'N/A' }}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('profile') }}">Hồ sơ</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('logout') }}">Đăng xuất</a></li>
        </ul>
    </div>
</nav>
```
**Giải thích:**
- **Bootstrap Navbar**: Responsive, tự động collapse trên mobile
- **Dropdown Menu**: Hiển thị tùy chọn Profile & Logout
- **`session('studentid')`**: Lấy ID sinh viên từ session

### 4. EXAM HEADER

```html
<div class="exam-header">
    <h1>{{ $exam['quiz_name'] ?? 'Bài thi' }}</h1>
    <p>Tổng số câu: {{ $exam['total'] ?? 40 }} | Thời gian: {{ $exam['duration'] ?? 45 }} phút</p>
</div>
```
**Giải thích:**
- Hiển thị thông tin bài thi
- Các giá trị mặc định nếu API không trả về

### 5. VALIDATION & ERROR CHECKING

```php
@if(isset($exam['questions']) && is_array($exam['questions']) && count($exam['questions']) > 0)
```
**Kiểm tra:**
- `isset()`: Dữ liệu tồn tại?
- `is_array()`: Là array?
- `count() > 0`: Có dữ liệu?

```php
@if(!isset($exam['rid']) || empty($exam['rid']))
    <div class="alert alert-danger">Không thể khởi tạo bài thi</div>
@endif
```
**Giải thích:**
- `rid` = Result ID: ID duy nhất cho mỗi lần làm bài
- Không có `rid` → không thể lưu câu trả lời → hiện lỗi

### 6. FORM & QUESTIONS LOOP

```html
<form method="POST" 
      action="/exams/{{ $exam['rid'] }}/submit" 
      id="examForm" 
      data-rid="{{ $exam['rid'] ?? 0 }}" 
      data-duration="{{ $exam['duration'] ?? 45 }}">
    @csrf
    <input type="hidden" name="is_timeout" value="0" id="is_timeout_field">
```
**Giải thích:**
- `data-rid`, `data-duration`: Lưu dữ liệu cho JavaScript (thay vì inline script)
- `is_timeout_field`: Nếu hết giờ → set = 1
- `@csrf`: Laravel tự động tạo CSRF token

```php
@foreach($exam['questions'] as $index => $question)
    <div class="question-container">
        <div class="question-number">{{ $index + 1 }}</div>
        <div class="question-text">
            @php
                // Strip HTML tags & decode entities
                $questionText = html_entity_decode(strip_tags($question['question']));
                $questionText = trim(preg_replace('/\s+/', ' ', $questionText));
            @endphp
            {{ $questionText ?: '(Câu hỏi)' }}
        </div>
        
        @foreach($question['options'] as $option)
            <label class="option">
                <input type="radio" 
                       name="question_{{ $question['qid'] }}" 
                       value="{{ $option['oid'] }}">
                {{ $option['q_option'] }}
            </label>
        @endforeach
    </div>
@endforeach
```
**Giải thích:**
- `@foreach`: Vòng lặp qua từng câu hỏi
- `html_entity_decode()`: Chuyển `&amp;` → `&`
- `strip_tags()`: Xóa HTML tags (giữ lại text)
- `preg_replace()`: Xóa whitespace thừa
- `name="question_{{qid}}"`: Nhóm radio buttons theo câu
- `value="{{oid}}"`: ID đáp án khi submit
- **Radio buttons**: Chỉ chọn 1 đáp án duy nhất

### 7. JAVASCRIPT - INITIALIZATION

```javascript
const examForm = document.getElementById('examForm');
const rid = parseInt(examForm?.dataset.rid || '0', 10) || 0;
const durationMinutes = parseInt(examForm?.dataset.duration || '45', 10) || 45;
```
**Giải thích:**
- `?.` (optional chaining): Nếu `examForm` null → không lỗi, trả về `undefined`
- `parseInt(..., 10)`: Chuyển string → số (base 10)
- `|| 0`: Nếu parse thất bại → dùng 0

```javascript
if (!rid || rid === null || rid === 0) {
    console.error('Invalid rid:', rid);
    document.body.innerHTML = '<div class="alert">...</div>';
}
```
**Giải thích:**
- Nếu không có `rid` hợp lệ → thay toàn trang bằng lỗi
- Ngừa trường hợp backend không trả về rid

### 8. TOAST NOTIFICATION

```javascript
function showToast(message, type = 'success') {
    const toastHtml = `
        <div class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
            <div class="alert alert-${type} mb-0" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'warning-circle'}"></i>
                ${message}
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    
    // Xóa sau 3 giây
    setTimeout(() => {
        const toast = document.querySelector('.alert-' + type);
        if (toast) toast.parentElement.remove();
    }, 3000);
}
```
**Giải thích:**
- `insertAdjacentHTML()`: Thêm HTML vào DOM động
- `position-fixed`: Thông báo cố định góc phải trên
- `z-index: 9999`: Đảm bảo nằm trên top
- `setTimeout()`: Tự động xóa sau 3 giây

### 9. SAVE ANSWER FUNCTION

```javascript
function saveAnswer(questionId, optionId) {
    if (!rid || rid === null) {
        showToast('Lỗi: ID bài thi không hợp lệ', 'warning');
        return;
    }
    
    const data = {
        qid: questionId,      // Question ID
        oid: optionId,        // Option ID (đáp án)
        rid: rid              // Result ID
    };
    
    fetch('/exams/save-answer', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                     .getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.body.status === 'success') {
            // Lưu thầm - không hiện thông báo
        } else {
            showToast('Lỗi lưu: ' + (result.body.message || 'Không xác định'), 'warning');
        }
    })
    .catch(error => {
        showToast('Lỗi kết nối: ' + error.message, 'warning');
    });
}
```
**Giải thích:**
- **AJAX Request**: Gửi POST request mà không reload trang
- **Body**: JSON chứa qid (Question ID), oid (Option ID), rid (Result ID)
- **CSRF Token**: Lấy từ meta tag để bảo mật
- **Silent Save**: Nếu thành công → không hiện thông báo
- **Error Handling**: Nếu lỗi → hiện warning toast

### 10. AUTO-SAVE ON RADIO CHANGE

```javascript
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const questionId = this.name.replace('question_', '');
        const optionId = this.value;
        saveAnswer(questionId, optionId);
    });
});
```
**Giải thích:**
- `querySelectorAll()`: Lấy tất cả radio buttons
- `addEventListener('change')`: Khi chọn → trigger
- `replace('question_', '')`: Trích suất questionId từ name
- **User Experience**: Không cần click "Lưu" → tự động lưu

### 11. COUNTDOWN TIMER

```javascript
document.addEventListener('DOMContentLoaded', function() {
    let totalSeconds = durationMinutes * 60;
    const timerElement = document.getElementById('timer');
    
    // Set giá trị ban đầu
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    timerElement.textContent = `${minutes}:${String(seconds).padStart(2, '0')}`;
```
**Giải thích:**
- `DOMContentLoaded`: Chờ trang load xong rồi chạy
- `Math.floor()`: Tround xuống (floor)
- `%`: Modulo - lấy phần dư
- `padStart(2, '0')`: Thêm 0 phía trước nếu cần (05 thay vì 5)

```javascript
const timerInterval = setInterval(() => {
    totalSeconds--;
    
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    timerElement.textContent = `${minutes}:${String(seconds).padStart(2, '0')}`;
    
    // Cảnh báo: <5 phút
    if (totalSeconds < 300 && totalSeconds >= 60) {
        timerElement.classList.remove('danger');
        timerElement.classList.add('warning');
    }
    
    // Nguy hiểm: <1 phút
    if (totalSeconds < 60) {
        timerElement.classList.remove('warning');
        timerElement.classList.add('danger');
    }
    
    // Hết giờ
    if (totalSeconds <= 0) {
        clearInterval(timerInterval);
        timerElement.textContent = '0:00';
        document.getElementById('is_timeout_field').value = '1';
        document.getElementById('examForm').submit();
    }
}, 1000);
```
**Giải thích:**
- `setInterval(..., 1000)`: Chạy mỗi 1 giây (1000ms)
- `totalSeconds--`: Giảm 1 giây
- `classList.add('warning')`: Thêm class CSS warning → vàng nhấp nháy
- `classList.add('danger')`: Thêm class CSS danger → đỏ nhấp nháy
- `clearInterval()`: Dừng timer
- **Auto submit**: Khi hết giờ → set `is_timeout=1` → submit form tự động

---

## 📊 Luồng Hoạt Động

```
1. User vào trang /exams/{quid}
   ↓
2. Laravel Controller lấy dữ liệu từ API
   - GET /api/exam-questions/{quid} → lấy câu hỏi
   - Trả về: quid, rid, questions(qid, options)
   ↓
3. Trang load, JavaScript initialize
   - Lấy rid & duration từ data attributes
   - Validate rid
   - Set up event listeners
   - Start timer
   ↓
4. User xem câu hỏi → chọn đáp án (click radio)
   ↓
5. Trigger 'change' event
   ↓
6. saveAnswer(questionId, optionId) chạy
   ↓
7. AJAX POST /exams/save-answer
   {qid, oid, rid}
   ↓
8. Controller lưu vào database
   ↓
9. Trả về JSON {status: 'success'}
   ↓
10. JavaScript nhận kết quả (silent save - không thông báo)
   ↓
11. Lặp 4-10 cho các câu khác
   ↓
12. ĐỒNG THỜI: Timer đếm ngược hàng giây
    - < 5 phút: Timer vàng nhấp nháy
    - < 1 phút: Timer đỏ nhấp nháy
   ↓
13. User click "Nộp Bài" hoặc hết giờ
    - Nộp: Submit form /exams/{rid}/submit
    - Hết giờ: Set is_timeout=1 → Submit tự động
   ↓
14. Controller submit exam
    - Tính điểm (số đúng)
    - Lưu vào database
    - Trả về result_id
   ↓
15. Redirect /exam-result/{result_id}
   ↓
16. Trang kết quả hiển thị
    - Điểm
    - Số câu đúng
    - Thời gian nộp
    - Nút "Làm lại" (nếu chưa làm lại)
```

---

## 🎯 Các Tính Năng Chính

### 1. Lưu Câu Trả Lời Tự Động
- **Khi nào**: Khi chọn đáp án (radio button change)
- **Cách hoạt động**: AJAX POST /exams/save-answer
- **Lợi ích**: Không lo mất dữ liệu nếu page crash
- **UX**: Silent save - không hiện thông báo khi lưu thành công

### 2. Timer Persistent
- **Vị trí**: Fixed position, góc phải trên
- **Luôn hiển thị**: Ngay cả khi scroll
- **Warning**: <5 phút vàng nhấp nháy
- **Danger**: <1 phút đỏ nhấp nháy
- **Auto submit**: Hết giờ tự động nộp bài

### 3. Validation & Error Handling
- **Kiểm tra rid**: Không có rid → hiện lỗi
- **Kiểm tra câu hỏi**: Không có câu → hiện lỗi
- **AJAX error**: Hiện warning toast
- **Network error**: Xử lý catch block

### 4. Responsive Design
- **Desktop**: Normal layout
- **Tablet**: Font size nhỏ lại, spacing giảm
- **Mobile**: Navbar collapse, timer bé hơn

### 5. Làm Lại Bài Thi (1 Lần)
- **Khi làm lại**: Bấm nút "Làm lại" → set session key → navigate đến /exams/{quid}
- **Lần 2**: Session key đã set → nút "Làm lại" bị disable
- **Trạng thái**: Kiểm tra ở `result()` controller: `quid_{quid}_retry_used`

---

## 🔐 Bảo Mật

### CSRF Token
```javascript
'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                         .getAttribute('content')
```
- Laravel tự động verify token
- Ngừa tấn công CSRF

### Session Authentication
- Kiểm tra `session()->has('auth_token')`
- Mỗi request phải có valid token
- Token hết hạn → redirect login

### Validation
- Server-side validate dữ liệu
- Check rid, qid, oid hợp lệ
- Check user có quyền truy cập

---

## 📱 Mobile Responsive

```css
@media (max-width: 768px) {
    #timerContainer {
        top: 80px;
        right: 15px;
        padding: 15px 20px;
    }
    
    #timer {
        font-size: 28px;
    }
}
```
- Timer bé hơn trên mobile
- Padding giảm để vừa màn hình

---

## ⚡ Performance Tips

1. **Lazy Load**: Chỉ load câu hỏi khi cần
2. **Silent Save**: Không hiện toast khi thành công → giảm reflow
3. **Event Delegation**: Không attach listener cho từng radio
4. **Efficient Timer**: Chỉ update DOM 1 lần/giây (không 60ms)

---

## 🐛 Debugging

### View Browser Console
```javascript
console.log('Exam started with rid:', rid);
console.log('Saving answer:', data);
console.log('Save answer response:', result);
```

### Check Network Tab
1. F12 → Network tab
2. Click radio button
3. Xem POST /exams/save-answer request
4. Response phải có `status: 'success'`

### Check Session
```php
// Trong controller
dd(session()->all());
```

---

## 📚 Các File Liên Quan

| File | Mục Đích |
|------|----------|
| `show.blade.php` | Giao diện làm bài thi |
| `index.blade.php` | Danh sách bài thi |
| `results.blade.php` | Lịch sử & kết quả thi |
| `result.blade.php` | Chi tiết kết quả 1 bài |
| `ExamController.php` | Logic xử lý bài thi |
| `routes/web.php` | Định nghĩa routes |

---

## 🚀 Cách Sử Dụng

### Cho Sinh Viên
1. Login vào /login
2. Vào /exams xem danh sách
3. Click vào bài thi → /exams/{quid}
4. Làm bài: chọn đáp án (auto-save)
5. Click "Nộp Bài" hoặc chờ hết giờ
6. Xem kết quả ngay lập tức
7. Có thể làm lại 1 lần

### Cho Admin/Teacher
- Tạo bài thi ở backend service
- Thiết lập số câu, thời gian, điểm
- Xem kết quả của từng sinh viên

---

## 📞 Liên Hệ & Support

Nếu có vấn đề:
1. Kiểm tra browser console (F12)
2. Kiểm tra network requests
3. Kiểm tra backend logs
4. Liên hệ team development

---

**Cập nhật: April 2, 2026**

**Version: 1.0**
