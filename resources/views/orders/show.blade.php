@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Заказ №{{ $order->id }}</h1>
        <p><strong>Статус:</strong> {{ $order->getTranslatedStatus() }}</p>
        <p><strong>Дата создания:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
        <p><strong>Подробности:</strong></p>
        @if($order->tickets->isEmpty())
            <p>Нет купленных билетов для этого заказа.</p>
        @else
            <div class="col">
                @foreach ($order->tickets as $ticket)
                    <div class="col-md-10 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Билет №{{ $ticket->id }}</h5>
                                <p class="card-text"><strong>Статус:</strong> {{ $ticket->getTranslatedStatus() }}</p>
                                <p class="card-text"><strong>Пассажир:</strong> {{ $ticket->user->name }}</p>
                                <p class="card-text"><strong>Маршрут:</strong> {{ $ticket->route_name }}</p>
                                <p class="card-text"><strong>Место:</strong> {{ $ticket->place }}</p>
                                <p class="card-text"><strong>Дата поездки:</strong> {{ $ticket->departure_date }}</p>
                                @if($ticket->status === 'confirmed')
                                    <a href="{{ route('ticket.refund.view', $ticket->id) }}" class="btn btn-outline-warning mt-2">Оформить возврат</a>
                                @endif
                                <a href="{{ route('tickets.pdf', $ticket->id) }}" class="btn btn-primary mt-2">Посмотреть билет</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <a href="{{ route('orders') }}" class="btn btn-secondary">Назад к заказам</a>
    </div>
@endsection
