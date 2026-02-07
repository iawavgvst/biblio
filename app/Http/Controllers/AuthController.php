<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Форма регистрации
     *
     * Метод GET: /register
     */
    public function showRegisterForm()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Регистрация пользователя
     *
     * Метод POST: /register
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->intended('/profile');
    }

    /**
     * Форма авторизации
     *
     * Метод GET: /login
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Авторизация пользователя
     *
     * Метод POST: /login
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (Auth::attempt($data, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/profile');
        }

        return back()->withErrors(['email' => 'Неверные учетные данные.']);
    }

    /**
     * Выход
     *
     * Метод POST: /logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->intended('/login');
    }
}
