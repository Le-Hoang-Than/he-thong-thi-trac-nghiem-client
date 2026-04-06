<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
   public function index()
{
    try {
        $response = Http::get('http://127.0.0.1:8000/api/users'); 
        
        if ($response->successful()) {
            $data = $response->json();
            $users = $data['data'] ?? $data ?? [];
        } else {
            $users = [];
        }
    } catch (\Exception $e) {
        $users = [];
    }
    
    return view('users', compact('users'));
}
    {
      
        $response = Http::withoutVerifying()->get(env('BASE_API') . '/test-users');
        
        $users = $response->json() ?? [];

        return view('users', compact('users'));
    }

    public function store(Request $request)
    {
        
        Http::withoutVerifying()->post(env('BASE_API') . '/users', [
            'name' => $request->name
        ]);

    return redirect('/');
}

public function profile()
{
    // Check authentication
    if (!session()->has('auth_token') || !session()->has('user')) {
        return redirect('/login')->with('error', 'Vui lòng đăng nhập');
    }

    $user = session('user');

    return view('profile', compact('user'));
}

public function updateProfile(\Illuminate\Http\Request $request)
{
    // Check authentication
    if (!session()->has('auth_token') || !session()->has('user')) {
        return redirect('/login')->with('error', 'Vui lòng đăng nhập');
    }

    // Get current user from session
    $user = session('user');
    
    // Update user information from form
    $user['first_name'] = $request->input('first_name', $user['first_name'] ?? '');
    $user['last_name'] = $request->input('last_name', $user['last_name'] ?? '');
    $user['email'] = $request->input('email', $user['email'] ?? '');
    $user['phone'] = $request->input('phone', $user['phone'] ?? '');
    
    // Update session
    session(['user' => $user]);
    
    return redirect('/profile')->with('success', 'Thông tin cá nhân đã được cập nhật thành công');
}
        return redirect('/');
    }
}