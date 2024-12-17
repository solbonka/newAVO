@php
    use Carbon\Carbon;
    Carbon::setLocale('ru'); // устанавливаем локаль на русский
@endphp
@extends('layouts.app')

@section('title', 'Результаты поиска')

@section('content')
    <div class="results">
        <section class="results-section">
            <p class="results-title">Автобусы из {{ $from }} в {{ $to }} на {{ Carbon::createFromFormat('d.m.Y', $date)->translatedFormat('j F') }}</p>

            @if(count($sheets) > 0)
                <div class="results-list">
                    @foreach ($sheets as $sheet)
                        <div class="result-item-large" id="result-item-{{ $sheet['id'] }}" onclick="toggleInfo({{ $sheet['id'] }})">
                            <div class="row ride-large" >
                                <div class="col from-col-large">
                                    <div class="row from-datetime">
                                        <p class="from-time">{{ $sheet['departure_time'] }} </p>
                                        <p class="from-date">{{ Carbon::createFromFormat('d.m.Y', $sheet['departure_date'])->translatedFormat('j M') }}</p>
                                    </div>
                                    <div class="row">
                                        <h4 class="ml-3">{{ $from }} </h4>
                                    </div>
                                    <div class="row">
                                        <p class="ml-3 mb-0">{{ $sheet['departure_address'] }}</p>
                                    </div>
                                </div>

                                <div class="col to-col-large">
                                    <div class="row to-datetime">
                                        <p class="to-time">{{ $sheet['arrival_time'] }} </p>
                                        <p class="to-date">{{ Carbon::createFromFormat('d.m.Y', $sheet['arrival_date'])->translatedFormat('j M') }}</p>
                                    </div>
                                    <div class="row">
                                        <h4 class="ml-3">{{ $to }} </h4>
                                    </div>
                                    <div class="row">
                                        <p class="ml-3 mb-0">{{ $sheet['arrival_address'] }}</p>
                                    </div>
                                </div>

                                <div class="col ride-duration-large">
                                    <div class="row">
                                        @php
                                            $departure = Carbon::createFromFormat('d.m.Y H:i', $sheet['departure_date'] . ' ' . $sheet['departure_time']);
                                            $arrival = Carbon::createFromFormat('d.m.Y H:i', $sheet['arrival_date'] . ' ' . $sheet['arrival_time']);
                                            $duration = $departure->diff($arrival);
                                        @endphp
                                        <p class="ml-3 mb-0 time-diff">{{ $duration->h }} ч {{ $duration->i }} м</p>
                                    </div>
                                    <div class="row">
                                        <p class="ml-3 mb-0 carrier">{{ $sheet['carrier'] }}</p>
                                    </div>
                                </div>

                                <form action="{{ route('book.tickets.post') }}" method="POST" class="form-class">
                                    @csrf
                                    @foreach($sheet as $key => $value)
                                        <input type="hidden" name="data[{{ $key }}]" value="{{ $value }}">
                                    @endforeach
                                    <div class="row mr-3 price">
                                        <p>{{ $sheet['price'] }} ₽</p>
                                    </div>
                                    <div class="row mr-3 count-place">
                                        <p>{{ $sheet['freePlaces'] }}
                                            @if ($sheet['freePlaces'] % 10 == 1 && $sheet['freePlaces'] % 100 != 11)
                                                место
                                            @elseif ($sheet['freePlaces'] % 10 >= 2 && $sheet['freePlaces'] % 10 <= 4 && ($sheet['freePlaces'] % 100 < 10 || $sheet['freePlaces'] % 100 >= 20))
                                                места
                                            @else
                                                мест
                                            @endif
                                        </p>
                                    </div>
                                    <div class="row mr-3">
                                        <button type="submit" class="button-choose">Выбрать</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="result-item-medium" id="result-item-{{ $sheet['id'] }}" onclick="toggleInfo({{ $sheet['id'] }})">
                            <div class="row ride-medium" >
                                <div class="col col-medium">
                                    <div class="row medium-datetime">
                                        <div class="from-datetime-medium">
                                            <div class="row">
                                                <p class="from-time">{{ $sheet['departure_time'] }} </p>
                                                <p class="from-date">{{ Carbon::createFromFormat('d.m.Y', $sheet['departure_date'])->translatedFormat('j M') }}</p>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <span class="ml-3 mb-0 time-diff duration-medium">
                                            @php
                                                $departure = Carbon::createFromFormat('d.m.Y H:i', $sheet['departure_date'] . ' ' . $sheet['departure_time']);
                                                $arrival = Carbon::createFromFormat('d.m.Y H:i', $sheet['arrival_date'] . ' ' . $sheet['arrival_time']);
                                                $duration = $departure->diff($arrival);
                                            @endphp
                                            {{ $duration->h }} ч {{ $duration->i }} м
                                        </span>
                                        <div class="line"></div>
                                        <div class="to-datetime-medium">
                                            <div class="row">
                                                <p class="to-time">{{ $sheet['arrival_time'] }} </p>
                                                <p class="to-date">{{ Carbon::createFromFormat('d.m.Y', $sheet['arrival_date'])->translatedFormat('j M') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="ml-3 mb-0 carrier">{{ $sheet['carrier'] }}</p>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p class="ml-3 address-medium">{{ $from }}, {{ $sheet['departure_address'] }}</p>
                                        </div>
                                        <div class="col">
                                            <p class="ml-3 address-medium">{{ $to }}, {{ $sheet['arrival_address'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('book.tickets.post') }}" method="POST" class="form-class">
                                    @csrf
                                    @foreach($sheet as $key => $value)
                                        <input type="hidden" name="data[{{ $key }}]" value="{{ $value }}">
                                    @endforeach
                                    <div class="row mr-3 price">
                                        <p>{{ $sheet['price'] }} ₽</p>
                                    </div>
                                    <div class="row mr-3 count-place">
                                        <p>{{ $sheet['freePlaces'] }}
                                            @if ($sheet['freePlaces'] % 10 == 1 && $sheet['freePlaces'] % 100 != 11)
                                                место
                                            @elseif ($sheet['freePlaces'] % 10 >= 2 && $sheet['freePlaces'] % 10 <= 4 && ($sheet['freePlaces'] % 100 < 10 || $sheet['freePlaces'] % 100 >= 20))
                                                места
                                            @else
                                                мест
                                            @endif
                                        </p>
                                    </div>
                                    <div class="row mr-3">
                                        <button type="submit" class="button-choose">Выбрать</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>К сожалению, рейсов не найдено.</p>
            @endif
        </section>
    </div>
@endsection

@push('styles')
    <style>
        .form-class {
            border-left: 1px solid #ddd;
            padding-left: 30px;
            display: flex;
            flex-direction: column;
            align-self: stretch;
        }
        .medium-datetime {
            width: 100%;
            justify-content: space-between;
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

        h1 {
            font-size: 34px;
            margin-bottom: 10px;
        }

        .results-title {
            margin-top: 1rem;
            font-size: 24px;
            font-weight: 600;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }

        .from-col-large {
            min-width: 270px;
        }

        .to-col-large {
            min-width: 270px;
        }

        .col-medium {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .address-medium {
            font-size: 19px;
            margin-bottom: 0 !important;
        }

        @media (min-width: 992px) {
            .result-item-large {
                display: block;
            }

            .result-item-medium {
                display: none;
            }

            .results {
                text-align: start;
            }
        }

        @media (max-width: 991px) {
            .result-item-large {
                display: none;
            }

            .result-item-medium {
                display: block;
            }

            .results {
                text-align: center;
            }
        }

        @media (max-width: 829px) {
            .form-class {
                border-left: none;
                padding-top: 20px;
                border-top: 1px solid #ddd;
                width: 100%;
                display: flex;
                justify-content: space-between;
            }
        }

        @media (max-width: 416px) {
            .line {
                display: none;
            }
        }

        .no-results {
            font-size: 18px;
            color: #d9534f; /* Красный цвет для предупреждения */
            margin-top: 15px;
        }

        .results {
            display: flex;
            flex-direction: column;
            align-items: center; /* Вертикальное выравнивание */
            justify-content: center; /* Горизонтальное выравнивание */
            text-align: start; /* Центрирование текста внутри контейнера */
            margin-bottom: 40px;
        }

        .results-section {
            max-width: 1200px;
            display: flex;
            width: 100%;
            flex-direction: column;
            align-items: center;
        }

        .results-list {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 20px;
            max-width: 1136px;
            width: 100%;
        }

        .result-item-large {
            border: 1px solid #ddd; /* рамка */
            border-radius: 16px;
            padding: 28px 32px 32px;
            background-color: #f9f9f9; /* цвет фона */
        }

        .result-item-medium {
            border: 1px solid #ddd; /* рамка */
            border-radius: 16px;
            padding: 28px 32px 32px;
            background-color: #f9f9f9; /* цвет фона */
        }

        .ride-large {
            display: flex;
            justify-content: space-between;
            align-items: start;
            max-width: 1136px;
        }
        .from-datetime, .to-datetime {
            line-height: 24px;
            margin-bottom: 4px;
        }
        .from-time, .to-time {
            margin-bottom: 0 !important;
            margin-left: 16px;
            margin-right: 8px;
            font-weight: 600;
            font-size: 24px;
        }

        .to-datetime-medium, .from-datetime-medium {
            overflow: hidden;
            width: 125px;
        }

        .from-date, .to-date {
            color: #9c9ba2;
            margin-bottom: 0 !important;
            font-size: 14px;
            line-height: 14px;
        }

        .time-diff {
            font-size: 16px;
            font-weight: 600;
        }

        .carrier {
            font-size: 16px;
            color: #9c9ba2;
        }

        .ride-info {
            justify-content: left; /* Горизонтальное выравнивание */
            text-align: left; /* Центрирование текста внутри контейнера */
            transition: max-height 0.3s ease, opacity 0.3s ease;
        }

        .price {
            color: #0d0d0f;
            font-size: 24px;
            letter-spacing: -.24px;
            line-height: 24px;
            font-weight: 600;
        }

        .count-place {
            color: #9c9ba2;
            font-size: 14px;
            line-height: 14px;
        }

        .button-choose {
            display: flex;
            width: 207px;
            height: 36px;
            padding: 8px;
            border: none;
            border-radius: 8px;
            background: #fa742d;
            align-items: center;
            align-self: end;
            justify-content: center;
            font-size: 19px;
            color: #FFFFFF;
            line-height: 25px;
            font-weight: 600;
        }

        .button-choose:hover {
            background: #e45c24;
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function toggleInfo(sheetId) {
            const infoContainer = document.getElementById(`rides-info-container-${sheetId}`);

            // Проверяем текущее состояние блока и переключаем его видимость
            if (infoContainer.style.display === 'none' || infoContainer.style.display === '') {
                infoContainer.style.display = 'block'; // Показываем блок
            } else {
                infoContainer.style.display = 'none'; // Скрываем блок
            }
        }
    </script>
@endpush
