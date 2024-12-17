<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketsMailNotification extends Notification
{
    public Order $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail']; // Выбираем канал отправки
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $user = $this->order->user()->first();
        $tickets = $this->order->tickets()->get();
        $message = (new MailMessage)
            ->subject('Ваши билеты')
            ->greeting('Здравствуйте, ' . $user->name . '!')
            ->line('Спасибо за покупку билетов. Ниже приведена информация о вашем заказе:');

        if (count($tickets) > 0) {
            // Добавляем ссылки на билеты
            foreach ($tickets as $ticket) {
                $message->line("[Скачать билет {$ticket->id}]({$ticket->ticketsUrl})");
            }
        } else {
            $message->line('Вы пока не приобрели билеты.');
        }

        $message->line('Если у вас есть вопросы, не стесняйтесь обращаться к нам.')
            ->salutation('С наилучшими пожеланиями, ' . config('app.name'));

        return $message;
    }
}
