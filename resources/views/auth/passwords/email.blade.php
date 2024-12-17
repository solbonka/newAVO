@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center">Сброс пароля</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="col-form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                @error('email')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-4">Отправить ссылку для сброса пароля</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}">Уже есть аккаунт? Войдите!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-group {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        label {
            width: 100%;
            margin-bottom: 5px;
        }
    </style>
@endpush
