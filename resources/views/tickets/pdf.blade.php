<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Билет</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; margin-top: 10px; border-collapse: collapse; }
        th, td { padding: 4px; text-align: left; }
        th { background-color: #c0c0c0; }
        .small { font-size: 10px; }
    </style>
</head>
<body>
@foreach($tickets as $ticket)
<div style="text-align: center;">
    <p style="margin-bottom: 0px;margin-top: 0px;"><b>БилетАвто - сайт заказа билетов на автобус</b></p>
    <p style="margin-bottom: 0px;margin-top: 0px;">biletavto.ru</p>
    <hr style="margin-bottom: 0px;margin-top: 0px;">
</div>
<div>
    <p style="text-align: right; margin-bottom: 0px;margin-top: 0px;">Билет <b>№{{ $ticket->ba_ticket_id }}</b></p>
    <p style="text-align: right; margin-bottom: 0px;margin-top: 0px;">Заказ <b>№{{ $ticket->order()->first()->ba_operation_id }}</b></p>
    <img src="img/logo.png" height="70">
</div>
<div>
    <table border="0" align="center" cellpadding="4" cellspacing="0" width="100%" style="margin-top: 10px">
        <tr>
            <th colspan="2" style="background-color: #c0c0c0; text-align: center;">Рейс</th>
        </tr>
        <tr>
            <td width="50%">Отправление</td>
            <td width="50%">Прибытие</td>
        </tr>
        <tr>
            <td><b>{{ $ticket->departure_station }}</b></td>
            <td><b>{{ $ticket->arrival_station }}</b></td>
        </tr>
        <tr>
            <td><b>{{ $ticket->departure_date }}</b></td>
            <td><b>{{ $ticket->arrival_date }}</b></td>
        </tr>
        <tr>
            <td>{{ $ticket->departure_time }}, {{ $ticket->departure_address }}</td>
            <td>{{ $ticket->arrival_time }}, {{ $ticket->arrival_address }}</td>
        </tr>
    </table>
    <table border="0" align="center" cellpadding="4" cellspacing="0" width="100%">
        <tr>
            <th colspan="6" style="background-color: #c0c0c0; text-align: center;">Пассажир</th>
        </tr>
        <tr>
            <th width="50%">Номер телефона</th>
            <th width="50%">Место</th>
        </tr>
        <tr>
            <td>{{ $ticket->passenger_phone }}</td>
            <td>{{ $ticket->place }}</td>
        </tr>
    </table>
    <table border="0" align="center" cellpadding="4" cellspacing="0" width="100%">
        <tr>
            <th colspan="3" style="background-color: #c0c0c0; text-align: center;">Оплата</th>
        </tr>
        <tr>
            <th width="50%">Цена (с учетом сервисных сборов)</th>
            <th width="50%">Дата покупки (Улан-Удэ)</th>
        </tr>
        <tr>
            <td>{{ $ticket->price }} руб.</td>
            <td>{{ $ticket->created_at }}</td>
        </tr>
    </table>
    <table border="0" align="center" cellpadding="4" cellspacing="0" width="100%">
        <tr>
            <th colspan="4" style="background-color: #c0c0c0; text-align: center;">Дополнительная информация</th>
        </tr>
        <tr>
            <td width="25%"><b>Маршрут</b></td>
            <td width="25%">{{ $ticket->route_name }}</td>
        </tr>
        <tr>
            <td width="25%"><b>Тип билета</b></td>
            <td width="25%">{{ $ticket->type }}</td>
        </tr>
        <tr>
            <td width="25%"><b>Телефон автокассы</b></td>
            <td width="25%">{{ config('avo.phone') }}</td>
        </tr>
    </table>
</div>
<div>
    <hr>
    <p>Вид транспортного средства: автобус. Время отправления указано местное.</p>
    <p style="font-size: 10px">Место пассажира и вид ТС могут быть изменены Перевозчиком.</p>
    <p>
        <b>Внимание!</b>
        Приходите к месту отправления заранее: посадка начинается за 30 минут и заканчивается за 5 минут до отправления автобуса. Стоимость багажа не включена в тариф билета.
    </p>
</div>
<div style="font-size: 10px">
    <div>
        <p>При посадке в автобус необходимо предъявить распечатанный билет и номер телефона пассажира.</p>
    </div>
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
        </ul>
        </p>
    </div>
    <div>
        <p style="padding: 0px; margin: 0px;">Продажу эл. билетов осуществляет ООО "БИЛЕТ АВТО"</p>
        <p style="padding: 0px; margin: 0px;">ИНН 0326562943, ОГРН 1180327010951</p>
        <p style="padding: 0px; margin: 0px;">Вопросы к тех. поддержке сайта отправляйте на <b>info@biletavto.ru</b></p>
    </div>
    <div>
        <br>
        <p>Для отчетности в бухгалтерию по командировке обратитесь заранее в кассу или к Перевозчику с целью обмена онлайн-билета на кассовый чек.</p>
    </div>
</div>
@endforeach
</body>
</html>
