@extends('layouts.app')

@section('content')
    <main>
        <section class="ticket-booking-section">
            <h1>Бронирование успешно завершено!</h1>

            <div class="success-message">
                <p>Спасибо за ваше бронирование! Ваши билеты были успешно оформлены.</p>
                <p><strong>Информация о вашем заказе:</strong></p>
                <ul>
{{--                    @foreach(session('tickets') as $ticket)--}}
{{--                        <li>--}}
{{--                            Место: {{ $ticket['seat_id'] }} ---}}
{{--                            ФИО: {{ $ticket['passenger_name'] }} ---}}
{{--                            Телефон: {{ $ticket['passenger_phone'] }}--}}
{{--                        </li>--}}
{{--                    @endforeach--}}
                </ul>
                <p><strong>Способ оплаты:</strong> {{ session('payment_method') }}</p>
                <p>Если у вас возникли вопросы, пожалуйста, свяжитесь с нашей службой поддержки.</p>
            </div>

            <div class="navigation">
                <a href="{{ route('home') }}">Вернуться на главную страницу</a>
            </div>
        </section>
    </main>
@endsection
