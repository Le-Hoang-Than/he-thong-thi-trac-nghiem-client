<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(env('BASE_API_URL', 'https://he-thong-thi-trac-nghiem-service-lnup.onrender.com'), '/');
    }
}
