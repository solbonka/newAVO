<?php

namespace App\Http\Controllers\web;

use App\Clients\BiletAvtoApiClient;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SearchController
{
    /**
     * @throws \
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
        $timestamp = strtotime($date);
        if ($timestamp !== false) {
            $date = date('d.m.Y', $timestamp);
        } else {
            return back()->withErrors(['message' => 'Некорректный формат даты.']);
        }
        $client = new BiletAvtoApiClient();
        try {
            $result = $client->search($from, $to, $date);

            foreach ($result as $sheet) {
                $freePlaces = count($sheet['places']['freePlaces']);
                if ($freePlaces > 10) {
                    $freePlaces = 10;
                }
                $sheets[] = [
                    'id' => $sheet['rideId'],
                    'name' => $sheet['routeName'],
                    'departure_station' => $sheet['departureCity'],
                    'departure_date' => Carbon::parse($sheet['departureDate'])->format('d.m.Y'),
                    'departure_time' => Carbon::parse($sheet['departureTime'])->format('H:i'),
                    'departure_address' => $sheet['departureStation'],
                    'arrival_station' => $sheet['arrivalCity'],
                    'arrival_date' => Carbon::parse($sheet['arrivalDate'])->format('d.m.Y'),
                    'arrival_time' => Carbon::parse($sheet['arrivalTime'])->format('H:i'),
                    'arrival_address' => $sheet['arrivalStation'],
                    'status' => '',
                    'freePlaces' => $freePlaces,
                    'carrier' => $sheet['carrier'],
                    'carrier_tin' => '',
                    'price' => $sheet['price'],
                    'price_id' => $sheet['priceId'],
                ];
            }

            usort($sheets, function($a, $b) {
                return strcmp($a['departure_time'], $b['departure_time']);
            });

            return view('search.results', [
                'sheets' => $sheets,
                'from' => $from,
                'to' => $to,
                'date' => $date,
            ]);
        } catch (ConnectionException $e) {
            Log::info($e->getMessage() . $e->getLine());
            return view('home.index')->with('error', 'Произошла ошибка');
        }
    }
}
