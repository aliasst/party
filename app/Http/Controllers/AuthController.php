<?php

namespace App\Http\Controllers;

use App\Models\Cabinet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    // ------------------ РЕГИСТРАЦИЯ ------------------
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Создаём кабинет
        $cabinet = Cabinet::create([
            'name' => 'Кабинет ' . $request->name, // временное название, можно потом редактировать
        ]);

        // Создаём пользователя с ролью user (глобально)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_USER,
        ]);

        // Привязываем пользователя к кабинету как admin
        $cabinet->users()->attach($user->id, ['role' => 'admin']);

        // Логиним пользователя
        Auth::login($user);

        return redirect()->route('cabinet.dashboard');
    }

    // ------------------ ЛОГИН ------------------
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('cabinet.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль.',
        ])->onlyInput('email');
    }

    // ------------------ ВЫХОД ------------------
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ------------------ ВОССТАНОВЛЕНИЕ ПАРОЛЯ: ЗАПРОС ------------------
    public function showForgotForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;

        // Генерация токена
        $token = Str::random(60);
        $hashedToken = Hash::make($token);

        // Удаляем старый токен для этого email (если есть)
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Вставляем новый токен
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $hashedToken,
            'created_at' => Carbon::now(),
        ]);

        // Формируем ссылку для сброса
        $resetUrl = url('/password/reset/' . $token . '?email=' . urlencode($email));

        // Отправляем письмо
        Mail::send('auth.emails.password_reset', ['resetUrl' => $resetUrl], function ($message) use ($email) {
            $message->to($email)
                ->subject('Сброс пароля');
        });

        return back()->with('status', 'Ссылка для сброса пароля отправлена на вашу почту.');
    }

    // ------------------ ВОССТАНОВЛЕНИЕ ПАРОЛЯ: СБРОС ------------------
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required'
        ]);

        // Ищем запись в таблице password_reset_tokens
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Неверный или устаревший токен сброса пароля.']);
        }

        // Проверка срока действия (60 минут)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'Срок действия ссылки истёк. Запросите новую.']);
        }

        // Обновляем пароль пользователя
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Удаляем использованный токен
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Пароль успешно изменён. Войдите с новым паролем.');
    }
}
