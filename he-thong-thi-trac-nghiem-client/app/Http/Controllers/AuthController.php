<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        // Kiểm tra dữ liệu người dùng nhập vào
        $credentials = $request->validate([
            'studentid' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'studentid.required' => 'Mã sinh viên không được để trống',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        try {
            // Gọi API sang bên Service
            $apiUrl = env('BASE_API', 'https://he-thong-thi-trac-nghiem-service-lnup.onrender.com/api');
            $response = Http::post($apiUrl . '/login', $credentials);

            // NẾU ĐĂNG NHẬP THÀNH CÔNG
            if ($response->successful()) {
                $data = $response->json();
                
                // Lưu thông tin vào Session của người dùng
                session([
                    'auth_token' => $data['token'] ?? null,
                    'user' => $data['user'] ?? null,
                    'studentid' => $data['user']['studentid'] ?? null,
                ]);
                
                // Chuyển hướng vào trang bài thi
                return redirect('/exams')->with('success', 'Đăng nhập thành công!');
            } 
            // NẾU ĐĂNG NHẬP THẤT BẠI (Sai pass, sai mã SV)
            else {
                $errorMsg = $response->json()['message'] ?? 'Đăng nhập thất bại';
                
                // Trả thẳng về view kèm theo lỗi (Né lỗi Render xóa session)
                if (str_contains(mb_strtolower($errorMsg, 'UTF-8'), 'mật khẩu')) {
                    return view('auth.login')->withErrors(['password' => $errorMsg]);
                } else {
                    return view('auth.login')->withErrors(['studentid' => $errorMsg]);
                }
            }
        } catch (\Exception $e) {
            // Bắt lỗi sập mạng hoặc không gọi được API
            return view('auth.login')->withErrors(['studentid' => 'Lỗi kết nối đến máy chủ: ' . $e->getMessage()]);
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
