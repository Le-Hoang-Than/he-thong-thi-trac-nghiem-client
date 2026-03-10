<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index()
    {
        $response = Http::get($this->api . '/users');

        $users = $response->json();

        return view('users', compact('users'));
    }
    public function store(Request $request)
    {
        Http::post($this->api . '/users', [
            'name' => $request->name
        ]);

        return redirect('/');
    }
}
