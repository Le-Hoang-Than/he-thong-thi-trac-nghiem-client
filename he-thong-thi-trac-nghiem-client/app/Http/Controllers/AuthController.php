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
        // Kiểm tra dữ liệu đầu vào
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
            // NẾU ĐĂNG NHẬP THẤT BẠI (SAI MÃ HOẶC SAI MẬT KHẨU)
            else {
                $errorMsg = $response->json()['message'] ?? 'Đăng nhập thất bại';
                
                // Dùng view() để ép Render hiển thị lỗi ngay lập tức, không cho chuyển trang gây mất Session
                if (str_contains(strtolower($errorMsg), 'mật khẩu')) {
                    return view('auth.login')->withErrors(['password' => $errorMsg]);
                } else {
                    return view('auth.login')->withErrors(['studentid' => $errorMsg]);
                }
            }
        } catch (\Exception $e) {
            // Bắt lỗi sập server API hoặc mất mạng
            return view('auth.login')->withErrors(['studentid' => 'Lỗi kết nối đến máy chủ: ' . $e->getMessage()]);
        }
    }

    // 3. Xử lý khi người dùng bấm Đăng xuất
    public function logout(Request $request)
    {
        $token = session('auth_token');
        if ($token) {
            try {
                // Gọi API để xóa token bên Service
                $apiUrl = env('BASE_API', 'https://he-thong-thi-trac-nghiem-service-lnup.onrender.com/api');
                Http::withToken($token)->post($apiUrl . '/logout');
            } catch (\Exception $e) {
                // Bỏ qua lỗi mạng nếu có, ưu tiên xóa session ở máy người dùng
            }
        }

        // Xóa sạch bộ nhớ tạm
        session()->flush();
        return redirect('/login')->with('success', 'Đã đăng xuất thành công');
    }

    // 4. Kiểm tra xem người dùng đã đăng nhập chưa (dùng cho Middleware)
    public function isAuthenticated()
    {
        return session()->has('auth_token') && session()->has('user');
    }
}
