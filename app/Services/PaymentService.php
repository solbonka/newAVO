<?php

namespace App\Services;
use YooKassa\Client;

class PaymentService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function createPayment(Plan $plan, int $chatId): string
    {
        $client = $this->getClient();
        $payment = $client->createPayment([
            'amount' => [
                'value' => $plan->price,
                'currency' => 'RUB',
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => 'https://t.me/OuVPNbot',
            ],
            'capture' => true,
            'description' => 'Оплата по тарифу: ' . $plan->name,
            'metadata' => [
                'plan_id' => $plan->id,
                'chat_id' => $chatId,
            ]
        ], uniqid('', true));

        return $payment->getConfirmation()->getConfirmationUrl();
    }

    public function processPayment(PaymentInterface $payment): void
    {
        if (isset($payment->status) && $payment->status === 'succeeded') {
            if($payment->paid) {
                $this->telegramHandler->createSubscription($payment->metadata['plan_id'], $payment->metadata['chat_id']);
            }
        }
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
