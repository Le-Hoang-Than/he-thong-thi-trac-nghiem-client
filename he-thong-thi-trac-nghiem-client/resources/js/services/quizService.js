import axios from 'axios';

const API_BASE_URL = '/api';

// Gọi API lấy chi tiết quiz
export const fetchQuizDetail = async () => {
  try {
    const response = await axios.get(`${API_BASE_URL}/quiz-detail`);
    return response.data;
  } catch (error) {
    console.error('Lỗi khi gọi API quiz-detail:', error);
    throw error;
  }
};

// Nếu muốn gọi API với tham số (nếu backend hỗ trợ)
export const fetchQuizDetailById = async (quizId) => {
  try {
    const response = await axios.get(`${API_BASE_URL}/quiz-detail/${quizId}`);
    return response.data;
  } catch (error) {
    console.error('Lỗi khi gọi API quiz-detail:', error);
    throw error;
  }
};


export const fetchTestUsers = async () => {
  try {
    const response = await axios.get('https://he-thong-thi-trac-nghiem-service-lnup.onrender.com/api/test-users');
    return response.data;
  } catch (error) {
    console.error('Lỗi khi gọi API test-users:', error);
    throw error;
  }
};
