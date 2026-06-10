<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    protected function checkAccess(Request $request)
    {
        $user = $request->user();
        $cabinet = $request->attributes->get('cabinet');

        if ($user->isSuperAdmin()) {
            return true;
        }

        $isCabinetAdmin = $cabinet->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();

        if (!$isCabinetAdmin) {
            abort(403, 'У вас нет прав на управление мероприятиями.');
        }

        return true;
    }

    /**
     * Список мероприятий текущего кабинета.
     */
    public function index(Request $request)
    {
        $cabinet = $request->attributes->get('cabinet');
        $events = $cabinet->events()->orderBy('start_date', 'desc')->paginate(10);
        return view('cabinet.events.index', compact('events'));
    }

    /**
     * Форма создания мероприятия.
     */
    public function create(Request $request)
    {
        $this->checkAccess($request);
        return view('cabinet.events.create');
    }

    /**
     * Сохранение мероприятия.
     */
    public function store(Request $request)
    {
        $this->checkAccess($request);
        $cabinet = $request->attributes->get('cabinet');

        $request->validate([
            'title'      => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'description'=> 'nullable|string',
            'cover'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['title', 'start_date', 'end_date', 'description']);
        $data['cabinet_id'] = $cabinet->id;

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('events', 'public');
            $data['cover'] = $path;
        }

        $event = Event::create($data);
        $event->loadDefaultStages();

        return redirect()->route('cabinet.events.index')
            ->with('success', 'Мероприятие создано.');
    }

    /**
     * Просмотр мероприятия с вкладками (счета, акты и т.д.).
     */
    public function show(Event $event)
    {
        $cabinet = request()->attributes->get('cabinet');
        if ($event->cabinet_id != $cabinet->id) {
            abort(404);
        }
        return view('cabinet.events.show', compact('event'));
    }

    /**
     * Форма редактирования мероприятия.
     */
    public function edit(Event $event)
    {
        $cabinet = request()->attributes->get('cabinet');
        if ($event->cabinet_id != $cabinet->id) {
            abort(404);
        }

        $this->checkAccess(request());
        return view('cabinet.events.edit', compact('event'));
    }

    /**
     * Обновление мероприятия.
     */
    public function update(Request $request, Event $event)
    {
        $cabinet = $request->attributes->get('cabinet');
        if ($event->cabinet_id != $cabinet->id) {
            abort(404);
        }

        $this->checkAccess($request);

        $request->validate([
            'title'      => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'description'=> 'nullable|string',
            'cover'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['title', 'start_date', 'end_date', 'description']);

        if ($request->hasFile('cover')) {
            // Удаляем старую обложку
            if ($event->cover && Storage::disk('public')->exists($event->cover)) {
                Storage::disk('public')->delete($event->cover);
            }
            $path = $request->file('cover')->store('events', 'public');
            $data['cover'] = $path;
        }

        $event->update($data);

        return redirect()->route('cabinet.events.index')
            ->with('success', 'Мероприятие обновлено.');
    }

    /**
     * Удаление мероприятия.
     */
    public function destroy(Event $event)
    {
        $cabinet = request()->attributes->get('cabinet');
        if ($event->cabinet_id != $cabinet->id) {
            abort(404);
        }

        $this->checkAccess(request());
        $event->delete();

        return redirect()->route('cabinet.events.index')
            ->with('success', 'Мероприятие удалено.');
    }
}
