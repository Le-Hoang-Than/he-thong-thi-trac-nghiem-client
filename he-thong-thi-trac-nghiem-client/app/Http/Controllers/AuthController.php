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
    $request->validate([
        'studentid' => 'required',
        'password' => 'required'
    ]);

    $studentId = strtoupper($request->studentid);
    $user = User::where('studentid', $studentId)->first();

   
    if(!$user){
        return back()->withErrors(['studentid' => 'Mã sinh viên không tồn tại'])->withInput();
    }

    if(md5($request->password) != $user->password){
        return back()->withErrors(['password' => 'Sai mật khẩu'])->withInput();
    }

    $token = bin2hex(random_bytes(32));
    $user->web_token = $token;
    $user->save();

    return redirect()->route('exams')->with('success', 'Đăng nhập thành công'); 
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
