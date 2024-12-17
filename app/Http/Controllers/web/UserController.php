<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        // Предположим, что вы хотите получить текущего аутентифицированного пользователя
        $user = Auth::user();

        return view('users.profile', compact('user')); // Передаем данные пользователя в представление
    }

    public function edit()
    {
        $user = Auth::user(); // Получаем текущего авторизованного пользователя
        return view('users.edit', compact('user')); // Возвращаем представление для редактирования профиля
    }

    public function update(Request $request)
    {
        $user = Auth::user(); // Получаем текущего пользователя

        // Валидация входящих данных
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
        ]);

        // Обновление информации о пользователе
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Профиль успешно обновлён!');
    }
}
