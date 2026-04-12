<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $api;
    protected $apiUrl;

    public function __construct()
    {
        $this->api = config('app.base_api');
        $this->apiUrl = $this->api;
    }
}
