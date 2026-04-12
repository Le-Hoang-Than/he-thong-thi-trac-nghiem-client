<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = [];

        try {
            $response = Http::get($this->api . '/test-users');

            if ($response->successful()) {
                $data = $response->json();
                $users = $data['data'] ?? $data ?? [];
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch test users', ['message' => $e->getMessage()]);
        }

        return view('users', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            Http::post($this->api . '/users', [
                'name' => $request->name,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save user', ['message' => $e->getMessage()]);
        }

        return redirect('/');
    }

    // Giữ lại hàm hiển thị trang Profile từ nhánh main
    public function profile()
    {
        if (!session()->has('auth_token') || !session()->has('user')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $user = session('user');

        return view('profile', compact('user'));
    }

   
    public function updateProfile(Request $request)
    {

        if (!session()->has('auth_token') || !session()->has('user')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Vui lòng đăng nhập'], 401);
            }
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

       
        $user = session('user');
        
        
        $user['first_name'] = $request->input('first_name', $user['first_name'] ?? '');
        $user['last_name'] = $request->input('last_name', $user['last_name'] ?? '');
        $user['email'] = $request->input('email', $user['email'] ?? '');
        $user['phone'] = $request->input('phone', $user['phone'] ?? '');
        
       
        session(['user' => $user]);
        
       
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thông tin cá nhân đã được cập nhật thành công',
                'user' => $user
            ], 200);
        }
        
      
        return redirect('/profile')->with('success', 'Thông tin cá nhân đã được cập nhật thành công');
    }
}