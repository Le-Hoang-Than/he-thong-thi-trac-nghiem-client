<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;

Route::get('/', function () {
    if (session()->has('auth_token')) {
        return redirect('/exams');
    }
    return redirect('/login');
});
Route::post('/add-user', [UserController::class, 'store']);

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



// Exam routes
Route::middleware(['web'])->group(function () {
    Route::get('/exams', [ExamController::class, 'index'])->name('exams');
    Route::get('/results', [ExamController::class, 'allResults'])->name('results');
    Route::get('/exams/{id}/status', [ExamController::class, 'status'])->name('exam.status');
    Route::get('/exams/{id}', [ExamController::class, 'show'])->name('exam.show');
    Route::post('/exams/{rid}/submit', [ExamController::class, 'submit'])->name('exam.submit');
    Route::get('/exam-result/{rid}', [ExamController::class, 'result'])->name('exam.result');
    Route::post('/exams/save-answer', [ExamController::class, 'saveAnswer'])->name('exam.save-answer');
    Route::post('/api/mark-retry/{rid}', [ExamController::class, 'markRetry'])->name('exam.mark-retry');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
});
