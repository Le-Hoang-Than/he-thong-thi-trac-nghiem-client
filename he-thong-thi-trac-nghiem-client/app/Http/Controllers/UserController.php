<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    protected $api = 'http://127.0.0.1:8000/api';
   public function index()
{
    $response = Http::get('http://127.0.0.1:8000/api/users'); 

    $users = $response->json();
    return view('users', compact('users'));
}

   public function store(Request $request)
{
    Http::post('http://127.0.0.1:8000/api/users', [
        'name' => $request->name
    ]);

    return redirect('/');
}
}