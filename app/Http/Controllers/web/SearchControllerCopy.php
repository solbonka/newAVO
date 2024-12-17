<?php

namespace App\Http\Controllers\web;

use Carbon\Carbon;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SearchControllerCopy
{
    /**
     * @throws \DateMalformedStringException
     * @throws ConnectionException
     */
    public function index(Request $request)
    {
        // Валидация входящих данных
        $request->validate([
            'from' => 'required|string|max:255',
            'to' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        // Получение данных из формы
        $from = $request->input('from');
        $to = $request->input('to');
        $to = mb_convert_case(mb_strtolower($to), MB_CASE_TITLE);
        $date = $request->input('date');
//        $timestamp = strtotime($date);
//        if ($timestamp !== false) {
//            $date = date('d.m.Y', $timestamp);
//        } else {
//            return back()->withErrors(['message' => 'Некорректный формат даты.']);
//        }
//
//        $username = 'biletavto';
//        $password = 'mhnKShz29N';
//
//        try {
//            $stationsResponse = Http::withQueryParameters([
//                    'LOGIN' => $username,
//                    'PASSWORD' => $password
//                ])->get('https://dev.avtovokzal-on-line.ru/api/stations/get.php');
//            $stations = json_decode($stationsResponse->body())->STATIONS ?? [];
//        } catch (ConnectionException $e) {
//            Log::info($e->getMessage() . $e->getLine());
//            return view('home.index')->with('error', 'Произошла ошибка');
//        }
//
//        $response = [];
//        $excludedStationIds = [496, 511, 459];
//
//        foreach ($stations as $station) {
//            $stationName = mb_convert_case(trim(preg_replace("/\([^)]+\)/", "", $station->NAME)), MB_CASE_TITLE, 'UTF-8');
//            preg_match('/\((.*?)\)/', $station->NAME, $address);
//            $address = !empty($address) ? mb_convert_case($address[1], MB_CASE_TITLE, 'UTF-8') : 'На трассе';
//
//            // Специальный случай для Улан-Удэ
//            if ($stationName === 'Улан-Удэ') {
//                $address = 'Автовокзал, ул. Советская, 1б';
//            }
//
//            // Добавляем в ответ, если ID не в исключениях
//            if (!in_array($station->ID, $excludedStationIds)) {
//                $response[] = [
//                    'station_id' => $station->ID,
//                    'station_name' => $stationName,
//                    'station_address' => $address,
//                    'region_id' => $station->REGION_ID,
//                    'region_name' => $station->REGION_NAME
//                ];
//            }
//        }
//
//        $departureStation = collect($response)->firstWhere('station_name', $from);
//        $arrivalStation = collect($response)->firstWhere('station_name', $to);
//
//        if (!$departureStation || !$arrivalStation) {
//            return back()->withErrors(['message' => 'Одна из указанных станций не найдена.']);
//        }
//
//        $routesResponse = Http::withQueryParameters([
//            'LOGIN' => $username,
//            'PASSWORD' => $password,
//        ])->get('https://dev.avtovokzal-on-line.ru/api/routes/get.php');
//
//        $routes = json_decode($routesResponse->body())->ROUTES ?? [];
//
//        $filteredRoutes = [];
//        foreach ($routes as $route) {
//            if ($route->PROPERTIES->DEPARTURE_STATION == $departureStation['station_id'] && in_array($arrivalStation['station_id'], $route->PROPERTIES->ARRIVAL_STATION)) {
//                $filteredRoutes[] = $route;
//            }
//        }
//
//        $sheets = [];
//        $prices = [];
//        $drivingTimes = [];
//// Проходим по маршрутам
//        foreach ($filteredRoutes as $route) {
//            // Проверяем, есть ли известная станция в маршруте
//            if (in_array($arrivalStation['station_id'], $route->PROPERTIES->ARRIVAL_STATION)) {
//                // Находим индекс станции
//                $index = array_search($arrivalStation['station_id'], $route->PROPERTIES->ARRIVAL_STATION);
//
//                // Получаем цену по индексу
//                if ($index !== false && isset($route->PROPERTIES->PRICE[$index])) {
//                    $prices[$route->ID] = $route->PROPERTIES->PRICE[$index]; // Добавляем цену в массив
//                    $drivingTimes[$route->ID] = $route->PROPERTIES->DRIVING_TIME[$index];
//                }
//            }
//        }
//
//        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Irkutsk'));
//
//        $currentDateTime->modify('+5400 seconds');
//        $saleDateTime = $currentDateTime->getTimestamp();
//
//        foreach ($filteredRoutes as $route) {
//            try {
//                $sheetsResponse = Http::withQueryParameters([
//                    'LOGIN' => $username,
//                    'PASSWORD' => $password,
//                    'ROUTE_ID' => $route->ID,
//                    'DATE_FROM' => $date,
//                    'DATE_TO' => $date,
//                ])->get('https://dev.avtovokzal-on-line.ru/api/sheets/get.php');
//            } catch (ConnectionException $e) {
//                Log::info($e->getMessage());
//            }
//            $sheetsResponseData = json_decode($sheetsResponse->body())->SHEETS ?? [];
//
//            foreach ($sheetsResponseData as $sheet) {
//                $departureDateTime = new DateTime($sheet->PROPERTIES->DEPARTURE_DATETIME, new DateTimeZone('Asia/Irkutsk'));
//                list($hours, $minutes) = explode(':', $drivingTimes[$route->ID]);
//                $drivingInterval = new DateInterval("PT{$hours}H{$minutes}M");
//                $arrivalDateTime = clone $departureDateTime;
//                $arrivalDateTime->add($drivingInterval);
//                $arrivalTime = $arrivalDateTime->format('H:i');
//                if ($sheet->PROPERTIES->STATUS_CODE == "active" and $departureDateTime->getTimestamp() >= $saleDateTime) {
//                    $sheets[] = [
//                        'id' => $sheet->ID,
//                        'name' => $sheet->NAME,
//                        'departure_station' => $departureStation['station_name'],
//                        'departure_date' => Carbon::parse($sheet->PROPERTIES->DEPARTURE_DATETIME)->format('d.m.Y'),
//                        'departure_time' => Carbon::parse($sheet->PROPERTIES->DEPARTURE_DATETIME)->format('H:i'),
//                        'departure_address' => $departureStation['station_address'],
//                        'arrival_station' => $arrivalStation['station_name'],
//                        'arrival_date' => Carbon::parse($sheet->PROPERTIES->DEPARTURE_DATETIME)->format('d.m.Y'),
//                        'arrival_time' => $arrivalTime,
//                        'arrival_address' => $arrivalStation['station_address'],
//                        'status' => $sheet->PROPERTIES->STATUS,
//                        'carrier' => $sheet->PROPERTIES->CARRIER_PUBLIC_NAME,
//                        'carrier_tin' => $sheet->PROPERTIES->CARRIER_TIN,
//                        'route' => $route->NAME,
//                        'route_id' => $route->ID,
//                        'price' => $prices[$route->ID],
//                        'arrival_station_id' => $arrivalStation['station_id'],
//                    ];
//                }
//            }
//        }
//        usort($sheets, function($a, $b) {
//            return strcmp($a['departure_time'], $b['departure_time']);
//        });
        $sheets = [
            [
                "id" => 893264,
                "name" => "260: УЛАН-УДЭ - КЯХТА 23.11.2024 07:30",
                "departure_station" => "Улан-Удэ",
                "departure_date" => "23.11.2024",
                "departure_time" => "07:30",
                "departure_address" => "Автовокзал, ул. Советская, 1б",
                "arrival_station" => "Кяхта",
                "arrival_date" => "23.11.2024",
                "arrival_time" => "11:00",
                "arrival_address" => "Автовокзал",
                "status" => "Идёт продажа билетов",
                "carrier" => "ООО \"Южный\"",
                "carrier_tin" => "0323378329",
                "route" => "260",
                "route_id" => 267,
                "price" => 700,
                "arrival_station_id" => 366
              ],
           [
               "id" => 893173,
               "name" => "260: УЛАН-УДЭ - КЯХТА 23.11.2024 09:00",
               "departure_station" => "Улан-Удэ",
               "departure_date" => "23.11.2024",
               "departure_time" => "09:00",
               "departure_address" => "Автовокзал, ул. Советская, 1б",
               "arrival_station" => "Кяхта",
               "arrival_date" => "23.11.2024",
               "arrival_time" => "12:30",
               "arrival_address" => "Автовокзал",
               "status" => "Идёт продажа билетов",
               "carrier" => "ООО \"Южный\"",
               "carrier_tin" => "0323378329",
               "route" => "260",
               "route_id" => 267,
               "price" => 700,
               "arrival_station_id" => 366
           ],
           [
               "id" => 893241,
               "name" => "260: УЛАН-УДЭ - КЯХТА 23.11.2024 10:00",
               "departure_station" => "Улан-Удэ",
               "departure_date" => "23.11.2024",
               "departure_time" => "10:00",
               "departure_address" => "Автовокзал, ул. Советская, 1б",
               "arrival_station" => "Кяхта",
               "arrival_date" => "23.11.2024",
               "arrival_time" => "13:30",
               "arrival_address" => "Автовокзал",
               "status" => "Идёт продажа билетов",
               "carrier" => "ООО \"Южный\"",
               "carrier_tin" => "0323378329",
               "route" => "260",
               "route_id" => 267,
               "price" => 700,
               "arrival_station_id" => 366
           ],
           [
               "id" => 893242,
               "name" => "260: УЛАН-УДЭ - КЯХТА 23.11.2024 11:00",
               "departure_station" => "Улан-Удэ",
               "departure_date" => "23.11.2024",
               "departure_time" => "11:00",
               "departure_address" => "Автовокзал, ул. Советская, 1б",
               "arrival_station" => "Кяхта",
               "arrival_date" => "23.11.2024",
               "arrival_time" => "14:30",
               "arrival_address" => "Автовокзал",
               "status" => "Идёт продажа билетов",
               "carrier" => "ООО \"Южный\"",
               "carrier_tin" => "0323378329",
               "route" => "260",
               "route_id" => 267,
               "price" => 700,
               "arrival_station_id" => 366
           ],
           [
               "id" => 893113,
               "name" => "260: УЛАН-УДЭ - КЯХТА 23.11.2024 12:00",
               "departure_station" => "Улан-Удэ",
               "departure_date" => "23.11.2024",
               "departure_time" => "12:00",
               "departure_address" => "Автовокзал, ул. Советская, 1б",
               "arrival_station" => "Кяхта",
               "arrival_date" => "23.11.2024",
               "arrival_time" => "15:30",
               "arrival_address" => "Автовокзал",
               "status" => "Идёт продажа билетов",
               "carrier" => "ООО \"Южный\"",
               "carrier_tin" => "0323378329",
               "route" => "260",
               "route_id" => 267,
               "price" => 700,
               "arrival_station_id" => 366
           ],
           [
               "id" => 893365,
               "name" => "260: УЛАН-УДЭ - КЯХТА 23.11.2024 13:00",
               "departure_station" => "Улан-Удэ",
               "departure_date" => "23.11.2024",
               "departure_time" => "13:00",
               "departure_address" => "Автовокзал, ул. Советская, 1б",
               "arrival_station" => "Кяхта",
               "arrival_date" => "23.11.2024",
               "arrival_time" => "16:30",
               "arrival_address" => "Автовокзал",
               "status" => "Идёт продажа билетов",
               "carrier" => "ООО \"Южный\"",
               "carrier_tin" => "0323378329",
               "route" => "260",
               "route_id" => 267,
               "price" => 700,
               "arrival_station_id" => 366
           ],
           [
               "id" => 893364,
               "name" => "260: УЛАН-УДЭ - КЯХТА 23.11.2024 14:00",
               "departure_station" => "Улан-Удэ",
               "departure_date" => "23.11.2024",
               "departure_time" => "14:00",
               "departure_address" => "Автовокзал, ул. Советская, 1б",
               "arrival_station" => "Кяхта",
               "arrival_date" => "23.11.2024",
               "arrival_time" => "17:30",
               "arrival_address" => "Автовокзал",
               "status" => "Идёт продажа билетов",
               "carrier" => "ООО \"Южный\"",
               "carrier_tin" => "0323378329",
               "route" => "260",
               "route_id" => 267,
               "price" => 700,
               "arrival_station_id" => 366
           ],
           [
               "id" => 893114,
               "name" => "260: УЛАН-УДЭ - КЯХТА 23.11.2024 15:00",
               "departure_station" => "Улан-Удэ",
               "departure_date" => "23.11.2024",
               "departure_time" => "15:00",
               "departure_address" => "Автовокзал, ул. Советская, 1б",
               "arrival_station" => "Кяхта",
               "arrival_date" => "23.11.2024",
               "arrival_time" => "18:30",
               "arrival_address" => "Автовокзал",
               "status" => "Идёт продажа билетов",
               "carrier" => "ООО \"Южный\"",
               "carrier_tin" => "0323378329",
               "route" => "260",
               "route_id" => 267,
               "price" => 700,
               "arrival_station_id" => 366
           ],
           [
               "id" => 893186,
               "name" => "260: УЛАН-УДЭ - КЯХТА 23.11.2024 16:00",
               "departure_station" => "Улан-Удэ",
               "departure_date" => "23.11.2024",
               "departure_time" => "16:00",
               "departure_address" => "Автовокзал, ул. Советская, 1б",
               "arrival_station" => "Кяхта",
               "arrival_date" => "23.11.2024",
               "arrival_time" => "19:30",
               "arrival_address" => "Автовокзал",
               "status" => "Идёт продажа билетов",
               "carrier" => "ООО \"Южный\"",
               "carrier_tin" => "0323378329",
               "route" => "260",
               "route_id" => 267,
               "price" => 700,
               "arrival_station_id" => 366
           ],
           [
               "id" => 893187,
               "name" => "260: УЛАН-УДЭ - КЯХТА 23.11.2024 17:00",
               "departure_station" => "Улан-Удэ",
               "departure_date" => "23.11.2024",
               "departure_time" => "17:00",
               "departure_address" => "Автовокзал, ул. Советская, 1б",
               "arrival_station" => "Кяхта",
               "arrival_date" => "23.11.2024",
               "arrival_time" => "20:30",
               "arrival_address" => "Автовокзал",
               "status" => "Идёт продажа билетов",
               "carrier" => "ООО \"Южный\"",
               "carrier_tin" => "0323378329",
               "route" => "260",
               "route_id" => 267,
               "price" => 700,
               "arrival_station_id" => 366
        ]
        ];

        return view('search.results', [
            'sheets' => $sheets,
            'from' => $from,
            'to' => $to,
            'date' => $date,
        ]);
    }
}
