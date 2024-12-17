<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Jobs\SendPasswordResetEmailJob;
use App\Http\Requests\Jobs\SendVerificationEmailJob;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use SendsPasswordResetEmails;
    public function login()
    {
        return view('auth.login'); // Путь к вашему шаблону для входа
    }

    public function register()
    {
        return view('auth.register'); // Путь к вашему шаблону для регистрации
    }

    /**
     * @throws ValidationException
     */
    public function doLogin(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            throw ValidationException::withMessages([
                'email' => 'Неверный email или пароль'
            ]);
        }

        return redirect()->intended();
    }

    // Метод для регистрации пользователя
    public function doRegister(RegisterRequest $request)
    {
        // Создание пользователя
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Хеширование пароля
        ]);
        // Отправка письма с подтверждением
        SendVerificationEmailJob::dispatch($user)->onQueue('verification');
        Auth::login($user);
        return redirect()->route('verification.notice', compact('user'));
    }

    public function resend(Request $request)
    {
        // Здесь валидация, если нужно
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        // Отправка повторного письма с подтверждением
        SendVerificationEmailJob::dispatch($user)->onQueue('verify-resend');

        return redirect()->back()->with('success', 'Письмо с подтверждением было повторно отправлено!');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    /**
     * @throws ValidationException
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // Получение пользователя
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return $this->sendResetLinkFailedResponse($request, 'Пользователь с таким адресом электронной почты не найден.');
        }

        // Отправка задания в очередь
        SendPasswordResetEmailJob::dispatch($user)->onQueue('password-reset');

        return $this->sendResetLinkResponse($request, Password::RESET_LINK_SENT)->with('success', 'Письмо с восстановлением пароля было успешно отправлено!');
    }

    public function reset(ResetPasswordRequest $request)
    {
        $response = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Ваш пароль был успешно изменен!')
            : back()->withErrors(['email' => trans($response)]);
    }
}
