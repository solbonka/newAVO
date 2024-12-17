@extends('layouts.app')

@section('title', 'Страница не найдена')

@section('content')
    <main>
        <section class="error-section">
            <div class="container text-center">
                <h1>404</h1>
                <h2>Страница не найдена</h2>
                <p>Извините, но запрашиваемая вами страница не существует.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Вернуться на главную</a>
            </div>
        </section>
    </main>
@endsection
