<?php

namespace App\Http\Controllers\web;

use App\Clients\BiletAvtoApiClient;
use App\Http\Controllers\Controller;
use App\Http\Requests\Jobs\SendTicketsJob;
use App\Models\Order;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;
use YooKassa\Model\Notification\NotificationCanceled;
use YooKassa\Model\Notification\NotificationRefundSucceeded;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\Payment\PaymentInterface;
use YooKassa\Request\Payments\CancelResponse;
use YooKassa\Request\Payments\CreateCaptureResponse;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);

        // Определяем тип события
        $eventType = $requestData['event'] ?? null;
        return match ($eventType) {
            'payment.succeeded' => $this->handlePaymentSucceeded($requestData),
            'payment.waiting_for_capture' => $this->handleWaitingForCapture($requestData),
            'payment.canceled' => $this->handlePaymentCanceled($requestData),
            'refund.succeeded' => $this->handleRefundSucceeded($requestData),
            default => response()->json(['status' => 'error', 'message' => 'Unknown event type'], 400),
        };
    }

    /**
     * @throws ConnectionException
     */
    protected function handlePaymentSucceeded(array $requestData)
    {
        $notification = new NotificationSucceeded($requestData);
        $yookassaPayment = $notification->getObject();

        // Получаем заказ
        $order = Order::where('id', $yookassaPayment->getMetadata()['order_id'])->first();
        if (!$order) {
            Log::error('Order not found', ['order_id' => $yookassaPayment->getMetadata()['order_id']]);
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // Обновляем статус платежа и заказа
        DB::beginTransaction();
        try {
            $payment = $order->payment()->first();
            if (!$payment) {
                Log::error('Payment not found for the order');
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            $payment->status = $yookassaPayment->status;
            $payment->save();
            $order->status = 'confirmed';
            $order->save();

            $client = new BiletAvtoApiClient();
            $ticketsData = [];
            foreach ($order->tickets()->get() as $ticket) {
                $ticketsData[] = [
                    'ticket_id' => $ticket->ba_ticket_id,
                    'agentPriceApi' => $ticket->price,
                ];
            }

            $response = $client->buy($order->ba_operation_id, $ticketsData);

            if (isset($response["success"]) && $response["success"] === true) {
                if (!empty($response["data"])) {
                    foreach ($response["data"] as $data) {
                        $ticket = $order->tickets()->where('ba_ticket_id', $data["ticketId"])->first();
                        if ($ticket) {
                            $ticket->status = 'confirmed';
                            $ticket->ticket_url = $data["url_ticket"];
                            $ticket->save();
                        }
                    }
                } else {
                    Log::error('No ticket data found in the response', ['response' => $response]);
                }
            } else {
                Log::error('BiletAvto buying failed or invalid response', ['response' => $response]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Transaction failed'], 500);
        }

        SendTicketsJob::dispatch($order)->onQueue('tickets-send');

        return response()->json([
            'status' => 'success',
            'order' => $order->toArray(),
            'payment' => $payment->toArray(),
            'response' => $response
        ]);
    }

    protected function handleWaitingForCapture(array $requestData)
    {
        $notification = new NotificationWaitingForCapture($requestData);
        $yookassaPayment = $notification->getObject();

        // Получаем заказ
        $order = Order::where('id', $yookassaPayment->getMetadata()['order_id'])->first();
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // Проверяем время создания заказа
        $orderCreatedAt = $order->created_at; // Время создания заказа
        $currentTime = now(); // Текущее время

        if ($currentTime->diffInSeconds($orderCreatedAt, true) > 20) {
            $response = $this->cancelPayment($yookassaPayment->id);
        } else {
            $response = $this->capturePayment($yookassaPayment);
        }

        return response()->json([
            'status' => 'success',
            'order' => $order,
            'diffInSeconds' => $currentTime->diffInSeconds($orderCreatedAt, true),
            'response' => $response,
        ]);
    }

    /**
     * @throws ConnectionException
     */
    protected function handlePaymentCanceled(array $requestData)
    {
        $notification = new NotificationCanceled($requestData);
        $yookassaPayment = $notification->getObject();

        // Получаем заказ
        $order = Order::where('id', $yookassaPayment->getMetadata()['order_id'])->first();
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $order->status = 'canceled';
        $payment = $order->payment()->first();
        $payment->status = $yookassaPayment->status;
        $payment->save();

        $client = new BiletAvtoApiClient();

        foreach ($order->tickets()->get() as $ticket) {
            $response = $client->cancelTicket($ticket->ba_ticket_id);

            if ($response->json()['success']['success_msg'] === 'Операция выполнена успешно') {
                $ticket->update(['status' => 'canceled']);
            } else {
                return response()
                    ->json([
                        'status' => 'error',
                        'message' => [
                            'Ошибка!', 'Не удалось отменить билет №' . $ticket->id . '. Непредвиденная ошибка!'
                        ]
                    ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * @throws ConnectionException
     */
    protected function handleRefundSucceeded(array $requestData): JsonResponse
    {
        $notification = new NotificationRefundSucceeded($requestData);
        $refund = $notification->getObject();
        $ticketId = str_replace('Возврат билета №', '', $refund->getDescription());
        $ticket = Ticket::findOrFail($ticketId);
        $order = $ticket->order()->first();
        $payment = $order->payment()->first();


        $client = new BiletAvtoApiClient();

        $response = $client->refund($ticket->ba_ticket_id);

        Log::info(json_encode($response));
        $payment->status = 'refunded';
        $payment->save();
        $order->status = 'refunded';
        $order->save();
        $ticket->status = 'refunded';
        $ticket->refunded_amount = $refund->getAmount()->getValue();
        $ticket->save();

        return response()->json(['status' => 'success', 'refund' => $refund, ]);
    }

    protected function cancelPayment(string $yookassaPaymentId): Exception|CancelResponse|null
    {
        $client = new Client();
        $client->setAuth('329678', 'test_KxzlNDscT3DPb4RgGecffe0kkSB5B4r2irPDL3m_2rI');
        $idempotenceKey = uniqid('', true);
        try {
            $response = $client->cancelPayment($yookassaPaymentId, $idempotenceKey);
        } catch (Exception $e) {
            $response = $e;
        }

        return $response;
    }

    protected function capturePayment(PaymentInterface $yookassaPayment): Exception|CreateCaptureResponse|null
    {
        $client = new Client();
        $client->setAuth(config('services.yookassa.shopId'), config('services.yookassa.secretKey'));
        $idempotenceKey = uniqid('', true);
        try {
            $response = $client->capturePayment([
                    'amount' => $yookassaPayment->amount,
                ],
                $yookassaPayment->id,
                $idempotenceKey
            );
        } catch (Exception $e) {
            $response = $e;
        }

        return $response;
    }
}
