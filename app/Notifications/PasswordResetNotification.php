<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class PasswordResetNotification extends Notification
{
    use Queueable;

    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    // Определяем каналы доставки уведомления
    public function via($notifiable): array
    {
        return ['mail'];
    }

    // Подготавливаем представление уведомления для почты
    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Сброс пароля для ' . config('app.name'))
            ->greeting('Здравствуйте!')
            ->line('Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.')
            ->action('Сбросить пароль', $url)
            ->line('Если вы не запрашивали сброс пароля, просто проигнорируйте это сообщение.')
            ->salutation('С уважением, ' . config('app.name'));
    }

    // Генерация URL для сброса пароля
    protected function resetUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'password.reset',
            now()->addMinutes(60),
            [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]
        );
    }

    // Поддержка массивного представления уведомления (необязательно)
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
