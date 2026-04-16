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
    // 1. Kiểm tra định dạng trước (Local Validation)
    $request->validate([
        'studentid' => 'required|string',
        'password' => 'required|string|min:6',
    ], [
        'studentid.required' => 'Mã sinh viên không được để trống',
        'password.required' => 'Mật khẩu không được để trống',
        'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
    ]);

    try {
        $apiUrl = env('BASE_API', 'https://he-thong-thi-trac-nghiem-service-lnup.onrender.com/api');
        $response = Http::post($apiUrl . '/login', [
            'studentid' => $request->studentid,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            session([
                'auth_token' => $data['token'] ?? null,
                'user' => $data['user'] ?? null,
                'studentid' => $data['user']['studentid'] ?? null,
            ]);
            return redirect('/exams')->with('success', 'Đăng nhập thành công!');
        } 

        // 2. XỬ LÝ LỖI TỪ API (Sai mật khẩu, không tồn tại...)
        $errorMsg = $response->json()['message'] ?? 'Đăng nhập thất bại (Lỗi ' . $response->status() . ')';
        
        // Tạo một túi lỗi thủ công để ép nó hiện lên box đỏ trên cùng
        return view('auth.login')->withErrors([
            'login_error' => $errorMsg, // Lỗi chung
            'studentid' => $errorMsg,   // Hiện ở ô MSSV
            'password' => $errorMsg     // Hiện ở ô Mật khẩu
        ])->withInput();

    } catch (\Exception $e) {
        return view('auth.login')->withErrors([
            'studentid' => 'Không thể kết nối đến máy chủ: ' . $e->getMessage()
        ])->withInput();
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
