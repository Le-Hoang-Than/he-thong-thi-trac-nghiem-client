<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// API Routes
Route::post('/api/register', [UserController::class, 'register']);
Route::post('/api/login', [UserController::class, 'login']);
Route::post('/api/logout', [UserController::class, 'logout']);

// React SPA - Catch all routes
Route::get('/{any?}', fn () => view('app'))->where('any', '.*');
