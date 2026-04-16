<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\MessageBag;
class AuthController extends Controller
{
    // 1. Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Xử lý khi người dùng bấm nút Đăng nhập
   public function login(Request $request)
    {
        // 1. KIỂM TRA LỖI THỦ CÔNG (Tuyệt đối không dùng validate() để tránh bị ép chuyển trang)
        $validator = Validator::make($request->all(), [
            'studentid' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'studentid.required' => 'Mã sinh viên không được để trống',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        // Nếu có lỗi (ví dụ mk ngắn hơn 6 ký tự), trả thẳng về view luôn!
        if ($validator->fails()) {
            return view('auth.login')->withErrors($validator);
        }

        try {
            // 2. GỌI API
            $apiUrl = env('BASE_API', 'https://he-thong-thi-trac-nghiem-service-lnup.onrender.com/api');
            $response = Http::post($apiUrl . '/login', $request->only(['studentid', 'password']));

            // NẾU ĐĂNG NHẬP THÀNH CÔNG
            if ($response->successful()) {
                $data = $response->json();
                session([
                    'auth_token' => $data['token'] ?? null,
                    'user' => $data['user'] ?? null,
                    'studentid' => $data['user']['studentid'] ?? null,
                ]);
                return redirect('/exams')->with('success', 'Đăng nhập thành công!');
            } 
            // NẾU SAI TÀI KHOẢN / MẬT KHẨU TỪ API
            else {
                $errorMsg = $response->json()['message'] ?? 'Đăng nhập thất bại';
                $errors = new MessageBag(); // Tạo túi đựng lỗi mới
                
                // Nhét lỗi vào đúng ô
                if (str_contains(mb_strtolower($errorMsg, 'UTF-8'), 'mật khẩu')) {
                    $errors->add('password', $errorMsg);
                } else {
                    $errors->add('studentid', $errorMsg);
                }

                // Trả thẳng view kèm túi lỗi
                return view('auth.login')->withErrors($errors);
            }
        } catch (\Exception $e) {
            $errors = new MessageBag();
            $errors->add('studentid', 'Lỗi kết nối đến máy chủ: ' . $e->getMessage());
            return view('auth.login')->withErrors($errors);
        }
    }

    // 3. Xử lý Đăng xuất
    public function logout(Request $request)
    {
        $token = session('auth_token');
        if ($token) {
            try {
                // Gọi API để xóa token
                $apiUrl = env('BASE_API', 'https://he-thong-thi-trac-nghiem-service-lnup.onrender.com/api');
                Http::withToken($token)->post($apiUrl . '/logout');
            } catch (\Exception $e) {
                // Bỏ qua nếu có lỗi mạng
            }
        }

        // Xóa sạch bộ nhớ tạm và quay về trang đăng nhập
        session()->flush();
        return redirect('/login')->with('success', 'Đã đăng xuất thành công');
    }

    // 4. Kiểm tra trạng thái đăng nhập
    public function isAuthenticated()
    {
        return session()->has('auth_token') && session()->has('user');
    }
}
