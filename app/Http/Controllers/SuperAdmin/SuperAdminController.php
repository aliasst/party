<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function index()
    {
        $superadmins = User::where('role', User::ROLE_SUPER_ADMIN)->paginate(10);
        return view('superadmin.superadmins.index', compact('superadmins'));
    }

    public function create()
    {
        return view('superadmin.superadmins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => User::ROLE_SUPER_ADMIN,
        ]);

        return redirect()->route('super.superadmins.index')
            ->with('success', 'Суперадмин добавлен');
    }

    public function edit(User $superadmin)
    {
        if ($superadmin->role !== User::ROLE_SUPER_ADMIN) abort(404);
        return view('superadmin.superadmins.edit', compact('superadmin'));
    }

    public function update(Request $request, User $superadmin)
    {
        if ($superadmin->role !== User::ROLE_SUPER_ADMIN) abort(404);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $superadmin->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = ['name' => $request->name, 'email' => $request->email];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $superadmin->update($data);

        return redirect()->route('super.superadmins.index')
            ->with('success', 'Суперадмин обновлён');
    }

    public function destroy(User $superadmin)
    {
        if ($superadmin->role !== User::ROLE_SUPER_ADMIN) abort(404);
        if ($superadmin->id === auth()->id()) {
            return back()->with('error', 'Нельзя удалить самого себя');
        }
        $superadmin->delete();
        return redirect()->route('super.superadmins.index')
            ->with('success', 'Суперадмин удалён');
    }
}
