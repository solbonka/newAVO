<?php

namespace App\Http\Controllers\web;

use Illuminate\Support\Facades\Http;

class RouteController
{
    public function index()
    {
        $username = 'biletavto';
        $password = 'mhnKShz29N';
        $routesResponse = Http::withQueryParameters([
            'LOGIN' => $username,
            'PASSWORD' => $password,
        ])->get('https://dev.avtovokzal-on-line.ru/api/routes/get.php');

        $routes = json_decode($routesResponse->body())->ROUTES ?? [];

        return view('routes.index', compact('routes'));
    }
}
