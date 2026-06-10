<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    // Конструктор удалён или оставлен пустым
    // public function __construct() {}

    public function index()
    {
        $cabinets = Cabinet::withCount('users', 'clients', 'events')->paginate(20);
        return view('superadmin.cabinets.index', compact('cabinets'));
    }

    public function create()
    {
        return view('superadmin.cabinets.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $cabinet = Cabinet::create($request->only('name'));
        return redirect()->route('superadmin.cabinets.index')->with('success', 'Кабинет создан');
    }

    public function edit(Cabinet $cabinet)
    {
        return view('superadmin.cabinets.edit', compact('cabinet'));
    }

    public function update(Request $request, Cabinet $cabinet)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $cabinet->update($request->only('name'));
        return redirect()->route('superadmin.cabinets.index')->with('success', 'Кабинет обновлён');
    }

    public function destroy(Cabinet $cabinet)
    {
        $cabinet->delete();
        return redirect()->route('superadmin.cabinets.index')->with('success', 'Кабинет удалён');
    }

    public function show(Cabinet $cabinet)
    {
        session(['cabinet_id' => $cabinet->id]);
        return redirect()->route('cabinet.dashboard');
    }
}
