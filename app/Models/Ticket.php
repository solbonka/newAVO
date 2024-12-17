<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'price_id',
        'ba_ticket_id',
        'user_id',
        'passenger_phone',
        'place',
        'price',
        'departure_station',
        'departure_date',
        'departure_time',
        'departure_address',
        'arrival_station',
        'arrival_date',
        'arrival_time',
        'arrival_address',
        'route_name',
        'type',
        'order_id',
        'ticket_url',
        'status',
        'refunded_amount'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getTranslatedStatus(): string
    {
        $statusTranslations = [
            'pending' => 'Ожидает',
            'confirmed' => 'Подтвержден',
            'canceled' => 'Отменен',
            'refunded' => 'Возвращен',
        ];

        return $statusTranslations[$this->status];
    }
}
