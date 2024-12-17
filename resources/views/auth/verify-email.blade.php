@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Почта не подтверждена!</h1>
        <p>На ваш адрес электронной почты было отправлено сообщение с подтверждением. Пожалуйста, проверьте свою почту.</p>

        <form action="{{ route('verification.resend') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', $user->email) }}">
            <button type="submit" class="btn">Повторно отправить письмо с подтверждением</button>
        </form>
    </div>
@endsection
