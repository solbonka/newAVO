<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ваши билеты</title>
</head>
<body>
<h1>Здравствуйте, {{ $user->name }}!</h1>
<p>Спасибо за покупку билетов. Ниже приведена информация о вашем заказе:</p>

<ul>
    @foreach($tickets as $ticket)
        <li><a href="{{ $ticket->ticketsUrl }}">Скачать билет {{ $loop->iteration }}</a></li>
    @endforeach
</ul>
@php
    if (count($tickets) === 1) {
        $linkText = 'ссылке';
    } else {
        $linkText = 'ссылкам';
    }
@endphp
<p>Или скачайте билеты по {{ $linkText }}</p>
<ul>
    @foreach($tickets as $ticket)
        <li>{{ $ticket->ticketsUrl }}</li>
    @endforeach
</ul>

<p>Если у вас есть вопросы, не стесняйтесь обращаться к нам.</p>

<p>С наилучшими пожеланиями,<br>Команда вашего сервиса</p>
</body>
</html>
