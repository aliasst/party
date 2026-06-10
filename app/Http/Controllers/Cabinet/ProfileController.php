<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {

    }

    // Форма редактирования профиля
    public function edit()
    {
        $user = Auth::user();
        return view('cabinet.profile.edit', compact('user'));
    }

    // Обновление профиля (имя, email, пароль, аватар)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Обновляем имя и email
        $user->name = $request->name;
        $user->email = $request->email;

        // Обновляем пароль, если заполнен
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Обработка аватарки
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар, если он есть
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('cabinet.profile.edit')
            ->with('success', 'Профиль успешно обновлён.');
    }
}
