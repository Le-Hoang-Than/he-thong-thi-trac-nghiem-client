// Ví dụ sử dụng quizService để gọi API /quiz-detail

import { fetchQuizDetail } from './services/quizService.js';

// Hàm load quiz detail
export const loadQuizDetail = async () => {
  try {
    console.log('Đang load quiz detail...');
    const data = await fetchQuizDetail();
    console.log('Dữ liệu quiz:', data);
    
    // Xử lý dữ liệu ở đây
    // Ví dụ: render lên UI, lưu vào state, v.v...
    return data;
  } catch (error) {
    console.error('Lỗi load quiz detail:', error);
    // Hiển thị thông báo lỗi cho user
    alert('Không thể tải chi tiết quiz. Vui lòng thử lại.');
  }
};

// Nếu sử dụng với DOM
document.addEventListener('DOMContentLoaded', async () => {
  // Gọi API khi trang load
  const quizData = await loadQuizDetail();
  
  if (quizData) {
    // Render dữ liệu
    console.log('Quiz loaded successfully:', quizData);
  }
});
