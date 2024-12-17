<?php

namespace App\Http\Controllers\web;

use App\Clients\BiletAvtoApiClient;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Ticket;
use Dompdf\Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use YooKassa\Client;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        // Найдем заказ по идентификатору
        $order = Order::with('tickets')->findOrFail($id);

        // Вернём представление с информацией о заказе
        return view('orders.show', compact('order'));
    }

    public function bookTickets(Request $request)
    {
        if (!$request->session()->get('data')) {
            return redirect()->route('home');
        }

        $requestData = $request->session()->get('data');
        $sheetId = $requestData['id'];
        $priceId = $requestData['price_id'];

        $client = new BiletAvtoApiClient();
        try {
            $result = $client->getRide($sheetId, $priceId)['data'][0];

            // Пример обработки полученных билетов
            $bookedSeats = $result['places']['occupied'];
            $countPlaces = $result['places']['countPlaces'];
            $seatMap = $this->generateSeatMap($countPlaces);

            return view('orders.booking', [
                'sheet' => $result,
                'countPlaces' => $countPlaces,
                'bookedSeats' => $bookedSeats,
                'seatMap' => $seatMap,
                'to' => $result['arrivalCity'],
                'data' => $requestData,
                'priceId' => $priceId,
            ]);
        } catch (ConnectionException $e) {
            Log::error($e->getMessage() . ' on line ' . $e->getLine());
            return view('home.index')->withErrors(['message' => 'Произошла ошибка при получении данных.']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return view('home.index')->withErrors(['message' => 'Произошла непредвиденная ошибка.']);
        }
    }

    public function prepareBookTicket(Request $request)
    {
        $request->session()->put($request->only('data'));

        return redirect()->route('book.tickets');
    }

    /**
     * @throws ValidationException
     */
    public function buyTickets(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required', // может быть не обязательно
            'sheet_id' => 'required|integer',
            'price_id' => 'required|integer',
            'total_price' => 'required|numeric',
            'selected_seats' => 'required|array',
            'selected_seats.*' => 'integer', // валидируем каждый элемент массива
            'email' => 'required|email',
            'phone' => 'required|regex:/^(\+?[0-9]{1,3})?(\(?\d{1,4}\)?)?[\d\- ]{5,15}$/',
            // Валидируем пассажиров
            'passenger' => 'required|array',
            'passenger.*.place' => 'required|integer', // Новое правило для места
            'passenger.*.firstname' => 'required|string|max:255',
            'passenger.*.name' => 'required|string|max:255',
            'passenger.*.lastname' => 'nullable|string|max:255', // lastname необязателен
            'passenger.*.gender' => 'required|in:male,female',
            'passenger.*.birthday' => 'required|date_format:Y-m-d',
            'passenger.*.document_type' => 'required|string',
            'passenger.*.docs_number' => 'required|string',
        ]);

        // Если валидация не прошла, возвращаем ошибку
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rideId = $request->sheet_id;
        $priceId = $request->price_id;
        $email = $request->email;
        $phone = $request->phone;
        $totalPrice = $request->total_price;

        $user = Auth::user();
        $order = Order::create([
            'user_id' => $user->id, // Используем идентификатор пользователя
            'total_price' => $totalPrice,
            'status' => 'pending', // Начальный статус
        ]);

        // Преобразуем массив пассажиров в нужный формат
        $passengers = array_map(function ($passenger) {
            if ($passenger['gender'] === 'male') {
                $genderId = 1;
            } elseif ($passenger['gender'] === 'female') {
                $genderId = 0;
            } else {
                return redirect()->back()->withErrors(['message' => 'Ошибка пола!']);
            }
            return [
                'place' => $passenger['place'],
                "passportNumber" => $passenger['docs_number'],
                'name' => $passenger['firstname'] . ' ' . $passenger['name'] . ' ' . ($passenger['lastname'] ?? ' '),
                'gender_id' => $genderId,
                'birthday' => $passenger['birthday'],
            ];
        }, $request->passenger);
        $client = new BiletAvtoApiClient();

        try {
            $result = $client->book($rideId, $priceId, $email, $phone, $passengers);

            if (isset($result['data']['operationId'])) {
                $order->ba_operation_id = $result['data']['operationId'];
                $order->save();
            }
            if (isset($result['data']['tickets'])) {
                foreach ($result['data']['tickets'] as $ticket) {
                    Ticket::create([
                        'ride_id' => $ticket['rideId'],
                        'price_id' => $priceId,
                        'ba_ticket_id' => $ticket['ticketId'],
                        'user_id' => $user->id, // Привязываем билет к пользователю
                        'passenger_phone' => $ticket['phone'],
                        'place' => $ticket['place'], // Используем поле 'place' вместо 'place_number'
                        'price' => $ticket['price'],
                        'departure_station' => $ticket['departureCity'],
                        'departure_date' => $ticket['departureDate'],
                        'departure_time' => $ticket['departureTime'],
                        'departure_address' => $ticket['departureStation'],
                        'arrival_station' => $ticket['arrivalCity'],
                        'arrival_date' => $ticket['arrivalDate'],
                        'arrival_time' => $ticket['arrivalTime'],
                        'arrival_address' => $ticket['arrivalStation'],
                        'route_name' => $ticket['routeName'],
                        'type' => "Полный",
                        'order_id' => $order->id,
//                        'ticket_url' => route('tickets.pdf', $this->id), // Здесь можно установить URL билета, если есть
                        'status' => 'booked',
                    ]);
                }
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount' => $totalPrice,
                    'method' => 'bank_card',
                    'status' => 'pending',
                ]);
                $yookassaClient = new Client();
                $yookassaClient->setAuth('329678', 'test_KxzlNDscT3DPb4RgGecffe0kkSB5B4r2irPDL3m_2rI');

                $ticketsInfo = [];
                foreach ($order->tickets()->get() as $ticket) {
                    $ticketsInfo[] = [
                        'description' => 'Билет №' . $ticket->id,
                        'quantity' => '1.00',
                        'amount' => [
                            'value' => $ticket->price,
                            'currency' => 'RUB'
                        ],
                        'vat_code' => '2',
                        'payment_mode' => 'full_payment',
                        'payment_subject' => 'commodity',
                    ];
                }
                // Создаем объект запроса

                $request = [
                    'amount' => [
                        'value' => $payment->amount,
                        'currency' => 'RUB',
                    ],
                    'confirmation' => [
                        'type' => 'redirect',
                        'locale' => 'ru_RU',
                        'return_url' => route('home'),
                    ],
                    'capture' => false,
                    'description' => 'Оплата заказа ' . $order->id,
                    'metadata' => [
                        'order_id' => $order->id,
                        'language' => 'ru',
                        'transaction_id' => $payment->id,
                    ],
                    'receipt' => [
                        'customer' => [
                            'email' => $user->email,
                            'phone' => $phone,
                        ],
                        'items' => $ticketsInfo,
                    ]
                ];
                $idempotenceKey = uniqid('', true);
                $response = $yookassaClient->createPayment($request, $idempotenceKey);

                //получаем confirmationUrl для дальнейшего редиректа
                $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();
                $payment->url = $confirmationUrl;
                $payment->yookassa_id = $response->getId();
                $payment->save();
                return redirect($confirmationUrl);
            } else {
                $order->delete();

                return redirect()
                    ->back()
                    ->withErrors(['message', 'Не удалось выполнить бронирование']);
            }
        } catch (Exception $e) {
            $responseErrors = [];
            foreach ($order->tickets()->get() as $ticket) {
                $result = $client->cancelTicket($ticket->avo_ticket_id);
                if ($result->json()['success'] === true) {
                    $ticket->update(['status' => 'canceled']);
                    Log::error($e);
                    $responseErrors[] = 'Не удалось забронировать место №' . $ticket->place;
                } else {
                    return redirect()
                        ->back()
                        ->withErrors(['Ошибка!', 'Не удалось отменить билет №' . $ticket->id . '. Непредвиденная ошибка!']);
                }
            }
            return redirect()
                ->back()
                ->withErrors(['Ошибка!',  implode(' ', $responseErrors), $e->getMessage()]);
        }
    }

    public function success()
    {
        return view('orders.success');
    }

    private function generateSeatMap(int $totalSeats)
    {
        $arr_place = [];
        $number_place = 1;
        $columns = 5; // Количество колонок в ряду
        $count_rows = ceil($totalSeats / ($columns - 1)); // Общее количество рядов

        // Заполняем места
        for ($i = 0; $i < $count_rows; $i++) {
            for ($j = 0; $j < $columns; $j++) {
                if ($j == 2) {
                    $arr_place[$i][$j] = 0;
                } else {
                    $arr_place[$i][$j] = $number_place <= $totalSeats ? $number_place : null;
                    $number_place++;
                }
            }
        }

        return $arr_place;
    }
}
