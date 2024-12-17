<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-md d-flex flex-wrap">
        <a href="{{ route('home') }}" class="custom-logo-link" rel="home" aria-current="page">
            <img width="52" height="52" src="/img/logo_ru.png" class="custom-logo" alt="АВТОВОКЗАЛ" decoding="async">
        </a>
        <a class="navbar-brand" href="{{ route('home') }}">АВТОВОКЗАЛ</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Войти</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
                            </svg>
                            Профиль
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile') }}">Мой профиль</a>
                            <a class="dropdown-item" href="{{ route('orders') }}">Мои заказы</a>
                            <div class="dropdown-divider"></div>
                            <a id="logout-link" class="dropdown-item" href="#">
                                Выйти
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        </div>
                    </li>
                @endguest
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarSupportDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" data-test-id="icon" style="display: inline-block;">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 21a9 9 0 1 0-7.586-4.155L3 20l1 1 3.155-1.414A8.958 8.958 0 0 0 12 21Zm1-5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm1.14-8.645c.668.454 1.11 1.189 1.11 2.145 0 .767-.355 1.3-.78 1.682-.272.245-.634.472-.92.651-.097.061-.186.117-.259.166-.35.233-.545.419-.641.628-.072.155-.129.412-.015.873h-1.528c-.084-.56-.024-1.058.18-1.502.28-.604.772-.98 1.172-1.247.158-.105.295-.191.418-.268.236-.148.42-.262.59-.416.2-.18.283-.335.283-.567 0-.443-.183-.72-.453-.905-.298-.202-.743-.312-1.237-.272-1.02.081-1.81.71-1.81 1.677h-1.5c0-2.033 1.71-3.054 3.19-3.172.756-.06 1.56.092 2.2.527Z">
                            </path>
                        </svg>
                        Поддержка
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarSupportDropdown">
                        <a class="dropdown-item" href="tel:89021668003"> 8 (9021) 66-80-03</a>
                        <a class="dropdown-item" href="mailto:info@biletavto.ru">info@biletavto.ru</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<section class="hero-section text-center">
    <div class="container d-flex flex-column justify-content-center">
        <h1 class="display-4 font-weight-bold animated fadeInDown">Найдите свои автобусные билеты</h1>
        <p class="lead animated fadeIn">Бронируйте автобусные билеты легко и просто.</p>
        <form class="search-form mt-4" action="{{ route('search.index') }}" method="POST">
            {{ csrf_field() }}
            <div class="input-group row justify-content-center gap-3">
                <div class="mb-3">
                    <input type="text" class="form-from" id="from" placeholder="Откуда" name="from" required>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-to" id="to" placeholder="Куда" name="to" required>
                </div>
                <button type="button" data-test-id="round-button" tabindex="-1"
                        class="round-button round-button--active button-icon-container" onclick="swapValues()">
                    <svg class="arrow-icon arrow-icon--up" width="13" height="6" viewBox="0 0 13 6" fill="currentColor">
                        <path d="M3 4V6L0 3L3 0V2H13V4H3Z" fill="currentColor"></path>
                    </svg>
                    <svg class="arrow-icon arrow-icon--down" width="13" height="6" viewBox="0 0 13 6" fill="currentColor">
                        <path d="M3 4V6L0 3L3 0V2H13V4H3Z" fill="currentColor"></path>
                    </svg>
                </button>
                <div class="date mb-3">
                    <input type="date" class="form-date" name="date" placeholder="Выберите дату" required>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="s__DdIDR8ZkE64iXWTGJcSG" data-test-id="departure-calendar-icon" style="display: inline-block;">
                        <path d="M8 10h3v3H8v-3Z"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9 3H7v1a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3V3h-2v1H9V3ZM6 17V8h12v9a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1Z">
                        </path>
                    </svg>
                </div>
                <div class="mb-3">
                    <button type="submit" class="button-search">Найти билеты</button>
                </div>
            </div>
        </form>
    </div>
</section>
