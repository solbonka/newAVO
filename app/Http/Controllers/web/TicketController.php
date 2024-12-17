<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;
use YooKassa\Model\CurrencyCode;
use YooKassa\Request\Refunds\CreateRefundRequest;
use setasign\Fpdi\Fpdi;

class TicketController extends Controller
{
    public function generatePdf($id)
    {
        // Находим билет по ID
        $ticket = Ticket::findOrFail($id);
        $order = $ticket->order()->first();

        // Получаем все билеты из заказа
        $tickets = $order->tickets()->where('status', 'confirmed')->get();

        // Собираем все ссылки на билеты
        $ticketUrls = $tickets->pluck('ticket_url')->toArray();

        // Создаём объект FPDI
        $pdf = new Fpdi();
        $tempFiles = [];

        try {
            foreach ($ticketUrls as $url) {
                // Загружаем PDF с внешнего URL
                $response = Http::get($url);

                if ($response->ok()) {
                    // Сохраняем временный файл
                    $tempFile = tempnam(sys_get_temp_dir(), 'ticket') . '.pdf';
                    file_put_contents($tempFile, $response->body());
                    $tempFiles[] = $tempFile;

                    // Добавляем PDF в общий документ
                    $pageCount = $pdf->setSourceFile($tempFile);

                    for ($i = 1; $i <= $pageCount; $i++) {
                        $tplId = $pdf->importPage($i);
                        $pdf->AddPage();
                        $pdf->useTemplate($tplId);
                    }
                } else {
                    throw new \Exception("Не удалось загрузить PDF по URL: $url");
                }
            }

            // Генерация объединённого PDF в браузер
            return response($pdf->Output('S', 'merged_tickets.pdf'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="merged_tickets.pdf"');

        } catch (\Exception $e) {
            // Удаляем временные файлы в случае ошибки
            foreach ($tempFiles as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }

            // Обрабатываем ошибку
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function refundView($id)
    {
        $ticket = Ticket::findOrFail($id);

        return view('orders.refund', ['ticket' => $ticket]);
    }

    public function refund(Request $request)
    {
        $ticket = Ticket::findOrFail($request->get('ticket_id'));
        $currentDateTime = now();

        $departureTime = Carbon::createFromFormat('Y-m-d H:i:s', $ticket->departure_date . ' ' . $ticket->departure_time);

        // Проверяем, прошло ли время отправления
        if ($currentDateTime->greaterThanOrEqualTo($departureTime)) {
            return redirect()->back()->withErrors([
                'Ошибка!', 'После отправления автобуса возврат билета невозможен.'
            ]);
        }
        $differenceInHours = $departureTime->diffInHours($currentDateTime, true);
        $differenceInMinutes = $departureTime->diffInMinutes($currentDateTime, true);

        $refund_amount = $ticket->price;
        if ($differenceInMinutes < 30) {
            // Менее чем за полчаса до отправления
            return redirect()->back()->withErrors([
                'Ошибка!', 'Менее, чем за полчаса до отправления автобуса возврат билета оформляется только через подачу письменного заявления на кассах автовокзала.'
            ]);
        } elseif ($differenceInHours < 2) {
            // Возврат билета в течение 2 часов до отправления
            $refund_amount -= $ticket->price * 0.15; // Удерживаем 15%
        } else {
            // Возврат билета более чем за 2 часа до отправления
            $refund_amount -= $ticket->price * 0.05; // Удерживаем 5%
        }

        // Проверяем, что сумма возврата положительная
        if ($refund_amount < 0) {
            $refund_amount = 0; // Если сумма меньше нуля, устанавливаем 0
        }

        $paymentId = $ticket->order->payment->yookassa_id;
        $client = new Client();
        $client->setAuth('329678', 'test_KxzlNDscT3DPb4RgGecffe0kkSB5B4r2irPDL3m_2rI');
        try {
            $refundBuilder = CreateRefundRequest::builder();
            $refundBuilder
                ->setPaymentId($paymentId)
                ->setAmount($refund_amount)
                ->setCurrency(CurrencyCode::RUB)
                ->setDescription('Возврат билета №' . $ticket->id)
                ->setReceiptItems([
                    [
                        'description' => 'Билет №' . $ticket->id,
                        'quantity' => '1.00',
                        'amount' => [
                            'value' => $refund_amount,
                            'currency' => CurrencyCode::RUB,
                        ],
                        'vat_code' => 2,
                        'payment_mode' => 'full_payment',
                        'payment_subject' => 'commodity',
                    ],
                ])
                ->setReceiptEmail($ticket->user->email)
                ->setTaxSystemCode(1);

            // Создаем объект запроса
            $request = $refundBuilder->build();

            $idempotenceKey = uniqid('', true);
            $response = $client->createRefund($request, $idempotenceKey);
            Log::info(json_encode($response));

        } catch (Exception $e) {

            $response = $e;
            Log::info(json_encode($response->getMessage()));
            return redirect()->back()->withErrors([
                'Ошибка!' => 'Что-то пошло не так!.'
            ]);
        }

        return redirect()->route('orders')->with(
            'success', 'Возврат успешно оформлен!.'
        );
    }
}
