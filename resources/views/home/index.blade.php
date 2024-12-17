@extends('layouts.app')

@section('title', 'Главная страница')

@section('content')
    <main class="main-content" id="main-content">
        <div class="ads">
            <div class="ad">
                <h4>Реклама 1</h4>
                <p>Супер скидки на всё! Не пропустите!</p>
                <a href="#" class="btn btn-light">Перейти к скидкам</a>
            </div>
            <div class="ad">
                <h4>Реклама 2</h4>
                <p>Супер скидки на всё! Не пропустите!</p>
                <a href="#" class="btn btn-light">Перейти к скидкам</a>
            </div>
        </div>

        <div class="avtovokzal-container" id="avtovokzal-container">
            <h3 class="collection-title">Автовокзалы</h3>
            <div class="owl-carousel owl-theme" id="slider">
                <!--Слайд 1-->
                @foreach(config('footer.автовокзалы.станции') as $station)
                <div class="slide" style="background: #ffffff">
                    <div class="location-card">
                        <figure>
                            <img style="display: none" src="{{ $station['modal_link'] }}" alt="modal-{{ $station['Название'] }}">
                            <img src="{{ $station['bg_link'] }}" alt="{{ $station['Название'] }}">

                            <div class="item-info">
                                <figcaption>{{ $station['Название'] }}</figcaption>
                                <span class="address">{{ $station['Адрес'] }}</span>
                                <p style="display: none" class="disp-phone">Диспетчерская: {{ $station['Диспетчерская'] }}</p>
                                <p style="display: none" class="is-open">Время работы: {{ $station['Время работы'] }}</p>
                                <p style="display: none" class="lunch">Обед: {{ $station['Обед'] }}</p>
                                <p style="display: none" class="info-phone">Справочная: {{ config('footer.автовокзалы.Справочная') }}</p>
                            </div>
                        </figure>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div id="avtovokzal-modal" class="avtovokzal-modal">
            <div class="avtovokzal-modal-content">
                <span class="avtovokzal-close">&times;</span>
                <h2 id="avtovokzal-modal-title"></h2>
                <div class="img-info-row">
                    <div class="img-col">
                        <img class="avtovokzal-modal-image" id="avtovokzal-modal-image" src="" alt="">
                    </div>
                    <div class="info-col">
                        <p class="address" id="avtovokzal-modal-address"></p>
                        <p class="working-hours" id="avtovokzal-modal-is-open"></p>
                        <p class="lunch-time" id="avtovokzal-modal-lunch"></p>
                        <p style="text-align: start">
                            <strong>Для получения более подробной информации, Вы можете позвонить по телефонам:</strong>
                        </p>
                        <p class="phone" id="avtovokzal-modal-disp-phone"></p>
                        <p class="phone" id="avtovokzal-modal-info-phone"></p>
                    </div>
                </div>

                <h2 id="avtovokzal-modal-title"></h2>
            </div>
        </div>

        <div class="ads">
            <div class="ad">
                <h4>Улан-Удэ - Иркутск</h4>
                <p>Супер скидки на всё! Не пропустите!</p>
                <a href="#" class="btn btn-light">Перейти к скидкам</a>
            </div>
            <div class="ad">
                <h4>Улан-Удэ - Чита</h4>
                <p>Супер скидки на всё! Не пропустите!</p>
                <a href="#" class="btn btn-light">Перейти к скидкам</a>
            </div>
        </div>

        <div class="popular">
            <div class="popular-routes mb-3">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 d-flex justify-content-center">
                    @foreach(config('popular_routes') as $popularRoute)
                    <div class="col-auto d-flex justify-content-center">
                        <div class="card card-popular mb-3">
                            <div class="card-body d-flex align-items-center">
                                <img src="{{ $popularRoute['image_link'] }}" alt="{{ $popularRoute['name'] }}" class="card-img mr-3"> <!-- Изображение -->
                                <div class="card-info">
                                    <h5 class="card-name">{{ $popularRoute['name'] }}</h5>
                                    <span class="card-price">от {{ $popularRoute['price'] }} ₽</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="more-routes mt-4">
                    <a href="/trains/popular-routes/" target="_blank" style="color: black;">
                        <div>
                            <span>
                                Больше популярных направлений
                            </span>
                            <span>
                                <svg width="16" height="16" fill="none" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" focusable="false">
                                    <path d="M14 9.5028C14.5523 9.5028 15 9.95051 15 10.5028V13.5028C15 14.3313 14.3284 15.0028 13.5 15.0028H2.5C1.67157 15.0028 1 14.3313 1 13.5028V2.5028C1 1.67437 1.67157 1.0028 2.5 1.0028H5.5C6.05228 1.0028 6.5 1.45051 6.5 2.0028C6.5 2.55508 6.05228 3.0028 5.5 3.0028H3V13.0028H13V10.5028C13 9.95051 13.4477 9.5028 14 9.5028ZM12.3 2.78694V2.46357L11.1503 2.77787C10.6125 2.92489 10.0575 2.99938 9.5 2.99938C8.9 2.99938 8.5 2.55166 8.5 1.99829C8.5 1.446 8.94772 0.998291 9.5 0.998291H14C14.5523 0.998291 15 1.446 15 1.99829V6.5028C15 7.05508 14.5523 7.5028 14 7.5028C13.4477 7.5028 13 7.10006 13 6.5028C13 5.94348 13.0747 5.38666 13.222 4.8471L13.5353 3.70006H13.2144L12.1366 5.13695C11.8524 5.51593 11.5415 5.87423 11.2065 6.20915L7.70697 9.70731C7.31637 10.0978 6.6832 10.0977 6.29276 9.70703C5.90231 9.31643 5.90243 8.68326 6.29303 8.29282L9.79386 4.79335C10.1265 4.46088 10.4821 4.15229 10.8581 3.86987L12.3 2.78694Z" fill="currentColor"></path>
                                </svg>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="info-main mb-5">
                <div class="row justify-content-center">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center">
                            <picture>
                                <source srcset="https://yastatic.net/s3/travel/static/_/b4e1d4b746a338e9614e.webp" type="image/webp" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/0da85b74d11bf7bd06fb.png" type="image/png" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/528e73cf6000bcb1b9ad.webp" type="image/webp">
                                <img src="https://yastatic.net/s3/travel/static/_/dfd3d6f273c4f2e8b648.png" alt="" width="220" height="180" class="img-fluid">
                            </picture>
                            <div class="info-main-text">
                                <div class="info-main-title">
                                    <strong>Укажите направление и дату вашей поездки.</strong><br>
                                </div>
                                <div class="info-main-description mt-2">
                                    Планируйте своё путешествие с нами — удобно, быстро и просто!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center">
                            <picture>
                                <source srcset="https://yastatic.net/s3/travel/static/_/95c31985c9c14e9e24bd.webp" type="image/webp" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/094e1f4010e0f6baec3f.png" type="image/png" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/59e785afcab31db6e6a3.webp" type="image/webp">
                                <img src="https://yastatic.net/s3/travel/static/_/e04378bb502e8acd9fe6.png" alt="" width="220" height="180" class="img-fluid">
                            </picture>
                            <div class="info-main-text">
                                <div class="info-main-title">
                                    <strong>Подберите идеальный вариант для себя!</strong><br>
                                </div>
                                <div class="info-main-description mt-2">
                                    Сэкономьте на поездках, используя фильтр по цене.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center">
                            <picture>
                                <source srcset="https://yastatic.net/s3/travel/static/_/f3fd6ba691dc8ea5333e.webp" type="image/webp" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/861e265be4cd56be70e9.png" type="image/png" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/fb4c30c9136fb277d452.webp" type="image/webp">
                                <img src="https://yastatic.net/s3/travel/static/_/f7384ae8ce92bf1b730f.png" alt="" width="220" height="180" class="img-fluid">
                            </picture>
                            <div class="info-main-text">
                                <div class="info-main-title">
                                    <strong>Забронируйте билеты уже сейчас!</strong><br>
                                </div>
                                <div class="info-main-description mt-2">
                                    Для оформления бронирования укажите паспортные данные всех пассажиров.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center">
                            <picture>
                                <source srcset="https://yastatic.net/s3/travel/static/_/4a848a824746f8f55d0a.webp" type="image/webp" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/6298b40d17753820c933.png" type="image/png" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/b77465f5c8ec3f2a81ad.webp" type="image/webp">
                                <img src="https://yastatic.net/s3/travel/static/_/e8048ec9fd8b2ad37482.png" alt="" width="220" height="180" class="img-fluid">
                            </picture>
                            <div class="info-main-text">
                                <div class="info-main-title">
                                    <strong>Произведите оплату заказа картой!</strong><br>
                                </div>
                                <div class="info-main-description mt-2">
                                    После подтверждения платежа вы сможете скачать билет или найти его в своём личном кабинете.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center">
                            <picture>
                                <source srcset="https://yastatic.net/s3/travel/static/_/b4e1d4b746a338e9614e.webp" type="image/webp" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/0da85b74d11bf7bd06fb.png" type="image/png" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/528e73cf6000bcb1b9ad.webp" type="image/webp">
                                <img src="https://yastatic.net/s3/travel/static/_/dfd3d6f273c4f2e8b648.png" alt="" width="220" height="180" class="img-fluid">
                            </picture>
                            <div class="info-main-text">
                                <div class="info-main-title">
                                    <strong>Покупайте билеты прямо здесь!</strong><br>
                                </div>
                                <div class="info-main-description mt-2">
                                    Забудьте о поездках на автовокзал и длинных очередях в кассу. У нас вы можете легко и быстро оформить покупку онлайн!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center">
                            <picture>
                                <source srcset="https://yastatic.net/s3/travel/static/_/95c31985c9c14e9e24bd.webp" type="image/webp" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/094e1f4010e0f6baec3f.png" type="image/png" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/59e785afcab31db6e6a3.webp" type="image/webp">
                                <img src="https://yastatic.net/s3/travel/static/_/e04378bb502e8acd9fe6.png" alt="" width="220" height="180" class="img-fluid">
                            </picture>
                            <div class="info-main-text">
                                <div class="info-main-title">
                                    <strong>Скидки для детей</strong><br>
                                </div>
                                <div class="info-main-description mt-2">
                                    Некоторые перевозчики предлагают скидки до 50% детям до 12-ти лет.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center">
                            <picture>
                                <source srcset="https://yastatic.net/s3/travel/static/_/f3fd6ba691dc8ea5333e.webp" type="image/webp" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/861e265be4cd56be70e9.png" type="image/png" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/fb4c30c9136fb277d452.webp" type="image/webp">
                                <img src="https://yastatic.net/s3/travel/static/_/f7384ae8ce92bf1b730f.png" alt="" width="220" height="180" class="img-fluid">
                            </picture>
                            <div class="info-main-text">
                                <div class="info-main-title">
                                    <strong>Возврат билетов</strong><br>
                                </div>
                                <div class="info-main-description mt-2">
                                    Возможность возврата билета в любое удобное время с получением возмещения его стоимости на банковскую карту.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center">
                            <picture>
                                <source srcset="https://yastatic.net/s3/travel/static/_/4a848a824746f8f55d0a.webp" type="image/webp" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/6298b40d17753820c933.png" type="image/png" media="(prefers-color-scheme: dark)">
                                <source srcset="https://yastatic.net/s3/travel/static/_/b77465f5c8ec3f2a81ad.webp" type="image/webp">
                                <img src="https://yastatic.net/s3/travel/static/_/e8048ec9fd8b2ad37482.png" alt="" width="220" height="180" class="img-fluid">
                            </picture>
                            <div class="info-main-text">
                                <div class="info-main-title">
                                    <strong>Поддержка по телефону и по электронной почте.</strong><br>
                                </div>
                                <div class="info-main-description mt-2">
                                    <a href="tel:+79021668003" class="main-phone">Телефон: 8 (9021) 66-80-03</a><br>
                                    <a href="mailto:info@biletavto.ru" class="main-email">Email: info@biletavto.ru</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <style>
        .owl-nav {
            position: absolute;
            top: -21%;
            left: 87%;
        }

        .card {
            background: #FFFFFF;
            border-radius: 12px !important;
            border: none;
        }

        .card-popular {
            height: 80px;
            max-width: 282px;
            min-width: 280px;
        }

        .card-img {
            width: 80px; /* Ширина изображения */
            height: 80px; /* Высота изображения */
            object-fit: cover; /* Обрезка изображения для соответствия размерам */
            border-radius: 12px;
        }

        .card-body {
            flex: 1 1 auto;
            padding: 0;
            cursor: pointer;
        }

        .card-info {
            display: flex;
            flex-direction: column;
        }

        .card-name {
            display: flex;
            text-align: start;
            font-size: 18px;
            line-height: 22px;
            font-weight: 500;
        }

        .card-price {
            display: flex;
            text-align: start;
        }

        .content {
            background: linear-gradient(to bottom, #007bff 50%, #eff1f4 50%);
            background-size: 100% 490px;
            background-repeat: no-repeat;
        }

        .info-col {
            font-family: Arial, sans-serif; /* Используйте читаемый шрифт */
            line-height: 1.6; /* Увеличьте высоту строки для лучшей читаемости */
            margin: 20px; /* Отступы вокруг блока */
        }

        .info-col p {
            margin: 10px 0; /* Отступы между параграфами */
        }

        .info-col p strong {
            font-weight: bold; /* Сделайте текст заголовков жирным */
            color: #333; /* Цвет заголовков */
        }

        .info-col .address {
            color: #555; /* Цвет адреса */
        }

        .info-col .working-hours {
            color: #007BFF; /* Цвет времени работы (например, синий) */
        }

        .info-col .lunch-time {
            color: #28A745; /* Цвет времени обеда (например, зеленый) */
        }

        .info-col .phone {
            color: #FF5733; /* Цвет телефонов (например, красный) */
            font-size: 16px; /* Увеличьте размер шрифта для телефонов */
            text-decoration: none; /* Уберите подчеркивание */
        }

        .info-col .phone:hover {
            text-decoration: underline; /* Плавно подчеркивать телефон при наведении */
        }

        .img-info-row {
            display: flex;
            padding: 50px;
        }

        .img-col {
            width: 50%;
        }

        .info-col {
            display: flex;
            width: 50%;
            flex-direction: column;
            justify-content: left;
            align-items: start;
            padding: 50px;
            text-align: start;
        }

        .info-main-title {
            font-size: large;
        }

        .info-main-description {
            font-size: medium;
        }

        .avtovokzal-modal {
            display: none; /* Скрыто по умолчанию */
            position: fixed; /* Остается на месте при прокрутке */
            z-index: 1000; /* Наверх остальных элементов */
            left: 0;
            top: 0;
            width: 100%; /* Ширина 100% */
            height: 100%; /* Высота 100% */
            background-color: rgba(12, 20, 32, 0.24); /* Полупрозрачный фон */
            justify-content: center;
            align-items: center;
        }

        .avtovokzal-modal-content {
            overflow: auto; /* Включает прокрутку, если необходимо */
            background-color: #eff1f4;
            scrollbar-color: rgba(90,100,114,0.12) transparent;
            scrollbar-width: thin;
            padding: 20px;
            width: 68%; /* Ширина модального окна */
            height: 100%
        }
        .avtovokzal-modal-content::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .avtovokzal-modal-content::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            -webkit-border-radius: 10px;
            border-radius: 10px;
        }

        .avtovokzal-modal-content::-webkit-scrollbar-thumb {
            -webkit-border-radius: 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.3);
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
        }

        .avtovokzal-modal-content::-webkit-scrollbar-thumb:window-inactive {
            background: rgba(255, 255, 255, 0.3);
        }


        .avtovokzal-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .avtovokzal-close:hover,
        .avtovokzal-close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .avtovokzal-modal-image {
            max-width: 100%;
            height: auto;
        }

        .collection-title {
            font-family: "Robotto", sans-serif !important;
            font-size: 34px;
            font-weight: 700;
            display: flex;
            color: #0c131d;
            padding: 10px 16px 12px;
            line-height: 30px;

        }

        .avtovokzal-container {
            border: none;
            border-radius: 20px;
            max-width: 800px;
            min-width: 200px;
            padding: 30px;
            width: 100%;
            height: 420px;
            overflow: hidden; /* Избегаем переполнение */
            background: #FFFFFF;
            text-align: left;
        }

        .item-info {
            max-height: 120px;
            overflow: hidden;           /* Скрыть переполнение */
            text-overflow: ellipsis;
        }
        .address {
            color: #6a6476;
        }
        .slide{
            text-align: center; /* Центрирование текста */
            height: auto; /* Сохраняет пропорции изображения */
            width: auto;
            border-radius: 16px !important;
            text-overflow: ellipsis;
        }

        .location-card img {
            border-radius: 16px !important;
            width: 100%;
        }

        /* Стили для кнопок навигации */

        figcaption {
            font-weight: bold;
        }

        .main-content {
            padding-top: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            flex-direction: column;
            border-bottom: 1px solid lightgray;
            gap: 50px;
        }

        .ads {
            display: flex;
            max-width: 1000px;
            align-items: center; /* Вертикальное выравнивание */
            justify-content: center; /* Горизонтальное выравнивание */
            text-align: center; /* Центрирование текста внутри контейнера */
            flex-wrap: wrap;
        }

        @media (min-width: 1200px) {
            .ads {
                min-width: 1000px;
            }
        }

        .ad {
            flex: 0 0 50%;
            background: black url("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTF8oMaRa9ZHBKKo_mMzstCT2Wl7_lwr-O9sg&s");
            background-size: cover;
            max-width: 388px;
            height: 388px;
            color: white;
            padding-bottom: 30px;
            text-align: center;
            margin: 15px;
            min-width: 345px;
            border-radius: 20px;
        }

        .popular {
            background: #FFFFFF;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center; /* Вертикальное выравнивание */
            justify-content: center; /* Горизонтальное выравнивание */
        }

        .popular-routes, .info-main {
            font-family: "Inter", sans-serif;
            margin-top: 50px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Вертикальное выравнивание */
            justify-content: center; /* Горизонтальное выравнивание */
            text-align: center; /* Центрирование текста внутри контейнера */
            max-width: 1200px;
            overflow: hidden;
        }

        .hero-section h1 {
            font-family: 'Montserrat', sans-serif;
        }

        .hero-section h1, .hero-section p, .hero-section form {
            position: relative; /* Поднимает контент над затемняющей подложкой */
            z-index: 2;
        }
        .nav-next {
            transform: rotate(-180deg);
        }

        .nav-prev, .nav-next {
            border: none #f0f2f3; /* Убираем рамку */
            border-radius: 50%; !important; /* Закругляем углы */
            height: 36px;
            width: 36px;
        }

        .owl-next:hover, .owl-prev:hover {
            background: none !important;
        }

        .nav-prev:hover, .nav-next:hover {
            background: #e2e6e8;
        }

        .owl-nav button:focus,
        .owl-nav button:active {
            outline: none; /* Убираем обводку при фокусе */
        }

        .owl-nav button:hover,
        .owl-nav button:focus,
        .owl-nav button:active {
            border: none !important;
        }

        .owl-stage-outer {
            padding: 20px;
        }

        @media (max-width: 1200px) {
            .img-info-row {
                flex-direction: column;
            }
            .img-col {
                width: 100%;
            }
            .info-col {
                width: 100%;
            }
        }

        @media (max-width: 767px) {
            .avtovokzal-modal-content {
                width: 100%; /* Ширина модального окна */
            }
        }

        @media (max-width: 567px) {
            .img-info-row {
                padding: 0;
            }
            .info-col {
                margin-top: 50px;
                padding: 0;
            }
        }
