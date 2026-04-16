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
                
                // ---- THÊM ĐÚNG 1 DÒNG NÀY VÀO ĐÂY ----
                dd("DỮ LIỆU TỪ API TRẢ VỀ LÀ:", $response->status(), $response->json());
                // -------------------------------------

                $errorMsg = $response->json()['message'] ?? 'Đăng nhập thất bại';
                if (str_contains(strtolower($errorMsg), 'mật khẩu')) {
                    return back()->withErrors(['password' => $errorMsg])->withInput();
                } else {
                    return back()->withErrors(['studentid' => $errorMsg])->withInput();
                }
            }
        } catch (\Exception $e) {
            return back()->withErrors(['studentid' => 'Lỗi kết nối: ' . $e->getMessage()])->withInput();
        }
    }

    public function logout(Request $request)
    {
        $token = session('auth_token');
        if ($token) {
            try {
               
                $apiUrl = env('BASE_API', 'https://he-thong-thi-trac-nghiem-service-lnup.onrender.com/api');
                Http::withToken($token)->post($apiUrl . '/logout');
            } catch (\Exception $e) {
               
            }
        }

        session()->flush();
        return redirect('/login')->with('success', 'Đã đăng xuất thành công');
    }

    public function isAuthenticated()
    {
        return session()->has('auth_token') && session()->has('user');
    }
}
