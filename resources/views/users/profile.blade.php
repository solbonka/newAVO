@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Профиль пользователя</h1>

        @if(isset($user) && $user)
            <div class="card">
                <div class="card-header">
                    Информация о пользователе
                </div>
                <div class="card-body">
                    <h5 class="card-title">Имя: {{ $user->name }}</h5>
                    <p class="card-text">Email: {{ $user->email }}</p>
                    <p class="card-text">Дата регистрации: {{ $user->created_at->format('d.m.Y') }}</p>

                    <!-- Информация о подтверждении почты -->
                    <p class="card-text">
                        Статус подтверждения почты:
                        @if($user->hasVerifiedEmail())
                            <span class="text-success">Подтверждено</span>
                        @else
                            <span class="text-danger">Не подтверждено</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Редактировать профиль</a>
            </div>

            @if (!$user->hasVerifiedEmail())
                <div class="alert alert-warning mt-3">
                    <strong>Внимание!</strong> Проверьте свою почту и подтвердите адрес электронной почты!
                </div>
                <form action="{{ route('verification.resend') }}" method="POST" class="mt-2">
                    @csrf
                    <input type="hidden" name="email" value="{{ old('email', $user->email) }}">
                    <button type="submit" class="btn btn-warning">Повторно отправить письмо с подтверждением</button>
                </form>
            @endif

        @else
            <p>Пользователь не найден.</p>
        @endif
    </div>
@endsection

