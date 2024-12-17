@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center">Сброс пароля</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label for="email" class="col-form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $email }}" required>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-form-label">Пароль</label>
                                <div class="input-group">
                                    <input type="password" class="form-control input-size" id="password" name="password" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="toggle-password" onclick="togglePassword()">
                                            <i class="fas fa-eye" id="eye-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="col-form-label">Подтверждение пароля</label>
                                <div class="input-group mb-4">
                                    <input type="password" class="form-control input-size" id="password_confirmation" name="password_confirmation" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="toggle-password-confirmation" onclick="togglePasswordConfirmation()">
                                            <i class="fas fa-eye" id="eye-icon-confirmation"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-4">Сбросить пароль</button>
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
            max-width: 400px; /* Максимальная ширина для всей формы */
            margin: 0 auto; /* Центрируем форму */
        }

        label {
            width: 100%; /* Устанавливаем одинаковую ширину для всех меток */
            margin-bottom: 5px; /* Отступ снизу для меток */
        }
    </style>
@endpush

@push('scripts')
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordField.type === "password") {
                passwordField.type = "text"; // Меняем тип на text
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password"; // Меняем тип обратно на password
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
        function togglePasswordConfirmation() {
            const passwordField = document.getElementById('password_confirmation');
            const eyeIcon = document.getElementById('eye-icon-confirmation');
            if (passwordField.type === "password") {
                passwordField.type = "text"; // Меняем тип на text
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password"; // Меняем тип обратно на password
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
@endpush
