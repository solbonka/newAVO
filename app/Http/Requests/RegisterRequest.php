<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Пожалуйста, введите ваше имя.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не может превышать 255 символов.',

            'email.required' => 'Пожалуйста, введите ваш адрес электронной почты.',
            'email.email' => 'Пожалуйста, введите действительный адрес электронной почты.',
            'email.max' => 'Адрес электронной почты не может превышать 255 символов.',
            'email.unique' => 'Этот адрес электронной почты уже занят.',

            'password.required' => 'Пожалуйста, введите пароль.',
            'password.confirmed' => 'Пароли не совпадают.',
            'password.min' => 'Пароль должен содержать не менее 8 символов.',
        ];
    }

    public function attributes()
    {
        return [
            "name" => "\"Имя\"",
            "email" => "\"Емейл\"",
            "password" => "\"Пароль\""
        ];
    }
}
