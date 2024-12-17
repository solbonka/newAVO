<?php

namespace App\Http\Requests\Jobs;

use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Password;

class SendPasswordResetEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Создание нового экземпляра задания.
     *
     * @param  User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Выполнение задания.
     *
     * @return void
     */
    public function handle()
    {
        // Генерация токена
        $token = Password::getRepository()->create($this->user);

        // Уведомление
        $this->user->notify(new PasswordResetNotification($token));
    }
}