</style>
@endpush

@push('scripts')
    <script src="js/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function(){
            const slider = $("#slider").owlCarousel({
                items: 4,
                margin:10,
                touchDrag: true,
                dots: false,
                autoplay: true,
                autoplayHoverPause: true,
                loop: true,
                nav: true,
                navText: [
                    '<button class="nav-prev"><svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="s__PPkSyOOcz_Pd3X2fKTwO s__WTyl449fWUq5VSrXZXlV s__VxY8Rc6Dv6XezK55gEMv" data-test-id="icon" style="display: inline-block;"><path d="M4 7.5 9.5 2l1.25 1.25L6 8l4.75 4.75L9.5 14 4 8.5v-1Z"></path></svg></button>',
                    '<button class="nav-next"><svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="s__PPkSyOOcz_Pd3X2fKTwO s__WTyl449fWUq5VSrXZXlV" data-test-id="icon" style="display: inline-block;"><path d="M4 7.5 9.5 2l1.25 1.25L6 8l4.75 4.75L9.5 14 4 8.5v-1Z"></path></svg></button>',
                ],
                responsive:{
                    0:{
                        items: 1 // для маленьких экранов
                    },
                    400:{
                        items: 2 // для экранов средней ширины
                    },
                    600:{
                        items: 3 // для больших экранов
                    },
                    800:{
                        items: 4 // для очень больших экранов
                    }
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            const modal = document.getElementById('avtovokzal-modal');
            const modalTitle = document.getElementById('avtovokzal-modal-title');
            const modalAddress = document.getElementById('avtovokzal-modal-address');
            const modalImage = document.getElementById('avtovokzal-modal-image');
            const closeButton = document.querySelector('.avtovokzal-close');
            const bodyContent = document.getElementById('body');
            const modalDispPhone = document.getElementById('avtovokzal-modal-disp-phone');
            const modalIsOpen = document.getElementById('avtovokzal-modal-is-open');
            const modalLunch = document.getElementById('avtovokzal-modal-lunch');
            const modalInfoPhone = document.getElementById('avtovokzal-modal-info-phone');

            slides.forEach(slide => {
                slide.addEventListener('click', function() {
                    const title = slide.querySelector('figcaption').innerText;
                    const address = "Адрес: " + slide.querySelector('.address').innerText;
                    const imgSrc = slide.querySelector('img').src;
                    const dispPhone = slide.querySelector('.disp-phone').innerText;
                    const isOpen = slide.querySelector('.is-open').innerText;
                    const lunch = slide.querySelector('.lunch').innerText;
                    const infoPhone = slide.querySelector('.info-phone').innerText;
                    console.log(title);
                    console.log(address);
                    console.log(imgSrc);
                    modalTitle.innerText = title;
                    modalAddress.innerText = address;
                    modalImage.src = imgSrc;
                    modalDispPhone.innerText = dispPhone;
                    modalIsOpen.innerText = isOpen;
                    modalLunch.innerText = lunch;
                    modalInfoPhone.innerText = infoPhone;
                    bodyContent.style.overflow = "hidden";
                    modal.style.display = "flex";
                });
            });

            closeButton.addEventListener('click', function() {
                modal.style.display = "none"; // Скрыть модальное окно
                bodyContent.style.overflow = "auto"
            });

            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = "none"; // Закрыть модальное окно, если кликнули вне его
                    bodyContent.style.overflow = "auto"
                }
            });
        });
        document.querySelectorAll('.route-card').forEach(card => {
            card.addEventListener('click', function() {const routeId = this.getAttribute('data-route-id');
                const stationsList = document.getElementById('arrival-stations-' + routeId);
                // Переключение видимости списка станций прибытия
                if (stationsList.style.display === 'none') {
                    stationsList.style.display = 'block';
                } else {
                    stationsList.style.display = 'none';
                }
            });
        });
        function toggleStations(routeId) {
            const stationsContainer = document.getElementById(`arrival-stations-container-${routeId}`);
            const routesContainer = document.getElementById(`route-card-${routeId}`);
            if (!routesContainer.style.borderBottomLeftRadius || routesContainer.style.borderBottomLeftRadius === '15px') {
                routesContainer.style.borderBottomLeftRadius = '0px';
            } else {
                routesContainer.style.borderBottomLeftRadius = '15px';
            }

            if (!routesContainer.style.borderBottomRightRadius || routesContainer.style.borderBottomRightRadius === '15px') {
                routesContainer.style.borderBottomRightRadius = '0px';
            } else {
                routesContainer.style.borderBottomRightRadius = '15px';
            }
            // Проверяем текущее состояние блока и переключаем его высоту
            if (stationsContainer.style.maxHeight === '0px' || stationsContainer.style.maxHeight === '') {
                stationsContainer.style.maxHeight = `${stationsContainer.scrollHeight}px`; // Устанавливаем высоту равной контенту
            } else {
                stationsContainer.style.maxHeight = '0px'; // Скрываем блок
            }
        }
        document.querySelectorAll('.station-item').forEach(item => {
            item.addEventListener('click', function() {
                const stationName = this.getAttribute('data-station').split(' ')[0]; // Получаем первое слово
                const currentDate = new Date().toISOString().split('T')[0]; // Получаем текущую дату в формате YYYY-MM-DD
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('search.index') }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const fromInput = document.createElement('input');
                fromInput.type = 'hidden';
                fromInput.name = 'from';
                fromInput.value = 'Улан-Удэ';
                form.appendChild(fromInput);

                const toInput = document.createElement('input');
                toInput.type = 'hidden';
                toInput.name = 'to';
                toInput.value = stationName;
                form.appendChild(toInput);

                const dateInput = document.createElement('input');
                dateInput.type = 'hidden';
                dateInput.name = 'date';
                dateInput.value = currentDate;
                form.appendChild(dateInput);

                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
@endpush
