<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $routes = Cache::get('routes');

        // Если в кэше нет данных, обращаемся к API
        if (!$routes) {
            $password = 'mhnKShz29N';
            $username = 'biletavto';

            $routesResponse = Http::withQueryParameters([
                'LOGIN' => $username,
                'PASSWORD' => $password,
            ])->get('https://dev.avtovokzal-on-line.ru/api/routes/get.php');

            // Проверка успешности запроса
            if ($routesResponse->successful()) {
                // Декодируем ответ
                $decodedRoutes = json_decode($routesResponse->body());

                // Проверка на ошибки декодирования
                if (json_last_error() === JSON_ERROR_NONE) {
                    $routes = $decodedRoutes->ROUTES ?? [];

                    // Сохраняем полученные маршруты в кеш на 24 часа
                    Cache::put('routes', $routes, 1440);
                } else {
                    Log::error('Ошибка декодирования JSON: ' . json_last_error_msg());
                    $routes = []; // Если произошла ошибка, возвращаем пустой массив
                }
            } else {
                Log::error('Ошибка запроса к API маршрутов: ' . $routesResponse->status());
                $routes = []; // Если ошибка API, возвращаем пустой массив
            }
        }

        //$routes = [];
        return view('home.index', ['routes' => $routes]);
    }
}
