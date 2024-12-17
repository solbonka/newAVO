@php
    use Carbon\Carbon;
    Carbon::setLocale('ru'); // устанавливаем локаль на русский
@endphp
@extends('layouts.app')

@section('content')
    <main>
        @if(is_null($sheet))
            <script>
                window.location.href = '{{ url('/') }}'; // Перенаправление на главную страницу
            </script>
        @endif
        <section class="ticket-booking-section">
            <form action="{{ route('orders.confirm') }}" method="POST">
                <div class="row">
                    <div class="col-md booking-col">
                        <div class="ride" id="result-item">
                            <div class="row">
                                <div class="ride-name">
                                    {{ $data['departure_station'] }} — {{ $data['arrival_station'] }}
                                </div>
                                <div class="departure-date">
                                    {{ Carbon::createFromFormat('d.m.Y', $data['departure_date'])->translatedFormat('d F, l') }}
                                </div>
                            </div>
                            <div class="row ride-info" >
                                <div class="col from-col">
                                    <div class="row from-datetime">
                                        <p class="from-time">{{ $data['departure_time'] }} </p>
                                        <p class="from-date">{{ Carbon::createFromFormat('d.m.Y', $data['departure_date'])->translatedFormat('d M') }}</p>
                                    </div>
                                    <div class="row">
                                        <h4 class="ml-3">{{ $data['departure_station'] }} </h4>
                                    </div>
                                    <div class="row">
                                        <p class="ml-3 mb-0">{{ $data['departure_address'] }}</p>
                                    </div>
                                </div>
                                <div class="col to-col">
                                    <div class="row to-datetime">
                                        <p class="to-time">{{ $data['arrival_time'] }} </p>
                                        <p class="to-date">{{ Carbon::createFromFormat('d.m.Y', $data['arrival_date'])->translatedFormat('d M') }}</p>
                                    </div>
                                    <div class="row">
                                        <h4 class="ml-3">{{ $data['arrival_station'] }} </h4>
                                    </div>
                                    <div class="row">
                                        <p class="ml-3 mb-0">{{ $data['arrival_address'] }}</p>
                                    </div>
                                </div>
                                <div class="col ride-duration">
                                    <div class="row">
                                        @php
                                            $departure = Carbon::createFromFormat('d.m.Y H:i', $data['departure_date'] . ' ' . $data['departure_time']);
                                            $arrival = Carbon::createFromFormat('d.m.Y H:i', $data['arrival_date'] . ' ' . $data['arrival_time']);
                                            $duration = $departure->diff($arrival);
                                        @endphp
                                        <p class="mb-0 time-diff">{{ $duration->h }} ч {{ $duration->i }} м</p>
                                    </div>
                                    <div class="row">
                                        <p class="mb-0 carrier">{{ $data['carrier'] }}</p>
                                    </div>
                                    <div class="row">
                                        <p>{{ $sheet['routeName'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div id="more-info" style="display: none;">
                                <!-- Полная информация -->
                                Сумма возврата зависит от условий Перевозчика, а также от времени, которое осталось до поездки. Чем раньше вы оформили возврат, тем большую сумму вернете. Билет можно вернуть только до отправления автобуса, после отправления автобуса билет возврату не подлежит.
                                <br><br>У большинства перевозчиков при возврате пассажиром билета по причине отказа от поездки, производятся следующие удержания:<br>
                                <ol>
                                    <li>Возврат билета более чем за 2 часа до отправления — удерживается 5% от стоимости проезда (сервисные сборы возврату не подлежат);</li>
                                    <li>Возврат билета в течение 2 часов до отправления — удерживается 15% от стоимости проезда (сервисные сборы возврату не подлежат);</li>
                                    <li>Возврат билета в случае опоздания к отправлению транспортного средства в течение 3 часов или 3 суток после отправления — удерживается 25% от стоимости проезда при наличии справки из медицинского учреждения (по причине болезни) или акта о несчастном случае.
                                    <p class="text-danger" style="margin: 0">Внимание! Менее, чем за полчаса до отправления автобуса возврат билета оформляется только через подачу письменного заявления на кассах автовокзала;</p></li>
                                    <li>В случае отмены рейса возвращается 100% от стоимости билета.</li>
                                </ol>
                                Штатно удерживается комиссия в размере перечисленных процентов от стоимости билета. У некоторых перевозчиков возможно удержание дополнительной комиссии за возврат билета.
                                Обращаем ваше внимание, что возврат средств осуществляется в течение 2 - 5 рабочих дней и зависит от скорости обработки заявки банком.
                                Если у вас возникнут какие-либо трудности при оформлении возврата, вы можете связаться с нами по номеру телефона <a href="tel:83012268003">+7 (3012) 26-80-03</a> (с 4 до 12 ч. по МСК времени в будни) или написать нам на служебную почту <a href="mailto:info@biletavto.ru">info@biletavto.ru</a>.
                            </div>

                            <div id="short-info">
                                <!-- Сокращенная информация (показывается по умолчанию) -->
                                Сумма возврата зависит от условий Перевозчика, а также от времени, которое осталось до...
                            </div>
                            <button class="more-info-btn" onclick="toggleMoreInfo()">
                                Подробнее
                                <svg id="caret-down" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                    <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                                </svg>
                                <svg style="display:none;" id="caret-up" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16">
                                    <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                                </svg>
                            </button>
                        </div>


                        @csrf
                        <input type="hidden" name="sheet_id" value="{{ $sheet['rideId'] }}">
                        <input type="hidden" name="price_id" value="{{ $priceId }}">
                        <input type="hidden" name="total_price" id="total_price" value="0">
                        @foreach($data as $key => $value)
                            <input type="hidden" name="data[{{ $key }}]" value="{{ $value }}">
                        @endforeach
                        <div class="bus-scheme">
                            <div class="bus-scheme--title">Места</div>
                            <div class="place-info d-flex flex-row">
                                <div class="p-4">
                                    <div class="row">
                                        <div class="occupied"></div>
                                        занято
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="row">
                                        <div class="free"></div>
                                        свободно
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="row">
                                        <div class="selected-info"></div>
                                        выбрано вами
                                    </div>
                                </div>
                            </div>
                            <div class="bus-scheme--desktop">
                                @for ($i = 0; $i < count($seatMap[0]); $i++) <!-- Итерация по рядам -->
                                <div class="bus-scheme__row">
                                    @if ($i === count($seatMap[0]) - 1)
                                        <span class="bus-scheme__place bus-scheme__place--wheel"></span> <!-- Отображение колеса на последнем ряду -->
                                    @else
                                        <span class="bus-scheme__place bus-scheme__place--way"></span> <!-- Отображение прохода -->
                                    @endif

                                    @for ($j = 0; $j < count($seatMap); $j++) <!-- Итерация по местам -->
                                    @if (isset($seatMap[$j][$i]) && $seatMap[$j][$i] != 0)
                                        @if (in_array($seatMap[$j][$i], $bookedSeats))
                                            <span class="bus-scheme__place bus-scheme__place--sold">
                                        <span>{{ $seatMap[$j][$i] }}</span>
                                    </span>
                                        @else
                                            <span class="bus-scheme__place bus-scheme__place--free j-booking-place"
                                                  data-position="{{ $seatMap[$j][$i] }}">
                                        <span>{{ $seatMap[$j][$i] }}</span>
                                    </span>
                                        @endif
                                    @else
                                        <span class="bus-scheme__place bus-scheme__place--way"><span></span></span> <!-- Пустое место -->
                                    @endif
                                    @endfor
                                </div>
                                @endfor
                            </div>

                            <div class="bus-scheme--mobile">
                                <span class="bus-scheme__place bus-scheme__place--wheel"></span>
                                @for ($i = 0; $i < count($seatMap); $i++) <!-- Итерация по рядам -->
                                <div class="bus-scheme__row">
                                    @for ($j = 0; $j < count($seatMap[0]); $j++) <!-- Итерация по местам -->
                                    @if (isset($seatMap[$i][count($seatMap[0]) - 1 -$j]) && $seatMap[$i][count($seatMap[0]) - 1 -$j] != 0)
                                        @if (in_array($seatMap[$i][count($seatMap[0]) - 1 -$j], $bookedSeats))
                                            <span class="bus-scheme__place bus-scheme__place--sold">
                                        <span>{{ $seatMap[$i][count($seatMap[0]) - 1 -$j] }}</span>
                                    </span>
                                        @else
                                            <span class="bus-scheme__place bus-scheme__place--free j-booking-place"
                                                  data-position="{{ $seatMap[$i][count($seatMap[0]) - 1 -$j] }}">
                                        <span>{{ $seatMap[$i][count($seatMap[0]) - 1 -$j] }}</span>
                                    </span>
                                        @endif
                                    @else
                                        <span class="bus-scheme__place bus-scheme__place--way"><span></span></span> <!-- Пустое место -->
                                    @endif
                                    @endfor
                                </div>
                                @endfor
                            </div>
                            <div class="bus-scheme--footer">
                                Схема мест предоставлена перевозчиком и может отличаться от реального расположения мест.
                                В случаях неисправности или изменения спроса автобус может быть заменён на другой.</div>
                        </div>
                        <input type="hidden" name="selected_seats" id="selected_seats" value="">
                        <div id="passenger-data" class="passenger-data">

                        </div>
                        <div class="buyer">
                            <div class="row buyer-title">
                                Контактная информация покупателя
                            </div>
                            <div class="row buyer-info">
                                Куда отправить информацию о бронировании
                            </div>
                            <div class="row buyer-data">
                                <div class="col">
                                    <div class="row">
                                        <label for="email">Электронная почта:</label>
                                    </div>
                                    <div class="row">
                                        <input class="email" type="text" name="email" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <label for="phone">Телефон:</label>
                                    </div>
                                    <div class="row">
                                        <input class="phone" type="text" name="phone" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col result-order">
                        <div class="result-order-info">
                            <div class="row result-title margin-row">Стоимость билетов</div>
                            <div class="row result-data margin-row mt-3 mb-2">
                                <div class="result-tariff-places">
                                    <span class="result-tariff">Полный</span>
                                    <span class="result-places"></span>
                                </div>
                                <div class="result-price">
                                    0 x 0,00 ₽
                                </div>
                            </div>
                            <div class="row mt-4 mb-3"><div class="line"></div></div>
                            <div class="row result-amount margin-row mb-2 mr-1">
                                <span id="total_text">Итого&nbsp;</span>
                                <span id="total_amount">0&nbsp;₽</span>
                            </div>
                            <button type="submit">Ввести карту и оплатить</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
@endsection

@push('styles')
    <style>
        .result-tariff-places {
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .margin-row {
            margin: 0;
        }
        .legend {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .result-amount {
            font-size: 24px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
        }
        .result-title {
            font-size: 18px;
            font-weight: 600;
            line-height: 22px;
        }
        .result-data {
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }
        .result-tariff, .result-price {
            font-weight: 600;
        }
        .result-places {
            color: #9d9b98;
        }
        .line {
            display: flex;
            content: "";
            border-top: 1px solid #cfd9e6;
            flex-grow: 1;
            margin-right: 4px;
            align-self: center;
            height: 1px;
        }
        .buyer-title, .buyer-info, .buyer-data {
            margin: 0;
        }
        .buyer {
            display: flex;
            flex-direction: column;
            background: #f9f9f9;
            padding: 28px 32px 32px;
            border-radius: 16px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 12px -4px hsla(60, 4%, 60%, .35), 0 0 2px hsla(60, 4%, 60%, .3);
            text-align: start;
        }
        .buyer-title {
            font-size: 18px;
            line-height: 22px;
            font-weight: 600;
        }
        .buyer-info {
            font-size: 16px;
            line-height: 20px;
            margin-bottom: 20px;
        }
        .row label {
            font-size: 14px;
            font-weight: 600;
        }
        .no_lastname_text {
            margin: 0;
            position: absolute;
            left: 30px;
            font-size: 14px;
            font-weight: 600;
        }
        .radio_group {
            position: relative; /* Для корректной работы абсолютного позиционирования */
            margin-bottom: 20px;
            background: #efecec;
            border-radius: 8px;
            height: 44px;
            padding: 4px 2px;
            width: max-content;
            min-width: 208px;
        }

        .switcher {
            width: 108px;
            height: 36px;
            position: absolute; /* Абсолютное позиционирование */
            background-color: #FFFFFF; /* Цвет фона переключателя */
            border-radius: 8px; /* Закругленные углы */
            box-shadow: 0 4px 12px -4px hsla(60, 4%, 60%, .35), 0 0 2px hsla(60, 4%, 60%, .3);
            transition: left 0.3s ease; /* Плавное смещение переключателя */
            z-index: 0;
            left: 1%; /* Начальная позиция переключателя для "Мужской" */
        }

        .radio-label {
            cursor: pointer; /* Курсор указателя при наведении */
            display: inline-block; /* Позволяет размещать метки рядом */
            padding: 6px 18px; /* Отступы по бокам */
            position: relative; /* Установить контекст позиционирования для переключателя */
            border-radius: 8px;
        }

        .radio-label:hover {
            background: #c1c1c1;
        }

        .radio-input {
            display: none; /* Скрыть стандартный стиль радио-кнопок */
        }

        /* Для выделенного текста радио-кнопки */
        .radio-input:checked + .g8osy {
            font-weight: bold; /* Делает текст выделенным для выбранной радио-кнопки */
        }

        .radio-text {
            display: inline-block;
            position: relative;
            z-index: 1; /* Чтобы текст находился поверх переключателя */
            font-size: 16px;
            font-weight: 500;
        }

        /* Общие стили для секции бронирования */
        .no_lastname {
            display: block;
            position: relative;
            cursor: pointer;
            font-size: 20px;
            height: 20px;
            width: 20px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .no_lastname input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #fff;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        .no_lastname:hover input ~ .checkmark {
            border-color: #ccc;
        }
        .no_lastname input:checked ~ .checkmark {
            background-color: #ffe356;
        }
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        .no_lastname input:checked ~ .checkmark:after {
            display: block;
        }
        .no_lastname .checkmark:after {
            left: 5px;
            top: 1px;
            width: 8px;
            height: 12px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .passenger-info-row, .docs-tariff {
            margin: 0;
        }
        .booking-col {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .ride-info {
            text-align: start;
        }
        .result-order {
            max-width: 340px;
            min-width: 340px;
            padding: 0;
        }
        .result-order-info {
            background-color: #f9f9f9;
            border-radius: 16px;
            border: 1px solid #ddd;
            height: max-content;
            position: sticky;
            top: 20px;
            padding: 28px 20px ;
            box-shadow: 0 4px 12px -4px hsla(60,4%,60%,.35),0 0 2px hsla(60,4%,60%,.3);
        }
        .ride {
            border: 1px solid #ddd; /* рамка */
            border-radius: 16px;
            padding: 28px 32px 32px;
            background-color: #f9f9f9; /* цвет фона */
            box-shadow: 0 4px 12px -4px hsla(60,4%,60%,.35),0 0 2px hsla(60,4%,60%,.3);
            width: 100%;
        }
        .ride-name {
            margin-left: 16px;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .from-time, .to-time {
            margin-bottom: 0 !important;
            margin-left: 16px;
            margin-right: 8px;
            font-weight: 600;
            font-size: 24px;
        }
        .from-date, .to-date {
            color: #9c9ba2;
            margin-bottom: 0 !important;
            font-size: 14px;
            line-height: 14px;
        }
        .from-datetime, .to-datetime {
            line-height: 24px;
            margin-bottom: 4px;
        }
        .time-diff {
            font-size: 16px;
            font-weight: 600;
        }
        .carrier {
            font-size: 16px;
            color: #9c9ba2;
        }
        .more-info-btn {
            background: none;
            color: #9c9ba2;
            border: none;
            display: list-item;
            padding: 0;
        }
        .more-info-btn:hover {
            background: none;
            color: #5e5d65;
            border: none;
        }
        .more-info-btn:focus, .more-info-btn:active {
            outline: none;
        }

        .departure-date{
            font-size: 24px;
            margin-left: 16px;
            margin-bottom: 1rem;
        }
        .ticket-booking-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Вертикальное выравнивание */
            justify-content: center; /* Горизонтальное выравнивание */
            text-align: center; /* Центрирование текста внутри контейнера */
        }

        .ticket-booking-section h1,
        .ticket-booking-section h2,
        .ticket-booking-section h3 {
            color: #0318d6;
            margin: 0 0 10px;
        }

        .bus-scheme {
            background: #f9f9f9;
            padding: 28px 32px 32px;
            border-radius: 16px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 12px -4px hsla(60,4%,60%,.35),0 0 2px hsla(60,4%,60%,.3);
            text-align: start;
        }
        .passenger-data {
            display: none;
            flex-direction: column;
            gap: 20px;
            background: #f9f9f9;
            padding: 28px 32px 32px;
            border-radius: 16px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 12px -4px hsla(60,4%,60%,.35),0 0 2px hsla(60,4%,60%,.3);
            text-align: start;
        }
        .bus-scheme--desktop {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 40px;
            border: 1px solid #ddd;
            border-radius: 16px;
            background-color: #f9f9f9;
            width: max-content;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .bus-scheme--mobile {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            width: max-content;
        }

        .bus-scheme__row {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 10px;
        }

        .bus-scheme__place {
            width: 32px;
            height: 32px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            font-size: 14px;
            font-weight: bold;
            position: relative;
        }

        .bus-scheme__place--wheel {
            width: 32px; /* Ширина для колеса */
            height: 32px; /* Высота колеса */
        }

        .bus-scheme__place--way {
            width: 32px; /* Ширина для прохода */
            background: none;
        }

        .bus-scheme__place--free {
            background-color: #f9f9f9; /* Цвет для свободного места */
            border: 2px solid #0072ff;
            color: #0072ff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .bus-scheme__place--free:hover {
            color:white;
            background-color: #0072ff;
            transform: scale(1.1);
            box-shadow: 0 4px 20px rgba(0, 123, 255, 0.4);
        }

        .bus-scheme__place.selected {
            color: #864a5d;
            border: 2px solid #ffeb3b;
            background-color: rgba(255, 235, 59, 0.7);
        }

        .bus-scheme__place--sold {
            background-color: #f44336;
            border: 2px solid #bf1c1c;
            color: #312800;
        }
        .bus-scheme--title {
            font-size: 18px;
            font-weight: 600;
        }
        .bus-scheme--footer {
            font-weight: 600;
        }
        .occupied {
            width: 16px;
            height: 16px;
            background: #f44336;
            border-radius: 4px;
            border: 1px solid #bf1c1c;
            margin-right: 5px;
        }
        .free {
            width: 16px;
            height: 16px;
            background: #f9f9f9;
            border-radius: 4px;
            border: 1px solid #0072ff;
            margin-right: 5px;
        }
        .selected-info {
            width: 16px;
            height: 16px;
            background: rgba(255, 235, 59, 0.7);
            border-radius: 4px;
            border: 1px solid #ffeb3b;
            margin-right: 5px;
        }

        input[type="checkbox"] {
            position: absolute;
            opacity: 0;
        }

        input[type="checkbox"] + .free-place {
            display: inline-block;
        }

        .place-info {
            margin: 0;
            line-height: 16px;
        }

        .passenger_firstname,
        .passenger_lastname,
        .passenger_name,
        .docs_number,
        .document-type,
        .passenger_birthday,
        .tariff-type,
        .email,
        .phone
        {
            max-width: 200px;
            min-width: 200px;
            font-size: 16px;
            height: 44px;
            border-radius: 8px;
            color: #0d0d0f;
            padding-right: 12px;
            padding-left: 12px;
            background-color: rgb(255, 255, 255);
            border: 1px solid rgb(227, 225, 220);

        }

        .passenger_firstname:hover,
        .passenger_lastname:hover,
        .passenger_name:hover,
        .docs_number:hover,
        .document-type:hover,
        .passenger_birthday:hover,
        .email:hover,
        .phone:hover
        /*.tariff-type:hover*/
        {
            border: 1px solid rgb(179, 179, 178);
        }
        .passenger_firstname:focus,
        .passenger_lastname:focus,
        .passenger_name:focus,
        .docs_number:focus,
        .document-type:focus,
        .passenger_birthday:focus,
        .email:focus,
        .phone:focus,
        /*.tariff-type:focus,*/
        .passenger_firstname:active,
        .passenger_lastname:active,
        .passenger_name:active,
        .docs_number:active,
        .document-type:active,
        .passenger_birthday:active,
        .email:active,
        .phone:active
        /*.tariff-type:active*/
        {
            border: 1px solid #ffeb3b;
            outline: 1px solid #ffeb3b;
        }

        #more-info, #short-info {
            margin-top: 16px;
            font-weight: bold;
            text-align: start;
        }

        /* Стили для кнопки */
        button {
            background-color: #fed42b; /* Цвет кнопки */
            color: #0d0d0f;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
            margin-top: 4px;
            width: 100%;
        }

        button:hover {
            background-color: #f5bc00; /* Цвет кнопки при наведении */
        }

        button:focus, button:active {
            outline: none;
        }
        @media (max-width: 1028px) { /* Замените 768px на ваше значение медиапараметра */
            .result-order {
                max-width: none;
                padding: 15px;
                margin-top: 5px;
            }
        }

        @media (max-width: 768px) { /* Замените 768px на ваше значение медиапараметра */
            .bus-scheme--desktop {
                display: none;
            }
            .bus-scheme--mobile {
                display: block;
            }
            .bus-scheme__place--wheel {
                background: url("http://localhost/img/wheel.svg"); /* Цвет для колеса */
            }
            .bus-scheme {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
        }

        @media (min-width: 768px) {
            .bus-scheme--desktop {
                display: block;
            }
            .bus-scheme--mobile {
                display: none;
            }
            .bus-scheme__place--wheel {
                background: url("http://localhost/img/driver.svg"); /* Цвет для колеса */
            }
            .bus-scheme {
                display: block;
            }
        }
        hr {
            margin: 32px 0; /* Отступы сверху и снизу */
        }
        *:disabled {
            background: #f9f9f9;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const updateGenderSwitchers = () => {
            const switchers = document.querySelectorAll('.switcher'); // Получаем все переключатели
            const maleRadios = document.querySelectorAll("input[type='radio'][value='male']");
            const femaleRadios = document.querySelectorAll("input[type='radio'][value='female']");

            maleRadios.forEach((maleRadio, index) => {
                const femaleRadio = femaleRadios[index];
                const switcher = switchers[index];

                // Устанавливаем начальное положение переключателя
                switcher.style.left = '0%'; // Начальное положение для "Мужской"

                // Добавляем обработчики событий
                maleRadio.addEventListener('change', () => {
                    switcher.style.left = '0%'; // позиция для "Мужской"
                });

                femaleRadio.addEventListener('change', () => {
                    switcher.style.left = '49%'; // позиция для "Женский"
                });
            });
        };
        document.addEventListener('DOMContentLoaded', () => {
            const selectedSeatsInput = document.getElementById('selected_seats');
            const bookingPlaces = document.querySelectorAll('.j-booking-place');
            const totalAmountElement = document.getElementById('total_amount');
            const totalPriceField = document.getElementById('total_price');
            const resultPlacesElement = document.querySelector('.result-places');
            const resultPriceElement = document.querySelector('.result-price');
            const pricePerSeat = {{ $sheet['price'] }};
            const selectedSeats = [];
            const maxSeats = 5; // Максимальное число мест

            // Функция для обновления итоговой стоимости, мест и полей
            const updateTotal = () => {
                const totalAmount = selectedSeats.length * pricePerSeat;
                totalAmountElement.textContent = totalAmount + ' ₽';
                totalPriceField.value = totalAmount; // Обновляем скрытое поле для общей цены

                // Обновляем скрытые поля для отправки выбранных мест
                const oldInputs = document.querySelectorAll("input[name='selected_seats[]']");
                oldInputs.forEach(input => input.remove());

                selectedSeats.forEach(seat => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selected_seats[]';
                    hiddenInput.value = seat;
                    selectedSeatsInput.parentNode.appendChild(hiddenInput);
                });

                // Обновляем отображение выбранных мест
                if (selectedSeats.length > 0) {
                    if (selectedSeats.length === 1) {
                        resultPlacesElement.textContent = 'место ' + selectedSeats[0];
                    } else if (selectedSeats.length === 2) {
                        resultPlacesElement.textContent = 'места ' + selectedSeats.join(' и ');
                    } else {
                        const lastSeat = selectedSeats.pop(); // Убираем последнее место
                        resultPlacesElement.textContent = 'места ' + selectedSeats.join(', ') + ' и ' + lastSeat;
                        selectedSeats.push(lastSeat); // Возвращаем последнее место обратно в массив
                    }
                    resultPriceElement.textContent = `${selectedSeats.length} x ${pricePerSeat.toFixed(2)} ₽`;
                } else {
                    resultPlacesElement.textContent = ''; // Очищаем, если мест нет
                    resultPriceElement.textContent = '0 x 0,00 ₽'; // Текст по умолчанию для отсутствия выбора мест
                }

                // Обновляем данные о пассажирах
                updatePassengerData();
            };

            const updatePassengerData = () => {
                const passengerDataContainer = document.getElementById('passenger-data');
                passengerDataContainer.style.display = 'flex';
                // Очистим предыдущие данные перед генерацией новых
                passengerDataContainer.innerHTML = '';
                if (selectedSeats.length === 0) {
                    passengerDataContainer.style.display = 'none';
                } else {
                    selectedSeats.forEach((seat, index) => {
                        // Создаем новый блок данных для каждого пассажира
                        const passengerSection = document.createElement('div');
                        passengerSection.classList.add('passenger-info-div');
                        passengerSection.innerHTML = `
                            <span class="legend">${index + 1}. Пассажир</span>
                            <input type="hidden" name="passenger[${index}][place]" value="${seat}">
                            <div class="row passenger-info-row">
                                <div class="col">
                                    <div class="row">
                                        <label for="passenger[${index}][firstname]">Фамилия:</label>
                                    </div>
                                    <div class="row">
                                        <input class="passenger_firstname" type="text" name="passenger[${index}][firstname]" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <label for="passenger[${index}][name]">Имя:</label>
                                    </div>
                                    <div class="row">
                                        <input class="passenger_name" type="text" name="passenger[${index}][name]" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <label for="passenger[${index}][lastname]">Отчество:</label>
                                    </div>
                                    <div class="row">
                                        <input class="passenger_lastname" type="text" name="passenger[${index}][lastname]" required>
                                    </div>
                                    <div class="row mt-2">
                                        <label class="no_lastname" for="passenger[${index}][no_lastname]">
                                            <input class="no_lastname_check" type="checkbox" id="passenger[${index}][no_lastname]" onclick="togglePatronymic(this, '${index}')">
                                            <span class="checkmark"></span>
                                        </label>
                                        <p class="no_lastname_text">Нет отчества</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row passenger-info-row">
                                <div class="col">
                                    <div class="row">
                                        <label for="passenger[${index}][gender]">Пол:</label>
                                    </div>
                                    <div class="row radio_group" role="radiogroup" aria-labelledby="gender-label">
                                        <div>
                                            <label class="radio-label" data-qa="male-radioItem">
                                                <input type="radio" id="male-radio[${index}]" autocomplete="off" class="radio-input" name="passenger[${index}][gender]" value="male" checked>
                                                <div class="radio-text" data-qa="male-radioItem-text">Мужской</div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="radio-label" data-qa="female-radioItem">
                                                <input type="radio" id="female-radio[${index}]" autocomplete="off" class="radio-input" name="passenger[${index}][gender]" value="female">
                                                <div class="radio-text" data-qa="female-radioItem-text">Женский</div>
                                            </label>
                                        </div>
                                        <div class="switcher" aria-hidden="true"></div>
                                    </div>
                                </div>
                                <div class="col">
                                     <div class="row">
                                         <label for="passenger[${index}][birthday]">Дата рождения:</label>
                                     </div>
                                     <div class="row">
                                         <input type="date" class="passenger_birthday" placeholder="__.__.____" name="passenger[${index}][birthday]" required>
                                     </div>
                                </div>
                                <div class="col">
                                </div>
                            </div>
                            <div class="row docs-tariff">
                                <div class="col">
                                    <div class="row">
                                        <label for="passenger[${index}][document_type]">Документ:</label>
                                    </div>
                                    <div class="row">
                                        <select class="document-type" id="document-type[${index}]" name="passenger[${index}][document_type]" required>
                                            <option value="passport" selected>Паспорт</option>
                                            <option value="birth_certificate">Свидетельство о рождении</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <label for="passenger[${index}][docs_number]">Серия и номер:</label>
                                    </div>
                                    <div class="row">
                                        <input class="docs_number" type="text" name="passenger[${index}][docs_number]" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <label for="passenger[${index}][tariff_type]">Тариф:</label>
                                    </div>
                                    <div class="row">
                                        <select class="tariff-type" id="tariff-type[${index}]" name="passenger[${index}][tariff_type]" disabled required>
                                            <option value="full" selected>Полный</option>
                                            <option value="child">Детский</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        `;
                        if (index < selectedSeats.length - 1) {
                            passengerSection.innerHTML += `<hr />`; // Разделительная линия
                        }
                        passengerDataContainer.appendChild(passengerSection);
                    });
                    updateGenderSwitchers();
                }
            };

            // Добавляем обработчик события для каждого места
            bookingPlaces.forEach(place => {
                place.addEventListener('click', () => {
                    const seatPosition = place.getAttribute('data-position');

                    if (selectedSeats.includes(seatPosition)) {
                        // Если место уже выбрано, снимаем выбор
                        selectedSeats.splice(selectedSeats.indexOf(seatPosition), 1);
                        place.classList.remove('selected');
                    } else {
                        // Если место не выбрано, добавляем его в массив
                        if (selectedSeats.length < maxSeats) {
                            selectedSeats.push(seatPosition);
                            place.classList.add('selected');
                        } else {
                            alert(`Вы можете выбрать не более ${maxSeats} мест.`); // Сообщение об ограничении мест
                        }
                    }
                updateTotal(); // Обновляем стоимость и отображение мест после каждого выбора
                });
            });
        });

        function toggleMoreInfo() {
            const moreInfoDiv = document.getElementById('more-info');
            const shortInfoDiv = document.getElementById('short-info');
            const caretDown = document.getElementById('caret-down');
            const caretUp = document.getElementById('caret-up');

            if (moreInfoDiv.style.display === 'none' || moreInfoDiv.style.display === '') {
                moreInfoDiv.style.display = 'block';
                shortInfoDiv.style.display = 'none';
                caretDown.style.display = 'none';
                caretUp.style.display = 'inline';
            } else {
                moreInfoDiv.style.display = 'none';
                shortInfoDiv.style.display = 'block';
                caretDown.style.display = 'inline';
                caretUp.style.display = 'none';
            }
        }
        function togglePatronymic(checkbox, index) {
            // Находим поле для отчества по имени
            var patronymicInput = document.querySelector(`input[name="passenger[${index}][lastname]"]`);

            // Проверяем состояние чекбокса
            if (checkbox.checked) {
                // Если чекбокс отмечен, скрыть поле для отчества
                patronymicInput.setAttribute('disabled', 'true');
                patronymicInput.value = ''; // Опционно очищаем поле
            } else {
                // Если чекбокс не отмечен, показать поле для отчества
                patronymicInput.removeAttribute('disabled');
            }
        }

        // Добавляем обработку загрузки страницы, если нужно скрыть элементы по умолчанию
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.no_lastname_check').forEach(checkbox => {
                // Поищем индекс, чтобы использовать в селекторе
                const index = checkbox.id.match(/\d+/)[0];
                togglePatronymic(checkbox, index);
            });
        });
    </script>
@endpush
