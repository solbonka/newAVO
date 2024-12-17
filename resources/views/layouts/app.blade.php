<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Название вашего сайта')</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/img/logo_ru.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    @stack('styles')
    <style>
        html {
            scroll-behavior: smooth; /* Плавная прокрутка */
        }
        #wrapper {
            margin: 0 auto;
            background: #eff1f4;
            transition: width 0.5s, background 0.5s; /* Плавный переход */
            width: 100%; /* Занимает всю возможную ширину */
            max-width: 100%; /* Ограничивает размеры относительно экрана */
        }
        .footer-list {
            color: #0c131d;
        }
        .footer-list:hover {
            color: #fa7b41;
            text-decoration: none;
        }
        @media (min-width: 1400px) {
            .container, .container-lg, .container-md, .container-sm, .container-xl {
                max-width: 1350px;
            }
        }
        .social-media-img {
            width: 40px;
            height: 40px;
            border:1px solid black;
            border-radius: 10px;
            margin-top: 10px;
        }


    </style>
</head>
<body id="body">
<div class="wrapper" id="wrapper" style="width: 100%;">
    @include('layouts.navbar')

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
                <span class="close" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mt-4 animated fadeIn">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>

    @include('layouts.footer')
</div>

<script src="{{ mix('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>


<script>
    const logoutLink = document.getElementById('logout-link');
    const logoutForm = document.getElementById('logout-form');

    // Проверяем, существуют ли элементы перед добавлением обработчика событий
    if (logoutLink && logoutForm) {
        logoutLink.addEventListener('click', function(event) {
            event.preventDefault(); // Предотвращает переход по ссылке
            logoutForm.submit(); // Отправляет форму
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("input[name='date']", {
            dateFormat: "d.m.Y",
            minDate: "today",
            locale: "ru"
        });
    });
    function swapValues() {
        const fromInput = document.getElementById('from');
        const toInput = document.getElementById('to');
        const temp = fromInput.value;
        fromInput.value = toInput.value;
        toInput.value = temp;
    }
    const scrollLinks = document.querySelectorAll('.scrollToTop');

    scrollLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Отменяем переход по ссылке
            document.getElementById('from').value = "Улан-Удэ"; // Заполняем поле "Откуда"
            document.getElementById('to').value = this.textContent; // Заполняем поле "Куда" значением текста ссылки
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // Плавная прокрутка
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const footerLinks = document.querySelectorAll('.footer-list');

        footerLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Получаем целевой элемент
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                // Прокручиваем страницу к целевому элементу
                targetElement.scrollIntoView({
                    behavior: 'smooth' // Добавляем плавное поведение
                });
            });
        });
    });
</script>
@stack('scripts')
</body>
</html>
