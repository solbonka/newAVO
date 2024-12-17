@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Оформление возврата</h1>

        @if (!$ticket)
            <p>У вас пока нет билетов.</p>
        @else
            <div class="col">
                    <div class="col-md-11 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <p>В случае отмены поездки действуют следующие Правила возврата:
                                    <ul>
                                        <li>
                                            Возврат билета более чем за 2 часа до отправления — удерживается 5% от стоимости проезда (сервисные сборы возврату не подлежат);
                                        </li>
                                        <li>
                                            Возврат билета в течение 2 часов до отправления — удерживается 15% от стоимости проезда (сервисные сборы возврату не подлежат).<br>
                                            <b>Внимание!</b> Менее, чем за полчаса до отправления автобуса возврат билета оформляется только через подачу письменного заявления на кассах автовокзала;
                                        </li>
                                        <li>
                                            В случае отмены рейса возвращается 100% от стоимости билета.
                                        </li>
                                        <li>
                                            Для возврата средств, нажмите кнопку "Возврат билета".

                                            Обращаем внимание на то, что возврат средств производится в течение 2-5 рабочих дней.
                                        </li>
                                    </ul>
                                    </p>
                                </div>
                                <h5 class="card-title">Билет №{{ $ticket->id }}</h5>
                                <p class="card-text"><strong>Статус:</strong> {{ $ticket->getTranslatedStatus() }}</p>
                                <p class="card-text"><strong>Пассажир:</strong> {{ $ticket->user->name }}</p>
                                <p class="card-text"><strong>Маршрут:</strong> {{ $ticket->route_name }}</p>
                                <p class="card-text"><strong>Место:</strong> {{ $ticket->place }}</p>
                                <p class="card-text"><strong>Дата поездки:</strong> {{ $ticket->departure_date }}</p>
                                <form action="{{ route('ticket.refund') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                    <button type="submit" class="btn btn-outline-danger">Возврат билета</button>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        @endif
    </div>
@endsection
