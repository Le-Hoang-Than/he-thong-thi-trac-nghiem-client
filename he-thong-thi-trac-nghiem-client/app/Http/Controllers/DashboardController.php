<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // Check authentication
        if (!session()->has('auth_token') || !session()->has('user')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $user = session('user');
        $token = session('auth_token');

        // Fetch user profile/exam data if needed
        try {
            // You can fetch more data from backend using the token
            // Example: get exam list, scores, etc.
            $dashboardData = [
                'user' => $user,
                'exams' => 0,
                'completed' => 0,
                'score' => 0,
            ];
        } catch (\Exception $e) {
            $dashboardData = [
                'user' => $user,
                'exams' => 0,
                'completed' => 0,
                'score' => 0,
            ];
        }

        return view('dashboard', compact('dashboardData'));
    }
}
