<?php

namespace App\Http\Requests\Jobs;

use App\Models\Order;
use App\Notifications\TicketsMailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// Например, класс для почтового уведомления

class SendTicketsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Order $order;

    /**
     * Создайте новый экземпляр задания.
     *
     * @param Order $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Выполните задание.
     *
     * @return void
     */
    public function handle()
    {
        $this->order->user->first()->notify(new TicketsMailNotification($this->order));
    }
}
