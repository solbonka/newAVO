<?php

namespace App\Http\Controllers\web;

use Illuminate\Support\Facades\Http;

class SchedulesController
{
    public function index()
    {
        $username = 'biletavto';
        $password = 'mhnKShz29N';
        $stationsResponse = Http::withQueryParameters([
            'LOGIN' => $username,
            'PASSWORD' => $password
        ])->get('https://dev.avtovokzal-on-line.ru/api/stations/get.php');
        $stations = json_decode($stationsResponse->body())->STATIONS ?? [];
        $routesResponse = Http::withQueryParameters([
            'LOGIN' => $username,
            'PASSWORD' => $password,
        ])->get('https://dev.avtovokzal-on-line.ru/api/routes/get.php');

        $routes = json_decode($routesResponse->body())->ROUTES ?? [];
        //dd($routes);

        return view('schedules.index', compact('stations', 'routes'));
    }
}
