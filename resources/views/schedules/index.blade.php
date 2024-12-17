@extends('layouts.app') <!-- Если у вас есть основной шаблон -->

@section('content')
    <div class="container">
        <h1 class="text-center my-4">Расписание маршрутов</h1>

        <h2 class="mb-4">Маршруты</h2>
        @if(!empty($routes))

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th>Номер маршрута</th>
                        <th>Время отправления</th>
                        <th>Станция отправления</th>
                        <th>Станция прибытия</th>
                        <th>Время в пути</th>
                        <th>Цена</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($routes as $route)
                        @php
                            $departureStationId = $route->PROPERTIES->DEPARTURE_STATION;
                            $arrivalStations = (array) $route->PROPERTIES->ARRIVAL_STATION_NAME;
                            $departureTimes = (array) $route->PROPERTIES->DEPARTURE_TIME;
                            $drivingTimes = (array) $route->PROPERTIES->DRIVING_TIME;
                            $prices = (array) $route->PROPERTIES->PRICE;

                            $maxStops = max(count($arrivalStations), count($drivingTimes), count($prices));
                        @endphp

                        @for($i = 0; $i < $maxStops; $i++)
                            <tr>
                                @if($i === 0) <!-- Показываем номер маршрута и станцию отправления только для первой строки -->
                                <td>{{ $route->PUBLIC_NAME }}</td>
                                <td>{{ implode(', ', $departureTimes) }}</td>
                                <td>{{ $stations->{$departureStationId}->NAME ?? 'Неизвестно' }}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                                <td>{{ $arrivalStations[$i] ?? '-' }}</td>
                                <td>{{ $drivingTimes[$i] ?? '-' }}</td>
                                <td>{{ $prices[$i] ?? '-' }} руб.</td>
                            </tr>
                        @endfor
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-danger text-center mt-4">Маршруты не найдены.</p>
        @endif
    </div>
@endsection
