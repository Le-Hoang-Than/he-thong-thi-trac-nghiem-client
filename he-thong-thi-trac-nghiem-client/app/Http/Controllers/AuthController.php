<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // API backend URL
    protected $apiUrl = 'http://127.0.0.1:8000';

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
            $response = Http::post($this->apiUrl . '/api/login', $credentials);

            if ($response->successful()) {
                $data = $response->json();
                session([
                    'auth_token' => $data['token'] ?? null,
                    'user' => $data['user'] ?? null,
                    'studentid' => $data['user']['studentid'] ?? null,
                ]);
                
                return redirect('/exams')->with('success', 'Đăng nhập thành công!');
            } else {
                $errorMsg = $response->json()['message'] ?? 'Đăng nhập thất bại';
                return back()->withErrors(['studentid' => $errorMsg])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['studentid' => 'Lỗi kết nối đến server. Vui lòng thử lại sau.'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        // Call backend logout API if token exists
        $token = session('auth_token');
        if ($token) {
            try {
                Http::withToken($token)->post($this->apiUrl . '/api/logout');
            } catch (\Exception $e) {
                // Ignore errors, just logout locally
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
