<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

public function login(Request $request)
    {
        $credentials = $request->validate([
            'studentid' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'studentid.required' => 'Mã sinh viên không được để trống',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        try {
            $apiUrl = env('BASE_API', 'https://he-thong-thi-trac-nghiem-service-lnup.onrender.com/api');
            $response = Http::post($apiUrl . '/login', $credentials);

            if ($response->successful()) {
                $data = $response->json();
                session([
                    'auth_token' => $data['token'] ?? null,
                    'user' => $data['user'] ?? null,
                    'studentid' => $data['user']['studentid'] ?? null,
                ]);
                
                return redirect('/exams')->with('success', 'Đăng nhập thành công!');
            } else {
                
                // ĐÃ XÓA HÀM dd() Ở ĐÂY

                $errorMsg = $response->json()['message'] ?? 'Đăng nhập thất bại';
                
                // SỬ DỤNG return view() ĐỂ NÉ LỖI SERVER RENDER ĂN MẤT SESSION
                if (str_contains(strtolower($errorMsg), 'mật khẩu')) {
                    return view('auth.login')->withErrors(['password' => $errorMsg]);
                } else {
                    return view('auth.login')->withErrors(['studentid' => $errorMsg]);
                }
            }
        } catch (\Exception $e) {
           
            return view('auth.login')->withErrors(['studentid' => 'Lỗi kết nối: ' . $e->getMessage()]);
        }
    }
}
