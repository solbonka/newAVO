<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Определите, авторизован ли пользователь на выполнение этого запроса.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Позволяем всем выполнять этот запрос
    }

    /**
     * Получите массив правил валидации, применимые к запросу.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ];
    }

    /**
     * Настройка сообщений об ошибках, если нужно.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Поле email обязательно для заполнения.',
            'password.required' => 'Поле пароль обязательно для заполнения.',
            'password.min' => 'Пароль должен содержать минимум :min символов.',
            'password.confirmed' => 'Пароли не совпадают.',
            'token.required' => 'Токен обязательный параметр.',
        ];
    }
}
