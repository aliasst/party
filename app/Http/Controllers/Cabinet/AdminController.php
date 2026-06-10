<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Проверка прав доступа к управлению пользователями кабинета.
     */
    protected function checkAccess(Request $request)
    {
        $user = $request->user();
        $cabinet = $request->attributes->get('cabinet');

        // Суперадмин может всё
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Администратор кабинета (pivot.role = admin) – может управлять пользователями
        $isCabinetAdmin = $cabinet->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();

        if (!$isCabinetAdmin) {
            abort(403, 'У вас нет прав на управление пользователями кабинета.');
        }

        return true;
    }

    /**
     * Список всех пользователей текущего кабинета.
     */
    public function index(Request $request)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');
        $users = $cabinet->users()->paginate(10);
        return view('cabinet.admins.index', compact('users'));
    }

    /**
     * Форма создания нового пользователя кабинета.
     */
    public function create(Request $request)
    {
        $this->checkAccess($request);
        return view('cabinet.admins.create');
    }

    /**
     * Сохранение нового пользователя в кабинете.
     */
    public function store(Request $request)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:6|confirmed',
            'cabinet_role'  => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => User::ROLE_USER, // глобальная роль – обычный пользователь
        ]);

        $cabinet->users()->attach($user->id, ['role' => $request->cabinet_role]);

        return redirect()->route('cabinet.admins.index')
            ->with('success', 'Пользователь добавлен в кабинет.');
    }

    /**
     * Форма редактирования пользователя кабинета.
     */
    public function edit(Request $request, User $admin)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');

        // Загружаем пользователя вместе с pivot-данными из кабинета
        $admin = $cabinet->users()->where('user_id', $admin->id)->firstOrFail();

        return view('cabinet.admins.edit', compact('admin'));
    }

    /**
     * Обновление данных пользователя кабинета.
     */
    public function update(Request $request, User $admin)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');

        // Проверяем, что пользователь действительно привязан к этому кабинету
        $cabinet->users()->where('user_id', $admin->id)->firstOrFail();

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password'      => 'nullable|string|min:6|confirmed',
            'cabinet_role'  => 'required|in:admin,user',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $admin->update($data);

        // Обновляем роль в связующей таблице
        $cabinet->users()->updateExistingPivot($admin->id, ['role' => $request->cabinet_role]);

        return redirect()->route('cabinet.admins.index')
            ->with('success', 'Пользователь обновлён.');
    }

    /**
     * Удаление пользователя из кабинета (отвязка).
     */
    public function destroy(Request $request, User $admin)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');

        // Проверяем принадлежность кабинету
        $cabinet->users()->where('user_id', $admin->id)->firstOrFail();

        // Запрещаем удалять самого себя
        if ($admin->id === auth()->id()) {
            return back()->with('error', 'Вы не можете удалить самого себя.');
        }

        $cabinet->users()->detach($admin->id);

        return redirect()->route('cabinet.admins.index')
            ->with('success', 'Пользователь удалён из кабинета.');
    }
}
