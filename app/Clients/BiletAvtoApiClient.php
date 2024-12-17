<?php

namespace App\Clients;

use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BiletAvtoApiClient
{
    private string $baseUrl;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->baseUrl = 'https://api.biletavto.ru/biletavto/v3';
        $this->username = 'avtovokzaly';
        $this->password = 'AvtoVokzal';
    }

    // Авторизация основана на OAuth 2.0 Bearer Token.
    // Время жизни токена - 24 часа.
    // При обращении к любому методу API, клиент должен передавать полученный токен в заголовке запроса.
    // При обращении к методам API требуется добавлять к заголовку HTTP запроса Authorization: Bearer %токен%
    public function getToken()
    {
        return Cache::remember('api_token', 1440, function () {
            $response = Http::post("{$this->baseUrl}/token", [
                'username' => $this->username,
                'password' => $this->password,
            ]);

            return json_decode($response->body(), true)["token"];
        });
    }

    /**
     * @throws ConnectionException
     */
    // Метод получения всех станций, на которые осуществляются перевозки
    public function getStations()
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/station");

        return json_decode($response->body(), true);
    }

    /**
     * @throws ConnectionException
     */
    // Метод получения всех маршрутов, на которые осуществляются перевозки
    public function getAllRoutes()
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/route/all");

        return json_decode($response->body(), true);
    }

    /**
     * @throws ConnectionException
     */
    // Метод получения всех рейсов, на которые осуществляются перевозки (включая промежуточные остановки)
    public function getRoadmap()
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/route/roadmap");

        return json_decode($response->body(), true);
    }

    /**
     * @throws ConnectionException
     */
    // Метод получения маршрутов и информации о перевозчиках
    public function getRouteInfo()
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/route/info");

        return json_decode($response->body(), true);
    }

    /**
     * @throws ConnectionException
     */
    // Метод поиска конкретного маршрута с определенными параметрами станции отправления,
    // станции прибытия и даты отправления
    public function search(string $departure, string $arrival, string $departureDate)
    {
        $token = $this->getToken();
        $formattedDate = Carbon::parse($departureDate)->format('y-m-d');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/route/search", [
            'departure' => $departure,
            'arrival' => $arrival,
            'departureDate' => $formattedDate,
        ]);

        return json_decode($response->body(), true);
    }

    /**
     * @throws ConnectionException
     */
    // Метод обновления информации о конкретном рейсе с параметрами ID рейса и ID цены
    public function getRide(int $rideId, int $priceId)
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/route/update", [
            'rideId' => $rideId,
            'priceId' => $priceId,
        ]);

        return json_decode($response->body(), true);
    }

    /**
     * @throws ConnectionException
     */
    // Метод бронирования билета. После выполнения метода, выбранные места считаются забронированными.
    // Если их не выкупить, то через 20 минут бронь снимается и место считается доступным для продажи
    public function book(int $rideId, int $priceId, string $email, string $phone, array $persons)
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post("{$this->baseUrl}/ticket/book", [
            'rideId' => $rideId,
            'priceId' => $priceId,
            'email' => $email,
            'phone' => $phone,
            'person' => $persons,
        ]);

        return json_decode($response->body(), true);
    }

    /**
     * @throws ConnectionException
     */
    // Метод отмены забронированного билета
    public function cancelTicket(int $ticketId)
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/order/cancel", [
            'ticketId' => $ticketId,
        ]);

        return json_decode($response->body(), true);
    }

    /**
     * @throws ConnectionException
     */
    // Купить билет возможно только если он был ранее забронирован.
    // Данное условие необходимо для избежания единовременной покупки одного и того же места разными клиентами
    public function buy(int $operationId, array $tickets)
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post("{$this->baseUrl}/order/buy", [
            'operation_id' => $operationId,
            'ticket' => $tickets,
        ]);

        return json_decode($response->body(), true);
    }

    /**
     * @throws ConnectionException
     */
    // Метод отмены забронированного билета
    public function refund(int $ticketId)
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/ticket/refund", [
            'ticketId' => $ticketId,
        ]);

        return json_decode($response->body(), true);
    }

    /**
     * @throws ConnectionException
     */
    // Метод отмены забронированного билета
    public function status(int $ticketId)
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/ticket/status", [
            'ticketId' => $ticketId,
        ]);

        return json_decode($response->body(), true);
    }
}
