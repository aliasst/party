<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected function checkAccess(Request $request)
    {
        $user = $request->user();
        $cabinet = $request->attributes->get('cabinet');

        if ($user->isSuperAdmin()) return true;

        $isCabinetAdmin = $cabinet->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();

        if (!$isCabinetAdmin) {
            abort(403, 'У вас нет прав на управление заказчиками.');
        }

        return true;
    }

    // Список заказчиков текущего кабинета
    public function index(Request $request)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');
        $clients = $cabinet->clients()->orderBy('name')->paginate(10);
        return view('cabinet.clients.index', compact('clients'));
    }

    // Форма создания
    public function create(Request $request)
    {
        $this->checkAccess($request);
        return view('cabinet.clients.create');
    }

    // Сохранение
    public function store(Request $request)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');

        $request->validate([
            'name'       => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:50',
            'requisites' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'legal_name', 'email', 'phone', 'requisites']);
        $data['cabinet_id'] = $cabinet->id;

        Client::create($data);

        return redirect()->route('cabinet.clients.index')
            ->with('success', 'Заказчик добавлен.');
    }

    // Форма редактирования
    public function edit(Request $request, Client $client)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');
        if ($client->cabinet_id != $cabinet->id) abort(404);
        return view('cabinet.clients.edit', compact('client'));
    }

    // Обновление
    public function update(Request $request, Client $client)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');
        if ($client->cabinet_id != $cabinet->id) abort(404);

        $request->validate([
            'name'       => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:50',
            'requisites' => 'nullable|string',
        ]);

        $client->update($request->only(['name', 'legal_name', 'email', 'phone', 'requisites']));

        return redirect()->route('cabinet.clients.index')
            ->with('success', 'Заказчик обновлён.');
    }

    // Удаление
    public function destroy(Request $request, Client $client)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');
        if ($client->cabinet_id != $cabinet->id) abort(404);
        $client->delete();
        return redirect()->route('cabinet.clients.index')
            ->with('success', 'Заказчик удалён.');
    }
}
